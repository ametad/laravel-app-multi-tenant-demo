<?php

namespace App\Console\Commands;

use Hyn\Tenancy\Contracts\Hostname;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Contracts\Website;
use Illuminate\Console\Command;

class HostnameCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hostname:create
                            {fqdn : Hostname Fully Qualified Domain Name}
                            {--website_id= : ID of the website to attach to}
                            {--redirect_to= : Redirect this domain here}
                            {--force_https : Always redirect to https}
                            {--under_maintenance_since= : Date from when this site shows a 503}
                            {--name= : Referential name or label}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create tenant hostname';

    /**
     * @var HostnameRepository
     */
    private $hostnameRepository;

    /**
     * @var Hostname
     */
    private $hostname;

    /**
     * @var WebsiteRepository
     */
    private $websiteRepository;

    /**
     * Create a new command instance.
     *
     * @param HostnameRepository $hostnameRepository
     * @param Hostname $hostname
     * @param WebsiteRepository $websiteRepository
     */
    public function __construct(HostnameRepository $hostnameRepository, Hostname $hostname, WebsiteRepository $websiteRepository)
    {
        parent::__construct();
        $this->hostnameRepository = $hostnameRepository;
        $this->hostname = $hostname;
        $this->websiteRepository = $websiteRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($this->option('no-interaction') || $this->confirm('Create hostname?')) {
            $this->createHostname();
        }
    }

    /**
     * Create hostname
     */
    private function createHostname(): void
    {
        if (!$website_id = $this->option('website_id')) {
            $this->error('The option \'website_id\' is mandatory.');

            return;
        }

        $this->line("Creating a new hostname...");

        /*
         * Parse options/arguments to create hostname
         */
        $this->hostname->fqdn = $this->argument('fqdn');
        $this->hostname->redirect_to = $this->option('redirect_to');
        if ($this->option('force_https') !== null) {
            $this->hostname->force_https = $this->option('force_https');
        }
        $this->hostname->under_maintenance_since = $this->option('under_maintenance_since');
        $this->hostname->name = $this->option('name');

        $this->hostnameRepository->create($this->hostname);

        /*
         * Attach to website
         */
        $this->line("Connect to website...");
        /** @var Website $website */
        $website = $this->websiteRepository->query()->withTrashed()->findOrFail($website_id);
        $this->hostnameRepository->attach($this->hostname, $website);

        $this->info("Hostname attached to website with ID {$website_id}");

        /*
         * Show success
         */
        $this->info("New hostname has ID {$this->hostname->id}");
    }
}