<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Interfaces\ChangeDataDayInterface;
use App\Entity\Interfaces\DefaultStatusInterface;
use App\Entity\Interfaces\IdInterface;
use App\Entity\Interfaces\NameInterface;
use App\Entity\Interfaces\RelatedMenuInterface;
use App\Entity\Interfaces\SeoInterface;
use App\Entity\Interfaces\SlugIntrface;
use App\Entity\Interfaces\StatusInterface;
use App\Entity\Interfaces\TypeInterface;
use App\Entity\Traits\ChangeDataDayTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\RelatedMenuTrait;
use App\Entity\Traits\SeoTrait;
use App\Entity\Traits\SlugTrait;
use App\Entity\Traits\StatusTrait;
use App\Entity\Traits\TypeTrait;
use App\Repository\PageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => ['page:read']],
    denormalizationContext: ['groups' => ['page:write']],
    paginationItemsPerPage: 10,
)]
#[ORM\Entity(repositoryClass: PageRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_NAME', fields: ['name'])]
#[UniqueEntity(fields: ['name'], message: 'There is already a page with this name')]
class Page implements
    IdInterface,
    SeoInterface,
    ChangeDataDayInterface,
    DefaultStatusInterface,
    StatusInterface,
    SlugIntrface,
    NameInterface,
    RelatedMenuInterface,
    TypeInterface
{
    use
        IdTrait,
        NameTrait,
        SeoTrait,
        ChangeDataDayTrait,
        StatusTrait,
        SlugTrait,

        RelatedMenuTrait,
        TypeTrait;

    public const PAGE_TYPE = 1;
    public const SECTION_TYPE = 2;
    public const CONTROLLER_ROUTE_TYPE = 4;

    public const TYPES = [
        self::PAGE_TYPE => 'Page',
        self::SECTION_TYPE => 'Section',
        self::CONTROLLER_ROUTE_TYPE => 'Controller route'
    ];
    public const SMALL_IMAGE_TYPE = 1;
    public const BIG_IMAGE_TYPE = 2;
    public const ORIGIN_IMAGE_TYPE = 3;
    public const IMAGE_TYPES = [
        self::ORIGIN_IMAGE_TYPE => 'Origin image',
        self::SMALL_IMAGE_TYPE => 'Small to preview',
        self::BIG_IMAGE_TYPE => 'Main image'
    ];
    public const PATH = [
        self::ORIGIN_IMAGE_TYPE => 'origin',
        self::SMALL_IMAGE_TYPE => 'small',
        self::BIG_IMAGE_TYPE => 'big'
    ];
    public const MIME_TYPES = [
        'image/gif' => 'Gif',
        'image/jpeg' => 'Jpeg',
        'image/jpg' => 'Jpg',

    ];
    public const MAX_SIZES = '10240k';
    public const SIZES = [
        self::SMALL_IMAGE_TYPE => [
            'width' => 300,
            'height' => 300
        ],
        self::BIG_IMAGE_TYPE => [
            'width' => 800,
            'height' => 800
        ]
    ];

    public const MENU_TYPE_VERTICAL = 1;
    public const MENU_TYPE_HORIZONTAL = 2;
    public const MENU_TYPE_BOTH_VERTICAL_AND_HORIZONTAL = 3;
    public const MENU_TYPE_NONE = 4;

    public const MENU_TYPES = [
        self::MENU_TYPE_VERTICAL => 'Vertical',
        self::MENU_TYPE_HORIZONTAL => 'Horizontal',
        self::MENU_TYPE_BOTH_VERTICAL_AND_HORIZONTAL => 'Vertical and horizontal',
        self::MENU_TYPE_NONE => 'No menu',
    ];

    public const MAIN_URL = 'app_page_main';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['page:read', 'page:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['page:read', 'page:write'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[Groups(['page:read', 'page:write'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $preview = null;

    #[Groups(['page:read', 'page:write'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $body = null;

    #[Groups(['page:read', 'page:write'])]
    #[ORM\Column(nullable: true)]
    private ?int $position = null;

    #[Groups(['page:read', 'page:write'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $publicAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['page:read', 'page:write'])]
    private ?string $image = null;

    #[ORM\Column]
    #[Groups(['page:read', 'page:write'])]
    private ?bool $isPreviewOnMain = false;
    #[Groups(['page:read', 'page:write'])]
    #[ORM\Column(length: 255, nullable: true)]
    private $seoTitle;
    #[Groups(['page:read', 'page:write'])]
    #[ORM\Column(length: 255, nullable: true)]
    private $seoDescription;
    #[Groups(['page:read', 'page:write'])]
    #[ORM\Column(length: 255, nullable: true)]
    private $seoKeywords;
    #[Groups(['page:read', 'page:write'])]
    #[ORM\Column(length: 255, nullable: true)]
    private $ogTitle;
    #[Groups(['page:read', 'page:write'])]
    #[ORM\Column(length: 255, nullable: true)]
    private $ogDescription;
    #[Groups(['page:read', 'page:write'])]
    #[ORM\Column(length: 255, nullable: true)]
    private $ogUrl;
    #[Groups(['page:read', 'page:write'])]
    #[ORM\Column(length: 255, nullable: true)]
    private $ogImage;
    #[Groups(['page:read', 'page:write'])]
    #[ORM\Column(length: 255, nullable: true)]
    private $ogType;
    #[Groups(['page:read', 'page:write'])]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $status = null;
    #[Groups(['page:read', 'page:write'])]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

   /* #[Groups(['page:read', 'page:write'])]
    #[ORM\ManyToOne(cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(name: "menu_id", referencedColumnName: "id", nullable: true)]
    private ?Menu $menu = null;*/

    #[Groups(['page:read', 'page:write'])]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $type = null;

    public function getDefaultType(): int
    {
        return self::PAGE_TYPE;
    }

    public function getDefaultStatus(): ?int
    {
        return self::STATUS_ACTIVE;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(?string $preview): static
    {
        $this->preview = $preview;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }


    public function getPublicAt(): ?\DateTimeImmutable
    {
        return $this->publicAt;
    }

    public function setPublicAt(?\DateTimeImmutable $publicAt): static
    {
        $this->publicAt = $publicAt;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function isIsPreviewOnMain(): ?bool
    {
        return $this->isPreviewOnMain;
    }

    public function setIsPreviewOnMain(bool $isPreviewOnMain): static
    {
        $this->isPreviewOnMain = $isPreviewOnMain;

        return $this;
    }


}
