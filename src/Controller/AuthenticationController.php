<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\Authentication\LoginType;
use App\Form\Type\Authentication\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticationController extends AbstractController
{
    #[Route('/login', name: 'app_authentication_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->createForm(LoginType::class, ['_username' => $authenticationUtils->getLastUsername()]);

        return $this->render('Page/Authentication/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/register', name: 'app_authentication_register')]
    public function register(
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_authentication_login');
        }

        return $this->render('Page/Authentication/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
