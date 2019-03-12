<?php

namespace App\Console\Commands;

use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Contracts\Website;
use Illuminate\Console\Command;
use Webplaats\TenantClusters\Contracts\Models\Cluster;
use Webplaats\TenantClusters\Contracts\Repositories\ClusterRepository;

class WebsiteCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'website:create
                            {--name= : Name of the website}
                            {--managed_by_database_connection= : References the database connection key in your database.php}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create tenant website';

    /**
     * @var WebsiteRepository
     */
    private $websiteRepository;

    /**
     * @var Website
     */
    private $website;

    /**
     * Create a new command instance.
     *
     * @param WebsiteRepository $websiteRepository
     * @param Website $website
     */
    public function __construct(WebsiteRepository $websiteRepository, Website $website)
    {
        parent::__construct();
        $this->websiteRepository = $websiteRepository;
        $this->website = $website;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($this->option('no-interaction') || $this->confirm('Create a new website?')) {
            $this->createWebsite();
        }
    }

    /**
     * Create website
     */
    private function createWebsite(): void
    {
        $this->line('Creating a new website...');

        $this->website->name = $this->option('name');
        $this->website->managed_by_database_connection = $this->option('managed_by_database_connection');

        $this->websiteRepository->create($this->website);

        $this->info("New website has ID: {$this->website->id} and UUID: {$this->website->uuid}");
    }
}
