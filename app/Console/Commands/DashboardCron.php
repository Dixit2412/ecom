<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DashboardCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron For update dashboard counter';

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
     * @return mixed
     */
    public function handle()
    {
        \Log::info("Cron working..!!");


    }
}
