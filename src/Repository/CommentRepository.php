<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
	public const PAGINATOR_PER_PAGE = 10;
	
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }
	
	/**
	 * @param int $trickId
	 *
	 * @return Paginator
	 */
	public function getFirstComments(int $trickId): Paginator
	{
		$query = $this->createQueryBuilder('c')
			->where(":trickId = c.trick")
			->setParameter("trickId", $trickId)
			->setMaxResults(self::PAGINATOR_PER_PAGE)
			->orderBy('c.created_at', 'desc')
			->getQuery();
		return new Paginator($query);
	}
	
	/**
	 * @param int $offset
	 *
	 * @param int $trickId
	 *
	 * @return Paginator
	 */
	public function getCommentPaginator(int $offset, int $trickId): Paginator
	{
		$query = $this->createQueryBuilder('c')
			->where(":trickId = c.trick")
			->setParameter("trickId", $trickId)
			->setFirstResult($offset)
			->setMaxResults(self::PAGINATOR_PER_PAGE)
			->orderBy('c.created_at', 'desc')
			->getQuery();
		return new Paginator($query);
	}
}
