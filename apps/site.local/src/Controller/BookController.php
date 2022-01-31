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

class BookController extends AbstractController
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @Route("/book/create", name="book_create", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param AuthorRepository $authorRepository
     * @return Response
     * @throws \Exception
     */
    public function createBook(
        Request $request,
        EntityManagerInterface $em,
        AuthorRepository $authorRepository
    ): Response {

        if (!$request->get('name') || !$request->request->get('author_ids')) {
            throw new \Exception('Not enough parameters in the request');
        }

        // validate param name
        $name = $request->get('name');

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
        // there can be many books with the same name (!)
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

    /**
     * @Route ("/book/search", name="book_search", methods={"GET"})
     *
     * @return void
     */
    public function searchBook(Request $request, EntityManagerInterface $em): Response
    {
        if (!$request->get('name')) {
            throw new \Exception('Not enough parameters in the request');
        }
        $name = $request->get('name');

        $books = $this->bookRepository->findAllBooksByNameWithItsAuthors($name);

        return $this->json([
            $books,
        ]);

    }

    /**
     * @Route("{lang<en|ru>}/book/{id}", name="lang_get", methods={"GET"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param string $lang
     * @param string $id
     * @return Response
     */
    public function getBookInfoWithEnRuLanguages(
        Request $request,
        EntityManagerInterface $em,
        string $lang,
        string $id
    ): Response {


        return $this->json([

        ]);
    }


}
