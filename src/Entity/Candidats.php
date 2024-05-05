<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\CandidatsRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: CandidatsRepository::class)]
#[Vich\Uploadable]
class Candidats  
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;   


    #[ORM\Column(type: 'string', length: 190)]
    private $cvName;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isValided;

    
    #[ORM\OneToOne(mappedBy: 'candidats', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private $user;

    #[ORM\OneToMany(mappedBy: 'candidats', targetEntity: Candidature::class)]
    private $candidatures;

    


    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
    }

     public function __toString()
     {
        return $this->cvName;
          
     }

    public function getId(): ?int
    {
        return $this->id;
    }   

    public function getCvName(): ?string
    {
        return $this->cvName;
    }

    public function setCvName(string $cvName): self
    {
        $this->cvName = $cvName;

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
            $this->user->setCandidate(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getCandidate() !== $this) {
            $user->setCandidate($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures[] = $candidature;
            $candidature->setCandidats($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getCandidats() === $this) {
                $candidature->setCandidats(null);
            }
        }

        return $this;
    }

    
}
