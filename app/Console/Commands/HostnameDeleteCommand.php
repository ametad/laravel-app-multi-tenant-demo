<?php

namespace App\Console\Commands;

use Hyn\Tenancy\Contracts\Hostname;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Illuminate\Console\Command;

class HostnameDeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hostname:delete
                            {hostname : ID of the hostname to delete}
                            {--restore : Restore the deleted hostname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete tenant hostname';

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
     *
     * @throws \Exception
     */
    public function handle(): void
    {
        $id = $this->argument('hostname');

        if ($this->option('restore')) {
            $this->restoreHostname($id);

            return;
        }

        $this->error("Be advised, this will delete hostname with ID {$id}");

        if ($this->option('no-interaction') || $this->confirm('Delete hostname?')) {
            $this->deleteHostname($id);
        }
    }

    /**
     * @param $id
     * @throws \Exception
     */
    private function deleteHostname($id): void
    {
        /** @var Hostname $hostname */
        $hostname = $this->hostnameRepository->query()->findOrFail($id);

        $this->hostnameRepository->delete($hostname);

        $this->info("Hostname with ID {$id} is deleted");
    }

    /**
     * @param $id
     */
    private function restoreHostname($id): void
    {
        $this->error('Restoring automatically is not implemented yet');
    }
}
