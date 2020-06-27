<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ApiResource(
 *      attributes={
 *          "order"={
 *              "createdAt"="DESC"
 *          }
 *      },
 *      itemOperations = {
 *          "get",  
 *      },
 *      collectionOperations = {
 *          "get"={
 *              
 *              "normalization_context"={"groups"={"get"}}
 *          },
 *          "post"={
 *              
 *              "normalization_context"={"groups"={"get-with-author"}},
 *              "access_control"="is_granted('IS_AUTHENTICATED_FULLY')"
 *          }
 *         
 *      },
 *      subresourceOperations={
 *          "api_articles_comments_get_subresource"= {
 *             "method"="GET",
 *             "normalization_context"={
 *                 "groups"={"get-with-author"}
 *             }
 *          }
 *      },
 *      denormalizationContext={
 *         "groups"={"post"}
 *      }
 * )
 */
class Comment implements AuthorInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *  max = 3000,
     *  maxMessage = "pas plus de 3000 caractères"
     * )
     * @Assert\NotBlank()
     * @Groups({"article","get","post","get-with-author"})
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @Groups({"get","article","get-with-author"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="comments")
     * @Groups({"get","post"})
     */
    private $article;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"article","get"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;


    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function __toString()
    {
        return $this->author->getUsername();
    }
}
