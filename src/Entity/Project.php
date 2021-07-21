<?php

// * @Assert\Expression(
//     * "this.getProjectDeadline() >= this.getProjectDeadline()",
//     * message="votre deadline doit être supérieure à la date d'aujourd'hui"
//     * )

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank  
     * @Assert\Type("string")
     */

    private $project_name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank  
     */

    private $projet_description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $projet_creation;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan("today")
     */

    private $projet_deadline;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Tasks::class, mappedBy="project", orphanRemoval=true)
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectName(): ?string
    {
        return $this->project_name;
    }

    public function setProjectName(string $project_name): self
    {
        $this->project_name = $project_name;

        return $this;
    }

    public function getProjetDescription(): ?string
    {
        return $this->projet_description;
    }

    public function setProjetDescription(string $projet_description): self
    {
        $this->projet_description = $projet_description;

        return $this;
    }

    public function getProjetCreation(): ?\DateTimeInterface
    {
        return $this->projet_creation;
    }

    public function setProjetCreation(\DateTimeInterface $projet_creation): self
    {
        $this->projet_creation = $projet_creation;

        return $this;
    }

    public function getProjetDeadline(): ?\DateTimeInterface
    {
        return $this->projet_deadline;
    }

    public function setProjetDeadline(\DateTimeInterface $projet_deadline): self
    {
        $this->projet_deadline = $projet_deadline;

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

    /**
     * @return Collection|Tasks[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Tasks $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Tasks $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }
}
