<?php


namespace App\Service;


use DateTime;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DateRetriever
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCurrentDateTime(): ?DateTime
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
}