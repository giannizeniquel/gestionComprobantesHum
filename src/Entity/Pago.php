<?php

namespace App\Entity;

use App\Repository\PagoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\ManyToOne(targetEntity=Curso::class, inversedBy="pagos", cascade={"persist"})
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
     * @ORM\OneToMany(targetEntity=PagoDetalle::class, mappedBy="pago", cascade={"persist"})
     */
    private $pagoDetalles;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observacion;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    public function __construct()
    {
        $this->pagoDetalles = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }


     //se usa en el detail de pagos para obtener mas detalle 
    public function getPagoMasDetallesObj(): array
    {
        $masDetalles = [];
        foreach ($this->pagoDetalles as $detalle) {
            $masDetalles[] = [
                'id' => $detalle->getId(),
                'numeroTicket' => $detalle->getNumeroTicket(),
                'montoCuota' => $detalle->getMontoCuotas(),
                'fechaTicket' => $detalle->getFechaTicket(),
                'observacion'=>$detalle->getObservacion(),
                'nombreArchivo'=>$detalle->getNombreArchivo(),
                'imageName'=>$detalle->getImageName(),             
                'getUpdate' => $detalle->getUpdatedAt() ? $detalle->getUpdatedAt()->format('d-m-Y') : '',
                //  'fechaTicket' => $detalle->getFechaTicket(),

            ];
        }
        return $masDetalles;
    }

}
