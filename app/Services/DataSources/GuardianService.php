<?php

namespace App\Services\DataSources;

use App\Services\NewsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class GuardianService extends NewsService
{

    public function getData($sourceId, $fromDate = null): array
    {
        $apiKey = config('services.news_sources.guardian.api_key');

        $url = "https://content.guardianapis.com/search";
        $queryParams = [
            'api-key' => $apiKey,
            'page-size' => 3000,
            'show-fields' => 'headline,trailText,byline,thumbnail,publication,body',
            'order-by' => 'newest',
        ];
        if ($fromDate) {
            $queryParams['from-date'] = $fromDate->toIso8601String();
        }
        $response = Http::get($url, $queryParams);

        if ($response->failed()) {
            report("Failed to get data from guardianapis.");
            return [];
        }
        $list = $response->json("response.results");

        return $this->mapResponseIntoArticle($list, $sourceId);
    }

    private function mapResponseIntoArticle(array $list, $sourceId) : array {
        $articles = [];
        foreach ($list as $item) {
            $description = html_entity_decode($item['fields']['trailText'] ?? '', ENT_QUOTES, 'UTF-8');
            $body = html_entity_decode($item['fields']['body'] ?? '', ENT_QUOTES, 'UTF-8');
            $articles[] = [
                'title' => $item['fields']['headline'] ?? null,
                'description' => $description,
                'content' => $body,
                'author' => $item['fields']['byline'] ?? null,
                'source_id' => $sourceId,
                'category' => $item['sectionName'] ?? null,
                'published_at' => Carbon::parse($item['webPublicationDate'])->toDateTimeString() ?? null,
                'url' => $item['webUrl'] ?? null,
                'image_url' => $item['fields']['thumbnail'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $articles;
    }
}
