<?php

namespace App\Entity;

use App\Repository\RecruteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecruteurRepository::class)]
class Recruteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 190)]
    private $addressFirm;

    #[ORM\Column(type: 'string', length: 190)]
    private $firmName;

    #[ORM\Column(type: 'string', length: 5)]
    private $zipcode;

    #[ORM\Column(type: 'string', length: 190)]
    private $city;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isValided;

    

    #[ORM\OneToOne(mappedBy: 'recruteur', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private $user;

    #[ORM\OneToMany(mappedBy: 'recruteur', targetEntity: Job::class)]
    private $job;

   

    public function __construct()
    {
        $this->job = new ArrayCollection();
    }

    public function __toString()
     {
        return $this->addressFirm;
          
     }

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getAddressFirm(): ?string
    {
        return $this->addressFirm;
    }

    public function setAddressFirm(string $addressFirm): self
    {
        $this->addressFirm = $addressFirm;

        return $this;
    }

    public function getFirmName(): ?string
    {
        return $this->firmName;
    }

    public function setFirmName(string $firmName): self
    {
        $this->firmName = $firmName;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function isIsValided(): ?bool
    {
        return $this->isValided;
    }

    public function setIsValided(?bool $isValided): self
    {
        $this->isValided = $isValided;

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
            $this->user->setRecruteur(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getRecruteur() !== $this) {
            $user->setRecruteur($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Job>
     */
    public function getJob(): Collection
    {
        return $this->job;
    }

    public function addJob(Job $job): self
    {
        if (!$this->job->contains($job)) {
            $this->job[] = $job;
            $job->setRecruteur($this);
        }

        return $this;
    }

    public function removeJob(Job $job): self
    {
        if ($this->job->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getRecruteur() === $this) {
                $job->setRecruteur(null);
            }
        }

        return $this;
    }

    
}