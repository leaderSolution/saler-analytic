<?php
namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class GlobalVar
{
    public function __construct(private UserRepository $userRepository){

    }
    public function getAllUsers()
    {
        return $this->userRepository->findAll();
    }
}
