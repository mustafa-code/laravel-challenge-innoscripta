<?php

namespace App\Services;

use App\Services\DataSources\GuardianService;
use App\Services\DataSources\NewsApiService;
use App\Services\DataSources\NewYorkTimesService;
use Exception;

abstract class NewsService
{

    // Add apstract function to get data
    public abstract function getData($sourceId) : array;

    // add get instance function,
    public static function getInstance($forceSource)
    {
        if ($forceSource == "Guardian") {
            return new GuardianService();
        } elseif ($forceSource == "NewsApi") {
            return new NewsApiService();
        } elseif ($forceSource == "NewYorkTimes") {
            return new NewYorkTimesService();
        } else {
            return null;
        }
    }
}
