<?php

namespace App\Console\Commands;

use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Contracts\Website;
use Illuminate\Console\Command;
use Webplaats\TenantClusters\Contracts\Models\Cluster;
use Webplaats\TenantClusters\Contracts\Repositories\ClusterRepository;

class WebsiteUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'website:update
                            {website_id : ID of the website}
                            {--name= : Name of the website}
                            {--managed_by_database_connection= : References the database connection key in your database.php}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update tenant website';

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
     */
    public function handle(): void
    {
        if ($this->option('no-interaction') || $this->confirm('Update website?')) {
            $this->updateWebsite();
        }
    }

    /**
     * Update website
     */
    private function updateWebsite(): void
    {
        $this->line('Updating website ...');

        $id = $this->argument('website_id');
        /** @var Website $website */
        $website = $this->websiteRepository->query()->findOrFail($id);

        /*
         * Parse options
         */
        if ($name = $this->option('name')) {
            $website->name = $name;
        }
        if ($managed_by_database_connection = $this->option('managed_by_database_connection')) {
            $website->managed_by_database_connection = $managed_by_database_connection;
        }

        /*
         * Save to database
         */
        $this->websiteRepository->update($website);

        /*
         * Success message
         */
        $this->info("Website with ID {$id} is updated");
    }
}
