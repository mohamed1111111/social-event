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
        $from =  '2021-01-01';
        $to = '2021-12-31';
        $name = $this->ask('name of  generated file?  like:  task.csv');
        $period = CarbonPeriod::create($from, '1 month', $to);
        $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
        $x=0;
        $array=array();
        foreach ($period  as $key => $value){

            $array[$key][0]=$value->format('y-m');
            $array[$key][1]=(new Carbon("first Monday of.$months[$x]."))->format('d-m-y');
            $array[$key][2]=($value->nthOfMonth(3, Carbon::THURSDAY ))->format('d-m-y');
            $array[$key][3]=(new Carbon("last FRIDAY of.$months[$x]."))->format('d-m-y');
            if($value->format("y-m") !=("12-21")){
                $x++;
            }
        }
        array_unshift($array,['Month','Brunch & Catchup','Thirsty Thursday','Friday Fry-up']);

        $fp = fopen($name, 'w');
        foreach ($array as $key =>  $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
        echo "Good job, The  file is generated in project path";
    }
}
