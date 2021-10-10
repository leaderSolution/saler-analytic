<?php
namespace App\Service;

use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Repository\VisitRepository;

class DateTimeService
{
    public function __construct(private UserRepository $userRepository){

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

    public function monthsOfThisYear(): array
    {
        $monthsOfThisYear = [];
       
        for ($m=1; $m<=12; $m++) {
            $month = date('Y-m', mktime(0,0,0,$m, 1, date('Y')));
            $monthsOfThisYear [] = $month;
            }

        return $monthsOfThisYear;
    }

    // The amount of visits per day during This week
    public function numVisitsPerDayOfThisWeek(VisitRepository $visitRepo){

        $visits = [];
        $daysOfThisWeek = $this->daysOfThisWeek();
        foreach ($daysOfThisWeek as $key => $value) {
            $visits [] = count($visitRepo->findVisitsPerDayOfThisWeek($value));
        }
        return $visits;

    }

     // The amount of visits per month during This year
     public function numVisitsPerMonthOfThisYear(VisitRepository $visitRepo){

        $visits = [];
        $monthsOfThisYear = $this->monthsOfThisYear();
        foreach ($monthsOfThisYear as $key => $value) {
            $visits [] = count($visitRepo->findVisitsPerMonthOfThisYear($value));
        }
        return $visits;

    }

    // The amount of new visits
    public function amountOfNewVisits(VisitRepository $visitRepo){

        $d = new \DateTime('now');
        $nb = count($visitRepo->findNewVisits($d->format('Y-m-d')));
    
        $total = count($visitRepo->findAll());

        if(0 !== $total ){

            $pourcentage = round(( $nb / $total) * 100, 2);
        }else {
            $pourcentage = 0;
        }

       return $pourcentage;
    }

    // The amount of new Clients
    public function amountOfNewClients(ClientRepository $clientRepo){

        $d = new \DateTime('now');
        $nb = count($clientRepo->findNewClients($d->format('Y-m-d')));
    
        $total = count($clientRepo->findAll());

        if(0 !== $total ){

            $pourcentage = round(( $nb / $total) * 100, 2);
        }else {
            $pourcentage = 0;
        }

        return $pourcentage;
    }
}
