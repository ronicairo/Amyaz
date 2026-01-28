<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VerbeRepository;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

#[ORM\Entity(repositoryClass: VerbeRepository::class)]
#[ORM\Table(name: "verbes")]
class Verbe
{

    use TimestampableEntity;
    use SoftDeleteableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, options: ["collation" => "utf8mb4_bin"])]
    private $verbeRifain;

    #[ORM\Column(type: 'string', length: 255)]
    private $verbeFrancais;

    #[ORM\Column(type: 'string', length: 255)]
    private $verbeTifinagh;

    #[ORM\Column(type: 'string', length: 255)]
    private $forme;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $R1;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $R2;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $R3;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $R4;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $R5;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $R6;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $R7;

    #[ORM\Column(type: 'datetime', nullable: true)] // Permettre à createdAt d'être nul
    protected $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)] // Permettre à updatedAt d'être nul
    protected $updatedAt;
    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVerbeRifain(): ?string
    {
        return $this->verbeRifain;
    }

    public function setVerbeRifain(string $verbeRifain): self
    {
        $this->verbeRifain = $verbeRifain;

        return $this;
    }

    public function getVerbeFrancais(): ?string
    {
        return $this->verbeFrancais;
    }

    public function setVerbeFrancais(string $verbeFrancais): self
    {
        $this->verbeFrancais = $verbeFrancais;

        return $this;
    }

    public function getVerbeTifinagh(): ?string
    {
        return $this->verbeTifinagh;
    }

    public function setVerbeTifinagh(string $verbeTifinagh): self
    {
        $this->verbeTifinagh = $verbeTifinagh;

        return $this;
    }

    public function getForme(): ?string
    {
        return $this->forme;
    }

    public function setForme(string $forme): self
    {
        $this->forme = $forme;

        return $this;
    }

    public function getR1(): ?string
    {
        return $this->R1;
    }

    public function setR1(?string $R1): self
    {
        $this->R1 = $R1;

        return $this;
    }

    public function getR2(): ?string
    {
        return $this->R2;
    }

    public function setR2(?string $R2): self
    {
        $this->R2 = $R2;

        return $this;
    }

    public function getR3(): ?string
    {
        return $this->R3;
    }

    public function setR3(?string $R3): self
    {
        $this->R3 = $R3;

        return $this;
    }

    public function getR4(): ?string
    {
        return $this->R4;
    }

    public function setR4(?string $R4): self
    {
        $this->R4 = $R4;

        return $this;
    }

    public function getR5(): ?string
    {
        return $this->R5;
    }

    public function setR5(?string $R5): self
    {
        $this->R5 = $R5;

        return $this;
    }

    public function getR6(): ?string
    {
        return $this->R6;
    }

    public function setR6(?string $R6): self
    {
        $this->R6 = $R6;

        return $this;
    }

    public function getR7(): ?string
    {
        return $this->R7;
    }

    public function setR7(?string $R7): self
    {
        $this->R7 = $R7;

        return $this;
    }
}
