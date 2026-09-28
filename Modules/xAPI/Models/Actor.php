<?php

namespace Modules\xAPI\Models;

class Actor
{
    public function __construct(
        public string $name,
        public ?string $mbox = null,
        public ?array $account = null,
        public string $objectType = 'Agent'
    ) {
        if ($this->mbox === null && $this->account === null) {
            throw new \InvalidArgumentException('An Actor must have either an mbox or an account');
        }
    }

    public static function fromArray(array $json): self
    {
        return new self(
            name: $json['name'] ?? '',
            mbox: $json['mbox'] ?? null,
            account: $json['account'] ?? null,
            objectType: $json['objectType'] ?? 'Agent'
        );
    }

    public function toArray(): array
    {
        $data = [
            'objectType' => $this->objectType,
            'name' => $this->name,
        ];

        if ($this->mbox !== null) {
            $data['mbox'] = str_starts_with($this->mbox, 'mailto:') ? $this->mbox : 'mailto:' . $this->mbox;
        }

        if ($this->account !== null) {
            $data['account'] = $this->account;
        }

        return $data;
    }
}