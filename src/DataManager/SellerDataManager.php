<?php

namespace App\DataManager;

use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\VisitRepository;
use App\Repository\LeaveRepository;
use App\Service\DateTimeService;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\Request;

class SellerDataManager
{
    public const DAYS = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    public const MONTHS = ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jui', 'aout','Sep', 'Oct', 'Nov', 'Dec'];

    public function __construct(private DateTimeService $dateTimeService, private LeaveRepository $leaveRepo)
    {
    }


    // The amount of seller's visits per day during This week
    public function visitsPerDayOfWeek($week, $year,VisitRepository $visitRepo, User $user): array
    {
        $data = [];
        $total = 0;
        $result = [];
        $visits = [];
        $nbLeaves = 0;
        $daysOfWeek= $this->dateTimeService->daysOfWeek((int)$week,(int)$year);

        foreach ($daysOfWeek as $key => $value) {
            $visits [] = count($visitRepo->findSellerVisitsPerDayOfThisWeek($value, $user));
            $nbLeaves += count($this->leaveRepo->findSellerLeavesPerDayOfThisWeek($value, $user));
        }
        foreach (self::DAYS as $key => $DAY){
            $total+= $visits[$key];
            $data [] = ['name' => $DAY, 'y' => $visits[$key]];
        }
        $result ['data'] = $data;
        $result['total'] = $total;
        $result['week'] = $week;
        $result['nbVisitsTarget'] = -$nbLeaves + (5*$user->getNbVisitsDay());

        return $result;

    }


    // The amount of visits per month during This year
    public function visitsPerMonthOfYear($month, $year, VisitRepository $visitRepo,User $user): array
    {
        $visits = [];
        $result = [];
        $data = [];
        $total = 0;
        $nbLeaves = 0;
        $monthsOfYear = $this->dateTimeService->monthsOfYear($year);
        foreach ($monthsOfYear as $key => $value) {
            $visits [] = count($visitRepo->findSellerVisitsPerMonthOfThisYear($value, $user));
            $nbLeaves += count($this->leaveRepo->findSellerLeavesPerMonthOfThisYear($value, $user));
        }

        foreach (self::MONTHS as $key => $MONTH){
            $total+= $visits[$key];
            $data [] = ['name' => $MONTH, 'y' => $visits[$key]];
        }

        $result['dataMonth'] = $data;
        $result['totalMonth'] = $total;
        $result['month'] = $month;
        $result['nbVisitsMonthTarget'] = -$nbLeaves + (12*$this->dateTimeService->daysInMonth((int)$month, (int)$year)*$user->getNbVisitsDay());

        return $result;

    }
    public function visitsOfTheMonth($month, $year,VisitRepository $visitRepo,User $user): array
    {
        $daysList = [];
        $limitDay = $this->dateTimeService->daysInMonth((int)$month, (int)$year);
        for ($i=1; $i <= $limitDay; $i++){
            $daysList [] = new \DateTime($year."-".$month."-".$i);
        }

        $visits = [];
        $result = [];
        $data = [];
        $total = 0;
        $nbLeaves = 0;

        foreach ($daysList as $key => $value) {
            $visits [] = count($visitRepo->findSellerVisitsOfTheMonth($value->format('Y-m-d'), $user));
            $nbLeaves += count($this->leaveRepo->findSellerLeavesOfTheMonth($value->format('Y-m-d'), $user));
        }


        foreach ($daysList as $key => $day){
            $total+= $visits[$key];
            $data [] = ['name' => $day->format('d'), 'y' => $visits[$key]];
        }

        $result['dataMonth'] = $data;
        $result['totalMonth'] = $total;
        $result['month'] = $month;
        $result['nbVisitsMonthTarget'] = -$nbLeaves + ($this->dateTimeService->daysInMonth((int)$month, (int)$year)*$user->getNbVisitsDay());

        return $result;

    }


