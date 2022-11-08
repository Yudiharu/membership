<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\transaksi;

class dataCharts extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        
    }

    public function data()
    {
        $data = transaksi::groupBy('tanggal_masuk')
            ->get()
            ->map(function ($item) {
                // Return the number of persons with that age
                return count($item);
            });
        $chart = new dataCharts;
        $chart->labels($data->keys());
        $chart->dataset('My dataset', 'line', $data->values());

        return $chart;
    }
}
