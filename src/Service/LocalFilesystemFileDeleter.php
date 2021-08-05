<?php


namespace App\Service;


use Symfony\Component\Filesystem\Filesystem;

class LocalFilesystemFileDeleter
{
	/**
	 * @var Filesystem
	 */
	private Filesystem $filesystem;
	
	public function __construct(Filesystem $filesystem)
	{
		$this->filesystem = $filesystem;
	}
	
	public function delete($pathToFile)
	{
		$this->filesystem->remove($pathToFile);
	}
}