<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ArticleRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Article;

class BlogController extends AbstractController
{
    // show all article route

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/blog', name: 'app_blog')]
    public function index(ArticleRepository $articleRepository ): Response
    {
        $allArticles = $articleRepository->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles'=> $allArticles
        ]);
    }
    //Home route
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('blog/home.html.twig', [
            'title' => 'Bienvenu mes amis!! ',
            'age'=>25
        ]);
    }
    //Route of create new article
    #[Route('/blog/new', name: 'blog_create')]
    #[Route('/blog/{id}/edit', name: 'blog_edit')]
    public function form(Article $article = null, Request $request ): Response
    {
        if(!$article){
            $article = new Article();
        }
        $article->setTitle("Title d'example")
                ->setContent("Le contenue de l'article");

        $form = $this->createFormBuilder($article)
                    ->add('title')
                    ->add('content')
                    ->add('image')
                   /* ->add('save', SubmitType::class,[
                            'label'=>"Enregistrer",
                    ])*/
                    ->getForm();
        $form->handleRequest($request);
        //dd($article);
        if($form->isSubmitted() && $form->isValid()){
            if(!$article->getId){
                $article->setCreatedAt(new \DateTimeImmutable());
            }

            $this->entityManager->persist($article);
            $this->entityManager->flush();
            return $this->redirectToRoute('blog_show', [
                "articleId" => $article->getId()
            ]);
        }

        return $this->render('blog/create.html.twig',[
            'formArticle' => $form->createView()
        ]);
    }
    // route show article by Id
    #[Route('/blog/{articleId}', name: 'blog_show')]
    public function show(ArticleRepository $articleRepository , $articleId): Response
    {
        $article = $articleRepository->find($articleId);
        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }

}
