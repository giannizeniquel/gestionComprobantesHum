<?php

namespace App\Entity;

use App\Repository\CarreraRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarreraRepository::class)
 */
class Carrera
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity=Curso::class, mappedBy="carrera")
     */
    private $cursos;

    /**
     * @ORM\OneToMany(targetEntity=TipoCurso::class, mappedBy="carrera")
     */
    private $tipoCurso;

    public function __construct()
    {
        $this->cursos = new ArrayCollection();
        $this->tipoCurso = new ArrayCollection();
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

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

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
            $curso->setCarrera($this);
        }

        return $this;
    }

    public function removeCurso(Curso $curso): self
    {
        if ($this->cursos->removeElement($curso)) {
            // set the owning side to null (unless already changed)
            if ($curso->getCarrera() === $this) {
                $curso->setCarrera(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nombre;
    }

    /**
     * @return Collection<int, TipoCurso>
     */
    public function getTipoCurso(): Collection
    {
        return $this->tipoCurso;
    }

    public function addTipoCurso(TipoCurso $tipoCurso): self
    {
        if (!$this->tipoCurso->contains($tipoCurso)) {
            $this->tipoCurso[] = $tipoCurso;
            $tipoCurso->setCarrera($this);
        }

        return $this;
    }

    public function removeTipoCurso(TipoCurso $tipoCurso): self
    {
        if ($this->tipoCurso->removeElement($tipoCurso)) {
            // set the owning side to null (unless already changed)
            if ($tipoCurso->getCarrera() === $this) {
                $tipoCurso->setCarrera(null);
            }
        }

        return $this;
    }
}
