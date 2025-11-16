<?php

namespace App\Entity;

use App\Repository\CadetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CadetRepository::class)]
class Cadet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $vmibadgeid = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVmibadgeid(): ?string
    {
        return $this->vmibadgeid;
    }

    public function setVmibadgeid(string $vmibadgeid): static
    {
        $this->vmibadgeid = $vmibadgeid;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
