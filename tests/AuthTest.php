<?php

use OneOffTech\Parse\Client\Connectors\ParseConnector;
use OneOffTech\Parse\Client\Requests\ExtractTextRequest;
use Saloon\Http\Auth\NullAuthenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('ensure connector support null token', function () {
    $mockClient = MockClient::global([
        ExtractTextRequest::class => MockResponse::fixture('extract-text'),
    ]);

    $connector = new ParseConnector;

    $connector->withMockClient($mockClient);

    expect($connector->getAuthenticator())
        ->toBeInstanceOf(NullAuthenticator::class);
});

test('ensure connector uses token authentication', function () {
    $mockClient = MockClient::global([
        ExtractTextRequest::class => MockResponse::fixture('extract-text'),
    ]);

    $connector = new ParseConnector('token');

    $connector->withMockClient($mockClient);

    expect($connector->getAuthenticator())
        ->toBeInstanceOf(TokenAuthenticator::class);
});
