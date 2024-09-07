<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TraductionRepository;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

#[ORM\Entity(repositoryClass: TraductionRepository::class)]
class Traduction
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $wordFR = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $wordEN = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $singular = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phonetic_plural = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phonetic_singular = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $plural = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $grammar_fr = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $grammar_en = null;

    #[ORM\Column(type: 'boolean')]
    private bool $request = false;

    #[ORM\Column(length: 400, nullable: true)]
    private ?string $justification = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?User $requestedBy = null;    

    #[ORM\ManyToOne(targetEntity: Status::class)]
    #[ORM\JoinColumn(nullable: true)]  // Permettre que status_id soit nul
    private ?Status $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWordFR(): ?string
    {
        return $this->wordFR;
    }

    public function setWordFR(?string $wordFR): static
    {
        $this->wordFR = $wordFR;

        return $this;
    }

    public function getWordEN(): ?string
    {
        return $this->wordEN;
    }

    public function setWordEN(?string $wordEN): static
    {
        $this->wordEN = $wordEN;

        return $this;
    }

    public function getSingular(): ?string
    {
        return $this->singular;
    }

    public function setSingular(?string $singular): static
    {
        $this->singular = $singular;

        return $this;
    }

    public function getPhoneticPlural(): ?string
    {
        return $this->phonetic_plural;
    }

    public function setPhoneticPlural(?string $phonetic_plural): static
    {
        $this->phonetic_plural = $phonetic_plural;

        return $this;
    }

    public function getPhoneticSingular(): ?string
    {
        return $this->phonetic_singular;
    }

    public function setPhoneticSingular(?string $phonetic_singular): static
    {
        $this->phonetic_singular = $phonetic_singular;

        return $this;
    }

    public function getPlural(): ?string
    {
        return $this->plural;
    }

    public function setPlural(?string $plural): static
    {
        $this->plural = $plural;

        return $this;
    }

    public function getGrammarFr(): ?string
    {
        return $this->grammar_fr;
    }

    public function setGrammarFr(?string $grammar_fr): static
    {
        $this->grammar_fr = $grammar_fr;

        return $this;
    }

    public function getGrammarEn(): ?string
    {
        return $this->grammar_en;
    }

    public function setGrammarEn(?string $grammar_en): static
    {
        $this->grammar_en = $grammar_en;

        return $this;
    }

    public function isRequest(): bool
    {
        return $this->request;
    }


    /**
     * @ORM\Column(type="text")
     */
    private $content;

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setRequest(bool $request, array $roles): static
    {
        if (in_array('ROLE_ADMIN', $roles) || in_array('ROLE_MODERATOR', $roles)) {
            $this->request = false;
        } else {
            $this->request = $request;
        }

        return $this;
    }

    public function getRequestedBy(): ?User
    {
        return $this->requestedBy;
    }

    public function setRequestedBy(?User $requestedBy): static
    {
        $this->requestedBy = $requestedBy;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getJustification(): ?string
    {
        return $this->justification;
    }

    public function setJustification(?string $justification): static
    {
        $this->justification = $justification;

        return $this;
    }
}
