<?php

use OneOffTech\Parse\Client\DocumentFormat\DocumentNode;
use OneOffTech\Parse\Client\Exceptions\EmptyDocumentException;
use OneOffTech\Parse\Client\Exceptions\InvalidDocumentFormatException;

test('node created from string', function () {

    $document = DocumentNode::fromString('test content');

    expect($document)
        ->toBeInstanceOf(DocumentNode::class)
        ->toHaveCount(1);

    expect($document->hasContent())
        ->toBeTrue();

    expect($document->isEmpty())
        ->toBeFalse();

    expect($document->text())
        ->toBeString()->toEqual('test content');

    $pages = $document->pages();

    expect($pages)
        ->toHaveCount(1);

});

test('node created from array', function () {
    $document = DocumentNode::fromArray([
        'category' => 'doc',
        'attributes' => null,
        'content' => [
            [
                'category' => 'page',
                'attributes' => [
                    'page' => 1,
                ],
                'content' => [
                    [
                        'role' => 'body',
                        'category' => 'text',
                        'content' => 'This is the page one text',
                        'marks' => [],
                        'attributes' => [],
                    ],
                ],
            ],
        ],
    ]);

    expect($document)
        ->toBeInstanceOf(DocumentNode::class)
        ->toHaveCount(1);

    expect($document->isEmpty())
        ->toBeFalse();

    expect($document->hasContent())
        ->toBeTrue();

    expect($document->text())
        ->toBeString()->toEqual('This is the page one text');

    $pages = $document->pages();

    expect($pages)
        ->toHaveCount(1);

});

test('throws if empty document', function () {
    DocumentNode::fromString('')->throwIfNoContent();
})->throws(EmptyDocumentException::class, 'Document has no textual content.');

test('throws if missing category', function () {
    DocumentNode::fromArray([
        'attributes' => null,
        'content' => [
            [
                'category' => 'page',
                'attributes' => [
                    'page' => 1,
                ],
                'content' => [
                    [
                        'role' => 'body',
                        'category' => 'text',
                        'content' => 'This is the page one text',
                        'marks' => [],
                        'attributes' => [],
                    ],
                ],
            ],
        ],
    ]);

})->throws(InvalidDocumentFormatException::class, 'Unexpected document structure. Missing category or content.');

test('throws if missing content', function () {
    DocumentNode::fromArray([
        'category' => 'doc',
        'attributes' => null,
    ]);

})->throws(InvalidDocumentFormatException::class, 'Unexpected document structure. Missing category or content.');

test('throws if category is not doc', function () {
    DocumentNode::fromArray([
        'category' => 'something',
        'attributes' => null,
        'content' => [
            [
                'category' => 'page',
                'attributes' => [
                    'page' => 1,
                ],
                'content' => [
                    [
                        'role' => 'body',
                        'category' => 'text',
                        'content' => 'This is the page one text',
                        'marks' => [],
                        'attributes' => [],
                    ],
                ],
            ],
        ],
    ]);

})->throws(InvalidDocumentFormatException::class, 'Unexpected node category. Expecting [doc] found [something].');

test('throws if content is not an array', function () {
    DocumentNode::fromArray([
        'category' => 'doc',
        'attributes' => null,
        'content' => 'a string',
    ]);

})->throws(InvalidDocumentFormatException::class, 'Unexpected content format. Expecting [array].');

test('can be serialized in json', function () {

    $expectedContent = [
        'category' => 'doc',
        'attributes' => null,
        'content' => [
            [
                'category' => 'page',
                'attributes' => [
                    'page' => 1,
                ],
                'content' => [
                    [
                        'role' => 'body',
                        'category' => 'text',
                        'content' => 'This is the page one text',
                        'marks' => [],
                        'attributes' => [],
                    ],
                ],
            ],
        ],
    ];

    $document = DocumentNode::fromArray($expectedContent);

    expect($document->toArray())
        ->toEqual($expectedContent);

    expect($document->toJson())
        ->toEqual(json_encode($expectedContent));

    expect(json_encode($document))
        ->toEqual(json_encode($expectedContent));

});
