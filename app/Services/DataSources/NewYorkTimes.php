<?php

namespace App\Services\DataSources;

use App\Services\NewsService;
use Illuminate\Support\Facades\Http;

class NewYorkTimes extends NewsService {
    public function getData()
    {
        $apiKey = config('services.news_sources.new_york_times.api_key');

        $url = "https://api.nytimes.com/svc/mostpopular/v2/viewed/1.json";
        $response = Http::get($url, [
            'api-key' => $apiKey,
        ]);

    }
}
