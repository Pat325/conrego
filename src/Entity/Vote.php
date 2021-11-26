<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoteRepository::class)
 */
class Vote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $kohana;

    /**
     * @ORM\Column(type="integer")
     */
    private $symfony;

    /**
     * @ORM\Column(type="integer")
     */
    private $laravel;

    /**
     * @ORM\Column(type="integer")
     */
    private $check_req;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $check_req_not;

    /**
     * @ORM\Column(type="date")
     */
    private $added;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getKohana(): ?int
    {
        return $this->kohana;
    }

    public function setKohana(int $kohana): self
    {
        $this->kohana = $kohana;

        return $this;
    }

    public function getSymfony(): ?int
    {
        return $this->symfony;
    }

    public function setSymfony(int $symfony): self
    {
        $this->symfony = $symfony;

        return $this;
    }

    public function getLaravel(): ?int
    {
        return $this->laravel;
    }

    public function setLaravel(int $laravel): self
    {
        $this->laravel = $laravel;

        return $this;
    }

    public function getCheckReq(): ?int
    {
        return $this->check_req;
    }

    public function setCheckReq(int $check_req): self
    {
        $this->check_req = $check_req;

        return $this;
    }

    public function getCheckReqNot(): ?int
    {
        return $this->check_req_not;
    }

    public function setCheckReqNot(?int $check_req_not): self
    {
        $this->check_req_not = $check_req_not;

        return $this;
    }

    public function getAdded(): ?\DateTimeInterface
    {
        return $this->added;
    }

    public function setAdded(\DateTimeInterface $added): self
    {
        $this->added = $added;

        return $this;
    }
}
