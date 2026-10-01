<?php

namespace Modules\xAPI\Models;

class xAPIObject
{
    public function __construct(
        public string $id,
        public ?Definition $definition = null,
        public string $objectType = 'Activity'
    ) {
    }

    public static function fromArray(array $json): self
    {
        return new self(
            id: $json['id'] ?? '',
            definition: isset($json['definition']) ? Definition::fromArray($json['definition']) : null,
            objectType: $json['objectType'] ?? 'Activity'
        );
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'objectType' => $this->objectType,
        ];

        if ($this->definition !== null) {
            $data['definition'] = $this->definition->toArray();
        }

        return $data;
    }
}