<?php

namespace App\Console\Commands;

use App\Traits\Console\Commands\DisplayTables;
use Hyn\Tenancy\Contracts\Hostname;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Illuminate\Console\Command;

class WebsiteCommand extends Command
{
    use DisplayTables;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'website
                            {website? : ID of the website for details}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List tenant websites or show details of one';

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
        if ($id = $this->argument('website')) {

            $this->showDetailsOfWebsite($id);

        } else {

            $this->listWebsites();
        }
    }

    /**
     * @param $id
     */
    private function showDetailsOfWebsite($id): void
    {
        /** @var Website $website */
        $website = $this->websiteRepository->query()->withTrashed()->findOrFail($id);

        /*
         * General
         */
        $this->websitesTable(collect([$website]));

        /*
         * Attached hostnames
         */
        $this->line('Attached hostnames');
        /** @var Hostname $hostnames */
        $hostnames = $website->hostnames()->withTrashed()->get();
        $this->hostnamesTable($hostnames);
    }

    private function listWebsites(): void
    {
        $this->line('List of websites');
        $websites = $this->websiteRepository->query()->withTrashed()->get();
        $this->websitesTable($websites);
    }
}
