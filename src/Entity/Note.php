<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Services\MarkdownServices;


#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{

    private ?MarkdownServices $markdown_services = null;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }


    public function setMarkdownService(MarkdownServices $service)
    {
        $this->markdown_services = $service;
    }

    public function getBodyHtml(): string
    {
        if(empty($this->body))
        {
            return '';
        }

        if(!isset($this->markdown_services))
        {
            return $this->body;
        }

        return $this->markdown_services->convertToHtml($this->body);
    }
}
