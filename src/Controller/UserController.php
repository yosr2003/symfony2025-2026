<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/add', name: 'user_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/add.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }


#[Route('/user/list', name: 'user_list')]
public function list(EntityManagerInterface $em): Response
{
    $users = $em->getRepository(User::class)->findAll();

    return $this->render('user/list.html.twig', [
        'users' => $users,
    ]);
}
#[Route('/user/{id}', name: 'user_show')]
public function show(User $user): Response
{
    return $this->render('user/show.html.twig', [
        'user' => $user,
    ]);
}
#[Route('/user/edit/{id}', name: 'user_edit')]
public function edit(Request $request, User $user, EntityManagerInterface $em): Response
{
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        return $this->redirectToRoute('user_list');
    }

    return $this->render('user/edit.html.twig', [
        'userForm' => $form->createView(),
    ]);
}
#[Route('/user/delete/{id}', name: 'user_delete')]
public function delete(User $user, EntityManagerInterface $em): Response
{
    $em->remove($user);
    $em->flush();

    return $this->redirectToRoute('user_list');
}



}
