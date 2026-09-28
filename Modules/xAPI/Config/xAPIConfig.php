<?php

namespace Modules\xAPI\Config;

use CodeIgniter\Config\BaseConfig;

class xAPIConfig extends BaseConfig
{
    public string $endpoint;
    public string $authUser;
    public bool $forceAnonStats = false;
    public string $authPass;
    public string $version;

    public function __construct()
    {
        parent::__construct();

        $this->endpoint = env('xapi.endpoint', 'https://sssclrs.uk/data/xAPI/statements/');
        $this->authUser = env('xapi.authUser', '');
        $this->authPass = env('xapi.authPass', '');
        $this->version = env('xapi.version', '1.0.3');
        $this->forceAnonStats = filter_var(env('xapi.forceAnonStats', false), FILTER_VALIDATE_BOOLEAN);
    }
}