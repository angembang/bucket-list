<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/wish', name: 'wish_')]
    final class WishController extends AbstractController
{
    #[Route('', name: 'list')]
    public function wishList(WishRepository $wishRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('wish/list.html.twig', [
            'categories' => $categories
        ]);
    }


    #[Route('/{id}', name: 'detail', requirements:['id' => '\d+'])]
    public function wishDetail(int $id, WishRepository $wishRepository): Response {
        $wish = $wishRepository->find($id);
        if(!$wish) {
            throw $this->createNotFoundException('Wish not found');
        }
        return $this->render('wish/detail.html.twig', [
            'wish' => $wish
        ]);
    }

    #[Route('/add', name: 'add')]
    public function createWish(EntityManagerInterface $manager, Request $request, CategoryRepository $categoryRepository, UserRepository $userRepository, int $id = null): Response {
        $wish = new Wish();
        if($id) {
            $category = $categoryRepository->find($id);
            $wish->setCategory($category);
        }
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handlerequest($request);
        if($wishForm->isSubmitted() && $wishForm->isValid()) {
            /**
             * Get and upload image
             * @var UploadedFile $image
             */
            $image = $wishForm->get('wishImage')->getData();
            $newFilename = uniqid() . '.' . $image->guessExtension();
            $image->move('uploads/wish', $newFilename);
            $wish->setWishImage($newFilename);
            $wish->setUser($this->getUser());
            $manager->persist($wish);
            $manager->flush();
            $this->addFlash("success", "Wish added successfully");
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }
        return $this->render('wish/createdWish.html.twig', [
            "wishForm" => $wishForm
        ]);


    }

    #[Route('/update/{id}', name: 'update', requirements:['id' => '\d+'])]
    public function updateWish(Request $request, EntityManagerInterface $manager, WishRepository $wishRepository, int $id): Response {
        $wish = $wishRepository->find($id);
        // Check if the wish is retrieved
        if(!$wish) {
            throw $this->createNotFoundException('Wish not found');
        }
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handlerequest($request);
        if($wishForm->isSubmitted() && $wishForm->isValid()) {
            $manager->persist($wish);
            $manager->flush();
            $this->addFlash("success", "Wish updated successfully");
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }
        return $this->render('wish/updatedWish.html.twig', [
            "wishForm" => $wishForm
        ]);

    }

    #[Route('/delete/{id}', name: 'delete', requirements:['id' => '\d+'])]
    #[IsGranted("ROLE_ADMIN", 'wish')]
    public function deleteWish(Request $request, EntityManagerInterface $manager ,WishRepository $wishRepository, Wish $wish): Response {
        //if(!$wish) {
            //throw $this->createNotFoundException('Wish not found');
        //}
        $manager->remove($wish);
        $manager->flush();
        $this->addFlash("success", "Wish deleted successfully");
        return $this->redirectToRoute('wish_list');
    }
}
