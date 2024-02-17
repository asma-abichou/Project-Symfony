<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ArticleRepository;
use App\Entity\Article;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(ArticleRepository $articleRepository ): Response
    {
        $allArticles = $articleRepository->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles'=> $allArticles
        ]);
    }
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('blog/home.html.twig', [
            'title' => 'Bienvenu mes amis!! ',
            'age'=>25
        ]);
    }
    #[Route('/blog/{articleId}', name: 'blog_show')]
    public function show(ArticleRepository $articleRepository , $articleId): Response
    {
        $article = $articleRepository->find($articleId);
        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }
}
