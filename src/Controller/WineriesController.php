<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\WineriesRepository;
use App\Repository\UsersRepository;
use App\Entity\Wineries;
use App\Entity\Users;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/api/wineries")
 */



class WineriesController extends AbstractController

{


    private $wineriesRepository;

    public function __construct(WineriesRepository $wineriesRepository)
    {
        $this->wineriesRepository = $wineriesRepository;


    }

    // Esta devuelve todas mis bodegas. Carga en la ruta: http://localhost/bouquet_server/public/index.php/api/wineries/

    // /**
    //  * @Route("/", name="wineries", methods={"GET"})
    //  */
    // public function wineries(WineriesRepository $wineriesRepository): Response
    // {

       
    //     return new JsonResponse(
    //         [
    //             'data' => $wineriesRepository->getWineriesWithSelect(['r.id, r.name, r.denomination', 'r.location', 'r.description']),
    //                         ]
    //     );
        
    
    // }

     /* Éste endpoint lo uso en FichaPage y me devuelve toda la info ampliada de una ruta en particular 
    cargará en la url `http://localhost/bouquet_server/public/index.php/api/wineries/read/route/${id}` donde ${id} será el id de la bodega particular a consultar: */


    /**
     * @Route("/read/route/{id}", name="show_detailed_winerie", methods={"GET"})
     */
    public function getRouteAction(Wineries $wineries): Response
     {   return new JsonResponse(
            [
                'data' => $this->wineriesRepository->getWineriesWithUser($wineries)
            ]
        );
    }

    /* Éste endpoint nos sirve para crear una nueva bodega en el CRUD. 
    cargará en la url `http://localhost/bouquet_server/public/index.php/api/wineries/create/${id}` donde ${id} será el id del usuario logeado para que cree dicha nueva bodega: */
    
    /**
     * @Route("/create/{id}", name="create-winerie", methods={"POST"})
     */
    public function createAction(Request $request, Users $users)
    {
       
        $data = json_decode($request->getContent(), true);
        $status = $this-> wineriesRepository->createWinerie($data, $users);   /* sera true o false según recibe del Wineriesrepository (si se crea o no la entrada) */

        return new JsonResponse(['status'=> true]);
    }


      /* Éste endpoint es para el formulario de reserva, para que devuelva el nombre de cada ruta para poderlo 
    seleccionar en el desplegable
    cargará en la url `http://localhost/bouquet_server/public/index.php/api/wineries/read/select`: */

    // /**
    //  * @Route("/read/booking/see/{id}", name="read-booking", methods={"GET"})
    //  */
    // public function seeBookingAvailabilityAction(Wineries $wineries): Response
    // {
        
    //     return new JsonResponse(
    //         ['data' => $this-> wineriesRepository->getWineriesWithAvailability($wineries)]
    //     );
    // }



     /* Éste endpoint es el que uso en WineriesPage y me devuelve la info resumida de cada bodega.
    cargará en la url "http://localhost/bouquet_server/public/index.php/api/wineries/read": */


    /**
     * @Route("/read", name="read_wineries", methods={"GET"})
     */
    public function allWineriesAction(): Response
    {
        return new JsonResponse(
            [
    

                'data' => $this-> wineriesRepository->getWineries(['r.id, r.name ,r.denomination', 'r.location', 'r.image'])  /* estos son los campos que quiero del $select ésto es lo realmente importante, lo que uso */

            ]
        );
    }



    /* Éste endpoint lo usaré en el crud y me devuelve todas las rutas que gestione un determinado usuario (el que esté conectado) 
    cargará en la url "http://localhost/bouquet_server/public/index.php/api/wineries/read/user/{id}": */

    /**
     * @Route("/read/user/{id}", name="wineries_show_by_user", methods={"GET"})
     */
    public function wineriesByUserAction( Users $users): Response
    {
        return new JsonResponse(
            [
                
                'data' => $this-> wineriesRepository->getWineriesWithSelectByUser(['r.id, r.name ,r.denomination', 'r.location', 'r.description'], $users),
            ]
        );
    }


    

    //Hasta aquí todos funcionando menos /read/user/{id}

    


     //ENDPOINTS NUEVOS, revisar y comprobar con thunder

    //Para editar una entrada en concreto de la tabla Wineries. 
    //Lo cargará en la url `http://localhost/bouquet_server/api/wineries/edit/${id}` donde ${id} será el id de la bodega que queramos modificar: */

    /**
     * @Route("/edit/{id}", name="edit-winerie", methods={"PUT"})
     */
    // public function editAction(Wineries $wineries,Request $request): Response
    // {
       
    //     $data = json_decode($request->getContent(), true);
    //     $this->wineriesRepository->editWinerie($data, $wineries);
    


    //     return new JsonResponse(['respuesta' => 'ok']);
    // }

    /* Para eliminar una entrada en concreto de la tabla Ride. 
    Lo cargará en la url `http://localhost/bouquet_server/api/wineries/delete/${id}` donde ${id} será el id de la bodega que queramos eliminar: */

    /**
     * @Route("/delete/{wineries}", name="delete-wineries", methods={"DELETE"})
     */
    // public function deleteAction(Wineries $wineries): Response
    
    // {
    
    //     return new JsonResponse(
    //         ['status' => $this->wineriesRepository->deleteWinerie($wineries)]
            
    //     );
       
    // }


}