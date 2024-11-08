<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ActionRepository::class)]
class Action
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["job"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["job"])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(["job"])]
        private ?bool $setClosed = null;

    /**
     * @var Collection<int, JobTracking>
     */
    #[ORM\OneToMany(targetEntity: JobTracking::class, mappedBy: 'action')]
    private Collection $jobTrackings;


    #[Groups(["job"])]
    private ?int $jobTrackings_count = null;
    public function __construct()
    {
        $this->jobTrackings = new ArrayCollection();
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

    public function isSetClosed(): ?bool
    {
        return $this->setClosed;
    }

    public function setSetClosed(bool $setClosed): static
    {
        $this->setClosed = $setClosed;

        return $this;
    }

    /**
     * @return Collection<int, JobTracking>
     */
    public function getJobTrackings(): Collection
    {
        return $this->jobTrackings;
    }

    public function addJobTracking(JobTracking $jobTracking): static
    {
        if (!$this->jobTrackings->contains($jobTracking)) {
            $this->jobTrackings->add($jobTracking);
            $jobTracking->setAction($this);
        }

        return $this;
    }

    public function removeJobTracking(JobTracking $jobTracking): static
    {
        if ($this->jobTrackings->removeElement($jobTracking)) {
            // set the owning side to null (unless already changed)
            if ($jobTracking->getAction() === $this) {
                $jobTracking->setAction(null);
            }
        }

        return $this;
    }

    public function getJobTrackingsCount(): ?int
    {
        return $this->jobTrackings_count;
    }

    public function setJobTrackingsCount(?int $jobTrackings_count): static
    {
        $this->jobTrackings_count = $jobTrackings_count;

        return $this;
    }

}
