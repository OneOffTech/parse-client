<?php

namespace OneOffTech\Parse\Client\DocumentFormat;

use Countable;
use OneOffTech\Parse\Client\Exceptions\InvalidDocumentFormatException;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class PageNode implements Countable
{
    public function __construct(
        public readonly array $content,
        public readonly array $attributes = [],
    ) {}

    public function type(): string
    {
        return 'page';
    }

    /**
     * The number of elements in this page.
     */
    public function count(): int
    {
        return count($this->content);
    }

    /**
     * Test if the page is empty, i.e. contains no textual content
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0 || ! $this->hasContent();
    }

    /**
     * Test if the page has discernible textual content
     */
    public function hasContent(): bool
    {
        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($this->content), RecursiveIteratorIterator::LEAVES_ONLY) as $key => $value) {
            if (($key === 'text' || $key === 'content') && ! empty($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * The elements in this page
     */
    public function items(): array
    {
        return $this->content;
    }

    public function text(): string
    {
        $text = [];

        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($this->content), RecursiveIteratorIterator::LEAVES_ONLY) as $key => $value) {
            if (($key === 'text' || $key === 'content') && ! empty($value)) {
                $text[] = $value;
            }
        }

        return implode(PHP_EOL, $text);
    }

    public function number(): int
    {
        return (int) $this->attributes['page'] ?? 1;
    }

    /**
     * Create a page node from associative array
     */
    public static function fromArray(array $data): PageNode
    {
        if (! (isset($data['category']) && isset($data['content']))) {
            throw new InvalidDocumentFormatException('Unexpected document structure. Missing category or content.');
        }

        if ($data['category'] !== 'page') {
            throw new InvalidDocumentFormatException("Unexpected node category. Expecting [doc] found [{$data['category']}].");
        }

        if (! is_array($data['content'])) {
            throw new InvalidDocumentFormatException('Unexpected content format. Expecting [array].');
        }

        return new PageNode($data['content'] ?? [], $data['attributes'] ?? []);
    }
}
