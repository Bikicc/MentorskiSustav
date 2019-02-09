<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubjectRepository")
 * @UniqueEntity(fields={"code"}, message="Code already taken!")
 */
class Subject
{
 

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=10, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $ECTS;

    /**
     * @ORM\Column(type="integer")
     */
    private $sem_redovni;

    /**
     * @ORM\Column(type="integer")
     */
    private $sem_izvanredni;

    /**
     * @ORM\Column(type="boolean")
     */
    private $izborni;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSubjects", mappedBy="subject_code", cascade={"all"})
     */
    private $userSubjects;

    public function __construct()
    {
        $this->userSubjects = new ArrayCollection();
    }

 

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getECTS(): ?int
    {
        return $this->ECTS;
    }

    public function setECTS(int $ECTS): self
    {
        $this->ECTS = $ECTS;

        return $this;
    }

    public function getSemRedovni(): ?int
    {
        return $this->sem_redovni;
    }

    public function setSemRedovni(int $sem_redovni): self
    {
        $this->sem_redovni = $sem_redovni;

        return $this;
    }

    public function getSemIzvanredni(): ?int
    {
        return $this->sem_izvanredni;
    }

    public function setSemIzvanredni(int $sem_izvanredni): self
    {
        $this->sem_izvanredni = $sem_izvanredni;

        return $this;
    }

    public function getIzborni(): ?bool
    {
        return $this->izborni;
    }

    public function setIzborni(bool $izborni): self
    {
        $this->izborni = $izborni;

        return $this;
    }

    /**
     * @return Collection|UserSubjects[]
     */
    public function getUserSubjects(): Collection
    {
        return $this->userSubjects;
    }

    public function addUserSubject(UserSubjects $userSubject): self
    {
        if (!$this->userSubjects->contains($userSubject)) {
            $this->userSubjects[] = $userSubject;
            $userSubject->setSubjectCode($this);
        }

        return $this;
    }

    public function removeUserSubject(UserSubjects $userSubject): self
    {
        if ($this->userSubjects->contains($userSubject)) {
            $this->userSubjects->removeElement($userSubject);
            // set the owning side to null (unless already changed)
            if ($userSubject->getSubjectCode() === $this) {
                $userSubject->setSubjectCode(null);
            }
        }

        return $this;
    }
}
