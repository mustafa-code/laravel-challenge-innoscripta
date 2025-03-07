<?php

namespace App\Services\DataSources;

use App\Services\NewsService;

class NewsApiService extends NewsService {

    public function getData() {
        // https://newsapi.org/v2/everything?q=trending&apiKey=8d03cb57e8564e5abb68d4916c95108c
    }

}

