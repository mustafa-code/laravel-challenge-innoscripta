<?php

namespace App\Services;

use Exception;

abstract class NewsService
{

    // Add apstract function to get data
    public abstract function getData();

    // add get instance function,
    public static function getInstance($forceSource = null)
    {
        throw new Exception('Invalid data source.');
    }
}
