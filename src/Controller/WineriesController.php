<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\WineriesRepository;
use App\Entity\Wineries;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\Request;

class WineriesController extends AbstractController

{
    // Esta devuelve todas mis bodegas. Carga en la ruta: http://localhost/bouquet_server/public/index.php/api/wineries

    /**
     * @Route("/api/wineries", name="wineries", methods={"GET"})
     */
    public function wineries(WineriesRepository $wineriesRepository): Response
    {

        $wineries = $wineriesRepository->findAll();
        $response = [];
        foreach ($wineries as $winerie) {
            $response[] = [
                'id' => $winerie->getId(),
                'name' => $winerie->getName(),
                'denomination' => $winerie->getDenomination(),
                'location' => $winerie->getLocation()
            ];
        }

        return new JsonResponse(
            $response
        );
        
    
}

/**
     * @Route("/api/wineries/{id}", name="winerie", methods={"GET"}, requirements={"id": "\d+"} )
     */
    public function winerie(int $id, WineriesRepository $wineriesRepository): Response
    {
        $winerie = $wineriesRepository->find($id);
        $response = [
            'id' => $winerie->getId(),
            'name' => $winerie->getName(),
            'denomination' => $winerie->getDenomination(),
            'address' => $winerie->getAddress(),
            'telephone' => $winerie->getTelephone(),
            'email' => $winerie->getEmail(),
            'services' => $winerie->getServices(),
            'description' => $winerie->getDescription(),
            
        ];


        return new JsonResponse(
            $response
        );
    }

/**
     * @Route("/api/wineries/select", name="select", methods={"GET"})
     */
    public function select(WineriesRepository $wineriesRepository): Response
    {
        $wineries = $wineriesRepository->findAll();
        $select = [];
        foreach ($wineries as $winerie) {
            $select[] = $winerie->getName();
        }

        return new JsonResponse(
            $select
        );
    }




}