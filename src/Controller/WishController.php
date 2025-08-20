<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wish', name: 'wish')]
    final class WishController extends AbstractController
{
    #[Route('', name: 'list')]
    public function wishList(): Response
    {
        return $this->render('wish/list.html.twig');
    }

    #[Route('/{id}', name: 'detail', requirements:['id' => '\d+'])]
    public function wishDetail(int $id): Response {
        return $this->render('wish/detail.html.twig');
    }
}
