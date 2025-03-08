<?php

namespace App\Console\Commands;

use App\Models\Source;
use App\Repositories\ArticleRepositoryInterface;
use App\Services\NewsService;
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
        $sources = Source::active()->get();
        foreach ($sources as $source) {
            $this->info("Start fetching data from $source->name");
            $dataSource = NewsService::getInstance($source->name);
            if ($dataSource == null) {
                $this->info("Invalid data source name: $source->name");
                continue;
            }

            $articles = $dataSource->getData();

            $isDone = $this->articleRepository->store($articles);

            if($isDone){
                $this->info("Complete fetching data from: $source->name");
                $source->update([
                    'last_fetched_at' => now(),
                ]);
            }
        }
    }
}
