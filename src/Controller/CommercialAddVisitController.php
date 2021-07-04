<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Visit;
use Symfony\Component\Security\Core\Security;

class CommercialAddVisitController
{

    public function __construct(private Security $security)
    {}

    public function __invoke(Visit $data): Visit{
       
        $user = $this->security->getUser();
        $user->addVisit($data);
        $data->setUser($user);
        return $data;
    }
    
}
