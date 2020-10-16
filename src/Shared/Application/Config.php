<?php

namespace Enchainte\Shared\Application;

use InvalidArgumentException;

class Config
{
    // TODO get from params or .env
    const SIGNED_HEADERS = 'x-ms-date;host;x-ms-content-sha256';
    const ENDPOINT = 'enchainte-config.azconfig.io';
    const CREDENTIAL = 'ihs8-l9-s0:JPRPUeiXJGsAzFiW9WDc';
    const SECRET = '1UA2dijC0SIVyrPKUKG0gT0oXxkVaMrUfJuXkLr+i0c=';
    const ENVIRONMENTS = ['PROD', 'TEST'];
    const ENVIRONMENT = 'PROD';
    const GMT_DATETIME_FORMAT = "D, d M Y H:i:s T";

    private $httpClient;
    private $params;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
//        $this->requestParams();
    }

    public function params(): array
    {
        return $this->params;
    }

    private function requestParams(): void
    {
        $path = sprintf("/kv?key=SDK_*&label=%s", $this->environment(self::ENVIRONMENT));
        $headers = $this->authHeaders("GET", $path, "");
        $url = sprintf("https://%s%s", self::ENDPOINT, $path);
        $response = $this->httpClient->get($url, $headers);

        $this->params = json_decode($response, true);
    }

    private function authHeaders(string $httpVerb, string $url, string $body): array
    {
        $gmtDateTime = gmdate(self::GMT_DATETIME_FORMAT);
        $hashedContent = base64_encode(hash("sha256", $body));
        $stringToSign = sprintf("%s\n %s\n %s; %s;%s", $httpVerb, $url, $gmtDateTime, self::ENDPOINT, $hashedContent);
        $signature = base64_encode(hash_hmac("sha256", $stringToSign, base64_decode(self::SECRET)));

        return [
            'x-ms-date'=> $gmtDateTime,
            'x-ms-content-sha256' => $hashedContent,
            'Authorization' => sprintf(
                "HMAC-SHA256 Credential=%sCREDENTIAL}&SignedHeaders=%s&Signature=%s",
                self::CREDENTIAL,
                self::SIGNED_HEADERS,
                $signature),
        ];
    }

    private function environment(string $environment)
    {
        if (!in_array($environment, self::ENVIRONMENTS)) {
            throw new InvalidArgumentException("invalid environment argument");
        }
        return $environment;
    }
}