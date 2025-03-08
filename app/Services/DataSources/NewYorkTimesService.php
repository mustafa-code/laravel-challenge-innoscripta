<?php

namespace App\Services\DataSources;

use App\Services\NewsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewYorkTimesService extends NewsService
{
    public function getData($sourceId, $fromDate = null): array
    {
        $apiKey = config('services.news_sources.new_york_times.api_key');

        $url = "https://api.nytimes.com/svc/mostpopular/v2/viewed/1.json";
        $response = Http::get($url, [
            'api-key' => $apiKey,
        ]);

        if ($response->failed()) {
            report("Failed to get data from NewYorkTimes.");
            return [];
        }
        $list = $response->json("results");

        $articles = $this->mapResponseIntoArticle($list, $sourceId);

        // Because nytimes API do not provide a built-in filter for from date, I have impleted manual filter.
        if ($fromDate) {
            $filteredArticles = array_filter($articles, function ($article) use ($fromDate) {
                $publishedDate = Carbon::parse($article['published_at']);
                return $publishedDate->greaterThanOrEqualTo($fromDate);
            });
            return $filteredArticles;
        }

        return $articles;
    }

    private function mapResponseIntoArticle(array $list, $sourceId): array
    {
        $articles = [];

        foreach ($list as $item) {
            $mediaMetadata = $item['media'][0]['media-metadata'] ?? [];
            $largestImage = null;

            foreach ($mediaMetadata as $image) {
                if ($largestImage === null || ($image['height'] > $largestImage['height'] && $image['width'] > $largestImage['width'])) {
                    $largestImage = $image;
                }
            }

            $articles[] = [
                'title' => $item['title'] ?? null,
                'description' => $item['abstract'],
                'content' => $item['abstract'],
                'author' => $item['byline'] ?? null,
                'source_id' => $sourceId,
                'category' => $item['section'] ?? $item['subsection'] ?? null,
                'published_at' => Carbon::parse($item['published_date'])->toDateTimeString() ?? null,
                'url' => $item['url'] ?? null,
                'image_url' => $largestImage['url'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        return $articles;
    }
}
