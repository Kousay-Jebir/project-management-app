<?php

// src/Security/TeamProjectVoter.php
namespace App\Security;

use App\Repository\TeamRepository;
use App\Repository\ProjectRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjectManagementVoter extends Voter
{
    private $teamRepository;
    private $projectRepository;

    public function __construct(TeamRepository $teamRepository, ProjectRepository $projectRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->projectRepository = $projectRepository;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // This voter only supports the "ACCESS_TEAM_PROJECT" attribute
        return $attribute === 'ACCESS_TEAM_PROJECT';
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // Get the current user
        $user = $token->getUser();

        // Check if the user is authenticated
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Get the team and project objects
        $team = $this->teamRepository->find($subject['teamId']);
        $project = $this->projectRepository->find($subject['projectId']);

        // Check if the team and project exist
        if (!$team || !$project) {
            return false;
        }

        // Check if the user is a member of the team and the project belongs to the team
        return $team->getUsers()->contains($user) && $team->getProjects()->contains($project);
    }
}

