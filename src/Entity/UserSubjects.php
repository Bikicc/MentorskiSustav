<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserSubjectsRepository")
 */
class UserSubjects
{
 
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userSubjects")
     * @ORM\JoinColumn(name="user_email", referencedColumnName="email")
     */
    private $user_email;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Subject", inversedBy="userSubjects")
     * @ORM\JoinColumn(name="subject_code", referencedColumnName="code")
     */
    private $subject_code;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $status;



    public function getUserEmail(): ?user
    {
        return $this->user_email;
    }

    public function setUserEmail(?user $user_email): self
    {
        $this->user_email = $user_email;

        return $this;
    }

    public function getSubjectCode(): ?subject
    {
        return $this->subject_code;
    }

    public function setSubjectCode(?subject $subject_code): self
    {
        $this->subject_code = $subject_code;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
