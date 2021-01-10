<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class DownloadCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download csv file that contain dates';

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
     * @return int
     */
    public function handle()
    {
        $from = Carbon::now()->format('Y-m-d');
        $to = Carbon::now()->addYear()->format('Y-m-d');
        $name = $this->ask('name of  generated file?  like:  task.csv or we add a default name') ?? 'period.csv';
        $period = CarbonPeriod::create($from, '1 month', $to);
        $monthName = $this->getTheNameOfTheMonth();
        $data = array();
        foreach ($period as $key => $value) {

            $data[$key][0] = $value->format('y-m');
            $data[$key][1] = (new Carbon("first Monday of.$monthName[$key]."))->format('d-m-y');
            $data[$key][2] = ($value->nthOfMonth(3, Carbon::THURSDAY))->format('d-m-y');
            $data[$key][3] = (new Carbon("last FRIDAY of.$monthName[$key]."))->format('d-m-y');
        }
        array_unshift($data, ['Month', 'Brunch & Catchup', 'Thirsty Thursday', 'Friday Fry-up']);

        $fp = fopen($name, 'w');
        foreach ($data as $key => $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
        echo "Good job, The  file is generated in project path";
    }

    /**
     * @return string[]
     */
    public function getTheNameOfTheMonth(): array
    {
        return [
            0 => 'january',
            1 => 'january',
            2 => 'february',
            3 => 'march',
            4 => 'april',
            5 => 'may',
            6 => 'june',
            7 => 'july',
            8 => 'august',
            9 => 'september',
            10 => 'october',
            11 => 'november',
            12 => 'december',
        ];
    }
 
}
