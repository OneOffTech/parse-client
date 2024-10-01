<?php

namespace OneOffTech\Parse\Client;

class ParseOption
{
    public function __construct(
        /**
         * The preferred document processor to use to extract text
         */
        public DocumentProcessor $processor,
    ) {}
}
