<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *     fields={"code"},
 *     errorPath="code",
 *     message="Este código ya se encuentra en uso."
 * )
* @UniqueEntity(
 *     fields={"name"},
 *     errorPath="name",
 *     message="Este producto ya existe."
 * )
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\Regex(
     *     pattern     = "/^[a-z0-9]+$/i",
     *     htmlPattern = "[a-zA-Z0-9]+"
     * )
     * @Assert\Length(
     *      min = 4,
     *      max = 10,
     *      minMessage = "El código no puede tener menos de {{ limit }} caracteres",
     *      maxMessage = "El código no puede tener mas de {{ limit }} caracteres"
     * )
     * @ORM\Column(type="string", length=10)
     */
    private $code;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 4,
     *      max = 255,
     *      minMessage = "El nombre no puede tener menos de {{ limit }} caracteres",
     *      maxMessage = "El nombre no puede tener mas de {{ limit }} caracteres"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", options={"default": 1})
     */
    private $active;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "El nombre no puede tener menos de {{ limit }} caracteres",
     *      maxMessage = "El nombre no puede tener mas de {{ limit }} caracteres"
     * )
     * @ORM\Column(type="string", length=50)
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products", fetch="EAGER")
     */
    private $category;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update

     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime("now");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

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

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
