<?php

use OneOffTech\Parse\Client\Connectors\ParseConnector;
use OneOffTech\Parse\Client\DocumentProcessor;
use OneOffTech\Parse\Client\ParseOption;
use OneOffTech\Parse\Client\Requests\ExtractTextRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\Http\Response;

test('ensure pdfact is selected as processor', function () {
    $mockClient = MockClient::global([
        ExtractTextRequest::class => MockResponse::fixture('extract-text-empty'),
    ]);

    $connector = new ParseConnector('fake', 'http://localhost:5002');
    $connector->withMockClient($mockClient);

    $connector->parse('http://localhost/empty.pdf');

    $mockClient->assertSent(ExtractTextRequest::class);

    $mockClient->assertSent(function (Request $request, Response $response) {
        if (! $request instanceof ExtractTextRequest) {
            return false;
        }

        /** @var array */
        $body = $request->body()->all();

        return $body['url'] === 'http://localhost/empty.pdf'
            && $body['driver'] === 'pdfact';
    });

    $mockClient->assertSentCount(1);

});

test('ensure pymupdf is selected as processor', function () {
    $mockClient = MockClient::global([
        ExtractTextRequest::class => MockResponse::fixture('extract-text-empty'),
    ]);

    $connector = new ParseConnector('fake', 'http://localhost:5002');
    $connector->withMockClient($mockClient);

    $connector->parse(
        url: 'http://localhost/empty.pdf',
        options: new ParseOption(DocumentProcessor::PYMUPDF),
    );

    $mockClient->assertSent(ExtractTextRequest::class);

    $mockClient->assertSent(function (Request $request, Response $response) {
        if (! $request instanceof ExtractTextRequest) {
            return false;
        }

        /** @var array */
        $body = $request->body()->all();

        return $body['url'] === 'http://localhost/empty.pdf'
            && $body['driver'] === 'pymupdf';
    });

    $mockClient->assertSentCount(1);
});

test('llamaparse can be selected as processor', function () {
    $mockClient = MockClient::global([
        ExtractTextRequest::class => MockResponse::fixture('extract-text-empty'),
    ]);

    $connector = new ParseConnector('fake', 'http://localhost:5002');
    $connector->withMockClient($mockClient);

    $connector->parse(
        url: 'http://localhost/empty.pdf',
        options: new ParseOption(DocumentProcessor::LLAMAPARSE),
    );

    $mockClient->assertSent(ExtractTextRequest::class);

    $mockClient->assertSent(function (Request $request, Response $response) {
        if (! $request instanceof ExtractTextRequest) {
            return false;
        }

        /** @var array */
        $body = $request->body()->all();

        return $body['url'] === 'http://localhost/empty.pdf'
            && $body['driver'] === 'llama';
    });

    $mockClient->assertSentCount(1);
});

test('unstructured can be selected as processor', function () {
    $mockClient = MockClient::global([
        ExtractTextRequest::class => MockResponse::fixture('extract-text-empty'),
    ]);

    $connector = new ParseConnector('fake', 'http://localhost:5002');
    $connector->withMockClient($mockClient);

    $connector->parse(
        url: 'http://localhost/empty.pdf',
        options: new ParseOption(DocumentProcessor::UNSTRUCTURED),
    );

    $mockClient->assertSent(ExtractTextRequest::class);

    $mockClient->assertSent(function (Request $request, Response $response) {
        if (! $request instanceof ExtractTextRequest) {
            return false;
        }

        /** @var array */
        $body = $request->body()->all();

        return $body['url'] === 'http://localhost/empty.pdf'
            && $body['driver'] === 'unstructured';
    });

    $mockClient->assertSentCount(1);
});
