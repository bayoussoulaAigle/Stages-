<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 * fields={"email"},
 * message="L'email que vous avez indiqué est déjà utilisé!"
 * )
 */

class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="2", minMessage = "Verifiez votre nom et prenom ")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fonction;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(min="7", max="10",  minMessage = "Votre numero n'est pas correcte ")
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="6", minMessage = "Votre adresse est invalide ")
     */
    private $adresse;

    

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(max="5",  minMessage = "Votre boite postale doit avoir 5 chiffre")
     */
    private $bPostale;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="4", minMessage = "Le nom de la ville est invalide")
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min="4", minMessage = "Votre site est invalide")
     */
    private $site;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(min="6", minMessage = "Votre numero de téléphone doit avoir 10 chiffre")
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="5", minMessage = "Votre adresse email doit avoir aumoins 5 caractere")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage = "Votre mot de passe doit faire minimum 8 caracteres");
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas tapé le meme mot de passe. ")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="3", minMessage = " Verifiez votre secteur activité ")
     */
    private $secteurActiviter;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getBoitePostale(): ?string
    {
        return $this->boitePostale;
    }

    public function setBoitePostale(?string $boitePostale): self
    {
        $this->boitePostale = $boitePostale;

        return $this;
    }

    public function getBPostale(): ?int
    {
        return $this->bPostale;
    }

    public function setBPostale(int $bPostale): self
    {
        $this->bPostale = $bPostale;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(?string $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSecteurActiviter(): ?string
    {
        return $this->secteurActiviter;
    }

    public function setSecteurActiviter(?string $secteurActiviter): self
    {
        $this->secteurActiviter = $secteurActiviter;

        return $this;
    }

    public function eraseCredentials(){}

        public function getSalt(){}
    
        public function getRoles(){
            return ['ROLE_USER'];
        }
    
}
