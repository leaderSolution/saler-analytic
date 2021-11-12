<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class CommercialEditVisitController
{

    public function __construct(private Security $security, private EntityManagerInterface $em)
    {}

    public function __invoke(Visit $data, Request $request): Visit{
       
        $user = $this->security->getUser();
        $reqData = json_decode($request->getContent(), true);
        if(array_key_exists('allDay', $reqData)){
            if($reqData['allDay']){
                $data->setAllDay(true);
            }
            else{
                $data->setAllDay(false);
            }
        }
        
        $data->setUser($user);
        $this->em->flush();
        return $data;
    }
    
}
