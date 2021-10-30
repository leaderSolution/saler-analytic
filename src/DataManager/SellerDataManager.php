<?php

namespace App\DataManager;

use App\Entity\User;
use App\Repository\VisitRepository;
use App\Service\DateTimeService;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\Request;

class SellerDataManager
{
    public const DAYS = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    public const MONTHS = ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jui', 'aout','Sep', 'Oct', 'Nov', 'Dec'];


    public function __construct(private DateTimeService $dateTimeService)
    {
    }


    // The amount of seller's visits per day during This week
    public function visitsPerDayOfWeek($week, $year,VisitRepository $visitRepo, User $user): array
    {
        $data = [];
        $total = 0;
        $result = [];
        $visits = [];
        $daysOfWeek= $this->dateTimeService->daysOfWeek((int)$week,(int)$year);

        foreach ($daysOfWeek as $key => $value) {
            $visits [] = count($visitRepo->findSellerVisitsPerDayOfThisWeek($value, $user));
        }
        foreach (self::DAYS as $key => $DAY){
            $total+= $visits[$key];
            $data [] = ['name' => $DAY, 'y' => $visits[$key]];
        }
        $result ['data'] = $data;
        $result['total'] = $total;
        $result['week'] = $week;
        $result['nbVisitsTarget'] = 7*$user->getNbVisitsDay();

        return $result;

    }


    // The amount of visits per month during This year
    public function visitsPerMonthOfYear($month, $year,VisitRepository $visitRepo, User $user): array
    {

        $visits = [];
        $result = [];
        $data = [];
        $total = 0;
        $monthsOfYear = $this->dateTimeService->monthsOfYear($year);
        foreach ($monthsOfYear as $key => $value) {
            $visits [] = count($visitRepo->findSellerVisitsPerMonthOfThisYear($value, $user));
        }

        foreach (self::MONTHS as $key => $MONTH){
            $total+= $visits[$key];
            $data [] = ['name' => $MONTH, 'y' => $visits[$key]];
        }

        $result['dataMonth'] = $data;
        $result['totalMonth'] = $total;
        $result['month'] = $month;
        $result['nbVisitsMonthTarget'] = $this->dateTimeService->daysInMonth((int)$month, (int)$year)*$user->getNbVisitsDay();

        return $result;

    }

    #[ArrayShape(['period' => "mixed|string", 'year' => "mixed|string"])]
    public function sendRequestedParameters(Request $request, $period, $format): array
    {

        $currentDate = new \DateTime('now');

        $periodP = $request->get($period);
        $year = $request->get('year');
        if((is_null($periodP) && is_null($year))){
            $periodP = $currentDate->format($format);
            $year = $currentDate->format('Y');
        }

        return ['period'=>$periodP, 'year'=>$year];
    }
}