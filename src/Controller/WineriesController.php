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
    // Esta devuelve todas mis bodegas. Carga en la ruta: http://localhost/bouquet_server/public/index.php/api/wineries

    /**
     * @Route("/", name="wineries", methods={"GET"})
     */
    public function wineries(WineriesRepository $wineriesRepository): Response
    {

       
        return new JsonResponse(
            [
                'data' => $wineriesRepository->getWineriesWithSelect(['r.id, r.name, r.denomination', 'r.location', 'r.description']),
                            ]
        );
        
    
    }

     /* Éste endpoint lo uso en FichaPage y me devuelve toda la info ampliada de una ruta en particular 
    cargará en la url `http://localhost/bouquet_server/public/index.php/api/wineries/read/route/${id}` donde ${id} será el id de la bodega particular a consultar: */


    /**
     * @Route("/read/route/{id}", name="show_detailed_winerie", methods={"GET"})
     */
    public function getRouteAction(Wineries $wineries): Response
     {   return new JsonResponse(
            [
                'data' => $wineries->toArray(),         /* aqui llamo a la funcion toArrray que he creado yo en el modelo (entidad) Wineries */
            ]
        );
    }

    /* Éste endpoint nos sirve para crear una nueva bodega en el CRUD. 
    cargará en la url `http://localhost/bouquet_server/public/index.php/api/wineries/create/${id}` donde ${id} será el id del usuario logeado para que cree dicha nueva bodega: */
    
    /**
     * @Route("/create/{id}", name="create-winerie", methods={"POST"})
     */
    public function createAction(Request $request, WineriesRepository $wineriesRepository, Users $users)
    {
       
        $data = json_decode($request->getContent(), true);
        $status = $wineriesRepository->createWinerie($data, $users);   /* sera true o false según recibe del Wineriesrepository (si se crea o no la entrada) */

        return new JsonResponse([

            'status' => $status,
            'message' => $status ? "Todo ha ido ok" : "Has metido datos que no corresponden"    /* Ésto es lo que envía al front como respuesta. Si los datos introducidos has sido correctos devolvera Todo ha ido ok, si no, dira Has metido datos que no corresponden */
        
        
        ]);
    }


      /* Éste endpoint es para el formulario de reserva, para que devuelva el nombre de cada ruta para poderlo 
    seleccionar en el desplegable
    cargará en la url `http://localhost/bouquet_server/public/index.php/api/wineries/read/select`: */

    /**
     * @Route("/read/select", name="select", methods={"GET"})
     */
    public function select(WineriesRepository $wineriesRepository): Response
    {
        
        return new JsonResponse(
            ['data' => $wineriesRepository->getWineries(['r.name', 'r.id'])]
        );
    }



     /* Éste endpoint es el que uso en WineriesPage y me devuelve la info resumida de cada bodega.
    cargará en la url "http://localhost/bouquet_server/public/index.php/api/wineries/read": */


    /**
     * @Route("/read", name="read_wineries", methods={"GET"})
     */
    public function allWineriesAction(WineriesRepository $wineriesRepository): Response
    {
        return new JsonResponse(
            [
    

                'data' => $wineriesRepository->getWineries(['r.id, r.name ,r.denomination', 'r.location'])  /* y aqui los campos que quiero del $select ésto es lo realmente importante, lo que uso */

            ]
        );
    }



    /* Éste endpoint lo usaré en el crud y me devuelve todas las rutas que gestione un determinado usuario (el que esté conectado) 
    cargará en la url "http://localhost/bouquet_server/public/index.php/api/wineries/read/user/{id}": */

    /**
     * @Route("/read/user/{id}", name="wineries_show_by_user", methods={"GET"})
     */
    public function wineriesByUserAction(WineriesRepository $wineriesRepository, Users $users): Response
    {
        return new JsonResponse(
            [
                'data' => $wineriesRepository->getWineriesWithSelect(['r.id, r.name ,r.denomination', 'r.location', 'r.description']),
                'data' => $wineriesRepository->getWineriesWithSelectByUser(['r.id, r.name ,r.denomination', 'r.location', 'r.description'], $users),
            ]
        );
    }


    /* ENDPOINTS NUEVOS, CORREGIR CON MIGUEL, AL INTENTAR EJECUTARLOS EN THUNDER DAN ERROR 500 DE ALGO RELACIONADO CON YAML (NO HE PODIDO IDENTIFICAR EL ERROR): */

    /* Para editar una entrada en concreto de la tabla Ride. 
    Lo cargará en la url `http://localhost/bouquet_server/api/wineries/edit/${id}` donde ${id} será el id de la bodega que queramos modificar: */

    /**
     * @Route("/edit/{id}", name="edit-winerie", methods={"PUT"})
     */
    public function edit(Request $request, $id, WineriesRepository $wineriesRepository): Response
    {
        $content = json_decode($request->getContent(), true);

        $wineries = $this->$wineriesRepository->find($id);

        if (isset($content['active'])) {
            $wineries->setTexto($content['active']);
        }
        if (isset($content['denomination'])) {
            $wineries->setTexto($content['denomination']);
        }
        if (isset($content['name'])) {
            $wineries->setTexto($content['name']);
        }
        if (isset($content['location'])) {
            $wineries->setTexto($content['location']);
        }
        if (isset($content['address'])) {
            $wineries->setTexto($content['address']);
        }
        if (isset($content['telephone'])) {
            $wineries->setTexto($content['telephone']);
        }
        
        if (isset($content['description'])) {
            $wineries->setTexto($content['description']);
        }
        

        /* insertar la imagen aqui como otro elemento o fuera? */


        $this->em->flush();

        return new JsonResponse(['respuesta' => 'ok']);
    }

    /* Para eliminar una entrada en concreto de la tabla Ride. 
    Lo cargará en la url `http://localhost/bouquet_server/api/wineries/delete/${id}` donde ${id} será el id de la bodega que queramos eliminar: */

    /**
     * @Route("/delete/{id}", name="delete-wineries", methods={"DELETE"})
     */
    public function delete($id): Response
    {
        $wineries = $this->wineriesRepository->find($id);
        $this->em->remove($wineries);
        $this->em->flush();

        return new JsonResponse(['respuesta' => 'ok']);
    }


}