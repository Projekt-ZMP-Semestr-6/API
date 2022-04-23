<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Game;
use DOMDocument;
use DOMNodeList;
use DOMXPath;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ShowFreebiesService
{
    public function get(): Collection
    {
        $response = Http::get('https://store.steampowered.com/search/', $this->getParams());

        $document = $this->parseContent($response);
        $nodes = $this->getNodes($document);
        $appids = $this->getAppIdsFromNodes($nodes);

        $games = $this->getGames($appids);

        return $games;
    }

    protected function parseContent(Response $response): DOMDocument
    {
        $content = $response->body();
        libxml_use_internal_errors(true);

        $document = new DOMDocument();
        $document->loadHTML($content);

        return $document;
    }

    protected function getNodes(DOMDocument $document): DOMNodeList
    {
        $xpath = new DOMXPath($document);
        $nodes = $xpath->evaluate('//div[@id="search_resultsRows"]/*');

        return $nodes;
    }

    protected function getAppIdsFromNodes(DOMNodeList $nodes): array
    {
        $appids = [];

        foreach($nodes as $node)
        {
            $appid = $node->getAttribute('data-ds-appid');
            $hasManyIds = strstr($appid, ',');

            if($hasManyIds) {
                continue;
            }

            array_push($appids, $appid);
        }

        return $appids;
    }

    protected function getGames(array $appids): Collection
    {
        $games = Game::whereIn('appid', $appids)->get();

        return $games;
    }

    protected function getParams(): array
    {
        return [
            'maxprice'=>'free',
            'specials'=> '1',
            'category1' => '998',
        ];
    }
}