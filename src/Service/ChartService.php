<?php

namespace App\Service;

use App\Repository\VisitRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartService
{
    public const DAYS = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    public const MONTHS = ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jui', 'aout','Sep', 'Oct', 'Nov', 'Dec'];


    public function __construct(private ChartBuilderInterface $chartBuilder, private DateTimeService $timeService, private VisitRepository $visitRepo)
    {
    }

    // Build a Chart by Type

    /**
     * @param string $type
     * @param array $labels
     * @param string $title
     * @param string $color
     * @param array $data
     * @return Chart
     */
    public function buildChart(string $type,array $labels,string $title,string $color,array $data): Chart
    {

        $chart = $this->chartBuilder->createChart($type);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $title,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'data' => $data,
                ],
            ],
        ]);
        $chart->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => 25]],
                ],
            ],
        ]);

        return $chart;
    }
}