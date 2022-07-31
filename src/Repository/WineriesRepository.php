<?php

namespace App\Repository;

use App\Entity\Wineries;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

/**
 * @extends ServiceEntityRepository<Wineries>
 *
 * @method Wineries|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wineries|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wineries[]    findAll()
 * @method Wineries[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WineriesRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        parent::__construct($registry, Wineries::class);
    }


    public function createWinerie(array $data, Users $users)
    {
        try {
            $wineries = new Wineries();

            $wineries->setActive($data['active']);
            $wineries->setDenomination($data['denomination']);
            $wineries->setName($data['name']);
            $wineries->setLocation($data['location']);
            $wineries->setAddress($data['address']);
            $wineries->setTelephone($data['telephone']);
            $wineries->setDescription($data['description']);
            $wineries->setUser($users);

            $this->getEntityManager()->persist($wineries);
            $this->getEntityManager()->flush();
            return true;
        } catch (Throwable $exception) {
            return false;
        }
    }
    /* try catch si se produce una excepción en el bloque del try, entraría dentro del catch si se especifica la excepción (en este caso, si la consulta
    falla porque recibe un tipo de datos que no corresponde para ese campo)  */


     //ésta sería la opción para devolver todas las rutas que pertenezcan a un usuario mostrando solo las que le pertenecen a ese usuario:
     public function getWineriesWithSelectByUser(array $select, Users $users)
     {
 
        
         //ésto de abajo sería como hacer ésta consulta en phpmyadmin:  select {selectParam} from ride r where r.user_id = {userid}
         
         return $this->createQueryBuilder('r')
             ->select($select)
             ->andWhere('r.user = :user')
             ->setParameter('user', $user)
             ->getQuery()
             ->getResult();
     }
 
     //ésta función sería para devolver todas las bodegas pero filtradas por  lo que le indique en el select, por ejemplo getWineries(['r.name'])(ver ApiController el endpoint read/select por ejemplo que ahí lo uso)::
     public function getWineries(array $select)
     {
       
         //ésto de abajo sería como hacer ésta consulta en phpmyadmin:  select {selectParam} from ride r
 
         return $this->createQueryBuilder('r')
             ->select($select)
             ->getQuery()
             ->getResult();
     }
 
     //Esta función es para editar una bodega en concreto:
     public function editWinerie(array $data, Wineries $wineries): bool
    {
        try {

            if (isset($data['active'])) {
                $wineries->setActive($data['active']);
            }

            if (isset($data['denomination'])) {
                $wineries->setDenomination($data['denomination']);
            }

            if (isset($data['name'])) {
                $wineries->setName($data['name']);
            }

            if (isset($data['location'])) {
                $wineries->setLocation($data['location']);
            }

            if (isset($data['address'])) {
                $wineries->setAddress($data['address']);
            }

            if (isset($data['telephone'])) {
                $wineries->setTelephone($data['telephone']);
            }


            if (isset($data['services'])) {
                $wineries->setDuration($data['services']);
            }

            if (isset($data['description'])) {
                $wineries->setDescription($data['description']);
            }


            /* if (isset($data['image'])) {
                $wineries->setImage($data['image']);
            } */



            $this->getEntityManager()->persist($wineries);
            $this->getEntityManager()->flush();
            return true;
        } catch (Throwable $exception) {
            return false;
        }
    }
            /* REVISAR SI ESTÁ BIEN: */

    public function deleteWinerie(Wineries $wineries)
    {
         $this->em->remove($wineries);
         $this->em->flush();
        }       
    
    
 
 
 
     /**            
  
      */
    
//    /**
//     * @return Wineries[] Returns an array of Wineries objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Wineries
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
