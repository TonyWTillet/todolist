<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Datetime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table
 */
#[ORM\Table]
#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[UniqueEntity(fields: ['title'], message: 'Le titre est déjà utilisé pour une autre tâche.')]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "datetime", nullable: false)]
    private Datetime $createdAt;

    #[ORM\Column(type: "string", nullable: false)]
    #[Assert\NotBlank(message: "Vous devez saisir un titre.")]
    private string $title;

    #[ORM\Column(type: "text", nullable: false)]
    #[Assert\NotBlank(message: "Vous devez saisir du contenu.")]
    private string $content;

    #[ORM\Column(type: "boolean", nullable: false, options: ["default" => false])]
    private bool $isDone;

    public function __construct()
    {
        $this->createdAt = new Datetime();
        $this->isDone = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): Datetime
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getContent() : string
    {
        return $this->content;
    }

    public function setContent($content): void
    {
        $this->content = $content;
    }

    public function isDone(): bool
    {
        return $this->isDone;
    }

    public function toggle($flag): void
    {
        $this->isDone = $flag;
    }
}
