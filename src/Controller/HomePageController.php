<?php

namespace App\Controller;

use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'home_page')]
    public function index(EntityManagerInterface $em): Response
    {
        $tricks = $em->getRepository(Trick::class)->findAll();
        return $this->render('home_page/index.html.twig', [
            'tricks'=>$tricks,
            'controller_name' => 'HomePageController',
        ]);
    }
}
