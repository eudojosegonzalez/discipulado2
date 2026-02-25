<?php

namespace App\Entity;

use App\Repository\DetallePlanificacionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetallePlanificacionRepository::class)]
class DetallePlanificacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Planificacion $planificacion = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Discipulo $discipulo = null;

    #[ORM\Column]
    private ?int $estado = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlanificacion(): ?Planificacion
    {
        return $this->planificacion;
    }

    public function setPlanificacion(?Planificacion $planificacion): static
    {
        $this->planificacion = $planificacion;

        return $this;
    }

    public function getDiscipulo(): ?Discipulo
    {
        return $this->discipulo;
    }

    public function setDiscipulo(?Discipulo $discipulo): static
    {
        $this->discipulo = $discipulo;

        return $this;
    }

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(int $estado): static
    {
        $this->estado = $estado;

        return $this;
    }
}
