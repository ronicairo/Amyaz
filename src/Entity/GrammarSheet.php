<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class GrammarSheet
{

    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    private ?string $titleFr = null;

    #[ORM\Column(type: 'text')]
    private ?string $subtitleFr = null;

    #[ORM\Column(type: 'text')]
    private ?string $contentFr = null;

    #[ORM\Column(type: 'text')]
    private ?string $titleEn = null;

    #[ORM\Column(type: 'text')]
    private ?string $subtitleEn = null;

    #[ORM\Column(type: 'text')]
    private ?string $contentEn = null;

    private ?string $locale = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleFr(): ?string
    {
        return $this->titleFr;
    }

    public function setTitleFr(string $titleFr): self
    {
        $this->titleFr = $titleFr;
        return $this;
    }

    public function getSubtitleFr(): ?string
    {
        return $this->subtitleFr;
    }

    public function setSubtitleFr(string $subtitleFr): self
    {
        $this->subtitleFr = $subtitleFr;
        return $this;
    }

    public function getContentFr(): ?string
    {
        return $this->contentFr;
    }

    public function setContentFr(string $contentFr): self
    {
        $this->contentFr = $contentFr;
        return $this;
    }

    public function getTitleEn(): ?string
    {
        return $this->titleEn;
    }

    public function setTitleEn(string $titleEn): self
    {
        $this->titleEn = $titleEn;
        return $this;
    }

    public function getSubtitleEn(): ?string
    {
        return $this->subtitleEn;
    }

    public function setSubtitleEn(string $subtitleEn): self
    {
        $this->subtitleEn = $subtitleEn;
        return $this;
    }

    public function getContentEn(): ?string
    {
        return $this->contentEn;
    }

    public function setContentEn(string $contentEn): self
    {
        $this->contentEn = $contentEn;
        return $this;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->locale === 'fr' ? $this->titleFr : $this->titleEn;
    }

    public function getSubtitle(): ?string
    {
        return $this->locale === 'fr' ? $this->subtitleFr : $this->subtitleEn;
    }

    public function getContent(): ?string
    {
        return $this->locale === 'fr' ? $this->contentFr : $this->contentEn;
    }
}
