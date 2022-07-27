<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\WineriesRepository;
use App\Repository\UsersRepository;
use App\Entity\Wineries;
use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request;

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
                'location' => $winerie->getLocation(),
                'image' => $winerie->getImage(),
            ];
        }

        return new JsonResponse(
            $response
        );
        
    
    }

    //La siguiente ruta devuelve la bodega que le pasemos por parámetros (al hacer click en la bodega que queramos le paso id para que devuelva la info ampliada de dicha ruta en concreto en FichaPage)
    
    //http://localhost/bouquet_server/public/index.php/api/wineries/{id}


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
            'services' => $winerie->getServices(),
            'description' => $winerie->getDescription(),
            
        ];


        return new JsonResponse(
            $response
        );
    }


      /* Éste endpoint es para el formulario de reserva, para que devuelva el nombre de cada ruta para poderlo 
    seleccionar en el desplegable: */

    //http://localhost/bouquet_server/public/index.php/api/wineries/select

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


    //Con este endpoint añadimos una entrada a la tabla Wineries: http://localhost/bouquet_server/public/index.php/api/winerie


     /**
     * @Route("/api/winerie", name="winerie", methods={"POST"})
     */
    public function add(Request $request, UsersRepository $usersRepository, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);       /*  true para que devuelva un array */

        /* if($this->getUser()->getRide()){
            return $this->json([
                'message' => "Ride exists"
            ],
                Response::HTTP_FORBIDDEN
            );
        } */

        $winerie= new Wineries();
        
        // $winerie->setUser($data['user']);
        $winerie->setDenomination($data['denomination']);
        $winerie->setName($data['name']);
        $winerie->setLocation($data['location']);
        $winerie->setAddress($data['address']);
        $winerie->setTelephone($data['telephone']);
        $winerie->setServices($data['services']);
        $winerie->setDescription($data['description']);
        $winerie->setImage($data['image']);

        $winerie->setActive(true);
        /* insertar la imagen aqui como otro elemento o fuera? */

        /* $userId = $this->getUser()->getId();
        $ride->setUser($userRepository->find($userId)); */


        $em->persist($winerie);
        $em->flush();

        return $this->json($winerie, Response::HTTP_CREATED);

    
    }



}