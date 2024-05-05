<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $commentText = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $assosiatedProject = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $assosiatedUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentText(): ?string
    {
        return $this->commentText;
    }

    public function setCommentText(string $commentText): static
    {
        $this->commentText = $commentText;

        return $this;
    }

    public function getAssosiatedProject(): ?Project
    {
        return $this->assosiatedProject;
    }

    public function setAssosiatedProject(?Project $assosiatedProject): static
    {
        $this->assosiatedProject = $assosiatedProject;

        return $this;
    }

    public function getAssosiatedUser(): ?User
    {
        return $this->assosiatedUser;
    }

    public function setAssosiatedUser(?User $assosiatedUser): static
    {
        $this->assosiatedUser = $assosiatedUser;

        return $this;
    }
}
