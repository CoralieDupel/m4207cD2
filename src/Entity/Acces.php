<?php

namespace App\Entity;

use App\Repository\AccesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccesRepository::class)
 */
class Acces
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=document::class, inversedBy="Auto")
     * @ORM\JoinColumn(nullable=false)
     */
    private $doc;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Autor;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="acces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Util;

    /**
     * @ORM\ManyToOne(targetEntity=Autorisation::class, inversedBy="acces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Auto;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDoc(): ?document
    {
        return $this->doc;
    }

    public function setDoc(?document $doc): self
    {
        $this->doc = $doc;

        return $this;
    }

    public function getAutor(): ?string
    {
        return $this->Autor;
    }

    public function setAutor(string $Autor): self
    {
        $this->Autor = $Autor;

        return $this;
    }

    public function getUtil(): ?Utilisateur
    {
        return $this->Util;
    }

    public function setUtil(?Utilisateur $Util): self
    {
        $this->Util = $Util;

        return $this;
    }

    public function getAuto(): ?Autorisation
    {
        return $this->Auto;
    }

    public function setAuto(?Autorisation $Auto): self
    {
        $this->Auto = $Auto;

        return $this;
    }
}
