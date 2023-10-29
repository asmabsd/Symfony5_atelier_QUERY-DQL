<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;
use App\Form\AuthorType;
use App\Form\BoussaadaType;
use App\Form\MinmaxType;
use App\Form\SearchType;
use App\Controller\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface as ORMEntityManagerInterface;

class AuthorController extends AbstractController
{ public $authors = array(
    array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
    array('id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200),
    array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
);


    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);}
    //trier les auteurs par ordre croissant des emails
    #[Route('/showDBauthor', name: 'showDBauthor')]
    public function showDBauthor(AuthorRepository $authorRepo): Response
    {
        $authors=$authorRepo->orderByEmail();
        return $this->renderForm('author/showDB.html.twig', [
            'authors'=>$authors,]);
            


            //$x=$authorRepo->searchByAlphabet();
        //$form = $this->createForm(MinmaxType::class);
       
       /* if ($form->isSubmitted()){
            $datainput=$form->get('username')->getData();
            //var_dump($datainput);
            $authors=$authorRepo->searchByUserName($datainput);
            return $this->renderForm('author/showDB.html.twig', [
                'authors' => $authors,
                'f' => $form
            ]);

        }*/
        //$form->handleRequest($req);
       /* $em = $manager->getManager();
        if ($form->isSubmitted()){
            $min=$form->get('min')->getData();
            $max=$form->get('max')->getData();
            //var_dump($datainput);
            $authors=$authorRepo->minmax($min,$max);
           
            return $this->renderForm('author/showDB.html.twig', [
                'authors' => $authors,
                'f' => $form
            ]);

        }
        

        return $this->renderForm('author/showDB.html.twig', [
            'authors' =>$authors,
            'form' => $form->createView(),
        ]);}

*/    
        }
        







// Recherche des auteurs dont le nombre des livres entre deux valeurs

/*#[Route('/showDBauthors', name: 'showDBauthors')]
    public function showDBAuthors(Request $request, AuthorRepository $authorRepository): Response
    {
        $form = $this->createForm(BoussaadaType::class);
        $form->handleRequest($request);

        $authors = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $min = $form->get('min')->getData();
            $max = $form->get('max')->getData();

            $authors = $authorRepository->searchAuthorsByNbBooks($minBooks, $maxBooks);
        }

        return $this->render('author/showDB.html.twig', [
            'form' => $form->createView(),
            'authors' => $authors,
        ]);
    }*/

//supprimer les auteurs ayant nb_books=0
#[Route('/showDBauthors', name: 'showDBauthors')]
public function deleteAuthorsWithZeroBooks(AuthorRepository $authorRepository): Response
{
    $authorRepository->deleteAuthorsWithZeroBooks();

    

    return $this->redirectToRoute('/showDBauthor'); 
}



    #[Route('/showbook/{id}', name: 'showbook')]
    public function showbook($id,AuthorRepository $repo,Request $req): Response
    {
        
        //$x = $authorRepo->findAll();
        //$x=$authorRepo->orderByUserName();
        //$x=$authorRepo->searchByAlphabet();
        // $book=$repo->searchById($id);
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($req);
        
        return $this->renderForm('author/showDB.html.twig', [
            'f' => $form
        ]);
       /* return $this->render('author/showDB.html.twig', [
            'authors' => $x
        ]);*/
    }




    #[Route('/addstaticSauthor', name: 'addstaticSauthor')]
    public function addstaticSauthor(ManagerRegistry $manager): Response
    {
        $em = $manager->getManager();
        $author = new Author();

        $author->setUsername("3a56");
        $author->setEmail("3a56@esprit.tn");
        $em->persist($author);
        $em->flush();

        return new Response("add with succcess");
    }

    #[Route('/addauthor', name: 'addauthor')]
    public function addauthor(ManagerRegistry $manager, Request $req): Response
    {
        $em = $manager->getManager();
        $author = new Author();
        $form = $this->createForm(AuthorType::class,   $author);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('showDBauthor');
        }

        return $this->renderForm('author/add.html.twig', [
            'f' => $form
        ]);
    }

     #[Route('/editauthor/{id}', name: 'editauthor')]
    public function editauthor($id, ManagerRegistry $manager, AuthorRepository $authorrepo, Request $req): Response
    {
        // var_dump($id) . die();

        $em = $manager->getManager();
        $idData = $authorrepo->find($id);
        // var_dump($idData) . die();
        $form = $this->createForm(AuthorType::class, $idData);
        $form->handleRequest($req);

        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($idData);
            $em->flush();

            return $this->redirectToRoute('showDBauthor');
        }

        return $this->renderForm('author/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/deleteauthor/{id}', name: 'deleteauthor')]
    public function deleteauthor($id, ManagerRegistry $manager, AuthorRepository $repo): Response
    {
        $emm = $manager->getManager();
        $idremove = $repo->find($id);
        $emm->remove($idremove);
        $emm->flush();


        return $this->redirectToRoute('showDBauthor');
    }

    #[Route('/showauthor', name: 'app_showauthor')]
    public function showauthor(): Response
    {

        return $this->render('author/show.html.twig', [
            'authorshtml' => $this->authors,
        ]);
    }

     #[Route('/authorDetails/{id}', name: 'authorDetails')]
    public function authorDetails($id): Response
    {
        //var_dump($id) . die();

        $author = null;
        foreach ($this->authors as $authorData) {
            if ($authorData['id'] == $id) {
                $author = $authorData;
            }
        }

        return $this->render('author/details.html.twig', [
            'author' => $author
        ]);
    }}
