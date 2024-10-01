<?php

use Saloon\Config;
use Saloon\Http\Faking\MockClient;

Config::preventStrayRequests();

uses()
    ->beforeEach(fn () => MockClient::destroyGlobal())
    ->in(__DIR__);
