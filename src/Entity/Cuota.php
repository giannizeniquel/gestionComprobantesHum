<?php

namespace App\Entity;

use App\Repository\CuotaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @ORM\Entity(repositoryClass=CuotaRepository::class)
 */
class Cuota
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $monto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaVencimiento;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $numeroCuota;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $pagado;

    /**
     * @ORM\ManyToMany(targetEntity=Curso::class, inversedBy="cuotas", cascade={"persist"})
     */
    private $cursos;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity=PagoDetalle::class, mappedBy="cuotas")
     */
    private $pagoDetalles;

    public function __construct()
    {
        $this->cursos = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->pagoDetalles = new ArrayCollection();
    }

 
    public function __toString()
    {
        return "Cuota ".$this->numeroCuota;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonto(): ?float
    {
        return $this->monto;
    }

    public function setMonto(?float $monto): self
    {
        $this->monto = $monto;
        $this->updated_at = new \DateTimeImmutable('now');
        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFechaVencimiento(): ?\DateTimeInterface
    {
        return $this->fechaVencimiento;
    }

    public function setFechaVencimiento(?\DateTimeInterface $fechaVencimiento): self
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    public function getNumeroCuota(): ?string
    {
        return $this->numeroCuota;
    }

    public function setNumeroCuota(string $numeroCuota): self
    {
        $this->numeroCuota = $numeroCuota;

        return $this;
    }

    public function isPagado(): ?bool
    {
        return $this->pagado;
    }

    public function setPagado(?bool $pagado): self
    {
        $this->pagado = $pagado;

        return $this;
    }

    /**
     * @return Collection<int, Curso>
     */
    public function getCursos(): Collection
    {
        return $this->cursos;
    }

    public function addCurso(Curso $curso): self
    {
        if (!$this->cursos->contains($curso)) {
            $this->cursos[] = $curso;
        }

        return $this;
    }

    public function removeCurso(Curso $curso): self
    {
        $this->cursos->removeElement($curso);

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
            $pagoDetalle->addCuota($this);
        }

        return $this;
    }

    public function removePagoDetalle(PagoDetalle $pagoDetalle): self
    {
        if ($this->pagoDetalles->removeElement($pagoDetalle)) {
            $pagoDetalle->removeCuota($this);
        }

        return $this;
    }
}
