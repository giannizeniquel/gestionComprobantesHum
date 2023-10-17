<?php

namespace App\Entity;

use App\Repository\ReclamoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReclamoRepository::class)
 */
class Reclamo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reclamos", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Pago::class, inversedBy="reclamos", cascade={"persist"})
     */
    private $pago;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estado = true;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=Mensaje::class, mappedBy="reclamo", cascade={"persist", "remove"})
     */
    private $mensajes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $leido;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaLeido;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->mensajes = new ArrayCollection();
    }

    public function __toString()
    {
        return strval($this->id);
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPago(): ?Pago
    {
        return $this->pago;
    }

    public function setPago(?Pago $pago): self
    {
        $this->pago = $pago;

        return $this;
    }

    public function isEstado(): ?bool
    {
        return $this->estado;
    }

    public function setEstado(bool $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Mensaje>
     */
    public function getMensajes(): Collection
    {
        return $this->mensajes;
    }

    public function addMensaje(Mensaje $mensaje): self
    {
        if (!$this->mensajes->contains($mensaje)) {
            $this->mensajes[] = $mensaje;
            $mensaje->setReclamo($this);
        }

        return $this;
    }

    public function removeMensaje(Mensaje $mensaje): self
    {
        if ($this->mensajes->removeElement($mensaje)) {
            // set the owning side to null (unless already changed)
            if ($mensaje->getReclamo() === $this) {
                $mensaje->setReclamo(null);
            }
        }

        return $this;
    }

    public function isLeido(): ?bool
    {
        return $this->leido;
    }

    public function setLeido(bool $leido): self
    {
        $this->leido = $leido;

        return $this;
    }

    public function getFechaLeido(): ?\DateTimeInterface
    {
        return $this->fechaLeido;
    }

    public function setFechaLeido(?\DateTimeInterface $fechaLeido): self
    {
        $this->fechaLeido = $fechaLeido;

        return $this;
    }
}
