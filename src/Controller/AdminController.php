<?php

namespace App\Controller;

use App\Entity\Wineries;
use App\Entity\Users;
use App\Entity\User;
use App\Repository\WineriesRepository;
use App\Repository\UsersRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;


/**
 * @Route("/admin/wineries")
 */


class AdminController extends AbstractController
{
    private $wineriesRepository;

    public function __construct(WineriesRepository $wineriesRepository)
    {
        $this->wineriesRepository = $wineriesRepository;
    }

  //Este endpoint es el que uso en WineriesPage y me devuelve la info resumida de cada bodega 
    //cargará en la url "http://localhost/bouquet_server/public/index.php/admin/wineries/read":

    /**
     * @Route("/read", name="admin_read_wineries", methods={"GET"})
     */
    
     public function allWineriesAction(): Response
    {
        return new JsonResponse(
            [

                'data' => $this->wineriesRepository->getWineries(['r.id, r.name ,r.denomination', 'r.location', 'r.image']),    //aqui los campos que quiero del $select ésto es lo realmente importante, lo que uso 
            ]
        );
}

//Este endpoint lo usaré en el crud y me devuelve todas las bodegas que gestione un determinado usuario (el que esté conectado) 
    //cargará en la url "http://localhost/bouquet_server/public/index.php/admin/wineries/read/user": 


    /**
     * @Route("/read/user", name="admin_wineries_shown_by_user", methods={"GET"})
     */
    public function wineriesByUserAction(Security $security): Response
    {
        $security->getUser();       /* con ésto saco mediante el token actual el usuario activo ahora mismo, hay que usarlo en todas las funciones del AdminController en las que necesite el usuario  */

        return new JsonResponse(
            [
                'data' => $this->wineriesRepository->getWineriesWithSelectByUser(['r.id', 'r.name' ,'r.denomination', 'r.location', 'r.image'], $security->getUser())
            ]
        );
    }

//Este endpoint lo uso en FichaPage y me devuelve toda la info ampliada de una bodega en particular 
    //cargará en la url `http://localhost/bouquet_server/public/index.php/admin/wineries/read/route/${id}` donde ${id} será el id de la bodega en concreto que queremos consultar:

    /**
     * @Route("/read/route/{id}", name="admin_show_detailed_winerie", methods={"GET"})
     */
    public function getRouteAction(Wineries $wineries): Response
    {
        return new JsonResponse(
            ['data' => $this->wineriesRepository->getWinerieWithUser($wineries)]
        );
    }

// Este endpoint lo usaré en el crud y es para añadir una nueva bodega a la bbdd por el usuario activo 
    //cargará en la url `http://localhost/bouquet_server/public/index.php/admin/wineries/create/${id}` donde ${id} será el id del usuario activo:

    /**
     * @Route("/create", name="admin_create-winerie", methods={"POST"})
     */
    public function createAction(Request $request, Security $security): Response
    {
        $data = json_decode($request->getContent(), true);
        $status = $this->wineriesRepository->createWinerie($data, $security->getUser());   //será true o false según recibe del Wineriesrepository (si se crea o no la entrada)

        return new JsonResponse([
            'status' => $status['status'],
            'message' =>  $status['message'] 
        ]);
    }


// Este endpoint es para editar una entrada en concreto de la tabla Winerie. 
    //Lo cargará en la url `http://localhost/bouquet_server/public/index.php/admin/wineries/edit/${winerie}` donde ${winerie} será el id de la bodega que queramos modificar: 

     /**
     * @Route("/edit/{id}", name="admin_edit-winerie", methods={"PUT"}, requirements={"id": "\d+"})
     */
    public function editAction(int $id, Request $request, WineriesRepository $wineriesRepository): Response
    {
        $wineries = $wineriesRepository->findById($id);
        $data = json_decode($request->getContent(), true);
        $this->wineriesRepository->editWinerie($data, $wineries);

        return new JsonResponse(Response::HTTP_ACCEPTED);
    }   

//Este endpoint es para eliminar una entrada en concreto de la tabla Winerie. 
    //Lo cargará en la url `http://localhost/bouquet_server/public/index.php/admin/wineries/delete/${id}` donde ${winerie} será el id de la bodega que queramos eliminar:

    /**
     * @Route("/delete/{id}", name="admin_delete-winerie", methods={"PUT"},requirements={"id": "\d+"} )
     */
    public function deleteAction(int $id, WineriesRepository $wineriesRepository): Response
    {
        $wineries = $wineriesRepository->findById($id);

        $wineriesRepository->deleteWinerie($wineries);

        return new JsonResponse(Response::HTTP_ACCEPTED);
    }



}
