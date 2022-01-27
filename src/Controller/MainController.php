<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {

        $rol = '';
        if ($this->isGranted('ROLE_USER')) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $username = $this->getUser()->getUsername();
                $rol = 'admin';
            } else {
                $username = $this->getUser()->getUsername();
                $rol = 'user';
            }
        } else $username = 'Visitante';

        if (isset($_POST['title'])) {
            $movie = new Movie();
            $movie->setTitle($_POST['title']);
            $movie->setImage($_POST['img']);
            $movie->setYear($_POST['year']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();
        }


        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'username' => $username,
            'rol' => $rol
        ]);
    }


    /**
     * @Route("/peliculas/buscar", name="search")
     */
    public function buscar(Request $request): Response
    {
        $rol = '';
        if ($this->isGranted('ROLE_USER')) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $username = $this->getUser()->getUsername();
                $rol = 'admin';
            } else {
                $username = $this->getUser()->getUsername();
                $rol = 'user';
            }
        } else $username = 'Visitante';


        $em = $this->getDoctrine()->getManager();
        $txtBuscar = $request->get('txtBuscar');
        $busqueda = $this->getDoctrine()->getRepository('App:Movie')->search($txtBuscar);

        $movies = $this->getDoctrine()
            ->getRepository('App:Movie')
            ->findAll();

        $comments = $this->getDoctrine()
            ->getRepository('App:Comment')
            ->findAll();


        return $this->render('main/peliculas.html.twig', [
            'controller_name' => 'MainController',
            'movies' => $busqueda,
            'username' => $username,
            'comments' => $comments,
            'rol' => $rol,
        ]);
    }

    /**
     * @Route("/peliculas/deleteArt{id}", name="delArt")
     */
    public function delArticle($id)
    {
        $rol = '';
        if ($this->isGranted('ROLE_USER')) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $username = $this->getUser()->getUsername();
                $rol = 'admin';
            } else {
                $username = $this->getUser()->getUsername();
                $rol = 'user';
            }
        } else $username = 'Visitante';


        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository('App:Movie')->find($id);
        $em->remove($movie);
        $em->flush();

        $movies = $this->getDoctrine()
            ->getRepository('App:Movie')
            ->findAll();

        $comments = $this->getDoctrine()
            ->getRepository('App:Comment')
            ->findAll();


        return $this->render('main/peliculas.html.twig', [
            'controller_name' => 'MainController',
            'movies' => $movies,
            'username' => $username,
            'comments' => $comments,
            'rol' => $rol
        ]);
    }


    /**
     * @Route("/peliculas", name="peliculas")
     */
    public function peliculas(): Response
    {
        $rol = '';
        if ($this->isGranted('ROLE_USER')) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $username = $this->getUser()->getUsername();
                $rol = 'admin';
            } else {
                $username = $this->getUser()->getUsername();
                $rol = 'user';
            }
        } else $username = 'Visitante';


        $movies = $this->getDoctrine()
            ->getRepository('App:Movie')
            ->findAll();

        $comments = $this->getDoctrine()
            ->getRepository('App:Comment')
            ->findAll();


        return $this->render('main/peliculas.html.twig', [
            'controller_name' => 'MainController',
            'movies' => $movies,
            'username' => $username,
            'comments' => $comments,
            'rol' => $rol
        ]);
    }

    /**
     * @Route("/peliculas/{id}", name="addComment")
     */
    public function addComment($id)
    {
        $rol = '';
        if ($this->isGranted('ROLE_USER')) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $username = $this->getUser()->getUsername();
                $rol = 'admin';
            } else {
                $username = $this->getUser()->getUsername();
                $rol = 'user';
            }
        } else $username = 'Visitante';


        if (isset($_POST["comment"])) {
            $comment = new Comment();
            $comment->setUser($this->getUser());
            $comment->setComment($_POST["comment"]);
            $comment->setPublicationDate(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $movie = $em->getRepository('App:Movie')->find($id);
            $movie->addComment($comment);
            $em->persist($comment);
            $em->persist($movie);
            $em->flush();
        }

        $movies = $this->getDoctrine()
            ->getRepository('App:Movie')
            ->findAll();

        $comments = $this->getDoctrine()
            ->getRepository('App:Comment')
            ->findAll();


        return $this->render('main/peliculas.html.twig', [
            'controller_name' => 'MainController',
            'movies' => $movies,
            'username' => $username,
            'comments' => $comments,
            'rol' => $rol
        ]);
    }

    

    /**
     * @Route("/peliculas/movie{idMovie}/deleteCmm{id}", name="delComment")
     */
    public function delComment($idMovie, $id)
    {
        $rol = '';
        if ($this->isGranted('ROLE_USER')) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $username = $this->getUser()->getUsername();
                $rol = 'admin';
            } else {
                $username = $this->getUser()->getUsername();
                $rol = 'user';
            }
        } else $username = 'Visitante';


        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository('App:Movie')->find($idMovie);
        $comment = $em->getRepository('App:Comment')->find($id);
        $movie->removeComment($comment);
        $em->persist($movie);
        $em->flush();


        $movies = $this->getDoctrine()
            ->getRepository('App:Movie')
            ->findAll();

        $comments = $this->getDoctrine()
            ->getRepository('App:Comment')
            ->findAll();


        return $this->render('main/peliculas.html.twig', [
            'controller_name' => 'MainController',
            'movies' => $movies,
            'username' => $username,
            'comments' => $comments,
            'rol' => $rol
        ]);
    }

    
}
