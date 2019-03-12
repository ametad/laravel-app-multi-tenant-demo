<?php

namespace App\Traits\Console\Commands;

use Illuminate\Support\Collection;

trait DisplayTables
{
    /**
     * @param Collection $clusters
     */
    protected function clustersTable(Collection $clusters): void
    {
        $headers = ['id', 'uuid', 'created at', 'updated at', 'deleted at'];
        $this->table($headers, $clusters);
    }

    /**
     * @param Collection $websites
     */
    protected function websitesTable(Collection $websites): void
    {
        $headers = ['id', 'uuid', 'name', 'created at', 'updated at', 'deleted at', 'managed by database connection', 'cluster'];
        $this->table($headers, $websites);
    }

    /**
     * @param Collection $hostnames
     */
    protected function hostnamesTable(Collection $hostnames): void
    {
        $headers = ['id', 'hostname', 'redirect to', 'force https', 'under maintenance since', 'website ID', 'name', 'created at', 'updated at', 'deleted at'];
        $this->table($headers, $hostnames);
    }
}