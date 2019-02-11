<?php
namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="Email already taken!")
 */
class User implements UserInterface
{


    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];


    /**
     * @var string The hashed password
     * @ORM\Column(type="string",length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $status;
  
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     * min=6,
     * max=4096,
     * minMessage = "Your password must be at least 6 characters long")
     */
    private $plainPassword;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSubjects", mappedBy="user_email", cascade={"all"})
     */
    private $userSubjects;

    public function __construct()
    {
        $this->userSubjects = new ArrayCollection();
        $this->roles = array('ROLE_USER');
    }
   

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER, ROLE_ADMIN';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
         $this->plainPassword = null;
    }

    public function getPlainPassword()
    {
        return  $this->plainPassword;
    }

    public function setPlainPassword(string $password)
    {
        $this->plainPassword = $password;
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
            $userSubject->setUserEmail($this);
        }

        return $this;
    }

    public function removeUserSubject(UserSubjects $userSubject): self
    {
        if ($this->userSubjects->contains($userSubject)) {
            $this->userSubjects->removeElement($userSubject);
            // set the owning side to null (unless already changed)
            if ($userSubject->getUserEmail() === $this) {
                $userSubject->setUserEmail(null);
            }
        }

        return $this;
    }
}
