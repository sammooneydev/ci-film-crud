<?php

namespace Modules\xAPI\Models;

class Verb
{
    public function __construct(
        public string $id,
        public string $display
    ) {
    }

    public static function fromArray(array $json): self
    {
        return new self(
            id: $json['id'] ?? '',
            display: $json['display']['en-GB'] ?? ''
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'display' => ['en-GB' => $this->display],
        ];
    }
}