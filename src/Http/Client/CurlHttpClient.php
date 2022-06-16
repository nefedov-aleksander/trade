<?php


namespace Trade\Api\Http\Client;


use Trade\Api\Generic\ListInterface;
use Trade\Api\Http\Request\HttpRequest;
use Trade\Api\Http\Response\Response;
use Trade\Api\Http\Response\ResponseInterface;

class CurlHttpClient implements HttpClientInterface
{

    public function send(HttpRequest $request): ResponseInterface
    {
        $ch = curl_init();

        $uri = $request->getUri();
        if($request->getMethod() == HttpRequestType::Post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->preparePostFields($request->getParams()));
        }
        else
        {
            $uri .= '?' . $this->prepareGetParams();
        }

        curl_setopt_array($ch, [
            CURLOPT_URL => $uri,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => $request->getHeaders()->select(fn($x) => $x->key . ': ' . $x->value)->toArray()
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return new Response($response);
    }

    private function preparePostFields(ListInterface $params) : string
    {
        $postFields = $params->select(fn($x) => [$x->key => $x->value])->toArray();

        return json_encode(array_merge(...$postFields));
    }

    private function prepareGetParams(ListInterface $params) : string
    {
        $params = $request->getParams()
            ->select(fn($x) => $x->key . '=' . $x->value)
            ->toArray();

        return implode('&', $params);
    }
}