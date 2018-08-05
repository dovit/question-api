<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ApiResource(
 *     normalizationContext={"groups"={"question"}},
 *     denormalizationContext={"groups"={"question"}},
 *     attributes={"access_control"="is_granted('ROLE_USER')"},
 *     itemOperations={
 *         "get"={"access_control"="is_granted('ROLE_USER') and object.owner == user"}
 *     }
 * )
 */
class Question
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"question"})
     */
    private $id;

    /**
     * @ORM\Column
     * @Groups({"question"})
     */
    public $content;

    /**
     * @ORM\OneToMany(targetEntity="Proposal", mappedBy="question", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="id", unique=true)
     * @ApiSubresource(maxDepth=20)
     * @Groups({"question"})
     */
    public $proposals;

    /**
     * @var User The owner
     *
     * @ORM\ManyToOne(targetEntity=User::class, cascade={"persist"})
     * @Groups({"question"})
     */
    public $owner;

    public function __construct()
    {
        $this->proposals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function addProposal(Proposal $proposal): void
    {
        $proposal->question = $this;
        $this->proposals->add($proposal);
    }

    public function removeProposal(Proposal $proposal): void
    {
        $proposal->question = null;
        $this->proposals->removeElement($proposal);
    }
}
