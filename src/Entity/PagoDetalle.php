<?php

namespace App\Entity;

use App\Repository\PagoDetalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\OneToMany(targetEntity=Archivo::class, mappedBy="pagoDetalle", cascade={"persist"})
     */
    private $comprobantes;

    public function __construct()
    {
        $this->comprobantes = new ArrayCollection();
    }

    public function __toString()
    {
        return 'Transaccion: '.$this->numeroTicket;
    }

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

    /**
     * @return Collection<int, Archivo>
     */
    public function getComprobantes(): Collection
    {
        return $this->comprobantes;
    }

    public function addComprobante(Archivo $comprobante): self
    {
        if (!$this->comprobantes->contains($comprobante)) {
            $this->comprobantes[] = $comprobante;
            $comprobante->setPagoDetalle($this);
        }

        return $this;
    }

    public function removeComprobante(Archivo $comprobante): self
    {
        if ($this->comprobantes->removeElement($comprobante)) {
            // set the owning side to null (unless already changed)
            if ($comprobante->getPagoDetalle() === $this) {
                $comprobante->setPagoDetalle(null);
            }
        }

        return $this;
    }
}
