<?php

namespace App\Console\Commands;

use App\Services\DepreciationServices;
use Illuminate\Console\Command;

class ExecuteDepreciation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:depreciation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * The DatabaseService instance.
     *
     * @var DepreciationServices
     */
    protected $depreciationServices;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DepreciationServices $depreciationServices)
    {
        parent::__construct();

        $this->depreciationServices = $depreciationServices;
    }


    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        $this->depreciationServices->monthlyExecute();
        return 0;
    }
}
