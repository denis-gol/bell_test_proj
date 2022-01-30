<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/author", name="author_")
 */
class AuthorController extends AbstractController
{
    /**
     * @Route("/create", name="create", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param AuthorRepository $authorRepository
     * @return Response
     * @throws \Exception
     */
    public function createAuthor(
        Request $request,
        EntityManagerInterface $em,
        AuthorRepository $authorRepository
    ): Response
    {
        $name = $request->get('name');
        if ($name === null) {
            throw new \Exception('Not enough parameters in the request');
        }

        // create new author
        // there can be many authors with the same name (!)
        $author = new Author();
        $author->setName($name);

        $em->persist($author);
        $em->flush();

        return $this->json([
            'author_id' => $author->getId(),
        ]);
    }

}
