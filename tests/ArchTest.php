<?php

use OneOffTech\Parse\Client\Connectors\ParseConnector;
use OneOffTech\Parse\Client\Requests\ExtractTextRequest;

it('will not use debugging functions')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'var_export'])
    ->not->toBeUsed();

test('ParseConnector is a Saloon connector')
    ->expect(ParseConnector::class)
    ->toBeSaloonConnector()
    ->toUseAcceptsJsonTrait()
    ->toUseTokenAuthentication()
    ->toUseAlwaysThrowOnErrorsTrait();

test('ExtractTextRequest is a Saloon request')
    ->expect(ExtractTextRequest::class)
    ->toBeSaloonRequest()
    ->toSendPostRequest()
    ->toHaveJsonBody();
