<?php

namespace App\Entity;

use App\Repository\PagoDetalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass=PagoDetalleRepository::class)
 * @Vich\Uploadable
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
    private $montoCuotas;

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
     * @ORM\ManyToMany(targetEntity=Cuota::class, inversedBy="pagoDetalles")
     */
    private $cuotas;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombreArchivo;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="archivos", fileNameProperty="imageName", size="imageSize")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer")
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updated_at;

    public function __construct()
    {
        $this->cuotas = new ArrayCollection();
    }

    public function __toString()
    {
        return 'Transaccion: ' . $this->numeroTicket;
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

    public function getMontoCuotas(): ?float
    {
        return $this->montoCuotas;
    }

    public function setMontoCuotas(?float $montoCuotas): self
    {
        $this->montoCuotas = $montoCuotas;

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
        }

        return $this;
    }

    public function removeCuota(Cuota $cuota): self
    {
        $this->cuotas->removeElement($cuota);

        return $this;
    }

    public function getNombreArchivo(): ?string
    {
        return $this->nombreArchivo;
    }

    public function setNombreArchivo(string $nombreArchivo): self
    {
        $this->nombreArchivo = $nombreArchivo;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     */
    public function setImageFile(?File $imageFile): self
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated_at = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(int $imageSize): self
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
