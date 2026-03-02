<?php

namespace App\Entity;

use App\Repository\PlanificacionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanificacionRepository::class)]
class Planificacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $fecha = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clases $leccion = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Aula $aula = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $usuario = null;

    #[ORM\Column]
    private ?int $estado = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observacion = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cohorte $cohorte = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Seccion $seccion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTime
    {
        return $this->fecha;
    }

    public function setFecha(\DateTime $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getLeccion(): ?Clases
    {
        return $this->leccion;
    }

    public function setLeccion(?Clases $leccion): static
    {
        $this->leccion = $leccion;

        return $this;
    }

    public function getAula(): ?Aula
    {
        return $this->aula;
    }

    public function setAula(?Aula $aula): static
    {
        $this->aula = $aula;

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

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(int $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getObservacion(): ?string
    {
        return $this->observacion;
    }

    public function setObservacion(?string $observacion): static
    {
        $this->observacion = $observacion;

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

    public function getSeccion(): ?Seccion
    {
        return $this->seccion;
    }

    public function setSeccion(?Seccion $seccion): static
    {
        $this->seccion = $seccion;

        return $this;
    }
}
