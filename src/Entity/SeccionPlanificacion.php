<?php

namespace App\Entity;

use App\Repository\SeccionPlanificacionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeccionPlanificacionRepository::class)]
class SeccionPlanificacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Seccion $seccion = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Planificacion $planificacion = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clases $clase = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $fecha_creacion = null;

    #[ORM\Column]
    private ?int $estado = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cohorte $cohorte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeccion(): ?Seccion
    {
        return $this->seccion;
    }

    public function setSeccion(?Seccion $seccion): static
    {
        $this->seccion = $seccion;

        return $this;
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

    public function getClase(): ?Clases
    {
        return $this->clase;
    }

    public function setClase(?Clases $clase): static
    {
        $this->clase = $clase;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTime
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(\DateTime $fecha_creacion): static
    {
        $this->fecha_creacion = $fecha_creacion;

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

    public function getCohorte(): ?Cohorte
    {
        return $this->cohorte;
    }

    public function setCohorte(?Cohorte $cohorte): static
    {
        $this->cohorte = $cohorte;

        return $this;
    }
}
