<?php

namespace App\Console\Commands;

use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Contracts\Website;
use Illuminate\Console\Command;

class WebsiteDeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'website:delete
                            {website : ID of the website to delete}
                            {--restore : Restore the deleted website}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete tenant website';

    /**
     * @var WebsiteRepository
     */
    private $websiteRepository;

    /**
     * Create a new command instance.
     *
     * @param WebsiteRepository $websiteRepository
     */
    public function __construct(WebsiteRepository $websiteRepository)
    {
        parent::__construct();
        $this->websiteRepository = $websiteRepository;
    }

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle(): void
    {
        $id = $this->argument('website');

        if ($this->option('restore')) {
            $this->restoreWebsite($id);

            return;
        }

        $this->error("Be advised, this will delete website with ID {$id}");

        if ($this->option('no-interaction') || $this->confirm('Delete website?')) {
            $this->deleteWebsite($id);
        }
    }

    /**
     * @param $id
     */
    private function restoreWebsite($id): void
    {
        $this->error('Restoring automatically is not implemented yet');
    }

    /**
     * @param $id
     * @throws \Exception
     */
    private function deleteWebsite($id): void
    {
        /** @var Website $website */
        $website = $this->websiteRepository->query()->findOrFail($id);

        $this->websiteRepository->delete($website);

        $this->info("Website with ID {$id} is deleted");
    }
}
