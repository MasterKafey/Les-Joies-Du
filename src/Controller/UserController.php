<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route(path: '/', name: 'app_user_list', methods: [Request::METHOD_GET])]
    public function list(): Response
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('Page/User/list.html.twig', [
            'users' => array_map(fn (User $user) => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'isEnabled' => $user->isEnabled(),
            ], $users),
        ]);
    }

    #[Route(path: '/toggle/{id}', name: 'app_user_toggle', methods: [Request::METHOD_POST])]
    public function toggleAccount(User $user): Response
    {
        $user->setIsEnabled(!$user->isEnabled());

        $this->entityManager->flush();

        return new Response('OK');
    }
}
