<?php

namespace App\Entity;

use App\Repository\JobCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JobCategoryRepository::class)
 */
class JobCategory
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
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Candidate::class, mappedBy="jobCategory")
     */
    private $candidate;

    /**
     * @ORM\OneToMany(targetEntity=JobOffer::class, mappedBy="jobCategory")
     */
    private $jobCategory;

    public function __construct()
    {
        $this->candidate = new ArrayCollection();
        $this->jobCategory = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Candidate[]
     */
    public function getCandidate(): Collection
    {
        return $this->candidate;
    }

    public function addCandidate(Candidate $candidate): self
    {
        if (!$this->candidate->contains($candidate)) {
            $this->candidate[] = $candidate;
            $candidate->setJobCategory($this);
        }

        return $this;
    }

    public function removeCandidate(Candidate $candidate): self
    {
        if ($this->candidate->removeElement($candidate)) {
            // set the owning side to null (unless already changed)
            if ($candidate->getJobCategory() === $this) {
                $candidate->setJobCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|JobOffer[]
     */
    public function getJobCategory(): Collection
    {
        return $this->jobCategory;
    }

    public function addJobCategory(JobOffer $jobCategory): self
    {
        if (!$this->jobCategory->contains($jobCategory)) {
            $this->jobCategory[] = $jobCategory;
            $jobCategory->setJobCategory($this);
        }

        return $this;
    }

    public function removeJobCategory(JobOffer $jobCategory): self
    {
        if ($this->jobCategory->removeElement($jobCategory)) {
            // set the owning side to null (unless already changed)
            if ($jobCategory->getJobCategory() === $this) {
                $jobCategory->setJobCategory(null);
            }
        }

        return $this;
    }
}
