<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Interfaces\ChangeDataDayInterface;
use App\Entity\Interfaces\IdInterface;
use App\Entity\Interfaces\StatusInterface;
use App\Entity\Traits\ChangeDataDayTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\StatusTrait;
use App\Repository\FeedbackRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    paginationItemsPerPage: 10,
)]
#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_FEEDBACK_EMAIL_SUBJECT', fields: ['fromEmail', 'subject'])]
#[UniqueEntity(fields: ['fromEmail', 'subject'], message: 'There is already a massage already exists')]
class Feedback implements
    IdInterface,
    StatusInterface,
    ChangeDataDayInterface
{
    use
        IdTrait,
        ChangeDataDayTrait,
        StatusTrait
        ;

    public const STATUS_NEW = 1;
    public const STATUS_VIEWED = 2;
    public const STATUS_DELETED = 3;

    public const STATUSES = [
        self::STATUS_NEW => 'New',
        self::STATUS_VIEWED => 'Viewed',
        self::STATUS_DELETED => 'Deleted',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $fromEmail = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $status = null;

    public function getFromEmail(): ?string
    {
        return $this->fromEmail;
    }

    public function setFromEmail(string $fromEmail): static
    {
        $this->fromEmail = $fromEmail;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getDefaultStatus(): ?int
    {
        return self::STATUS_NEW;
    }

}
