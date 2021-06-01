<?php

namespace App\Entity;

use App\Repository\JobTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JobTypeRepository::class)
 */
class JobType
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
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=JobOffer::class, mappedBy="jobType")
     */
    private $jobOffer;

    public function __construct()
    {
        $this->jobOffer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|JobOffer[]
     */
    public function getJobOffer(): Collection
    {
        return $this->jobOffer;
    }

    public function addJobOffer(JobOffer $jobOffer): self
    {
        if (!$this->jobOffer->contains($jobOffer)) {
            $this->jobOffer[] = $jobOffer;
            $jobOffer->setJobType($this);
        }

        return $this;
    }

    public function removeJobOffer(JobOffer $jobOffer): self
    {
        if ($this->jobOffer->removeElement($jobOffer)) {
            // set the owning side to null (unless already changed)
            if ($jobOffer->getJobType() === $this) {
                $jobOffer->setJobType(null);
            }
        }

        return $this;
    }
}
