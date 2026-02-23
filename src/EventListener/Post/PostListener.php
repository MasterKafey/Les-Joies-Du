<?php

namespace App\EventListener\Post;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Post::class)]
readonly class PostListener
{
    public function __construct(
        private Security $security
    )
    {

    }

    public function prePersist(Post $post): void
    {
        $post->setAuthor($this->security->getUser());
    }
}
