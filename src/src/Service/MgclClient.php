<?php


namespace App\Service;


use DateTime;
use Symfony\Component\HttpClient\Exception\TimeoutException;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MgclClient
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getDateTime(): ?DateTime
    {
        $response = $this->client->request(
            'HEAD',
            'https://mgcl.ru/date'
        );
        $date = $response->getHeaders()['date'][0] ?? null;

        if ($date === null) {
            return null;
        }
        return new DateTime($date);
    }

    public function showlist(): array
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://mgcl.ru/showlist'
            );
            $list = json_decode($response->getContent(), true);
        } catch (HttpExceptionInterface | TimeoutException $e) {
            return [];
        }

        $list = array_map(function ($item) {
            return reset($item);
        }, $list);
        $list = array_flip($list);
        array_walk($list, function (&$item, $key) {
            $item = $key;
        });
        return $list;
    }
}