<?php

namespace App\Entity;

use App\Repository\CandidacyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CandidacyRepository::class)
 */
class Candidacy
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Candidate::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $candidate;

    /**
     * @ORM\OneToOne(targetEntity=JobOffer::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $jobOffer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidate(): ?Candidate
    {
        return $this->candidate;
    }

    public function setCandidate(Candidate $candidate): self
    {
        $this->candidate = $candidate;

        return $this;
    }

    public function getJobOffer(): ?JobOffer
    {
        return $this->jobOffer;
    }

    public function setJobOffer(JobOffer $jobOffer): self
    {
        $this->jobOffer = $jobOffer;

        return $this;
    }
}
