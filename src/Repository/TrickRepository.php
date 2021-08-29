<?php

namespace App\Repository;

use App\Entity\Trick;
use App\Entity\Search\TrickSearch;
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
	 * @param int         $offset
	 *
	 * @param TrickSearch $search
	 *
	 * @return Paginator
	 */
	public function getTrickPaginator(int $offset, TrickSearch $search): Paginator
	{
	if (count($search->getCategories()) > 0) {
			$query = $this->getPaginateQuery($offset);
			$k = 0;
				foreach($search->getCategories() as $category) {
					$k++;
					$query = $query
						->orWhere(":categories$k = t.category")
						->setParameter("categories$k", $category);
				}
			$query->getQuery();
			return new Paginator($query);
		}
		$query = $this->getPaginateQuery($offset)
			->getQuery();
		return new Paginator($query);
	}
	
	private function getPaginateQuery($offset)
	{
		return $this->createQueryBuilder('t')
			->setMaxResults($offset)
			->orderBy('t.created_at', 'desc');
	}
}
