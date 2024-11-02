<?php

namespace App\Entity;

use App\Repository\JobSourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: JobSourceRepository::class)]
class JobSource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["job_source"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["job_source"])]
    private ?string $name = null;

    /**
     * @var Collection<int, Job>
     */
    #[ORM\OneToMany(targetEntity: Job::class, mappedBy: 'source')]
    private Collection $jobs;

    
    #[Groups(["job_source"])]
    private ?int $job_count = null;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Job>
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): static
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
            $job->setSource($this);
        }

        return $this;
    }

    public function removeJob(Job $job): static
    {
        if ($this->jobs->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getSource() === $this) {
                $job->setSource(null);
            }
        }

        return $this;
    }
    public function getJobCount(): ?int
    {
        return $this->job_count;
    }

    public function setJobCount(?int $job_count): static
    {
        $this->job_count = $job_count;

        return $this;
    }

}
