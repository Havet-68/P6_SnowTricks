<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Trick;
use App\Form\TrickType;
use App\Form\CommentsType;
use App\Repository\TrickRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/trick')]
class TrickController extends AbstractController
{
    #[Route('/', name: 'trick_index', methods: ['GET'])]
    public function index(TrickRepository $trickRepository, EntityManagerInterface $em, ): Response
    {   $comments = $em->getRepository(Comments::class)->findAll();
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
            'comments' => $comments,
        ]);
    }

    #[Route('/new', name: 'trick_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('home_page');
        }
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['photo']->getData();
            $filename = $trick->getName().date('YmdHis').'.'.$file->guessExtension();
            $trick->setPhoto($filename);
            $trick->setUser($this->getUser()); 
            $entityManager->persist($trick);
            $entityManager->flush();
            
            $file->move( './img', $filename);

            return $this->redirectToRoute('home_page', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'trick_show', methods: ['GET', 'POST'])]
    public function show(Trick $trick, EntityManagerInterface $entityManager, Request $request): Response
    {
        $comment = new Comments;
        $comments = $entityManager->getRepository(Comments::class)->findBy(['trick'=>$trick]);
        $dateTimeImmutable = new DateTimeImmutable();
        $commentform = $this->createForm(CommentsType::class, $comment);
        $commentform->handleRequest($request);

        
        if ($commentform->isSubmitted() && $commentform->isValid()) {
            $comment->setCreatedAt($dateTimeImmutable);
            $comment->setTrick($trick);
            $comment->setUser($this->getUser());
            $comment->setContent($request->get('comments')['content']);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('trick_show', ['id'=>$trick->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $commentform,
            'comments' => $comments,
            
        ]);
    }

    #[Route('/{id}/edit', name: 'trick_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, EntityManagerInterface $entityManager): Response
    {
        if (!($this->isGranted('ROLE_ADMIN') || $trick->getUser()->getEmail()==$this->getUser()->getUserIdentifier())) {
            return $this->redirectToRoute('home_page');
        }
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['photo']->getData();
            $filename = $trick->getName().date('YmdHis').'.'.$file->guessExtension();
            $trick->setPhoto($filename);
            $entityManager->flush();
            
            $file->move( './img', $filename);
            return $this->redirectToRoute('home_page', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'trick_delete', methods: ['GET','POST'])]
    public function delete(Request $request, Trick $trick, EntityManagerInterface $entityManager): Response
    {
        if (!($this->isGranted('ROLE_ADMIN') || $trick->getUser()->getEmail()==$this->getUser()->getUserIdentifier())) {
            return $this->redirectToRoute('home_page');
        }
        // if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            
        // }  ça pour une raison obscur ça me renvoyait sur l'index des tricks sans supprimer l'article: se renseigner

        $entityManager->remove($trick);
        $entityManager->flush();

        return $this->redirectToRoute('home_page', [], Response::HTTP_SEE_OTHER);
    }

}
