<?php

namespace App\Entity;

use App\Repository\HaircutRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HaircutRepository::class)]
class Haircut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $cadetbadge = null;

    #[ORM\Column(length: 255)]
    private ?string $cadetname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $barbername = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private DateTime $starttime;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?DateTime $endtime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cuttype = null;

    public function __construct()
    {
        $this->starttime = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCadetbadge(): ?string
    {
        return $this->cadetbadge;
    }

    public function setCadetbadge(string $cadetbadge): static
    {
        $this->cadetbadge = $cadetbadge;

        return $this;
    }

    public function getCadetname(): ?string
    {
        return $this->cadetname;
    }

    public function setCadetname(string $cadetname): static
    {
        $this->cadetname = $cadetname;

        return $this;
    }

    public function getBarbername(): ?string
    {
        return $this->barbername;
    }

    public function setBarbername(?string $barbername): static
    {
        $this->barbername = $barbername;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCuttype(): ?string
    {
        return $this->cuttype;
    }

    public function setCuttype(?string $cuttype): static
    {
        $this->cuttype = $cuttype;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getStarttime(): DateTime
    {
        return $this->starttime;
    }

    /**
     * @param DateTime $starttime
     * @return Haircut
     */
    public function setStarttime(DateTime $starttime): static
    {
        $this->starttime = $starttime;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getEndtime(): ?DateTime
    {
        return $this->endtime;
    }

    /**
     * @param DateTime|null $endtime
     * @return Haircut
     */
    public function setEndtime(?DateTime $endtime): static
    {
        $this->endtime = $endtime;
        return $this;
    }


}
