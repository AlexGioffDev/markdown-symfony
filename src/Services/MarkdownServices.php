<?php

namespace App\Services;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;

class MarkdownServices
{
    private MarkdownConverter $converter;

    public function __construct()
    {

        $environmet = new Environment();
        $environmet->addExtension(new CommonMarkCoreExtension());
        $this->converter = new MarkdownConverter($environmet);
    }

    public function convertToHtml(string $markdown): string
    {
        return $this->converter->convert($markdown)->getContent();
    }
}
