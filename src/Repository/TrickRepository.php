<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
	public const PAGINATOR_PER_PAGE = 15;
	
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }
	
	/**
	 * @param int        $offset
	 *
	 * @return Paginator
	 */
	public function getTrickPaginator(int $offset): Paginator
    {
        $query = $this->createQueryBuilder('t')
            ->setMaxResults($offset)
			->orderBy('t.created_at')
            ->getQuery();
        return new Paginator($query);
    }
}
