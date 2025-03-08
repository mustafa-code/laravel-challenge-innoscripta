<?php

namespace App\Services\DataSources;

use App\Services\NewsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewsApiService extends NewsService
{

    public function getData($sourceId, $fromDate = null): array
    {
        $apiKey = config('services.news_sources.news_api.api_key');

        $url = "https://newsapi.org/v2/everything";
        $queryParams = [
            'apiKey' => $apiKey,
            'q' => 'trending',
            'pageSize' => 100,
        ];
        if($fromDate) {
            $queryParams['from'] = $fromDate->toIso8601String();
        }
        $response = Http::get($url, $queryParams);

        if ($response->failed()) {
            report("Failed to get data from NewsAPI.");
            return [];
        }
        $list = $response->json("articles");

        return $this->mapResponseIntoArticle($list, $sourceId);
    }

    private function mapResponseIntoArticle(array $list, $sourceId) : array {
        $articles = [];
        foreach ($list as $item) {
            $articles[] = [
                'title' => $item['title'] ?? null,
                'description' => $item['description'],
                'content' => $item['content'],
                'author' => $item['author'] ?? null,
                'source_id' => $sourceId,
                'category' => null,  // No category in News API
                'published_at' => Carbon::parse($item['publishedAt'])->toDateTimeString() ?? null,
                'url' => $item['url'] ?? null,
                'image_url' => $item['urlToImage'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $articles;
    }
}
