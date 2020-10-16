<?php

namespace Enchainte\Shared\Infrastructure\Blockchain;

use Enchainte\Shared\Application\BlockchainClient;
use Enchainte\Shared\Application\Config;
use Web3\Contract;

class Web3 implements BlockchainClient
{
    const PROVIDER_HTTP_PARAM = "SDK_HTTP_PROVIDER";
    const CONTRACT_ABI = "SDK_CONTRACT_ABI";
    const CONTRACT_ADDRESS = "SDK_CONTRACT_ADDRESS";

    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function validateRoot(array $root): bool
    {
        $contract = new Contract($this->config[self::PROVIDER_HTTP_PARAM], $this->config[self::CONTRACT_ABI]);

        // TODO add callback
        $contract->at(self::CONTRACT_ADDRESS)->call("getCheckpoint", '0x' . $root);

    }
}