<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\Type\Post\PostType;
use App\Security\Voter\Post\PostVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/post')]
class PostController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {

    }

    #[Route('/create', name: 'app_post_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request): Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_post_read', ['id' => $post->getId()]);
        }

        return $this->render('Page/Post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'app_post_read', requirements: ['id' => '\d+'], methods: [Request::METHOD_GET])]
    public function read(Post $post): Response
    {
        return $this->render('Page/Post/read.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route(path: '/{id}/update', name: 'app_post_update', requirements: ['id' => '\d+'], methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function update(Request $request, Post $post): Response
    {
        $this->denyAccessUnlessGranted(PostVoter::IS_MANAGEABLE, $post);

        $form = $this->createForm(PostType::class, $post)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_post_read', ['id' => $post->getId()]);
        }

        return $this->render('Page/Post/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}/delete', name: 'app_post_delete', requirements: ['id' => '\d+'], methods: [Request::METHOD_DELETE])]
    public function delete(
        Post $post
    ): Response
    {
        $this->denyAccessUnlessGranted(PostVoter::IS_MANAGEABLE, $post);

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return new Response('OK');
    }
}
