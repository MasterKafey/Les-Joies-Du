<?php

namespace App\Security\Voter\Post;

use App\Entity\Post;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
{
    const string IS_MANAGEABLE = 'IS_MANAGEABLE';

    public function __construct(
        private readonly Security $security
    )
    {

    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Post && $attribute === self::IS_MANAGEABLE;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        if (!($subject instanceof Post) || $attribute !== self::IS_MANAGEABLE) {
            throw new \LogicException('This code should not be reached: supports method is not called or invalid');
        }

        if ($this->security->isGranted('ROLE_ADMIN', $token->getUser())) {
            return true;
        }

        return $subject->getAuthor() === $token->getUser();
    }
}
