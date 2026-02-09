<?php

namespace App\Entity;

use App\Repository\DiscipuloRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiscipuloRepository::class)]
class Discipulo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $cedula = null;

    #[ORM\Column(length: 150)]
    private ?string $nombre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $fecha_nac = null;

    #[ORM\Column]
    private ?int $sexo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $telefono = null;

    #[ORM\Column]
    private ?int $estado = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $direccion = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $instruccion = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $ocupacion = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $discipulado = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $area_servicio = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $tiempo_asistencia = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $fecha_registro = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observacion = null;

    #[ORM\Column(length: 100)]
    private ?string $apellido = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCedula(): ?string
    {
        return $this->cedula;
    }

    public function setCedula(string $cedula): static
    {
        $this->cedula = $cedula;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getFechaNac(): ?\DateTime
    {
        return $this->fecha_nac;
    }

    public function setFechaNac(?\DateTime $fecha_nac): static
    {
        $this->fecha_nac = $fecha_nac;

        return $this;
    }

    public function getSexo(): ?int
    {
        return $this->sexo;
    }

    public function setSexo(int $sexo): static
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): static
    {
        $this->telefono = $telefono;

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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): static
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getInstruccion(): ?string
    {
        return $this->instruccion;
    }

    public function setInstruccion(?string $instruccion): static
    {
        $this->instruccion = $instruccion;

        return $this;
    }

    public function getOcupacion(): ?string
    {
        return $this->ocupacion;
    }

    public function setOcupacion(?string $ocupacion): static
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    public function getDiscipulado(): ?string
    {
        return $this->discipulado;
    }

    public function setDiscipulado(?string $discipulado): static
    {
        $this->discipulado = $discipulado;

        return $this;
    }

    public function getAreaServicio(): ?string
    {
        return $this->area_servicio;
    }

    public function setAreaServicio(?string $area_servicio): static
    {
        $this->area_servicio = $area_servicio;

        return $this;
    }

    public function getTiempoAsistencia(): ?string
    {
        return $this->tiempo_asistencia;
    }

    public function setTiempoAsistencia(?string $tiempo_asistencia): static
    {
        $this->tiempo_asistencia = $tiempo_asistencia;

        return $this;
    }

    public function getFechaRegistro(): ?\DateTime
    {
        return $this->fecha_registro;
    }

    public function setFechaRegistro(?\DateTime $fecha_registro): static
    {
        $this->fecha_registro = $fecha_registro;

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

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): static
    {
        $this->apellido = $apellido;

        return $this;
    }
}
