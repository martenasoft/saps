<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\CreateMenuController;
use App\Entity\Interfaces\ChangeDataDayInterface;
use App\Entity\Interfaces\DefaultStatusInterface;
use App\Entity\Interfaces\NameInterface;
use App\Entity\Interfaces\NodeInterface;
use App\Entity\Interfaces\SlugIntrface;
use App\Entity\Interfaces\StatusInterface;
use App\Entity\Interfaces\TypeInterface;
use App\Entity\Traits\ChangeDataDayTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\NodeTrait;
use App\Entity\Traits\SlugTrait;
use App\Entity\Traits\StatusTrait;
use App\Entity\Traits\TypeTrait;
use App\Repository\MenuRepository;
use App\State\MenuStateProcessor;
use App\State\MenuStateProvider;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    normalizationContext: ['groups' => ['menu:read']],
    denormalizationContext: ['groups' => ['menu:write']],
    validationContext: ['groups' => ['menu:write']],
    order: ['tree' => 'ASC', 'lft' => 'ASC'],
    processor: MenuStateProcessor::class
)]
#[ApiResource(
    operations: [
        new Patch(uriTemplate: '/menus/move-up/{id}', processor: MenuStateProcessor::class),
        new Patch(uriTemplate: '/menus/move-down/{id}', processor: MenuStateProcessor::class)
    ],
    normalizationContext: ['groups' => ['menu:move_up', 'menu:move_down']],
    denormalizationContext: ['groups' => ['menu:move_up', 'menu:move_down']],

)]
#[ApiFilter(RangeFilter::class, properties: ['lft', 'rgt', 'tree', 'lvl'])]
#[ApiFilter(NumericFilter::class, properties: ['lft', 'rgt', 'tree', 'lvl'])]


#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[UniqueEntity(fields: ["slug", "tree"], message: 'There is already a page with this name')]
#[ORM\UniqueConstraint(fields: ["slug", "tree"], name: 'UNIQ_IDENTIFIER_MENU_SLUG_TREEE',)]

#[ORM\Index(columns: ["lft"], name: "lft")]
#[ORM\Index(columns: ["lft", "rgt"], name: "lft_rgt")]
#[ORM\Index(columns: ["lft", "rgt", "rgt"], name: "id_lft_rgt")]
#[ORM\Index(columns: ["is_bottom_menu"], name: "is_bottom_menu")]
#[ORM\Index(columns: ["is_left_menu"], name: "is_left_menu")]
#[ORM\Index(columns: ["is_top_menu"], name: "is_top_menu")]
class Menu implements

    NameInterface,
    DefaultStatusInterface,
    SlugIntrface,
    NodeInterface,
    StatusInterface,
    ChangeDataDayInterface,
    TypeInterface
{
    use
        NameTrait,
        SlugTrait,
        NodeTrait,
        StatusTrait,
        ChangeDataDayTrait,
        TypeTrait
        ;

    public const ITEM_MENU_TYPE = 1;
    public const EXTERNAL_PAGE_TYPE = 2;

    public const TYPES = [
        self::ITEM_MENU_TYPE => 'Item menu',
        self::EXTERNAL_PAGE_TYPE => 'External page'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['menu:read', 'menu:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['menu:read', 'menu:write'])]
    #[Assert\NotBlank(groups: ['menu:read', 'menu:write'])]
    private ?string $name = null;
    #[Groups(['menu:read', 'menu:write'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $path = null;
    #[Groups(['menu:read', 'menu:write'])]
    #[ORM\Column]
    private ?bool $isBottomMenu = false;
    #[Groups(['menu:read', 'menu:write'])]
    #[ORM\Column]
    private ?bool $isLeftMenu = false;
    #[Groups(['menu:read', 'menu:write'])]
    #[ORM\Column]
    private ?bool $isTopMenu = false;
    #[Groups(['menu:read', 'menu:write'])]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $status = null;
    #[Groups(['menu:read', 'menu:write'])]
    #[ORM\Column(type: Types::SMALLINT)]
  //  #[ApiFilter(SearchFilter::class)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'enum' => self::TYPES,
            'example' => self::STATUS_ACTIVE
        ]
    )]
    private ?int $type = null;
    #[Groups(['menu:read', 'menu:write'])]
    private array $types = self::TYPES;
 //   #[ApiFilter(SearchFilter::class)]
    #[Groups(['menu:read', 'menu:write'])]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['menu:read', 'menu:write'])]
    private ?string $slug = null;

    // node interface
    #[Groups(['menu:read'])]
    #[ORM\Column(type: 'integer')]
    private ?int $lft = null;
    #[Groups(['menu:read'])]
    #[ORM\Column(type: 'integer')]
    private ?int $rgt = null;
    #[Groups(['menu:read'])]
    #[ORM\Column(type: 'integer')]
    private ?int $lvl = null;
    #[Groups(['menu:read'])]
    #[ORM\Column(type: 'integer')]
    private ?int $tree = null;
    #[Groups([ 'menu:read', 'menu:write'])]
    #[ORM\Column(type: 'integer')]
    private ?int $parentId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;
        return $this;
    }
    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function isIsBottomMenu(): ?bool
    {
        return $this->isBottomMenu;
    }

    public function setIsBottomMenu(bool $isBottomMenu): static
    {
        $this->isBottomMenu = $isBottomMenu;

        return $this;
    }

    public function isIsLeftMenu(): ?bool
    {
        return $this->isLeftMenu;
    }

    public function setIsLeftMenu(bool $isLeftMenu): static
    {
        $this->isLeftMenu = $isLeftMenu;

        return $this;
    }

    public function isIsTopMenu(): ?bool
    {
        return $this->isTopMenu;
    }

    public function setIsTopMenu(bool $isTopMenu): static
    {
        $this->isTopMenu = $isTopMenu;

        return $this;
    }

    public function getDefaultStatus(): ?int
    {
        return self::STATUS_ACTIVE;
    }

    public function getDefaultType(): int
    {
        return self::ITEM_MENU_TYPE;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function setTypes(array $types): Menu
    {
        $this->types = $types;
        return $this;
    }

    // node interface

    public function getLft(): ?int
    {
        return $this->lft;
    }

    public function setLft(?int $lft): self
    {
        $this->lft = $lft;
        return $this;
    }

    public function getRgt(): ?int
    {
        return $this->rgt;
    }

    public function setRgt(?int $rgt): self
    {
        $this->rgt = $rgt;
        return $this;
    }

    public function getLvl(): ?int
    {
        return $this->lvl;
    }

    public function setLvl(?int $lvl): self
    {
        $this->lvl = $lvl;
        return $this;
    }

    public function getTree(): ?int
    {
        return $this->tree;
    }

    public function setTree(?int $tree): self
    {
        $this->tree = $tree;
        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): self
    {
        $this->parentId = $parentId;
        return $this;
    }
}
