<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait NameTrait
{
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
