<?php

namespace Modules\xAPI\Models;

class Definition
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public ?string $type = null,
        public ?array $extensions = null
    ) {
    }

    public static function fromArray(array $json): self
    {
        return new self(
            name: $json['name']['en-GB'] ?? '',
            description: $json['description']['en-GB'] ?? null,
            type: $json['type'] ?? null,
            extensions: $json['extensions'] ?? null
        );
    }

    public function toArray(): array
    {
        $data = [
            'name' => ['en-GB' => $this->name],
        ];

        if ($this->type !== null) {
            $data['type'] = $this->type;
        }
        if ($this->description !== null) {
            $data['description'] = ['en-GB' => $this->description];
        }
        if (!empty($this->extensions)) {
            $data['extensions'] = $this->extensions;
        }

        return $data;
    }
}