<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userName = null;

    /**
     * @var Collection<int, Team>
     */
    #[ORM\ManyToMany(targetEntity: Team::class, inversedBy: 'users')]
    private Collection $team;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'assignedUser')]
    private Collection $assignedTask;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'taskCreator', orphanRemoval: true)]
    private Collection $taskCreator;

    public function __construct()
    {
        $this->team = new ArrayCollection();
        $this->assignedTask = new ArrayCollection();
        $this->taskCreator = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(?string $userName): static
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeam(): Collection
    {
        return $this->team;
    }

    public function addTeam(Team $team): static
    {
        if (!$this->team->contains($team)) {
            $this->team->add($team);
        }

        return $this;
    }

    public function removeTeam(Team $team): static
    {
        $this->team->removeElement($team);

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getAssignedTask(): Collection
    {
        return $this->assignedTask;
    }

    public function addAssignedTask(Task $assignedTask): static
    {
        if (!$this->assignedTask->contains($assignedTask)) {
            $this->assignedTask->add($assignedTask);
            $assignedTask->setAssignedUser($this);
        }

        return $this;
    }

    public function removeAssignedTask(Task $assignedTask): static
    {
        if ($this->assignedTask->removeElement($assignedTask)) {
            // set the owning side to null (unless already changed)
            if ($assignedTask->getAssignedUser() === $this) {
                $assignedTask->setAssignedUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTaskCreator(): Collection
    {
        return $this->taskCreator;
    }

    public function addTaskCreator(Task $taskCreator): static
    {
        if (!$this->taskCreator->contains($taskCreator)) {
            $this->taskCreator->add($taskCreator);
            $taskCreator->setTaskCreator($this);
        }

        return $this;
    }

    public function removeTaskCreator(Task $taskCreator): static
    {
        if ($this->taskCreator->removeElement($taskCreator)) {
            // set the owning side to null (unless already changed)
            if ($taskCreator->getTaskCreator() === $this) {
                $taskCreator->setTaskCreator(null);
            }
        }

        return $this;
    }
}
