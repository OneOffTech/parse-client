<?php

namespace OneOffTech\Parse\Client\Dto;

use Countable;
use Saloon\Traits\Responses\HasResponse;
use Saloon\Contracts\DataObjects\WithResponse;
use OneOffTech\Parse\Client\DocumentFormat\DocumentNode;

class DocumentDto implements WithResponse, Countable
{
    use HasResponse;

    protected readonly DocumentNode $raw;

    public function __construct(array $data) {
        $this->raw = DocumentNode::fromArray($data);
    }

    public function pages(): array
    {
        return $this->raw->pages();
    }

    /**
     * The number of pages in this document
     */
    public function count(): int
    {
        return $this->raw->count();
    }
    
    /**
     * Get the underlying document node
     */
    public function document(): DocumentNode
    {
        return $this->raw;
    }
}
