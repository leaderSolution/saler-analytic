<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Security;

class ProfileController
{

    public function __construct(private Security $security)
    {}

    public function __invoke(){
       
        $user = $this->security->getUser();
        
        return $user;
    }
    
}
