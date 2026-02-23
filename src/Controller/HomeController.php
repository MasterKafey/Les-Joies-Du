<?php

namespace App\Controller;

use App\Entity\Post;
use App\Security\Voter\Post\PostVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(methods: [Request::METHOD_GET])]
class HomeController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/')]
    public function index(): Response
    {
        $posts = $this->entityManager->getRepository(Post::class)->findBy([], ['id' => 'DESC']);

        return $this->render('Page/Home/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/posts')]
    public function loadPosts(
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $posts = $entityManager
            ->getRepository(Post::class)
            ->findAllByPage(
                page: $request->query->getInt('page', 1),
                limit: $request->query->getInt('limit', 10)
            );

        return $this->json(array_map(fn($post) => [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'url' => $post->getUrl(),
            'author' => $post->getAuthor()->getUsername(),
            'manageable' => $this->isGranted(PostVoter::IS_MANAGEABLE, $post),
        ], $posts));
    }
}
