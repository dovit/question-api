<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ApiResource
 */
class Proposal
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column
     * @Groups({"question"})
     */
    public $content;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"question"})
     */
    public $isAnswer;

    /**
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="proposals")
     */
    public $question;

    public function getId(): ?int
    {
        return $this->id;
    }
}
