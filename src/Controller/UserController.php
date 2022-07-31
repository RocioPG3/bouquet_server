<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api/user")
 */


class UserController extends AbstractController
{

    private $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    /* Aqui creamos una funcion para CONSULTAR los usuarios existentes en mi bbdd, un metodo get en esta url http://localhost/bouquet_server/public/index.php/api/user */

    /**
     * @Route("/api/user", name="user", methods={"GET"})
     */
    public function users(Request $request, UsersRepository $usersRepository): Response
    {

        $users = $usersRepository->findAll();
        $response = [];
        foreach ($users as $user) {
            $response[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail()
            ];
        }

        return new JsonResponse([
            'users' => $response
        ]);
    }



    /* Aqui creamos una funcion para AÑADIR usuarios nuevos a mi bbdd, un metodo post en esta url http://localhost/bouquet_server/public/index.php/api/user */

    /**
     * @Route("/api/user", methods={"POST"})
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $content = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($content['email']);
        $user->setRoles($content['roles']);   /* ej en el json: {"roles": ["ROLE_ADMIN"]} ó {"roles": ["ROLE_USER"]} ó {"roles": ["ROLE_USER, ROLE_ADMIN"]} */
        $user->setPassword($content['password']);

        $em->persist($user);
        $em->flush();

        return new JsonResponse([
            'result' => 'ok',
            'content' => $content
        ]);
    }



    /* Aqui creamos una funcion para EDITAR un usuario ya existente en mi bbdd (le paso el id del usuario por parametros en la ruta y los recibe como parametros en la funcion), un metodo put en esta url http://localhost/bouquet_server/public/index.php/api/users/{id} */

    /**
     * @Route("/api/user/{id}", methods={"PUT"})
     */
    public function modificar($id, UsersRepository $usersRepository, Request $request, EntityManagerInterface $em)
    {
        $content = json_decode($request->getContent(), true);  /* para que me devuelva los datos de la bbdd (en json) en un array */

        $user = $usersRepository->find($id);            /* con esto buscara en mi usuarioRepository que tengo toda la tabla de usuarios, aquel con la id que coincida con la q le hemos metido por parametros y lo guardara en la variable $usuario */


        if (isset($content['email'])) {                         /* con estas expresiones le digo que si tiene datos en ese campo y recibe nuevos datos, los sobreescriba, si no recibe nada, que respete el contenido actual para ese campo  */
            $user->setEmail($content['email']);
        }

        if (isset($content['roles'])) {                         /* con estas expresiones le digo que si tiene datos en ese campo y recibe nuevos datos, los sobreescriba, si no recibe nada, que respete el contenido actual para ese campo  */
            $user->setEmail($content['roles']);
        }

        if (isset($content['password'])) {                         /* con estas expresiones le digo que si tiene datos en ese campo y recibe nuevos datos, los sobreescriba, si no recibe nada, que respete el contenido actual para ese campo  */
            $user->setEmail($content['password']);
        }


        $em->flush();

        return new JsonResponse([
            'result' => 'ok'
        ]);
    }


    /* Aqui creamos una funcion para BORRAR un usuario ya existente en mi bbdd (le paso el id del usuario por parametros en la ruta y los recibe como parametros en la funcion), un metodo put en esta url http://localhost/bouquet_server/public/index.php/api/user/{id} */

    /**
     * @Route("/api/user/{id}", methods={"DELETE"})
     */
    public function borrar($id, UsersRepository $usersRepository, EntityManagerInterface $em)
    {
        $user = $usersRepository->find($id);
        $em->remove($user);

        $em->flush();

        return new JsonResponse([
            'result' => 'ok'
        ]);
   }


    /**
     * @Route("/read", name="read_users", methods={"GET"})
     */

    public function allUsersAction(): Response
    {
        return new JsonResponse(
            [

        'data' => $this->usersRepository->getUsers(['u.id, u.email, u.active, u.roles ,u.password']),  /*aqui los campos que quiero del $select ésto es lo realmente importante, lo que uso */
            ]
        );
        
    }


    /**
     * @Route("/create", name="create-user", methods={"POST"})
     */

    public function createUserAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $status = $this->usersRepository->createUser($data);   /* sera true o false según recibe del usersrepository (si se crea o no la entrada) */

        return new JsonResponse([
            'status' => $status,
            'message' => $status ? "Todo ha ido ok" : "Has metido datos que no corresponden"     //Ésto es lo que envía al front como respuesta. 
        ]);
    }







}