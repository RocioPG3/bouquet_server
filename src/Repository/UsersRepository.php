<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Throwable;
/**
 * @extends ServiceEntityRepository<Users>
 *
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $users, string $newHashedPassword): void
    {
        if (!$users instanceof Users) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($users)));
        }
        $users->setPassword($newHashedPassword);
        $this->_em->persist($users);
        $this->_em->flush();
    }


     /*Ésta función sería para devolver todos los usario pero filtradas por lo que le indique en el select en cada caso en la UsersController, 
    por ejemplo si en la UsersController, pongo en getUsers(['u.email']) me traeré de la bbdd sólo los emails de los usuarios:*/ 
    
    public function getUsers(array $select)
    {
        //ésto de abajo sería como hacer ésta consulta en phpmyadmin:  select {selectParam} from user u
        return $this->createQueryBuilder('u')
            ->select($select)
            ->getQuery()
            ->getResult();
    }

     /* Ésta función es para añadir un usuario nuevo: */

     public function createUser($data, $hasher)
     {
         try {
             $users = new Users();
 
             $hashedPassword = $hasher->hashPassword($users, $data['password']);
 
             $users->setEmail($data['email']);
             $users->setActive($data['active']);
             $users->setRoles($data['roles']);
             $users->setPassword($hashedPassword);
 
             $this->getEntityManager()->persist($users);
             $this->getEntityManager()->flush();
             return true;
         } catch (Throwable $exception) {
             return false;
         }
     }

    

    //Esta función es para que devuelva un usuario:

    public function findByEmailAndPass($email, $password): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->andWhere('u.password = :password')
            ->setParameter('password', $password)
            ->getQuery()
            ->getOneOrNullResult();
    }



//    /**
//     * @return Users[] Returns an array of Users objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Users
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
