<?php

namespace Enchainte\Shared\Application;


interface HttpClient
{
    // TODO change return type
    public function post(string $url, array $headers, string $data);

    public function get(string $url, array $headers): array;
}