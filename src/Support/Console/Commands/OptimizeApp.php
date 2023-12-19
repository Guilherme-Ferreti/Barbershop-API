<?php

declare(strict_types=1);

namespace Support\Console\Commands;

use Illuminate\Console\Command;

class OptimizeApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimize-app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache all framework cachable files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Clearing application cache...');

        $this->call('optimize:clear');

        $this->info('Caching files...');

        $this->call('optimize');
        $this->call('event:cache');
        $this->call('view:cache');

        $this->info('Application cached successfully!');
    }
}
