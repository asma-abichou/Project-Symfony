<?php

namespace App\Controller;

use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ArticleRepository;
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
            'title' => 'Bienvenue mes amis!! ',
            'age' => 25
        ]);
    }
    //Route of create new article
    #[Route('/blog/new', name: 'blog_create')]
    #[Route('/blog/{id}/edit', name: 'blog_edit')]
    public function form(Request $request, Article $article = null): Response
    {
        if(!$article){
            $article = new Article();
        }
       /* $form = $this->createFormBuilder($article)
                    ->add('title')
                    ->add('content')
                    ->add('image')
                    ->getForm();*/

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(!$article->getId()){
                $article->setCreatedAt(new \DateTimeImmutable());
            }
            $this->entityManager->persist($article);
            $this->entityManager->flush();
            return $this->redirectToRoute('blog_show', [
                "articleId" => $article->getId()
            ]);
        }
        return $this->render('blog/create.html.twig',[
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
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
