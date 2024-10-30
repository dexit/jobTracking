<?php

namespace App\Controller;

use App\Entity\AddressBook;
use App\Form\AddressBookType;
use App\Repository\AddressBookRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/address/book')]
final class AddressBookController extends AbstractController
{
    #[Route(name: 'app_address_book_index', methods: ['GET'])]
    public function index(AddressBookRepository $addressBookRepository, Security $security, SerializerInterface $serializer): Response
    {

        $addressBooks = $addressBookRepository->findBy(['user' => $security->getUser()]);
        $addressBooksJson = $serializer->serialize($addressBooks, 'json', ['groups' => ['address_book']] );


        return $this->render('address_book/index.html.twig', [
            'address_books' => $addressBooks,
            'address_books_json' => $addressBooksJson,
        ]);
    }

    #[Route('/nouveau_contact', name: 'app_address_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $addressBook = new AddressBook();
        $form = $this->createForm(AddressBookType::class, $addressBook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addressBook->setUser($security->getUser())
            ->setCreatedAt(new DateTimeImmutable());
            $entityManager->persist($addressBook);
            $entityManager->flush();

            return $this->redirectToRoute('app_address_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('address_book/new.html.twig', [
            'address_book' => $addressBook,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_address_book_show', methods: ['GET'])]
    public function show(AddressBook $addressBook): Response
    {
        return $this->render('address_book/show.html.twig', [
            'address_book' => $addressBook,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_address_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AddressBook $addressBook, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddressBookType::class, $addressBook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_address_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('address_book/edit.html.twig', [
            'address_book' => $addressBook,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_address_book_delete', methods: ['POST'])]
    public function delete(Request $request, AddressBook $addressBook, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $addressBook->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($addressBook);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_address_book_index', [], Response::HTTP_SEE_OTHER);
    }
}
