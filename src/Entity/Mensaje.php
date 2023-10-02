<?php

namespace App\Entity;

use App\Repository\MensajeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MensajeRepository::class)
 */
class Mensaje
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Reclamo::class, inversedBy="mensajes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $reclamo;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="mensajes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mensaje;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function __toString()
    {
        if(in_array('ROLE_ADMIN', $this->getUser()->getRoles())){
            return ('Admin: '.$this->getUser()->getFullName().' - '.date('d-m-Y H:i',$this->getCreatedAt()->getTimestamp()).' hs.');
        }else{
            return ('Usuario: '.$this->getUser()->getFullName().' - '.date('d-m-Y H:i',$this->getCreatedAt()->getTimestamp()).' hs.');
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReclamo(): ?Reclamo
    {
        return $this->reclamo;
    }

    public function setReclamo(?Reclamo $reclamo): self
    {
        $this->reclamo = $reclamo;

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

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(string $mensaje): self
    {
        $this->mensaje = $mensaje;

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
}
