<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $taskName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $taskDescription = null;

    #[ORM\Column]
    private ?int $taskStatus = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\ManyToOne(inversedBy: 'assignedTask')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $assignedUser = null;

    #[ORM\ManyToOne(inversedBy: 'taskCreator')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $taskCreator = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskName(): ?string
    {
        return $this->taskName;
    }

    public function setTaskName(?string $taskName): static
    {
        $this->taskName = $taskName;

        return $this;
    }

    public function getTaskDescription(): ?string
    {
        return $this->taskDescription;
    }

    public function setTaskDescription(?string $taskDescription): static
    {
        $this->taskDescription = $taskDescription;

        return $this;
    }

    public function getTaskStatus(): ?int
    {
        return $this->taskStatus;
    }

    public function setTaskStatus(int $taskStatus): static
    {
        $this->taskStatus = $taskStatus;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getAssignedUser(): ?User
    {
        return $this->assignedUser;
    }

    public function setAssignedUser(?User $assignedUser): static
    {
        $this->assignedUser = $assignedUser;

        return $this;
    }

    public function getTaskCreator(): ?User
    {
        return $this->taskCreator;
    }

    public function setTaskCreator(?User $taskCreator): static
    {
        $this->taskCreator = $taskCreator;

        return $this;
    }
}
