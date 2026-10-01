<?php

namespace Modules\xAPI\Models;

class Score
{
    public function __construct(
        public ?float $scaled = null,
        public ?float $raw = null,
        public ?float $min = null,
        public ?float $max = null
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'scaled' => $this->scaled,
            'raw' => $this->raw,
            'min' => $this->min,
            'max' => $this->max,
        ], fn($val) => $val !== null);
    }
}