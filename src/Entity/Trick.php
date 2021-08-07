<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TrickRepository::class)
 * @UniqueEntity("title")
 */
class Trick
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 * @Assert\Length(min=5, max=255)     *
	 */
	private $title;
	
	/**
	 * @ORM\Column(type="text")
	 * @Assert\NotBlank
	 * @Assert\Length(min=10)     *
	 */
	private $description;
	
	/**
	 * @ORM\Column(type="datetime_immutable")
	 */
	private $created_at;
	
	/**
	 * @ORM\Column(type="datetime_immutable", nullable=true)
	 */
	private $updated_at;
	
	/**
	 * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="tricks")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $category;
	
	/**
	 * @ORM\OneToMany(targetEntity=Image::class, mappedBy="trick", orphanRemoval=true, cascade={"persist"})
	 */
	private $images;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $headerImage;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="trick", orphanRemoval=true, cascade={"persist"})
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="trick", orphanRemoval=true)
     */
    private $comments;
	
	public function __construct()
                              	{
                              		$this->created_at = new \DateTimeImmutable();
                              		$this->images = new ArrayCollection();
                                $this->videos = new ArrayCollection();
                                $this->comments = new ArrayCollection();
                              	}
	
	public function getId(): ?int
                              	{
                              		return $this->id;
                              	}
	
	public function getTitle(): ?string
                              	{
                              		return $this->title;
                              	}
	
	public function setTitle(string $title): self
                              	{
                              		$this->title = $title;
                              		
                              		return $this;
                              	}
	
	public function getSlug(): string
                              	{
                              		return strtolower((new AsciiSlugger())->slug($this->title));
                              	}
	
	public function getDescription(): ?string
                              	{
                              		return $this->description;
                              	}
	
	public function setDescription(string $description): self
                              	{
                              		$this->description = $description;
                              		
                              		return $this;
                              	}
	
	public function getCreatedAt(): ?\DateTimeImmutable
                              	{
                              		return $this->created_at;
                              	}
	
	public function setCreatedAt(\DateTimeImmutable $created_at): self
                              	{
                              		$this->created_at = $created_at;
                              		
                              		return $this;
                              	}
	
	public function getUpdatedAt(): ?\DateTimeImmutable
                              	{
                              		return $this->updated_at;
                              	}
	
	public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
                              	{
                              		$this->updated_at = $updated_at;
                              		
                              		return $this;
                              	}
	
	public function getCategory(): ?Category
                              	{
                              		return $this->category;
                              	}
	
	public function setCategory(?Category $category): self
                              	{
                              		$this->category = $category;
                              		
                              		return $this;
                              	}
	
	/**
	 * @return Collection|Image[]
	 */
	public function getImages(): Collection
                              	{
                              		return $this->images;
                              	}
	
	public function addImage(Image $image): self
                              	{
                              		if (!$this->images->contains($image)) {
                              			$this->images[] = $image;
                              			$image->setTrick($this);
                              		}
                              		
                              		return $this;
                              	}
	
	public function removeImage(Image $image): self
                              	{
                              		if ($this->images->removeElement($image)) {
                              			// set the owning side to null (unless already changed)
                              			if ($image->getTrick() === $this) {
                              				$image->setTrick(null);
                              			}
                              		}
                              		
                              		return $this;
                              	}
	
	/**
	 * @return mixed
	 */
	public function getHeaderImage()
                              	{
                              		return $this->headerImage;
                              	}
	
	/**
	 * @param mixed $headerImage
	 *
	 * @return Trick
	 */
	public function setHeaderImage($headerImage)
                              	{
                              		$this->headerImage = $headerImage;
                              		return $this;
                              	}

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }
}
