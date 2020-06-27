<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use DateTime;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ApiFilter(
 *      PropertyFilter::class,
 *      arguments={
 *          "overrideDefaultProperties":true,
 *          "whitelist" = {
 *              "id",
 *              "author",
 *              "content"
 *          }
 *      }
 * )
 * @ApiFilter(
 *      OrderFilter::class,
 *      properties={
 *          "id",
 *          "title"
 *      }
 * )
 * @ApiFilter(
 *      RangeFilter::class,
 *      properties={
 *          "id",
 *          "author.id"
 *      }
 * )
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "id"="exact",
 *          "title"="ipartial",
 *          "content"="ipartial",
 *          "author" ="exact",
 *          "author.username"="partial"
 *      }
 * )
 * @ApiFilter(
 *      DateFilter::class,
 *      properties={
 *         "createdAt"
 *      }
 * )
 * @ApiResource(
 *      attributes={
 *          "order"={
 *              "createdAt"="DESC"
 *          }
 *      },
 *      itemOperations = {
 *          "get"={
 *               "normalization_context"={"groups"={"article"}}
 *          },
 *          "put"={
 *              "access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object.getAuthor() == user "
 *          }
 *      },
 *      collectionOperations = {
 *          "get"={
 *               "normalization_context"={"groups"={"get"}}
 *          },
 *          "post"={
 *              "access_control"="is_granted('IS_AUTHENTICATED_FULLY')"
 *          }
 *      }
 * )
 */
class Article implements AuthorInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"article","get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage= "Le titre doit être entre 6 et 50 caracteres",
     *      maxMessage= "Le titre doit être entre 6 et 50 caracteres"
     * )
     * @Assert\NotBlank()
     * @Groups({"article","get"})
     * 
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 50000,
     *      maxMessage= "Le contenu ne doit pas être plus de 50 000 caracteres"
     * )
     * @Groups({"article","get"})
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"article","get"})
     */
    private $shown;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"article","get"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="article")
     * @Groups({"article"})
     * @ApiSubResource
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @Groups({"article"})
     */
    private $author;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function getShown(): ?bool
    {
        return $this->shown;
    }

    public function setShown(bool $shown): self
    {
        $this->shown = $shown;

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

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

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
    public function __toString()
    {
        return $this->title;
    }
}
