<?php

namespace Modules\xAPI\Lrs;

use Modules\xAPI\Models\Statement;
use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\xAPI\Models\Actor;

class ManualLrs
{
    protected $config;
    protected $client;
    protected bool $isInitialized = false;

    public function __construct()
    {
        $this->config = new \Modules\xAPI\Config\xAPIConfig();
        $this->initializeClient();
    }

    protected function initializeClient(): void
    {
        $this->client = Services::curlrequest([
            'baseURI' => $this->config->endpoint,
            'auth' => [$this->config->authUser, $this->config->authPass],
            'headers' => $this->getHeaders(),
            'http_errors' => false,
            'verify' => false,
        ]);

        $this->isInitialized = true;
    }

    public function ensureInitialised(): bool
    {
        return $this->isInitialized;
    }

    public function isForcingAnonStats(): bool
    {
        return $this->config->forceAnonStats;
    }

    public function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'X-Experience-API-Version' => $this->config->version ?? '1.0.3',
        ];
    }

    public function sendStatement(Statement $statement): ?ResponseInterface
    {
        try {
            return $this->client->post('', [
                'json' => $statement->toArray()
            ]);
        } catch (\Exception $e) {
            throw new \RuntimeException('cURL Error: ' . $e->getMessage());
        }
    }

    public function sendStatements(array $statements): ?ResponseInterface
    {
        try {
            $data = array_map(fn(Statement $stmt) => $stmt->toArray(), $statements);

            return $this->client->post('', [
                'json' => $data
            ]);
        } catch (\Exception $e) {
            throw new \RuntimeException('cURL Error: ' . $e->getMessage());
        }
    }

    // NOT FINISHED YET!
    // public function queryAndParseStatements(array $queryParameters): ?array
    // {
    //     $response = $this->queryStatements($queryParameters);

    //     if (!$response || $response->getStatusCode() !== 200) {
    //         return null;
    //     }

    //     $body = json_decode($response->getBody(), true);

    //     // Standard LRS responses from Learning Locker return statements inside a 'statements' key
    //     $statementsData = $body['statements'] ?? $body;

    //     if (!is_array($statementsData)) {
    //         return null;
    //     }

    //     return array_map(function ($stmtData) {
    //         return Statement::fromArray($stmtData);
    //     }, $statementsData);
    // }

    public function getActorDetails(): Actor
    {
        $name = 'A learner';
        $email = 'learner@sssc.uk.com';

        return new Actor(name: $name, mbox: $email);
    }
}