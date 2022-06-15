<?php


namespace Trade\Api\Http\Auth;


class SignatureSha256 implements SignatureInterface
{
    public readonly string $secret;

    public readonly string $method;

    public readonly array $payload;

    public function __construct(string $secret, string $method, array $payload)
    {

        $this->secret = $secret;
        $this->method = $method;
        $this->payload = $payload;
    }

    public function getHash(): string
    {
        $payload = json_encode($this->payload);

        return hash_hmac('sha256', $this->method . $payload, $this->secret);
    }

    public function __toString()
    {
        return $this->getHash();
    }
}