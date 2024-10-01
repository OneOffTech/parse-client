<?php

namespace OneOffTech\Parse\Client\DocumentFormat;

use Countable;
use OneOffTech\Parse\Client\Exceptions\EmptyDocumentException;
use OneOffTech\Parse\Client\Exceptions\InvalidDocumentFormatException;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class DocumentNode implements Countable
{
    public function __construct(
        public readonly array $content,
        public readonly array $attributes = [],
    ) {}

    public function type(): string
    {
        return 'doc';
    }

    /**
     * The number of pages in this document as extracted by the parser.
     */
    public function count(): int
    {
        return count($this->content);
    }

    /**
     * Test if the document is empty, i.e. contains no pages or has no textual content on any of the pages
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0 || ! $this->hasContent();
    }

    /**
     * Test if the document has discernible textual content on any of the pages
     */
    public function hasContent(): bool
    {
        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($this->content), RecursiveIteratorIterator::LEAVES_ONLY) as $key => $value) {
            if ($key === 'text' && ! empty($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * The pages in this document
     *
     * @return \OneOffTech\Parse\Client\DocumentFormat\PageNode[]
     */
    public function pages(): array
    {
        return array_map(fn ($page) => PageNode::fromArray($page), $this->content);
    }

    public function text(): string
    {
        $text = [];

        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($this->content), RecursiveIteratorIterator::LEAVES_ONLY) as $key => $value) {
            if ($key === 'text' && ! empty($value)) {
                $text[] = $value;
            }
        }

        return implode(PHP_EOL, $text);
    }

    /**
     * Throw exception if document has no textual content
     *
     * @throws OneOffTech\Parse\Client\Exceptions\EmptyDocumentException when document has no textual content
     */
    public function throwIfNoContent(): self
    {
        if (! $this->hasContent()) {
            throw new EmptyDocumentException('Document has no textual content.');
        }

        return $this;
    }

    /**
     * Create a document node from associative array
     */
    public static function fromArray(array $data): DocumentNode
    {
        if (! (isset($data['category']) && isset($data['content']))) {
            throw new InvalidDocumentFormatException('Unexpected document structure. Missing category or content.');
        }

        if ($data['category'] !== 'doc') {
            throw new InvalidDocumentFormatException("Unexpected node category. Expecting [doc] found [{$data['category']}].");
        }

        if (! is_array($data['content'])) {
            throw new InvalidDocumentFormatException('Unexpected content format. Expecting [array].');
        }

        return new DocumentNode($data['content'] ?? [], $data['attributes'] ?? []);
    }
}
