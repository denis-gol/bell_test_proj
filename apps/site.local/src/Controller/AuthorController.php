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
     * @throws \Exception
     */
    public function createAuthor(
        Request $request,
        EntityManagerInterface $em,
        AuthorRepository $authorRepository
    ): Response
    {
        if (!$request->get('name')) {
            throw new \Exception('Not enough parameters in the request');
        }

        // validate param name
        $name = $request->get('name');
        if ($authorRepository->findOneBy(['name' => $name]) !== null) {
            throw new \Exception('The author with this name already exists');
        }

        // create new author
        $author = new Author();
        $author->setName($name);

        $em->persist($author);
        $em->flush();

        return $this->json([
            'author_id' => $author->getId(),
        ]);
    }

}