    /**
     * @param Request $request
     * @param VisitRepository $visitRepo
     * @param ClientRepository $clientRepo
     * @param User $user
     * @return array
     */
    public function sellerVisitsByQuarter(Request $request,VisitRepository $visitRepo,ClientRepository $clientRepo, User $user):array
    {
        $targetNbVisits = [];
        $sellerNbVisits = [];
        $visitedClients = [];
        $tempVC = [];
        $A = [];
        $B = [];
        $tabNVC = [];
        $tabALL = [];
        $nonVisitedClients = [];
        $sellerAllClients = [];
        $temp = 0;
        $nbLeaves =0;
        $unVisitedClients = [];
        $currentDate = new \DateTime('now');
        $year = $request->get('year');

        $nbSellerClients = count($clientRepo->findBy(['user' =>$user,'isProspect'=> false]));
        if(is_null($year)){
            $year = $currentDate->format('Y');
        }
        
        $quarterSE = $this->dateTimeService->getQuarterDayStartEnd($year);
        foreach ($quarterSE as $quarter){
            foreach ($quarter as $item){
                $visits = $visitRepo->findSellerVisitsByQuarter($item['start'], $item['end'], $user);
                // Get the nb leaves in the quarter
                $nbLeaves += count($this->leaveRepo->findSellerLeavesByQuarter($item['start'], $item['end'], $user));
                
                if(null != $visits){
                    foreach ($visits as $visit){
                        $visitedClients [] = $visit->getClient()->getCodeUniq();
                        $tempVC [] = $visit->getClient()->getDesignation();
                        $code = $visit->getClient()->getCodeUniq();
                        $clients = $clientRepo->findUnVisitedClientsDuringQuarter($code);
                        //dump($clients);
                        foreach ($clients as $key => $client) {
                            $tabNVC [] = $client->getCodeUniq();
                        }
                        //dump($clientRepo->findUnVisitedClientsDuringQuarter($visit->getClient()->getCodeUniq()));
                    }
                    
                }

                $temp += count($visits);
            } 
                if (! $user->getClients()->isEmpty()) {
                    foreach ($user->getClients() as $key => $client) {
                        $sellerAllClients [] = $client->getCodeUniq();
                    }
                }
                
                $sellerNbVisits [] = [
                     "nbVisits"=>$temp,
                     "nbVisitedClients" => count(array_unique($visitedClients)),
                     "nonVCQuarter" => array_values(array_diff($sellerAllClients, array_unique($visitedClients))),
                ];

                $sellerAllClients = [];
                $nonVisitedClients = [];
                $visitedClients = [];
                $tempVC = [];
                $B = [];
                $tabALL = [];
                $tabNVC = [];
                $temp = 0;
        }

        // return the 4 quarters of a given year
        $quarterNbDays = $this->dateTimeService->getQuarterNbDays($year);
        foreach ($quarterNbDays as $nbDay){
            $targetNbVisits [] = -$nbLeaves + ($user->getNbVisitsDay()*$nbDay);
        }

        $result['sellerNbVisits'] = $sellerNbVisits;
        $result['targetNbVisits'] = $targetNbVisits;
        //$result['nbNonVisitedClients'] = count($nonVisitedClients);
        $result['nbSellerClients'] = $nbSellerClients;
        $result['nonVisitedClients'] = array_unique($nonVisitedClients);
        $result['nbVisitedClients'] = count(array_unique($visitedClients));



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
        if(is_null($periodP)){
            $periodP = $currentDate->format($format);
        }

        return ['period'=>$periodP, 'year'=>$year];
    }

    /**
     * @param Request          $request
     * @param ClientRepository $clientRepo
     * 
     * @return array
     */
    public function turnoverPerMonthOfYear($month, $year,VisitRepository $visitRepo, User $user): array
    {
        $visits = [];
        $result = [];
        $data = [];
        $target = [];
        $turnoverCummul = 0;
        $monthsOfYear = $this->dateTimeService->monthsOfYear($year);
        foreach ($monthsOfYear as $key => $value) {
     
            $visits [] = $visitRepo->findSellerVisitsPerMonthOfThisYear($value, $user);
           /*  if(isset($visit[0])){
                $m = sprintf("%02d", ($key+1));
                //dd($visit[0]->getStartTime()->format('m') === $m);
                if($visit[0]->getClient()->getTurnover() != 0){
                    if($visit[0]->getStartTime()->format('Y-m') === $year.'-'.$m ){
                        $turnoverCummul +=  $visit[0]->getClient()->getTurnover();
                        $data [] = $turnoverCummul;
                    }else {
                        $data [] = $visit[0]->getClient()->getTurnover();
                    }
                    
                }else {
                    $data [] = 0;
                }
            }
            $turnoverCummul = 0; */
        }

        foreach(self::MONTHS as $key => $MONTH){
            $target [] = $user->getTurnoverTarget();
            /* nb monthly visits */
             $nbVisits = count($visits[$key]);
             if($nbVisits !== 0 && $nbVisits > 1){
                 $turnoverCummul = $visits[$key][0]->getClient()->getTurnover();
                for ($i=1; $i < $nbVisits-1 ; $i++) { 
                        if ($visits[$key][0]->getStartTime()->format('Y-m') === $visits[$key][$i]->getStartTime()->format('Y-m') ) {
                            $turnoverCummul += $visits[$key][$i]->getClient()->getTurnover();
                            
                           
                        }
                }
                 
                 $data [] = $turnoverCummul/1000;
                 
             }elseif ($nbVisits === 1) {
                $data [] =  $visits[$key][0]->getClient()->getTurnover();
             }else {
                $data [] = 0;
             }
          
        }
        
        $result['monthlyTurnover'] = $data;
        $result['targetTurnover'] = $target;
        $result['month'] = self::MONTHS;
        

        
        return $result;
    }
}