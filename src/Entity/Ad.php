<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Cocur\Slugify\Slugify;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\AdRepository")
 * @ORM\HasLifecycleCallbacks
 *  @UniqueEntity(
 * fields={"title"},
 * message="une autre annonce possede le meme titre"
 * )
 */
class Ad
{
/**
 * @ORM\Id()
 * @ORM\GeneratedValue()
 * @ORM\Column(type="integer")
 */
private $id;

/**
 * @ORM\Column(type="string", length=255)
 * @Assert\Length(
 *      min = 10,
 *      max = 50,
 *      minMessage = "le titre de faire au moins 10 caractéres",
 *      maxMessage = "le titre ne doit pas faire plus de 255 caractéres")
 */
private $title;

/**
 * @ORM\Column(type="string", length=255)
 */
private $slug;

/**
 * @ORM\Column(type="float")
 */
private $price;

/**
 * @ORM\Column(type="text")
 */
private $introduction;

/**
 * @ORM\Column(type="text")
 */
private $content;

/**
 * @ORM\Column(type="string", length=255)
 * @Assert\Length(min=10, max=255))
 */
private $coverImage;

/**
 * @ORM\Column(type="integer")
 */
private $rooms;

/**
 * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="ad", orphanRemoval=true)
 * @Assert\valid()
 */
private $images;

public function __construct()
{
$this->images = new ArrayCollection();
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



/**
 *
 * @ORM\PrePersist
 * @ORM\PreUpdate
 *
 * @return void
 */
public function initializeSlug() {

if(empty($this->slug)){
$slugify = new Slugify();
$this->slug = $slugify->slugify($this->title);

}
}



public function getSlug(): ?string
{
return $this->slug;
}

public function setSlug(string $slug): self
{
$this->slug = $slug;

return $this;
}

public function getPrice(): ?float
{
return $this->price;
}

public function setPrice(float $price): self
{
$this->price = $price;

return $this;
}

public function getIntroduction(): ?string
{
return $this->introduction;
}


public function setIntroduction(string $introduction): self
{
$this->introduction = $introduction;

return $this;
}




public function getContent(): ?string
{
return $this->content;
}

public function setContent(string $content): self
{
$this->content = $content;

return $this;
}

public function getCoverImage(): ?string
{
return $this->coverImage;
}

public function setCoverImage(string $coverImage): self
{
$this->coverImage = $coverImage;

return $this;
}

public function getRooms(): ?int
{
return $this->rooms;
}

public function setRooms(int $rooms): self
{
$this->rooms = $rooms;

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
$image->setAd($this);
}

return $this;
}

public function removeImage(Image $image): self
{
if ($this->images->contains($image)) {
$this->images->removeElement($image);
// set the owning side to null (unless already changed)
if ($image->getAd() === $this) {
$image->setAd(null);
}
}

return $this;
}
}
