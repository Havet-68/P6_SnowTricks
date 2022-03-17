<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Trick;
use App\Entity\TrickPhoto;
use App\Entity\User;
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
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'trick_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'trick_show', methods: ['GET', 'POST'])]
    public function show(Trick $trick, EntityManagerInterface $entityManager, Request $request): Response
    {
        $comments = new Comments;
        
        $dateTimeImmutable = new DateTimeImmutable();
        $commentform = $this->createForm(CommentsType::class, $comments);
        $commentform->handleRequest($request);

        
        if ($commentform->isSubmitted() && $commentform->isValid()) {
            $comments->setCreatedAt($dateTimeImmutable);
            $comments->setTrick($trick);
            $comments->setUser()

            $entityManager->flush();

            return $this->redirectToRoute('trick_show', ['id'=>$trick->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $commentform,
        ]);

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
        ]);
    }

    #[Route('/{id}/edit', name: 'trick_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'trick_delete', methods: ['POST'])]
    public function delete(Request $request, Trick $trick, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
    }

}
