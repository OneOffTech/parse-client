# OneOffTech Parse client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/oneofftech/parse-client.svg?style=flat-square)](https://packagist.org/packages/oneofftech/parse-client)
[![Tests](https://github.com/OneOffTech/parse-client/actions/workflows/run-tests.yml/badge.svg)](https://github.com/OneOffTech/parse-client/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/oneofftech/parse-client.svg?style=flat-square)](https://packagist.org/packages/oneofftech/parse-client)

Parse client is a library to interact with [OneOffTech Parse](https://parse.oneofftech.de) service. OneOffTech Parse is designed to extract text from PDF files preserving the [structure of the document](#document-structure) to improve interaction with Large Language Models (LLMs).

OneOffTech Parse is based on [Parxy extractor](https://github.com/OneOffTech/parxy). The client is also suitable to connect to self-hosted versions of [Parxy](https://github.com/OneOffTech/parxy).


> [!NOTE]
> The Parse client package is under development and is not ready for production use.


## Installation

You can install the package via Composer:

```bash
composer require oneofftech/parse-client
```

## Usage

The Parse client is able to connect to self-hosted instances of the [Parxy](https://github.com/OneOffTech/parxy) extractor service or the cloud hosted [OneOffTech Parse](https://parse.oneofftech.de) service.

### Use with self-hosted instance

Before proceeding a running instance of [Parxy](https://github.com/OneOffTech/parxy) is required. Once you have a running instance, you can instantiate the connector by passing the url that the extractor service is listening on.

```php
use OneOffTech\Parse\Client\Connectors\ParseConnector;

$client = new ParseConnector(baseUrl: "http://localhost:5000");

/** @var \OneOffTech\Parse\Client\Dto\DocumentDto */
$document = $client->parse("https://domain.internal/document.pdf");
```

> [!NOTE] 
> - The URL of the document must be accessible without authentication.
> - Documents are downloaded for the time of processing and then the file is immediately deleted.


### Use the cloud hosted service

> [!IMPORTANT]
> The cloud hosted service is currently in private beta. [Drop us a message](https://oneofftech.de/).

Go to [parse.oneofftech.de](https://parse.oneofftech.de) and obtain an access token. Instantiate the client and provide a URL of a PDF document. 

```php
use OneOffTech\Parse\Client\Connectors\ParseConnector;

$client = new ParseConnector("token");

/** @var \OneOffTech\Parse\Client\Dto\DocumentDto */
$document = $client->parse("https://domain.internal/document.pdf");
```

> [!NOTE] 
> - The URL of the document must be accessible without authentication.
> - Documents are downloaded for the time of processing and then the file is immediately deleted.


### Specify the preferred extraction method

Parse service supports different processors, [`pymupdf`](https://github.com/pymupdf/PyMuPDF) or [`pdfact`](https://github.com/data-house/pdfact), [`unstructured`](https://unstructured.io/) and [`llamaparse`](https://docs.cloud.llamaindex.ai/llamaparse/getting_started). You can specify the preferred processor for each request.

```php
use OneOffTech\Parse\Client\ParseOption;
use OneOffTech\Parse\Client\DocumentProcessor;
use OneOffTech\Parse\Client\Connectors\ParseConnector;

$client = new ParseConnector("token");

/** @var \OneOffTech\Parse\Client\Dto\DocumentDto */
$document = $client->parse(
    url: "https://domain.internal/document.pdf", 
    options: new ParseOption(DocumentProcessor::PYMUPDF)
);
```

### PDFAct vs PyMuPDF

PDFAct offers more flexibility than PyMuPDF. You should evaluate the extraction method best suitable for your application. Here is a small comparison of the two methods.

| feature                           | PDFAct | PyMuPDF |
|-----------------------------------|--------|---------|
| Text extraction                   | :white_check_mark: | :white_check_mark: |
| Pagination                        | :white_check_mark: | :white_check_mark: |
| Headings identification           | :white_check_mark: | - |
| Text styles (e.g. bold or italic) | :white_check_mark: | - |
| Page header                       | :white_check_mark: | - |
| Page footer                       | :white_check_mark: | - |




## Document structure

Parse is designed to preserve the document's structure hence the content is returned in a hierarchical fashion.

```
Document
 ├─Page
 │  ├─Text (category: heading)
 │  └─Text (category: body)
 └─Page
    ├─Text (category: heading)
    └─Text (category: body)
```

For a more in-depth explanation of the structure see [Parse Document Model](https://github.com/OneOffTech/parse-document-model-python).


## Testing

Parse client is tested using [PEST](https://pestphp.com/). Tests run for each commit and pull request.

To execute the test suite run:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to the Parse client! The contribution guide can be found in the [CONTRIBUTING.md](./.github/CONTRIBUTING.md) file.

## Security Vulnerabilities

Please review [our security policy](./.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [OneOffTech](https://github.com/OneOffTech)
- [All Contributors](../../contributors)

## Supporters

The project is provided and supported by [OneOff-Tech (UG)](https://oneofftech.de).

<p align="left"><a href="https://oneofftech.de" target="_blank"><img src="https://raw.githubusercontent.com/OneOffTech/.github/main/art/oneofftech-logo.svg" width="200"></a></p>


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
