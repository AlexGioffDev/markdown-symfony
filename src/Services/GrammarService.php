<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GrammarService
{
    public function __construct(private HttpClientInterface $client) {}

    public function checkAndFix(string $text): string
    {
        $response = $this->client->request('POST', 'https://api.languagetool.org/v2/check', [
            'body' => [
                'text' => $text,
                'language' => 'en'
            ]
            ]);

        $data = $response->toArray();

        if(!isset($data['matches']))
        {
            return $text;
        }

        $matches = $data['matches'];

        usort($matches, function($a, $b) {
            return $b['offset'] <=> $a['offset'];
        });

        foreach($matches as $match)
        {
            if(!isset($match['replacements'][0]['value']))
            {
                continue;
            }

            $replacement = $match['replacements'][0]['value'];
            $offset = $match['offset'];
            $length = $match['length'];

            $text = substr_replace($text, $replacement, $offset, $length);
        }

        return $text;
    }

}
