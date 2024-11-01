<?php

namespace App\Entity;

use App\Repository\JobApiServicesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: JobApiServicesRepository::class)]
class JobApiServices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api_service'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api_service'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['api_service'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api_service'])]
    private ?string $functionName = null;



    #[ORM\Column(length: 255)]
    #[Groups(['api_service'])]
    private ?string $url = null;

    /**
     * @var Collection<int, JobSearchSettings>
     */
    #[ORM\ManyToMany(targetEntity: JobSearchSettings::class, inversedBy: 'jobApiServices')]
    private Collection $jobSearchSettings;


    public function __construct()
    {
        $this->jobSearchSettings = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getFunctionName(): ?string
    {
        return $this->functionName;
    }

    public function setFunctionName(string $functionName): static
    {
        $this->functionName = $functionName;

        return $this;
    }
    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection<int, JobSearchSettings>
     */
    public function getJobSearchSettings(): Collection
    {
        return $this->jobSearchSettings;
    }

    public function addJobSearchSetting(JobSearchSettings $jobSearchSetting): static
    {
        if (!$this->jobSearchSettings->contains($jobSearchSetting)) {
            $this->jobSearchSettings->add($jobSearchSetting);
        }

        return $this;
    }

    public function removeJobSearchSetting(JobSearchSettings $jobSearchSetting): static
    {
        $this->jobSearchSettings->removeElement($jobSearchSetting);

        return $this;
    }

}
