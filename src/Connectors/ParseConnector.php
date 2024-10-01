<?php

namespace OneOffTech\Parse\Client\Connectors;

use OneOffTech\Parse\Client\DocumentProcessor;
use OneOffTech\Parse\Client\Dto\DocumentDto;
use OneOffTech\Parse\Client\ParseOption;
use OneOffTech\Parse\Client\Requests\ExtractTextRequest;
use OneOffTech\Parse\Client\Responses\ParseResponse;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\NullAuthenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;
use SensitiveParameter;

class ParseConnector extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use HasTimeout;

    protected int $connectTimeout = 30;

    protected int $requestTimeout = 120;

    protected ?string $response = ParseResponse::class;

    public function __construct(

        /**
         * The authentication token
         */
        #[SensitiveParameter]
        public readonly ?string $token = null,

        /**
         * The base url where the API listen
         */
        protected readonly string $baseUrl = 'https://parse.oneofftech.de/api/v0',
    ) {
        //
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function defaultAuth(): Authenticator
    {
        if (is_null($this->token)) {
            return new NullAuthenticator;
        }

        return new TokenAuthenticator($this->token);
    }

    /**
     * Determine if the request has failed.
     */
    public function hasRequestFailed(Response $response): ?bool
    {
        return $response->serverError() || $response->clientError();
    }

    // Resources and helper methods

    /**
     * Parse a document hosted on a web server
     *
     * @param  string  $url  The URL under which the document is accessible
     * @param  string  $mimeType  The mime type of the document. Default application/pdf
     * @param  \OneOffTech\Parse\Client\ParseOption  $options  Specifiy additional options for the specific parsing processor
     */
    public function parse(string $url, string $mimeType = 'application/pdf', ?ParseOption $options = null): DocumentDto
    {
        return $this
            ->send((new ExtractTextRequest(
                url: $url,
                mimeType: $mimeType,
                preferredDocumentProcessor: $options?->processor?->value ?? DocumentProcessor::PDFACT->value,
            ))->validate())
            ->dto();
    }
}
