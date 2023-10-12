<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Curso;
use App\Entity\Pago;
use App\Entity\Reclamo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    /**
     * @return User[] Returns an array of User objects
     */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('u')
    //         ->andWhere('u.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('u.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    /**
     * @return Curso[] Returns an array of User objects
     */
    public function findByMisCursos($userId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c')
            ->from('App:Curso', 'c')
            ->join('c.users', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId);

        $query = $qb->getQuery();

        return $query->execute();
    }

    /**
     * @return Pago[] Returns an array of User objects
     */
    public function findByMisPagos($userId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from('App:Pago', 'p')
            ->join('p.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId);

        $query = $qb->getQuery();

        return $query->execute();
    }

    // public function findOneBySomeField($value): ?User
    // {
    //     return $this->createQueryBuilder('u')
    //         ->andWhere('u.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }


    //->select('c', 'u') // trae todos los cursos y los  Usuarios
    //->from('App:Curso', 'c')
    //->join('c.users', 'u');         


    /**
     * @return Curso[] Returns an array of User objects
     */
    public function findUsuariosCursos(): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c.id AS curso_id', 'u.nombre', 'u.apellido', 'u.email')
            ->from('App:Curso', 'c')
            ->join('c.users', 'u');
        $query = $qb->getQuery();

        return $query->getResult();
    }

    /**
     * @return Reclamo[] Returns an array of User objects
     */
    public function findByMisReclamos($userId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('r')
            ->from('App:Reclamo', 'r')
            ->join('r.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId);

        $query = $qb->getQuery();
        
        return $query->execute();
    }
}
