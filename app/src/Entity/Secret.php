<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="secret")
 */
class Secret{
    /**
    * @ORM\Id()
    * @ORM\Column(name="hash")
    */
    protected $hash;

    /**
     * @ORM\Column(name="secretText")
     */
    protected $secretText;

    /**
     * @ORM\Column(name="createdAt")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="expiresAt")
     */
    protected $expiresAt;

    /**
     * @ORM\Column(name="remainingViews")
     */
    protected $remainingViews;


    /**
     * Get the value of remainingViews
     */ 
    public function getRemainingViews()
    {
        return $this->remainingViews;
    }

    /**
     * Set the value of remainingViews
     *
     * @return  self
     */ 
    public function setRemainingViews($remainingViews)
    {
        $this->remainingViews = $remainingViews;

        return $this;
    }

    /**
     * Get the value of hash
     */ 
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set the value of hash
     *
     * @return  self
     */ 
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get the value of secretText
     */ 
    public function getSecretText()
    {
        return $this->secretText;
    }

    /**
     * Set the value of secretText
     *
     * @return  self
     */ 
    public function setSecretText($secretText)
    {
        $this->secretText = $secretText;

        return $this;
    }

    /**
     * Get the value of createdAt
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @return  self
     */ 
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of expiresAt
     */ 
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Set the value of expiresAt
     *
     * @return  self
     */ 
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }
}