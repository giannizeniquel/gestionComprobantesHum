<?php

namespace App\Entity;

use App\Repository\PagoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PagoRepository::class)
 */
class Pago
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $monto;

    /**
     * @ORM\ManyToOne(targetEntity=Curso::class, inversedBy="cuotas")
     */
    private $curso;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuotas;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="pagos")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=PagoDetalle::class, mappedBy="pago")
     */
    private $pagoDetalles;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observacion;

    public function __construct()
    {
        $this->pagoDetalles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonto(): ?float
    {
        return $this->monto;
    }

    public function setMonto(float $monto): self
    {
        $this->monto = $monto;

        return $this;
    }

    public function getCurso(): ?Curso
    {
        return $this->curso;
    }

    public function setCurso(?Curso $curso): self
    {
        $this->curso = $curso;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, PagoDetalle>
     */
    public function getPagoDetalles(): Collection
    {
        return $this->pagoDetalles;
    }

    public function addPagoDetalle(PagoDetalle $pagoDetalle): self
    {
        if (!$this->pagoDetalles->contains($pagoDetalle)) {
            $this->pagoDetalles[] = $pagoDetalle;
            $pagoDetalle->setPago($this);
        }

        return $this;
    }

    public function removePagoDetalle(PagoDetalle $pagoDetalle): self
    {
        if ($this->pagoDetalles->removeElement($pagoDetalle)) {
            // set the owning side to null (unless already changed)
            if ($pagoDetalle->getPago() === $this) {
                $pagoDetalle->setPago(null);
            }
        }

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

}
