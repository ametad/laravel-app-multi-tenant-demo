<?php

namespace App\Console\Commands;

use App\Traits\Console\Commands\DisplayTables;
use Hyn\Tenancy\Contracts\Hostname;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Illuminate\Console\Command;

class HostnameCommand extends Command
{
    use DisplayTables;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hostname
                            {hostname? : ID of the hostname for details}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List tenant hostnames or show details of one';

    /**
     * @var HostnameRepository
     */
    private $hostnameRepository;

    /**
     * Create a new command instance.
     *
     * @param HostnameRepository $hostnameRepository
     */
    public function __construct(HostnameRepository $hostnameRepository)
    {
        parent::__construct();
        $this->hostnameRepository = $hostnameRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($id = $this->argument('hostname')) {

            $this->showDetailsOfHostname($id);

        } else {

            $this->listHostnames();
        }
    }

    /**
     * @param $id
     * @return void
     */
    private function showDetailsOfHostname($id): void
    {
        /** @var Hostname $hostname */
        $hostname = $this->hostnameRepository->query()->withTrashed()->findOrFail($id);

        /*
         * General
         */
        $this->hostnamesTable(collect([$hostname]));

        /*
         * Attached to website
         */
        $this->line('Attached to website');
        $website = $hostname->website()->withTrashed()->get();
        $this->websitesTable($website);
    }

    private function listHostnames(): void
    {
        $this->line('List of hostnames');
        $hostnames = $this->hostnameRepository->query()->withTrashed()->get();
        $this->hostnamesTable($hostnames);
    }
}
