<?php

namespace Trade\Api\Http\Request;

enum HttpRequestType: string
{
    case Get = 'GET';
    case Post = 'POST';
}