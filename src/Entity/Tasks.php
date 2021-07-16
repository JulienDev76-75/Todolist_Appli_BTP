<?php

namespace App\Entity;

use App\Repository\TasksRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TasksRepository::class)
 */
class Tasks
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
    private $task_name;

    /**
     * @ORM\Column(type="text")
     */
    private $task_description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $task_creation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $task_deadline;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $task_statut;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     * */

    private $project;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskName(): ?string
    {
        return $this->task_name;
    }

    public function setTaskName(string $task_name): self
    {
        $this->task_name = $task_name;

        return $this;
    }

    public function getTaskDescription(): ?string
    {
        return $this->task_description;
    }

    public function setTaskDescription(string $task_description): self
    {
        $this->task_description = $task_description;

        return $this;
    }

    public function getTaskCreation(): ?\DateTimeInterface
    {
        return $this->task_creation;
    }

    public function setTaskCreation(\DateTimeInterface $task_creation): self
    {
        $this->task_creation = $task_creation;

        return $this;
    }

    public function getTaskDeadline(): ?\DateTimeInterface
    {
        return $this->task_deadline;
    }

    public function setTaskDeadline(\DateTimeInterface $task_deadline): self
    {
        $this->task_deadline = $task_deadline;

        return $this;
    }

    public function getTaskStatut(): ?string
    {
        return $this->task_statut;
    }

    public function setTaskStatut(string $task_statut): self
    {
        $this->task_statut = $task_statut;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}
