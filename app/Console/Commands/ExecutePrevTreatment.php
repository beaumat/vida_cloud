<?php
namespace App\Console\Commands;

use App\Services\TimerServices;
use Illuminate\Console\Command;

class ExecutePrevTreatment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:previous';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute SQL queries at at end the previous day';

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
     */
    public function handle()
    {
        $this->timerServices->getExecutePrevious();
        return 0;
    }
}
