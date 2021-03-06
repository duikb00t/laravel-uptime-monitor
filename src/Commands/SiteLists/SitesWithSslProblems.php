<?php

namespace Spatie\UptimeMonitor\Commands\SiteLists;

use Illuminate\Console\Command;
use Spatie\UptimeMonitor\Models\Site;
use Spatie\UptimeMonitor\SiteRepository;

class SitesWithSslProblems
{
    protected $output;

    public function __construct(Command $output)
    {
        $this->output = $output;
    }

    public function display()
    {
        $sitesWithSslProblems = SiteRepository::withSslProblems();

        if (! $sitesWithSslProblems->count()) {
            return;
        }

        $this->output->warn('Sites with ssl problems');
        $this->output->warn('=======================');

        $rows = $sitesWithSslProblems->map(function (Site $site) {
            $url = $site->url;

            $reason = $site->chunkedLastSslFailureReason;

            return compact('url', 'reason');
        });

        $titles = ['URL', 'Problem description'];

        $this->output->table($titles, $rows);
        $this->output->line('');
    }
}
