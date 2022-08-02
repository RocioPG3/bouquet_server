<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\Token\JWTPostAuthenticationToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Request;




class AuthController extends AbstractController
{

    private $userRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    /**
     * @Route("/login", methods={"OPTIONS"})
     */
    public function options(): Response
    {
        return new Response("", 200, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, DELETE, PUT',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization'
        ]);

    }
   
}






