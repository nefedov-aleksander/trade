<?php

namespace Trade\Api\Http\Auth;


interface SignatureInterface
{
    public function getHash() : string;
}