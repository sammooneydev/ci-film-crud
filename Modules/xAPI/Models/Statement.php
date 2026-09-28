<?php

namespace Modules\xAPI\Models;

class Statement
{
    public string $timestamp;

    public function __construct(
        public Actor $actor,
        public Verb $verb,
        public xAPIObject $object,
        public string $version = '1.0.3',
        ?string $timestamp = null,
        public ?array $result = null,
        public ?array $context = null
    ) {
        $this->timestamp = $timestamp ?? gmdate('Y-m-d\TH:i:s\Z');
    }

    public function toArray(): array
    {
        $data = [
            'version' => $this->version,
            'actor' => $this->actor->toArray(),
            'verb' => $this->verb->toArray(),
            'object' => $this->object->toArray(),
            'timestamp' => $this->timestamp,
        ];

        if ($this->result !== null) {
            $data['result'] = $this->result;
        }
        if ($this->context !== null) {
            $data['context'] = $this->context;
        }

        return $data;
    }

    public static function fromArray(array $json): self
    {
        return new self(
            actor: Actor::fromArray($json['actor']),
            verb: Verb::fromArray($json['verb']),
            object: xAPIObject::fromArray($json['object']),
            version: $json['version'] ?? '1.0.3',
            timestamp: $json['timestamp'] ?? null,
            result: $json['result'] ?? null,
            context: $json['context'] ?? null
        );
    }
}