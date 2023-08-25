<?php

namespace App\Repository;

use App\Entity\Cuota;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<Cuota>
 *
 * @method Cuota|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cuota|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cuota[]    findAll()
 * @method Cuota[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CuotaRepository extends ServiceEntityRepository
{

     /**
     * @var Security
     */
    private $security;
    
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Cuota::class);
        $this->security = $security;
    }

    public function add(Cuota $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cuota $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Cuota[] Returns an array of Cuota objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cuota
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
    * @return Cuota[] Returns an array of Cuota objects
    */
    public function findByCuotasDeCursoAjax($idCurso): array
    {
        return $this->createQueryBuilder('cuotas')
             ->join('cuotas.cursos', 'curso')
             ->where('curso.id = :idCurso')
             ->setParameter('idCurso', $idCurso)
             ->getQuery()
             ->getResult()
        ;
    }

    /**
    * @return Cuota[] Returns an array of Cuota objects
    */
    public function findByCuotasPagadasDeCursoAjax($idCurso): array
    {
        $idUser = $this->security->getUser();
        return $this->createQueryBuilder('cuotas')
            ->join('cuotas.cursos', 'curso')
            ->join('cuotas.pagoDetalles', 'pagoDetalle')
            ->join('pagoDetalle.pago', 'pago')
            ->join('pago.user', 'user')
            ->where('curso.id = :idCurso')
            ->andWhere('user.id = :idUser')
            ->setParameter('idCurso', $idCurso)
            ->setParameter('idUser', $idUser)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByCuotasDeCurso($idCurso)
    {
        return $this->createQueryBuilder('cuotas')
            ->join('cuotas.cursos', 'curso')
            ->where('curso.id = :idCurso')
            ->setParameter('idCurso', $idCurso)
        ;
    }

}
