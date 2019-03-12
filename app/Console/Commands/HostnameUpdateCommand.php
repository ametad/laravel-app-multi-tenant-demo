<?php

namespace App\Console\Commands;

use Hyn\Tenancy\Contracts\Hostname;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Contracts\Website;
use Illuminate\Console\Command;

class HostnameUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hostname:update
                            {hostname_id : ID of the hostname}
                            {--website_id= : ID of the website to attach to}
                            {--fqdn= : Hostname Fully Qualified Domain Name}
                            {--redirect_to= : Redirect this domain here}
                            {--force_https : Always redirect to https}
                            {--under_maintenance_since= : Date from when this site shows a 503}
                            {--name= : Referential name or label}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update tenant hostname';

    /**
     * @var HostnameRepository
     */
    private $hostnameRepository;

    /**
     * @var WebsiteRepository
     */
    private $websiteRepository;

    /**
     * Create a new command instance.
     *
     * @param HostnameRepository $hostnameRepository
     * @param WebsiteRepository $websiteRepository
     */
    public function __construct(HostnameRepository $hostnameRepository, WebsiteRepository $websiteRepository)
    {
        parent::__construct();
        $this->hostnameRepository = $hostnameRepository;
        $this->websiteRepository = $websiteRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($this->option('no-interaction') || $this->confirm('Update hostname?')) {
            $this->updateHostname();
        }
    }

    /**
     * Update hostname
     */
    private function updateHostname(): void
    {
        $this->line("Updating hostname...");

        /*
         * Parse arguments
         */
        /** @var Hostname $hostname */
        $hostname = $this->hostnameRepository->query()->findOrFail($this->argument('hostname_id'));

        /*
         * Parse options
         */
        if ($this->option('fqdn') !== null) {
            $hostname->fqdn = $this->option('fqdn');
        }
        if ($this->option('redirect_to') !== null) {
            $hostname->redirect_to = $this->option('redirect_to');
        }
        if ($this->option('force_https') !== null) {
            $hostname->force_https = $this->option('force_https');
        }
        if ($this->option('under_maintenance_since') !== null) {
            $hostname->under_maintenance_since = $this->option('under_maintenance_since');
        }
        if ($this->option('name') !== null) {
            $hostname->name = $this->option('name');
        }

        $this->hostnameRepository->update($hostname);

        /*
         * Attach to website if needed
         */
        if ($website_id = $this->option('website_id')) {
            /** @var Website $website */
            $website = $this->websiteRepository->query()->withTrashed()->findOrFail($website_id);
            $this->hostnameRepository->attach($hostname, $website);

            $this->info("Hostname attached to website with ID {$website_id}");
        }

        /*
         * Show success
         */
        $this->info("Hostname with ID {$hostname->id} is updated");
    }
}
