<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
class Trick
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $difficulty;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $trickGroup;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: TrickVideo::class, orphanRemoval: true)]
    private $trickVideos;

    #[ORM\Column(type: 'string', length: 255)]
    private $content;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: TrickPhoto::class, orphanRemoval: true)]
    private $trickPhotos;

    public function __construct()
    {
        $this->trickVideos = new ArrayCollection();
        $this->trickPhotos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(?int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
        
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTrickGroup(): ?string
    {
        return $this->trickGroup;
    }

    public function setTrickGroup(string $trickGroup): self
    {
        $this->trickGroup = $trickGroup;

        return $this;
    }

    /**
     * @return Collection|TrickVideo[]
     */
    public function getTrickVideos(): Collection
    {
        return $this->trickVideos;
    }

    public function addTrickVideo(TrickVideo $trickVideo): self
    {
        if (!$this->trickVideos->contains($trickVideo)) {
            $this->trickVideos[] = $trickVideo;
            $trickVideo->setTrick($this);
        }

        return $this;
    }

    public function removeTrickVideo(TrickVideo $trickVideo): self
    {
        if ($this->trickVideos->removeElement($trickVideo)) {
            // set the owning side to null (unless already changed)
            if ($trickVideo->getTrick() === $this) {
                $trickVideo->setTrick(null);
            }
        }

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

    /**
     * @return Collection|TrickPhoto[]
     */
    public function getTrickPhotos(): Collection
    {
        return $this->trickPhotos;
    }

    public function addTrickPhoto(TrickPhoto $trickPhoto): self
    {
        if (!$this->trickPhotos->contains($trickPhoto)) {
            $this->trickPhotos[] = $trickPhoto;
            $trickPhoto->setTrick($this);
        }

        return $this;
    }

    public function removeTrickPhoto(TrickPhoto $trickPhoto): self
    {
        if ($this->trickPhotos->removeElement($trickPhoto)) {
            // set the owning side to null (unless already changed)
            if ($trickPhoto->getTrick() === $this) {
                $trickPhoto->setTrick(null);
            }
        }

        return $this;
    }
}
