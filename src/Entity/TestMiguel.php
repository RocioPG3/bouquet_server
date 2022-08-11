<?php

namespace App\Entity;

use App\Repository\TestMiguelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TestMiguelRepository::class)
 */
class TestMiguel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=user::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $relacion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelacion(): ?user
    {
        return $this->relacion;
    }

    public function setRelacion(?user $relacion): self
    {
        $this->relacion = $relacion;

        return $this;
    }
}
