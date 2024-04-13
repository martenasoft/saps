<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\CreateMenuController;
use App\Entity\Interfaces\ChangeDataDayInterface;
use App\Entity\Interfaces\DefaultStatusInterface;
use App\Entity\Interfaces\IdInterface;
use App\Entity\Interfaces\NameInterface;
use App\Entity\Interfaces\NodeInterface;
use App\Entity\Interfaces\SlugIntrface;
use App\Entity\Interfaces\StatusInterface;
use App\Entity\Interfaces\TypeInterface;
use App\Entity\Traits\ChangeDataDayTrait;
use App\Entity\Traits\IdTrait;
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
use PhpParser\Node\Param;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(

    normalizationContext: ['groups' => ['menu:read']],
    denormalizationContext: ['groups' => ['menu:write']],

    processor: MenuStateProcessor::class
)]
#[ApiFilter(RangeFilter::class, properties: ['lft', 'rgt', 'tree', 'lvl'])]


#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ORM\UniqueConstraint(fields: ["slug", "tree"])]
#[ORM\Index(name: "lft", columns: ["lft"])]
#[ORM\Index(name: "lft_rgt", columns: ["lft", "rgt"])]
#[ORM\Index(name: "id_lft_rgt", columns: ["lft", "rgt", "rgt"])]
#[ORM\Index(name: "is_bottom_menu", columns: ["is_bottom_menu"])]
#[ORM\Index(name: "is_left_menu", columns: ["is_left_menu"])]
#[ORM\Index(name: "is_top_menu", columns: ["is_top_menu"])]
class Menu implements
  //  IdInterface,
    NameInterface,
    DefaultStatusInterface,
    SlugIntrface,
    NodeInterface,
    StatusInterface,
    ChangeDataDayInterface,
    TypeInterface
{
    use
      //  IdTrait,
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
    private ?string $slug = null;

    // node interface
//    #[ApiFilter(RangeFilter::class)]
    #[ORM\Column(type: 'integer')]
    private ?int $lft = null;
 //   #[ApiFilter(RangeFilter::class)]
    #[ORM\Column(type: 'integer')]
    private ?int $rgt = null;
  //  #[ApiFilter(RangeFilter::class)]
    #[ORM\Column(type: 'integer')]
    private ?int $lvl = null;
   // #[ApiFilter(RangeFilter::class)]
    #[ORM\Column(type: 'integer')]
    private ?int $tree = null;

    #[Groups(['menu:read', 'menu:write'])]
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
