<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $teamName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $teamDescription = null;

    #[ORM\Column(nullable: true)]
    private ?int $teamJoinCode = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'team')]
    private Collection $users;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'team', orphanRemoval: true)]
    private Collection $projects;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamName(): ?string
    {
        return $this->teamName;
    }

    public function setTeamName(string $teamName): static
    {
        $this->teamName = $teamName;

        return $this;
    }

    public function getTeamDescription(): ?string
    {
        return $this->teamDescription;
    }

    public function setTeamDescription(?string $teamDescription): static
    {
        $this->teamDescription = $teamDescription;

        return $this;
    }

    public function getTeamJoinCode(): ?int
    {
        return $this->teamJoinCode;
    }

    public function setTeamJoinCode(?int $teamJoinCode): static
    {
        $this->teamJoinCode = $teamJoinCode;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addTeam($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeTeam($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setTeam($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getTeam() === $this) {
                $project->setTeam(null);
            }
        }

        return $this;
    }
}
