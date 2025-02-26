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

    $connector->parse('');

    $mockClient->assertNothingSent();

})->throws(InvalidArgumentException::class, 'The [url] is required to be non-empty.');
