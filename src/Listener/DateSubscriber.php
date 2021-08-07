<?php


namespace App\Listener;


use App\Entity\Trick;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class DateSubscriber implements EventSubscriber
{
	public function getSubscribedEvents()
	{
		return ['preUpdate'];
	}
	
	public function preUpdate(PreUpdateEventArgs $args)
	{
		if ($args->getObject() instanceof Trick) {
			$args->getObject()->setUpdatedAt(new \DateTimeImmutable());
		}
	}
}