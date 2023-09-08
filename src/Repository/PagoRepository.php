<?php

namespace App\Repository;

use App\Entity\Pago;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pago>
 *
 * @method Pago|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pago|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pago[]    findAll()
 * @method Pago[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PagoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pago::class);
    }

    public function add(Pago $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Pago $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    
   
   
    public function findAllPagos(): array
    {
        $qb = $this->createQueryBuilder('pago')
        ->select('pago, PARTIAL curso.{id, nombre, activo}, PARTIAL user.{id, dni, apellido},
        PARTIAL cuota.{id, monto, numeroCuota}, 
        PARTIAL pagoDetalle.{id, montoCuotas, numeroTicket, 
        montoTicket, fechaTicket, observacion, imageName}')
        ->join('pago.curso', 'curso')
        ->join('pago.user', 'user')
        ->join('pago.pagoDetalles', 'pagoDetalle')
        ->join('pagoDetalle.cuotas', 'cuota')
        ->orderBy('pago.id', 'ASC');

            $query = $qb->getQuery();
            
            return $query->execute();
    }


    public function findAllPagosPorDniFecha($dni, $startDate, $endDate): array
{
    $qb = $this->createQueryBuilder('pago')
        ->select('pago, PARTIAL curso.{id, nombre, activo}, PARTIAL user.{id, dni, apellido},
            PARTIAL cuota.{id, monto, numeroCuota}, 
            PARTIAL pagoDetalle.{id, montoCuotas, numeroTicket, 
            montoTicket, fechaTicket, observacion, imageName}')
        ->join('pago.curso', 'curso')
        ->join('pago.user', 'user')
        ->join('pago.pagoDetalles', 'pagoDetalle')
        ->join('pagoDetalle.cuotas', 'cuota')
        ->orderBy('pago.id', 'ASC');
        // Condiciones OR para cada filtro
        $orX = $qb->expr()->orX();

        if ($dni) {
            $orX->add($qb->expr()->eq('user.dni', ':dni'));
            $orX->add($qb->expr()->eq('user.apellido', ':dni'));
            $qb->setParameter('dni', $dni);
        }

        if ($startDate) {
            $startDate = new \DateTimeImmutable($startDate);
            $qb->andWhere($qb->expr()->gte('pago.created_at', ':startDate'));
            $qb->setParameter('startDate', $startDate);
        }

        if ($endDate) {
            $endDate = new \DateTimeImmutable($endDate);
            $qb->andWhere($qb->expr()->lte('pago.created_at', ':endDate'));
            $qb->setParameter('endDate', $endDate);
        }

         $qb->andWhere($orX);

        return $qb->getQuery()->getResult();
        }
        
  
    public function findAllPagosPorDni($dni): array
    {
        $qb = $this->createQueryBuilder('pago')
            ->select('pago, PARTIAL curso.{id, nombre, activo}, PARTIAL user.{id, dni, apellido},
                PARTIAL cuota.{id, monto, numeroCuota}, 
                PARTIAL pagoDetalle.{id, montoCuotas, numeroTicket, 
                montoTicket, fechaTicket, observacion, imageName}')
            ->join('pago.curso', 'curso')
            ->join('pago.user', 'user')
            ->join('pago.pagoDetalles', 'pagoDetalle')
            ->join('pagoDetalle.cuotas', 'cuota')
            ->orderBy('pago.id', 'ASC');
    
        if (is_string($dni)) {
            $qb->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('user.dni', ':dni'),
                    $qb->expr()->eq('user.apellido', ':dni')
                )
            );
    
            $qb->setParameter('dni', $dni);
        }
    
        return $qb->getQuery()->getResult();
    }


//    /**
//     * @return Pago[] Returns an array of Pago objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Pago
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
