<?php

namespace App\Entity;

use App\Repository\ConsultantsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultantsRepository::class)]
class Consultants
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    
    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private $tel;

    #[ORM\OneToOne(mappedBy: 'consultants', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private $user;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setConsultants(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getConsultants() !== $this) {
            $user->setConsultants($this);
        }

        $this->user = $user;

        return $this;
    }
}
