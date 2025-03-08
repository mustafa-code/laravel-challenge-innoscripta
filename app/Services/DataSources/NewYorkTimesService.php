<?php

namespace App\Services\DataSources;

use App\Services\NewsService;
use Illuminate\Support\Facades\Http;

class NewYorkTimesService extends NewsService
{
    public function getData($sourceId): array
    {
        $apiKey = config('services.news_sources.new_york_times.api_key');

        $url = "https://api.nytimes.com/svc/mostpopular/v2/viewed/1.json";
        $response = Http::get($url, [
            'api-key' => $apiKey,
        ]);

        return [];
    }
}
