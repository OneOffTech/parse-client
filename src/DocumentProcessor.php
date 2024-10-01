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
}
