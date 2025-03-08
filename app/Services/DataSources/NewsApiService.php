<?php

namespace App\Services\DataSources;

use App\Services\NewsService;
use Illuminate\Support\Facades\Http;

class NewsApiService extends NewsService {

    public function getData() {
        $apiKey = config('services.news_sources.news_api.api_key');

        $url = "https://newsapi.org/v2/everything";
        $response = Http::get($url, [
            'apiKey' => $apiKey,
            'q' => 'trending',
        ]);
    }

}

