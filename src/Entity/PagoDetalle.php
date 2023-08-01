<?php

namespace App\Entity;

use App\Repository\PagoDetalleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PagoDetalleRepository::class)
 */
class PagoDetalle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Pago::class, inversedBy="pagoDetalles")
     */
    private $pago;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $monto;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuotas;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numeroTicket;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montoTicket;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaTicket;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observacion;

    /**
     * @ORM\OneToOne(targetEntity=Archivo::class, cascade={"persist", "remove"})
     */
    private $archivo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPago(): ?Pago
    {
        return $this->pago;
    }

    public function setPago(?Pago $pago): self
    {
        $this->pago = $pago;

        return $this;
    }

    public function getMonto(): ?float
    {
        return $this->monto;
    }

    public function setMonto(?float $monto): self
    {
        $this->monto = $monto;

        return $this;
    }

    public function getCuotas(): ?int
    {
        return $this->cuotas;
    }

    public function setCuotas(?int $cuotas): self
    {
        $this->cuotas = $cuotas;

        return $this;
    }

    public function getNumeroTicket(): ?string
    {
        return $this->numeroTicket;
    }

    public function setNumeroTicket(?string $numeroTicket): self
    {
        $this->numeroTicket = $numeroTicket;

        return $this;
    }

    public function getMontoTicket(): ?float
    {
        return $this->montoTicket;
    }

    public function setMontoTicket(?float $montoTicket): self
    {
        $this->montoTicket = $montoTicket;

        return $this;
    }

    public function getFechaTicket(): ?\DateTimeInterface
    {
        return $this->fechaTicket;
    }

    public function setFechaTicket(?\DateTimeInterface $fechaTicket): self
    {
        $this->fechaTicket = $fechaTicket;

        return $this;
    }

    public function getObservacion(): ?string
    {
        return $this->observacion;
    }

    public function setObservacion(?string $observacion): self
    {
        $this->observacion = $observacion;

        return $this;
    }

    public function getArchivo(): ?Archivo
    {
        return $this->archivo;
    }

    public function setArchivo(?Archivo $archivo): self
    {
        $this->archivo = $archivo;

        return $this;
    }
}
