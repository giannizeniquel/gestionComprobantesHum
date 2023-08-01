<?php

namespace App\Entity;

use App\Repository\ArchivoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArchivoRepository::class)
 */
class Archivo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToOne(targetEntity=PagoDetalle::class, cascade={"persist", "remove"})
     */
    private $pagoDetalle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPagoDetalle(): ?PagoDetalle
    {
        return $this->pagoDetalle;
    }

    public function setPagoDetalle(?PagoDetalle $pagoDetalle): self
    {
        $this->pagoDetalle = $pagoDetalle;

        return $this;
    }
}
