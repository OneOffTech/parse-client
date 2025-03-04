<?php

namespace OneOffTech\Parse\Client;

enum DocumentProcessor: string
{
    /**
     * The PDFAct processor
     *
     * Uses https://github.com/data-house/pdfact as document processor to extract text
     */
    case PDFACT = 'pdfact';

    /**
     * The PymuPDF processor
     *
     * Uses https://github.com/pymupdf/PyMuPDF as document processor to extract text
     */
    case PYMUPDF = 'pymupdf';

    /**
     * The LLama Parse processor
     *
     * Uses LLamaCloud https://cloud.llamaindex.ai/ as document processor to extract text
     */
    case LLAMAPARSE = 'llama';

    /**
     * The Unstructured processor
     *
     * Uses Unstructored https://unstructured.io/ as document processor to extract text
     */
    case UNSTRUCTURED = 'unstructured';
}
