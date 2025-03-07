<?php

namespace App\Services\DataSources;

use App\Services\NewsService;

class NewYorkTimes extends NewsService {
    public function getData()
    {
        // https://api.nytimes.com/svc/mostpopular/v2/viewed/1.json?api-key=60RnkTG49ClKWMlkTG0ZX7CBlCqjaLfh
    }
}
