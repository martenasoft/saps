<?php

namespace App\Exceptions;

use ApiPlatform\Metadata\ErrorResource;
use ApiPlatform\Metadata\Exception\ProblemExceptionInterface;

#[ErrorResource]
class ApiErrorException extends \Exception implements ProblemExceptionInterface
{
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
       private array $data = []
    )
    {
        parent::__construct($message, $code, $previous);
    }

    public function getType(): string
    {
        return $this->data['type'] ?? 'error';
    }

    public function getTitle(): ?string
    {
        return $this->data['title'] ?? 'Error';
    }

    public function getStatus(): ?int
    {
        return $this->data['status'] ?? $this->getCode();
    }

    public function getDetail(): ?string
    {
        return$this->data['detail'] ?? $this->getMessage();
    }

    public function getInstance(): ?string
    {
         return $this->data['instance'] ?? '';
    }
}
