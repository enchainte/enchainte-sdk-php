<?php

namespace Enchainte\Shared\Infrastructure\Blockchain;

use Enchainte\Shared\Application\BlockchainClient;
use Enchainte\Shared\Application\Config;
use Web3\Contract;

class Web3 implements BlockchainClient
{
    private const PROVIDER_HTTP_PARAM = "SDK_HTTP_PROVIDER";
    private const CONTRACT_ABI = "SDK_CONTRACT_ABI";
    private const CONTRACT_ADDRESS = "SDK_CONTRACT_ADDRESS";

    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function validateRoot(string $root): bool
    {
        $contract = new Contract(
            $this->config->params()[self::PROVIDER_HTTP_PARAM],
            $this->config->params()[self::CONTRACT_ABI]
        );

        $response = false;
        $callback = function($error, $result) use (&$response) {
            if(!empty($error) || empty($result[0])) {
                $response = false;
                return;
            }
            $response = $result[0];
            return $response;
        };

        $contract->at($this->config->params()[self::CONTRACT_ADDRESS])->call("getCheckpoint", '0x' . $root, $callback);

        return $response;
    }
}