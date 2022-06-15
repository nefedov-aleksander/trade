<?php

namespace Trade\Api\Http\Client;


use Trade\Api\Http\Request\HttpRequest;
use Trade\Api\Http\Response\ResponseInterface;

interface HttpClientInterface
{
    public function send(HttpRequest $request): ResponseInterface;
}