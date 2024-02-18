<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       for($i = 1; $i<=10; $i++){
           $article = new Article();
           $article->setTitle("Title de l'article n°$i")
                    ->setContent("<p>Contenu de l'article n°$i</p>")
                    ->setImage("https://fakeimg.pl/250x250")
                    ->setCreatedAt(new \DateTimeImmutable());
           $manager->persist($article);
       }
        $manager->flush();
    }
}
