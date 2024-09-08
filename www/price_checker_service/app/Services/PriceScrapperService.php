<?php

namespace App\Services;

use App\Exceptions\ParsingErrorException;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class PriceScrapperService
{
    public function __construct(public string $link)
    {
    }

    private function getDataFromSource(): string
    {
        return Http::get($this->link)
            ->body();
    }

    public function parse(): int
    {
        $data = $this->getDataFromSource();
        $crawler = new Crawler($data);

        try {
            $parsedPrice = $crawler->filter('.css-1dp6pbg .css-e2ir3r h3')->text();
        } catch (\Exception $exception) {
            throw new ParsingErrorException();
        }

        return preg_replace('/[^0-9]+/', '',
            $parsedPrice
        );
    }
}
