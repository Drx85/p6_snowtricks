<?php


namespace App\Entity\Search;


use Doctrine\Common\Collections\ArrayCollection;

class TrickSearch
{
	/**
	 * @var ArrayCollection
	 */
	private ArrayCollection $categories;
	
	public function __construct()
	{
		$this->categories = new ArrayCollection();
	}
	
	/**
	 * @return ArrayCollection
	 */
	public function getCategories(): ArrayCollection
	{
		return $this->categories;
	}
	
	/**
	 * @param ArrayCollection $categories
	 */
	public function setCategories(ArrayCollection $categories): void
	{
		$this->categories = $categories;
	}
	
}