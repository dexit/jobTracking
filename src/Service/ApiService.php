<?php

namespace App\Service;

use App\Entity\User;
use GuzzleHttp\Client;


class ApiService
{
    public function   getAdzunaJobs(array $params, $country = 'fr')
    {

        $url = 'https://api.adzuna.com/v1/api/jobs/' . $country . '/search/1';

        return $this->sendRequest($url, $params);
    }

    public function getFranceTravailJobs($params)
    {

        $url = 'https://api.francetravail.io/partenaire/offresdemploi/v2/offres/search';
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getFranceTravailAccessToken()
        ];

        return $this->sendRequest($url, $params, $headers);
    }

    private function sendRequest(string $url, array $params = [], array $headers = [])
    {
        $client = new Client();

        try {
            $response = $client->get($url, [
                'query' => $params,
                'headers'=> $headers
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return $responseData;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new \RuntimeException('Erreur lors de l\'appel à l\'API : ' . $e->getMessage());
        }
    }

    private  function getFranceTravailAccessToken()
    {
        $url = 'https://entreprise.francetravail.fr/connexion/oauth2/access_token?realm=partenaire';
        $clientId = $_ENV('FRANCE_TRAVAIL_API_ID');
        $clientSecret = $_ENV('FRANCE_TRAVAIL_API_KEY');;
        $grantType = 'client_credentials';

        $client = new Client();

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'grant_type' => $grantType,
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return $responseData['access_token']; // Retourne le token d'accès
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new \RuntimeException('Erreur lors de l\'appel à l\'API : ' . $e->getMessage());
        }
    }
}