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


#[Route('/user/delete/zero-books', name: 'user_delete_zero_books')]
public function deleteUsersWithZeroBooks(EntityManagerInterface $em): Response
{
    // Requête DQL pour supprimer directement les auteurs avec nb_books = 0
    $query = $em->createQuery('DELETE FROM App\Entity\User u WHERE u.nb_books = 0');
    $count = $query->execute(); // renvoie le nombre de lignes supprimées

    $this->addFlash('success', $count . ' auteur(s) supprimé(s) avec nbBooks = 0.');

    return $this->redirectToRoute('user_list');
}

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

#[Route('/user/search', name: 'user_search')]
    public function search(Request $request, EntityManagerInterface $em): Response
    {
        $min = $request->query->get('min');
        $max = $request->query->get('max');

        $qb = $em->getRepository(User::class)->createQueryBuilder('u');

        if ($min !== null && $max !== null && $min !== '' && $max !== '') {
            $qb->where('u.nb_books BETWEEN :min AND :max')
                ->setParameter('min', (int)$min)
                ->setParameter('max', (int)$max);
        } elseif ($min !== null && $min !== '') {
            $qb->where('u.nb_books >= :min')
                ->setParameter('min', (int)$min);
        } elseif ($max !== null && $max !== '') {
            $qb->where('u.nb_books <= :max')
                ->setParameter('max', (int)$max);
        }

        $users = $qb->getQuery()->getResult();

        return $this->render('user/search.html.twig', [
            'users' => $users,
            'min' => $min,
            'max' => $max,
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
