<?php

namespace App\Controller;
use DateTimeInterface;
use App\Entity\Book;
use App\Form\AsmaType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BookType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

//showDB 

/* #[Route('/showDBbook', name: 'showDBbook')]
    public function showDBbook(BookRepository $bookRepo,Request $request): Response
    {
        // $x = $bookRepo->findAll();
        /*return $this->render('book/showDBbook.html.twig', [
            'books' => $x
        ]);*/


//rechercher un book donné par sa ref

    /*#[Route('/showDBbook', name: 'showDBbook')]
    public function showDBbook(BookRepository $bookRepo,Request $request): Response
    {
         
        $form = $this->createForm(AsmaType::class);
        $form->handleRequest($request);

        
            $datainput = $form->get('id')->getData();
            
            $book= $bookRepo->findById($datainput);

            
        

        return $this->render('book/showbookbyid.html.twig', [
            'form' => $form->createView(),
            'book'=>$book,
        ]);
    }*/


    //trier les books par ordre croissant des auteurs 

  /*  #[Route('/showDBbook', name: 'showDBbook')]
    public function showDBauthor(BookRepository $bookRepo): Response
    {
        $books=$bookRepo->orderByAuthorName();
        return $this->renderForm('book/showDBbook.html.twig', [
            'books'=>$books,
            
        ]);}*/


//Afficher la liste des livres publiés avant l’année 2023 dont l’auteur a plus de 35 livres

/*#[Route('/showDBbook', name: 'showDBbook')]
public function showDBbook(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findBooksBeforeYearWithnbbooks();

        return $this->render('book/showDBbook.html.twig', [
            'books' => $books,
        ]);

    
    }*/
//affecter la category romance a tous les books de william shakespeare

/*#[Route('/showDBbook', name: 'showDBbook')]
public function showDBbook(BookRepository $bookRepository,EntityManagerInterface $entityManager): Response
    {
$shakespeareBooks = $bookRepository->updateCategoryForShakespeareBooks();

        foreach ($shakespeareBooks as $books) {
            $books->setCategory('Romance');
        }

        $entityManager->flush();

        
        return  $this->render('book/showDBbook.html.twig', [
            'books' => $books,]);

    }*/
// Afficher la somme des livres dont la catégorie est “Science Fiction” .
#[Route('/showDBScienceFictionbook', name: 'showDBScienceFictionbook')]
public function showDBScienceFictionbook(BookRepository $bookRepository): Response
    {
        $sum = $bookRepository->sumScienceFictionBooks();

        return $this->render('book/sum.html.twig', [
            'sum' => $sum,
        ]);
    }


    //
    #[Route('/showDBbook', name: 'showDBbook')]
    public function showBooksBetweenDates(BookRepository $bookRepository): Response
    {
        $startDate = new \DateTime('2014-01-01');
        $endDate = new \DateTime('2018-12-31');

        $books = $bookRepository->findBooksBetweenDates($startDate, $endDate);

        return $this->render('book/showDBbook.html.twig', [
            'books' => $books,
        ]);
    }




    

        
    #[Route('/addbook', name: 'addbook')]
    public function addbook( ManagerRegistry $manager,Request $req): Response
    {
        $em = $manager->getManager();
       

        $book = new Book();
      
        $form = $this->createForm(BookType::class,$book);
        $form->add('ajouter',SubmitType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('showDBbook');
        }

        return $this->renderForm('book/add.html.twig', array(
            'f' => $form
        ));
    }
   
#[Route('/editbook/{id}', name: 'app_editBook')]
public function edit(ManagerRegistry $manager,BookRepository $repository, $id, Request $request)
{
    $book = $repository->find($id);
    $form = $this->createForm(BookType::class, $book);
    $form->add('Edit', SubmitType::class);
     $em=$manager->getManager();
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('showDBbook');
    }

    return $this->render('book/edit.html.twig', [
        'f' => $form->createView(),

        
    ]);
}
#[Route('/deletebook/{id}', name: 'deletebook')]
    public function deletebook($id, ManagerRegistry $manager, BookRepository $bookRepo): Response
    {
        $emm = $manager->getManager();
        $Idremove= $bookRepo->find($id);
        $emm->remove($Idremove);
        $emm->flush();


        return $this->redirectToRoute('showDBbook');
    }



}

