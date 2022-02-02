<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
class Document
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Chemin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Actif;

    /**
     * @ORM\OneToMany(targetEntity=Acces::class, mappedBy="doc")
     */
    private $Auto;

    public function __construct()
    {
        $this->Auto = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChemin(): ?string
    {
        return $this->Chemin;
    }

    public function setChemin(string $Chemin): self
    {
        $this->Chemin = $Chemin;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->Actif;
    }

    public function setActif(bool $Actif): self
    {
        $this->Actif = $Actif;

        return $this;
    }

    /**
     * @return Collection|Acces[]
     */
    public function getAuto(): Collection
    {
        return $this->Auto;
    }

    public function addAuto(Acces $auto): self
    {
        if (!$this->Auto->contains($auto)) {
            $this->Auto[] = $auto;
            $auto->setDoc($this);
        }

        return $this;
    }

    public function removeAuto(Acces $auto): self
    {
        if ($this->Auto->removeElement($auto)) {
            // set the owning side to null (unless already changed)
            if ($auto->getDoc() === $this) {
                $auto->setDoc(null);
            }
        }

        return $this;
    }
}
