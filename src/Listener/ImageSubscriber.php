<?php


namespace App\Listener;


use App\Entity\Image;
use App\Entity\Trick;
use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class ImageSubscriber implements EventSubscriber
{
	private ParameterBagInterface $params;
	
	public function __construct(ParameterBagInterface $params)
	{
		$this->params = $params;
	}
	
	public function getSubscribedEvents()
	{
		return ['preRemove'];
	}
	
	public function preRemove(LifecycleEventArgs $args)
	{
		$filesystem = new Filesystem();
		$entity = $args->getObject();
		
		if ($entity instanceof Trick && $entity->getHeaderImage() !== 'default.jpg') {
			$filesystem->remove(
				$this->params->get('header_directory') . '/' . $entity->getHeaderImage()
			);
		}
		if ($entity instanceof Image) {
			$filesystem->remove(
				$this->params->get('images_directory') . '/' . $entity->getName()
			);
		}
	}
}