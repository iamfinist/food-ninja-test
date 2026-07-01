<?php

namespace App\DTO;

final readonly class LinkDTO
{
    public function __construct(
        public int $id,
        public string $originalUrl,
    ) {}

    /**
     * @param  array{id: int, original_url: string}  $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data['id'], $data['original_url']);
    }

    /**
     * @return array{id: int, original_url: string}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'original_url' => $this->originalUrl,
        ];
    }
}
