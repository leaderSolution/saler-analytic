<?php
namespace App\Service;

use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Repository\VisitRepository;
use DateTime;

class DateTimeService
{
    public function __construct(private UserRepository $userRepository){

    }

    function getIsoWeeksInYear($year) {
        $date = new DateTime;
        $date->setISODate($year, 53);
        return ($date->format("W") === "53" ? 53 : 52);
    }
    public function daysOfThisWeek(): array
    {
        $daysOfThisWeek = [];
        $monday = strtotime("last monday");
        $monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;

        $sunday = strtotime(date("Y-m-d",$monday)." +6 days");
        $thisWeekSd = date("Y-m-d",$monday);
        $thisWeekEd = date("Y-m-d",$sunday);

        $daysOfThisWeek [] = date("Y-m-d",$monday);

        for ($i=1; $i < 7; $i++) { 
            $daysOfThisWeek [] = date("Y-m-d",strtotime(date("Y-m-d",$monday)." +".$i." days"));
        }
        return $daysOfThisWeek;
    }
    public function daysOfWeek($week, $year): array
    {
        $daysOfWeek = [];

        $dateTime = new DateTime();
        $dateTime->setISODate($year, $week);
        $result['start_date'] = $dateTime->format('Y-m-d');

        $dateTime->modify('+6 days');

        $result['end_date'] = $dateTime->format('Y-m-d');

        $begin = new DateTime($result['start_date']);
        $end   = new DateTime($result['end_date']);

        for($i = $begin; $i <= $end; $i->modify('+1 day')){
            $daysOfWeek [] = $i->format("Y-m-d");
        }

        return $daysOfWeek;
    }

    public function monthsOfThisYear(): array
    {
        $monthsOfThisYear = [];
       
        for ($m=1; $m<=12; $m++) {
            $month = date('Y-m', mktime(0,0,0,$m, 1, date('Y')));
            $monthsOfThisYear [] = $month;
            }

        return $monthsOfThisYear;
    }

    public function monthsOfYear($year): array
    {
        $monthsOfYear = [];

        for ($m=1; $m<=12; $m++) {
            $month = date('Y-m', mktime(0,0,0,$m, 1, date($year)));
            $monthsOfYear [] = $month;
        }

        return $monthsOfYear;
    }

    function daysInMonth($month, $year): int
    {
        // calculate number of days in a month
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }

    // The amount of visits per day during This week
    public function numVisitsPerDayOfThisWeek(VisitRepository $visitRepo): array
    {

        $visits = [];
        $daysOfThisWeek = $this->daysOfThisWeek();
        foreach ($daysOfThisWeek as $key => $value) {
            $visits [] = count($visitRepo->findVisitsPerDayOfThisWeek($value));
        }
        return $visits;

    }


    // The amount of seller's visits per day during This week
    public function numSellerVisitsPerDayOfThisWeek(VisitRepository $visitRepo, User $user): array
    {

        $visits = [];
        $daysOfThisWeek = $this->daysOfThisWeek();
        $daysOfWeek= $this->daysOfWeek(43,2021);

        foreach ($daysOfWeek as $key => $value) {
            $visits [] = count($visitRepo->findSellerVisitsPerDayOfThisWeek($value, $user));
        }
        return $visits;

    }

     // The amount of visits per month during This year
     public function numVisitsPerMonthOfThisYear(VisitRepository $visitRepo): array
     {

        $visits = [];
        $monthsOfThisYear = $this->monthsOfThisYear();
        foreach ($monthsOfThisYear as $key => $value) {
            $visits [] = count($visitRepo->findVisitsPerMonthOfThisYear($value));
        }
        return $visits;

    }


    // The amount of visits per month during This year
    public function numSellerVisitsPerMonthOfThisYear(VisitRepository $visitRepo, User $user): array
    {

        $visits = [];
        $monthsOfThisYear = $this->monthsOfThisYear();
        foreach ($monthsOfThisYear as $key => $value) {
            $visits [] = count($visitRepo->findSellerVisitsPerMonthOfThisYear($value, $user));
        }
        return $visits;

    }


    // The amount of new visits
    public function amountOfNewVisits(VisitRepository $visitRepo): float|int
    {

        $d = new \DateTime('now');
        $nb = count($visitRepo->findNewVisits($d->format('Y-m-d')));
    
        $total = count($visitRepo->findAll());

        if(0 !== $total ){

            $percentage = round(( $nb / $total) * 100, 2);
        }else {
            $percentage = 0;
        }

       return $percentage;
    }

    // The amount of new Clients
    public function amountOfNewClients(ClientRepository $clientRepo): float|int
    {

        $d = new \DateTime('now');
        $nb = count($clientRepo->findNewClients($d->format('Y-m-d')));
    
        $total = count($clientRepo->findAll());

        if(0 !== $total ){

            $percentage = round(( $nb / $total) * 100, 2);
        }else {
            $percentage = 0;
        }

        return $percentage;
    }
}
