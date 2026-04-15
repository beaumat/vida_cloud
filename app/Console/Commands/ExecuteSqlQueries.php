<?php

namespace App\Console\Commands;

use App\Services\TimerServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExecuteSqlQueries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:treatment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute SQL queries at at end the day';


    /**
     * The DatabaseService instance.
     *
     * @var TimerServices
     */
    protected $timerServices;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TimerServices $timerServices)
    {
        parent::__construct();

        $this->timerServices = $timerServices;
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //
        // $ php artisan schedule:work = must run per minute
        $this->timerServices->getExecute();
        return 0;
    }
}
