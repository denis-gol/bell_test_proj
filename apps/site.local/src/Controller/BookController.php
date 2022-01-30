<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book", name="book_")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/create", name="create", methods={"POST"})
     * @throws \Exception
     */
    public function createBook(
        Request $request,
        EntityManagerInterface $em,
        BookRepository $bookRepository,
        AuthorRepository $authorRepository
    ): Response {

        if (!$request->get('name') || !$request->request->get('author_ids')) {
            throw new \Exception('Not enough parameters in the request');
        }

        // validate param name
        $name = $request->get('name');
        if ($bookRepository->findOneBy(['name' => $name]) !== null) {
            throw new \Exception('The book with this name already exists');
        }

        // validate param author_ids
        $authorIds = $request->get('author_ids');
        $authors = $authorRepository->findBy([
                'id' => $authorIds,
            ]
        );
        if (count($authors) < count($authorIds)) {
            // @todo return ID of non-existent authors
            throw new \Exception('Author(s) ID doesn\'t exist');
        }

        // create new book
        $book = new Book();
        $book->setName($name);
        foreach ($authors as $author) {
            $book->addAuthor($author);
        }

        $em->persist($book);
        $em->flush();

        return $this->json([
            'book_id' => $book->getId(),
        ]);
    }

}
