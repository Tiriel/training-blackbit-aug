<?php

namespace App\Security\Voter;

use App\Entity\Book;
use App\Entity\User;
use App\Security\Permissions\BookPermissions;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BookCreatedByVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Book
            && \in_array($attribute, [BookPermissions::EDIT, BookPermissions::DELETE]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        /** @var Book $subject */
        return $user === $subject->getCreatedBy();
    }
}
