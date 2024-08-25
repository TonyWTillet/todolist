<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UserTaskPermissions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserTaskPermissions $userTaskPermissions;

    public function __construct(UserTaskPermissions $userTaskPermissions, private PasswordHasherFactoryInterface $passwordHasherFactory)
    {
        $this->userTaskPermissions = $userTaskPermissions;
    }

    #[Route('/users', name: 'user_list', methods: ['GET', 'POST'])]
    public function listAction(UserRepository $userRepository): Response
    {
        return $this->render('user/list.html.twig', ['users' => $userRepository->findAll()]);
    }


    #[Route('/users/create', name: 'user_create', methods: ['GET', 'POST'])]
    public function createAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $form->getData();
                $user->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash($user->getPassword()));

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', "L'utilisateur a bien été ajouté.");
                return $this->redirectToRoute('user_list');
            } catch (\Exception $e) {
                $this->addFlash('error', "L'utilisateur n'a pas pu être ajouté. Veuillez réessayer.");
            }

        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/users/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function editAction(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()  && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash($user->getPassword()));

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
