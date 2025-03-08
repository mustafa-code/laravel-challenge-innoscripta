<?php

namespace App\Services\DataSources;

use App\Services\NewsService;
use Illuminate\Support\Facades\Http;

class GuardianService extends NewsService
{

    public function getData($sourceId): array
    {
        $apiKey = config('services.news_sources.guardian.api_key');

        $url = "https://content.guardianapis.com/search";
        $response = Http::get($url, [
            'api-key' => $apiKey,
            'page-size' => 100,
            'show-fields' => 'headline,trailText,byline,thumbnail,publication,body',
            'order-by' => 'newest',
        ]);

        return [];
    }
}
