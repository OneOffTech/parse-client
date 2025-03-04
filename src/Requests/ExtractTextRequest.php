<?php

namespace OneOffTech\Parse\Client\Requests;

use InvalidArgumentException;
use OneOffTech\Parse\Client\Dto\DocumentDto;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ExtractTextRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $url,
        protected readonly string $preferredDocumentProcessor = 'pdfact'
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return '/extract-text';
    }

    protected function defaultBody(): array
    {
        return [
            'url' => $this->url,
            'driver' => $this->preferredDocumentProcessor ?? 'pdfact',
        ];
    }

    public function validate(): self
    {
        if (empty(trim($this->url))) {
            throw new InvalidArgumentException('The [url] is required to be non-empty.');
        }

        return $this;
    }

    public function createDtoFromResponse(Response $response): DocumentDto
    {
        $data = $response->json();

        return (new DocumentDto($data))->setResponse($response);
    }
}
