<?php


namespace App\Listener;


use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\User;
use App\Service\LocalFilesystemFileDeleter;
use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MediaSubscriber implements EventSubscriber
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
		return ['preRemove', 'preUpdate'];
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
	
	public function preUpdate(PreUpdateEventArgs $args)
	{
		if ($args->hasChangedField('headerImage')) {
			if ($args->getObject() instanceof Trick && $args->getOldValue('headerImage') !== 'default.jpg') {
				$oldHeaderImagePath = $this->params->get('header_directory') . '/' . $args->getOldValue('headerImage');
				$this->deleter->delete($oldHeaderImagePath);
			}
		}
		
		if ($args->hasChangedField('picture')) {
			if ($args->getObject() instanceof User && $args->getOldValue('picture')) {
				$oldUserPicturePath = $this->params->get('user_picture_directory') . '/' . $args->getOldValue('picture');
				$this->deleter->delete($oldUserPicturePath);
			}
		}
	}
}