<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Source;
use App\Repositories\ArticleRepositoryInterface;
use App\Services\NewsService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports products into database';

    public function __construct(public ArticleRepositoryInterface $articleRepository)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $checkd = $this->checkConfig();
        if (!$checkd) {
            $this->info("Please check your API Keys in .env file");
            return;
        }

        $sources = Source::active()->get();
        foreach ($sources as $source) {
            $this->info("Start fetching data from $source->name");
            $dataSource = NewsService::getInstance($source->name);
            if ($dataSource == null) {
                $this->info("Invalid data source name: $source->name");
                continue;
            }

            $lastArticle = Article::where('source_id', $source->id)
                ->orderBy('published_at', 'desc')
                ->first();
            $fromDate = null;
            if($lastArticle) {
                // Add one second to the last fetched article to prevent duplicate last one.
                $fromDate = Carbon::parse($lastArticle->published_at)->addSecond();
            }

            $articles = $dataSource->getData($source->id, $fromDate);
            $count = count($articles);
            $this->info("Fetch $count articles from $source->name");

            if($count == 0){
                continue;
            }
            $isDone = $this->articleRepository->store($articles);

            if($isDone){
                $this->info("Complete fetching data from: $source->name");
                $source->update([
                    'last_fetched_at' => now(),
                ]);
            }
        }
    }

    private function checkConfig() : bool {
        $nytApiKey = config('services.news_sources.new_york_times.api_key');
        $guardianApiKey = config('services.news_sources.guardian.api_key');
        $newsApiKey = config('services.news_sources.news_api.api_key');

        return $nytApiKey || $guardianApiKey || $newsApiKey;
    }
}
