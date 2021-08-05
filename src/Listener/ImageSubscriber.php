<?php


namespace App\Listener;


use App\Entity\Image;
use App\Entity\Trick;
use App\Service\LocalFilesystemFileDeleter;
use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageSubscriber implements EventSubscriber
{
	/**
	 * @var ParameterBagInterface
	 */
	private ParameterBagInterface $params;
	/**
	 * @var LocalFilesystemFileDeleter
	 */
	private LocalFilesystemFileDeleter $deleter;
	
	public function __construct(ParameterBagInterface $params, LocalFilesystemFileDeleter $deleter)
	{
		$this->params = $params;
		$this->deleter = $deleter;
	}
	
	public function getSubscribedEvents()
	{
		return ['preRemove'];
	}
	
	public function preRemove(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		
		if ($entity instanceof Trick && $entity->getHeaderImage() !== 'default.jpg') {
			$headerImage = $this->params->get('header_directory') . '/' . $entity->getHeaderImage();
			$this->deleter->delete($headerImage);
		}
		if ($entity instanceof Image) {
			$image = $this->params->get('images_directory') . '/' . $entity->getName();
			$this->deleter->delete($image);
		}
	}
}