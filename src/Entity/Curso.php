<?php

namespace App\Entity;

use App\Repository\CursoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @ORM\Entity(repositoryClass=CursoRepository::class)
 */
class Curso
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $observacion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $duracion;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $precio;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacidad;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $modalidad;

    /**
     * @ORM\ManyToOne(targetEntity=TipoCurso::class, inversedBy="cursos")
     */
    private $tipo;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="cursos")
     */
    private $users;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cantidadCuotas;

    /**
     * @ORM\OneToMany(targetEntity=Pago::class, mappedBy="curso")
     */
    private $pagos;

    /**
     * @ORM\ManyToMany(targetEntity=Cuota::class, mappedBy="cursos")
     */
    private $cuotas;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->pagos = new ArrayCollection();
        $this->cuotas = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getObservacion(): ?string
    {
        return $this->observacion;
    }

    public function setObservacion(string $observacion): self
    {
        $this->observacion = $observacion;

        return $this;
    }

    public function isActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    public function getDuracion(): ?string
    {
        return $this->duracion;
    }

    public function setDuracion(?string $duracion): self
    {
        $this->duracion = $duracion;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(?float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getCapacidad(): ?int
    {
        return $this->capacidad;
    }

    public function setCapacidad(?int $capacidad): self
    {
        $this->capacidad = $capacidad;

        return $this;
    }

    public function getModalidad(): ?string
    {
        return $this->modalidad;
    }

    public function setModalidad(?string $modalidad): self
    {
        $this->modalidad = $modalidad;

        return $this;
    }

    public function getTipo(): ?TipoCurso
    {
        return $this->tipo;
    }

    public function setTipo(?TipoCurso $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addCurso($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeCurso($this);
        }

        return $this;
    }

    public function getCantidadCuotas(): ?int
    {
        return $this->cantidadCuotas;
    }

    public function setCantidadCuotas(?int $cantidadCuotas): self
    {
        $this->cantidadCuotas = $cantidadCuotas;

        return $this;
    }

    /**
     * @return Collection<int, Pago>
     */
    public function getPagos(): Collection
    {
        return $this->pagos;
    }

    public function addPago(Pago $pago): self
    {
        if (!$this->pagos->contains($pago)) {
            $this->pagos[] = $pago;
            $pago->setCurso($this);
        }

        return $this;
    }

    public function removePago(Pago $pago): self
    {
        if ($this->pagos->removeElement($pago)) {
            // set the owning side to null (unless already changed)
            if ($pago->getCurso() === $this) {
                $pago->setCurso(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cuota>
     */
    public function getCuotas(): Collection
    {
        return $this->cuotas;
    }

    public function addCuota(Cuota $cuota): self
    {
        if (!$this->cuotas->contains($cuota)) {
            $this->cuotas[] = $cuota;
            $cuota->addCurso($this);
        }

        return $this;
    }

    public function removeCuota(Cuota $cuota): self
    {
        if ($this->cuotas->removeElement($cuota)) {
            $cuota->removeCurso($this);
        }

        return $this;
    }
}
