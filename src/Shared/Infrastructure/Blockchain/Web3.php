<?php


namespace Enchainte\Shared\Infrastructure\Blockchain;


use Enchainte\Shared\Application\BlockchainClient;
use Enchainte\Shared\Application\Config;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Web3 as Web3Provider;

class Web3 implements BlockchainClient
{
    const PROVIDER_URL_PARAM = "SDK_PROVIDER";
    const CONTRACT_ABI = "SDK_CONTRACT_ABI";
    const CONTRACT_ADDRESS = "SDK_CONTRACT_ADDRESS";

    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function validateRoot(array $root): bool
    {
        $web3 = new Web3Provider(new HttpProvider(new HttpRequestManager(
            $this->config->params()[self::PROVIDER_URL_PARAM],
            0.1
        )));
        $web3->getEth();

    }
}