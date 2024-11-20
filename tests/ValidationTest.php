<?php

use OneOffTech\Parse\Client\Connectors\ParseConnector;
use OneOffTech\Parse\Client\Requests\ExtractTextRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('url required to be non-empty', function () {
    $mockClient = MockClient::global([
        ExtractTextRequest::class => MockResponse::fixture('extract-text-invalid-url'),
    ]);

    $connector = new ParseConnector('fake', 'http://localhost:5002');
    $connector->withMockClient(MockClient::getGlobal());

    $connector->parse('', 'application/pdf');

    $mockClient->assertNothingSent();

})->throws(InvalidArgumentException::class, 'The [url] is required to be non-empty.');

test('mime type required to be non-null', function () {
    $mockClient = MockClient::global([
        ExtractTextRequest::class => MockResponse::fixture('extract-text-invalid-mime'),
    ]);

    $connector = new ParseConnector('fake', 'http://localhost:5002');
    $connector->withMockClient(MockClient::getGlobal());

    $connector->parse('http://localhost/test.pdf', '');

    $mockClient->assertNothingSent();

})->throws(InvalidArgumentException::class, 'The [mime type] is required to be non-empty.');
