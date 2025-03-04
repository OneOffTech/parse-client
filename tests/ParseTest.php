<?php

use OneOffTech\Parse\Client\Connectors\ParseConnector;
use OneOffTech\Parse\Client\DocumentProcessor;
use OneOffTech\Parse\Client\Dto\DocumentDto;
use OneOffTech\Parse\Client\ParseOption;
use OneOffTech\Parse\Client\Requests\ExtractTextRequest;
use Saloon\Exceptions\Request\Statuses\ServiceUnavailableException;
use Saloon\Exceptions\Request\Statuses\UnprocessableEntityException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\Http\Response;

test('can parse a pdf using pdfact', function () {
    $mockClient = MockClient::global([
        ExtractTextRequest::class => MockResponse::fixture('extract-text'),
    ]);

    $connector = new ParseConnector('fake', 'http://localhost:5002');
    $connector->withMockClient($mockClient);

    $document = $connector->parse('http://localhost/base.pdf');

    expect($document)
        ->toBeInstanceOf(DocumentDto::class)
        ->toHaveCount(2);

    expect($document->document()->isEmpty())
        ->toBeFalse();

    expect($document->document()->hasContent())
        ->toBeTrue();

    expect($document->document()->text())
        ->toBeString()->toContain('This is the title of the document');

    $pages = $document->pages();

    expect($pages)
        ->toHaveCount(2);

    expect($pages[0]->hasContent())
        ->toBeTrue();

    expect($pages[0]->text())
        ->toBeString()
        ->toEqual('Type of document / Offer / Contract / Report'.PHP_EOL.'This is the title of the document, it'.PHP_EOL.'can use multiple lines and grow a bit'.PHP_EOL.'Subtitle of the document'.PHP_EOL.'OneOff-Tech UG');

    expect($pages[0]->number())
        ->toEqual(1);

    expect($pages[1]->number())
        ->toEqual(2);

});

test('can parse a pdf using pymupdf', function () {
    $mockClient = MockClient::global([
        ExtractTextRequest::class => MockResponse::fixture('extract-text-pymupdf'),
    ]);

    $connector = new ParseConnector('fake', 'http://localhost:5002');
    $connector->withMockClient($mockClient);

    $document = $connector->parse('http://localhost/base.pdf', new ParseOption(DocumentProcessor::PYMUPDF));

    expect($document)
        ->toBeInstanceOf(DocumentDto::class)
        ->toHaveCount(2);

    expect($document->document()->isEmpty())
        ->toBeFalse();

    expect($document->document()->hasContent())
        ->toBeTrue();

    expect($document->pages())
        ->toHaveCount(2);

    expect($document->document()->text())
        ->toBeString()->toContain('This is the title of the document');

});

test('can parse an empty pdf', function () {
    $mockClient = MockClient::global([
        ExtractTextRequest::class => MockResponse::fixture('extract-text-empty'),
    ]);

    $connector = new ParseConnector('fake', 'http://localhost:5002');
    $connector->withMockClient(MockClient::getGlobal());

    $document = $connector->parse('http://localhost/empty.pdf');

    expect($document)
        ->toBeInstanceOf(DocumentDto::class)
        ->toHaveCount(1);

    expect($document->document()->isEmpty())
        ->toBeTrue();

    expect($document->document()->hasContent())
        ->toBeFalse();

    $mockClient->assertSent(ExtractTextRequest::class);

    $mockClient->assertSent(function (Request $request, Response $response) {
        if (! $request instanceof ExtractTextRequest) {
            return false;
        }

        /** @var array */
        $body = $request->body()->all();

        return $body['url'] === 'http://localhost/empty.pdf';
    });

    $mockClient->assertSentCount(1);

});

test('cannot parse file types other than pdf', function () {
    $mockClient = MockClient::global([
        ExtractTextRequest::class => MockResponse::fixture('extract-text-non-pdf'),
    ]);

    $connector = new ParseConnector('fake', 'http://localhost:5002');
    $connector->withMockClient(MockClient::getGlobal());

    $connector->parse('http://localhost/base.docx');

    $mockClient->assertSent(ExtractTextRequest::class);

    $mockClient->assertSent(function (Request $request, Response $response) {
        if (! $request instanceof ExtractTextRequest) {
            return false;
        }

        /** @var array */
        $body = $request->body()->all();

        return $body['url'] === 'http://localhost/base.docx';
    });

    $mockClient->assertSentCount(1);

})->throws(UnprocessableEntityException::class, 'The given file is not supported. Expected [application/pdf] found [application/vnd.openxmlformats-officedocument.wordprocessingml.document].');

test('handle non existing files', function () {
    $mockClient = MockClient::global([
        ExtractTextRequest::class => MockResponse::fixture('extract-text-non-existing'),
    ]);

    $connector = new ParseConnector('fake', 'http://localhost:5002');
    $connector->withMockClient(MockClient::getGlobal());

    $connector->parse('http://localhost/test.pdf');

    $mockClient->assertSent(ExtractTextRequest::class);

    $mockClient->assertSent(function (Request $request, Response $response) {
        if (! $request instanceof ExtractTextRequest) {
            return false;
        }

        /** @var array */
        $body = $request->body()->all();

        return $body['url'] === 'http://localhost/test.pdf';
    });

    $mockClient->assertSentCount(1);

})->throws(UnprocessableEntityException::class, 'Unprocessable Entity (422) Response: {"detail":"Unsupported mime type \'text/html\'. Expecting application/pdf."}');

test('handle pdfact not available', function () {
    $mockClient = MockClient::global([
        ExtractTextRequest::class => MockResponse::fixture('extract-text-pdfact-not-available'),
    ]);

    $connector = new ParseConnector('fake', 'http://localhost:5002');
    $connector->withMockClient($mockClient);

    $connector->parse('http://localhost/km-f.pdf');

})->throws(ServiceUnavailableException::class, 'The pdfact service is not reachable.');
