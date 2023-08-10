<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use App\Security\Permissions\MoviePermissions;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MovieVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Movie
            && \in_array($attribute, MoviePermissions::All);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            MoviePermissions::RATED => $this->checkRated($subject, $user),
            MoviePermissions::EDIT, MoviePermissions::DELETE => $this->checkEdit($subject, $user),
            default => false,
        };
    }

    public function checkRated(Movie $movie, User $user): bool
    {
        if ('G' === $movie->getRated()) {
            return true;
        }

        $age = $user->getBirthday()?->diff(new \DateTimeImmutable('now'))->y;

        return match ($movie->getRated()) {
            'PG', 'PG-13' => $age && $age >= 13,
            'R', 'NC-17' => $age && $age >= 17,
            default => false,
        };
    }

    public function checkEdit(Movie $movie, User $user): bool
    {
        return $this->checkRated($movie, $user) && $user === $movie->getCreatedBy();
    }
}
