<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\TokenGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public const USER_REFERENCE = 'user_';
    public const ARTICLE_REFERENCE = 'article';


    public const NUMBER_ARTICLE = '10';
    public const NUMBER_USER = '3';
    public const NUMBER_COMMENT = '30';

    private $faker;
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;

        $this->faker = \Faker\Factory::create();
    }


    public function loadArticle(ObjectManager $manager, $number)
    {
        for ($index = 1; $index <= $number; $index++) {
            $article = new Article();
            $article->setTitle($this->faker->title);
            $article->setContent($this->faker->realText);
            $article->setShown($this->faker->boolean);
            $author = $this->getReference($this::USER_REFERENCE . $this->faker->numberBetween(1, $this::NUMBER_USER));
            $article->setAuthor($author);
            $manager->persist($article);
            $this->addReference($this::ARTICLE_REFERENCE  . $index, $article);
        }
        $manager->flush();
    }
    public function loadComment(ObjectManager $manager, $number)
    {
        for ($index = 1; $index <= $number; $index++) {
            $comment = new Comment();
            $comment->setContent($this->faker->realText);
            $author = $this->getReference($this::USER_REFERENCE . $this->faker->numberBetween(1, $this::NUMBER_USER));
            $comment->setAuthor($author);
            $article = $this->getReference($this::ARTICLE_REFERENCE . $this->faker->numberBetween(1, $this::NUMBER_ARTICLE));
            $comment->setArticle($article);
            $manager->persist($comment);
        }
        $manager->flush();
    }
    public function loadUser(ObjectManager $manager, $number)
    {
        for ($index = 1; $index <= $number; $index++) {
            $user = new User();
            $user->setName($this->faker->name);
            $user->setPassword($this->passwordEncoder->encodePassword($user, "test"));
            $user->setUsername($this->faker->userName);
            $user->setEmail($this->faker->email);
            $user->setEnabled(false);
            $user->setConfirmationToken(TokenGenerator::generate());

            $manager->persist($user);
            $this->addReference($this::USER_REFERENCE  . $index, $user);
        }
        $manager->flush();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUser($manager, $this::NUMBER_USER);
        $this->loadArticle($manager, $this::NUMBER_ARTICLE);
        $this->loadComment($manager, $this::NUMBER_COMMENT);
    }
}
