<?php

namespace App\Services\DataSources;

use App\Services\NewsService;
use Illuminate\Support\Facades\Http;

class GuardianService extends NewsService {

    public function getData() {
        $apiKey = config('services.news_sources.guardian.api_key');

        $url = "https://content.guardianapis.com/search";
        $response = Http::get($url, [
            'api-key' => $apiKey,
        ]);
    }

}

