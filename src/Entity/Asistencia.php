<?php

namespace App\Entity;

use App\Repository\AsistenciaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AsistenciaRepository::class)]
class Asistencia
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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clases $clase = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $usuario = null;

    #[ORM\Column]
    private ?\DateTime $fecha_reg = null;

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

    public function getClase(): ?Clases
    {
        return $this->clase;
    }

    public function setClase(?Clases $clase): static
    {
        $this->clase = $clase;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getFechaReg(): ?\DateTime
    {
        return $this->fecha_reg;
    }

    public function setFechaReg(\DateTime $fecha_reg): static
    {
        $this->fecha_reg = $fecha_reg;

        return $this;
    }
}
