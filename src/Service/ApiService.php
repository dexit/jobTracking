<?php

namespace App\Service;

use App\Entity\JobSearchSettings;
use App\Model\ApiJob;
use DateTime;
use GuzzleHttp\Client;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;

class ApiService
{
    public function __construct(private JobSearchSettings $userApiSettings, private MistralAiService $mistralAiService, private CacheInterface $cache) {}
    public function getJoobleJobs()
    {
        $params = [
            'keywords' => $this->userApiSettings->getWhat(),
            'location' => $this->userApiSettings->getCity()->getZipCode(),
            'radius' => $this->userApiSettings->getDistance(),
            "companysearch" => "false",
            "page" => "1",
        ];

        $url = 'https://fr.jooble.org/api/' . $_ENV['JOOBLE_API'];
        $response = $this->sendRequest($url, $params, [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ], 'post');

        return array_map(function ($job) {
            $apiJob  = new ApiJob;
            $apiJob
                ->setSource('Jooble')
                ->setTitle($job['title']  ??  $apiJob->getNoInfoStr())
                ->setCompany($job['company'] ?? $apiJob->getNoInfoStr())
                ->setLocation($job['location'] ?? $apiJob->getNoInfoStr())
                ->setDescription($job['snippet']  ?? $apiJob->getNoInfoStr())
                ->setLink($link ?? '')
                ->setCreated($job['updated'])
                ->setId($job['id'])
                ->setTypeContrat($job['type'] ??  $apiJob->getNoInfoStr())
                ->setLogo('jooble.png')
            ;

            return $apiJob->getJobArray();
        }, $response['jobs']);
    }

    public function   getAdzunaJobs(): array
    {
        $maxOldDays = intval($this->userApiSettings->getMaxDaysOld()) ?? 8;

        $params = [
            'results_per_page' => 50,
            'what' => $this->userApiSettings->getWhat(),
            'where' => $this->userApiSettings->getCity()->getZipCode(),
            'what_exclude' => $this->userApiSettings->getWhatExclude(),
            'sort_by' => 'date',
            'distance' => $this->userApiSettings->getDistance(),
            'max_days_old' => $maxOldDays,
            'app_id' => $_ENV['ADZUNA_API_ID'],
            'app_key' => $_ENV['ADZUNA_API_KEY'],
        ];


        $url = 'https://api.adzuna.com/v1/api/jobs/fr/search/1';

        $response =  $this->sendRequest($url, $params);
        return array_map(
            function ($job) {
                $apiJob  = new ApiJob;
                $apiJob
                    ->setSource('Adzuna')
                    ->setTitle($job['title']  ??  $apiJob->getNoInfoStr())
                    ->setCompany($job['company']['display_name'] ?? $apiJob->getNoInfoStr())
                    ->setLocation(explode(',', $job['location']['display_name'])[0] ?? $apiJob->getNoInfoStr())
                    ->setDescription($job['description'] ?? $apiJob->getNoInfoStr())
                    ->setLink($job['redirect_url'] ?? '')
                    ->setCreated($job['created'])
                    ->setId($job['id'])
                    ->setTypeContrat($apiJob->getNoInfoStr())
                    ->setLogo('adzuna.png')

                ;

                return $apiJob->getJobArray();
            },
            $response['results']
        );
    }

    public function getFranceTravailJobs(): array
    {

        $ftMaxOlDays = [1, 3, 7, 14];

        $params = [
            'motsCles' => $this->userApiSettings->getWhat(),      // Mot clé de recherche
            'commune' => $this->userApiSettings->getCity()->getInseeCode(),     // Localisation
            'what_exclude' => $this->userApiSettings->getWhatExclude(),
            'distance' => $this->userApiSettings->getDistance(),
            'publieeDepuis' =>  $this->findClosestValue(intval($this->userApiSettings->getMaxDaysOld()) ?? 8,   $ftMaxOlDays)
        ];



        $url = 'https://api.francetravail.io/partenaire/offresdemploi/v2/offres/search';
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getFranceTravailAccessToken()
        ];


        $response =  $this->sendRequest($url, $params, $headers);

        return array_map(function ($job) {
            $apiJob  = new ApiJob;

            $link = 'https://candidat.francetravail.fr/offres/recherche/detail/' . $job['id'];

            if (isset($job['contact']['urlPostulation'])) {
                $link = $job['contact']['urlPostulation'];
            }

            $company = $apiJob->getNoInfoStr();

            if (isset($job['entreprise']['nom'])) {
                $company = $job['entreprise']['nom'];
            }
            $apiJob
                ->setSource('France travail')
                ->setTitle($job['intitule']  ??  $apiJob->getNoInfoStr())
                ->setCompany($company)
                ->setLocation(substr($job['lieuTravail']['libelle'], 4)  ?? $apiJob->getNoInfoStr())
                ->setDescription($job['description'] ?? $apiJob->getNoInfoStr())
                ->setLink($link)
                ->setCreated($job['dateCreation'])
                ->setId($job['id'])
                ->setTypeContrat($job['typeContratLibelle'] ??  $apiJob->getNoInfoStr())
                ->setLogo('france_travail.webp')

            ;

            return $apiJob->getJobArray();
        }, $response['resultats']);
    }
    private function sendRequest(string $url, array $params = [], array $headers = [], $method = 'get'): array
    {
        $client = new Client();

        try {
            $response = $client->$method($url, [
                'query' => $method === 'get' ? $params : '',
                'headers' => $headers,
                'json' => $method === 'post' ? $params : []
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return $responseData;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new \RuntimeException('Erreur lors de l\'appel à l\'API : ' . $e->getMessage());
        }
    }

    private  function getFranceTravailAccessToken(): string
    {
        $url = 'https://entreprise.francetravail.fr/connexion/oauth2/access_token?realm=partenaire';

        $clientId = $_ENV['FRANCE_TRAVAIL_API_ID'];
        $clientSecret = $_ENV['FRANCE_TRAVAIL_API_KEY'];
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
                    'scope' => 'api_offresdemploiv2 o2dsoffre'
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return $responseData['access_token']; // Retourne le token d'accès
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new \RuntimeException('Erreur lors de l\'appel à l\'API : ' . $e->getMessage());
        }
    }

    public function generateCoverLetter(string $jobDescription, string $cvFilePath): string
    {
        return $this->mistralAiService->generateCoverLetter($jobDescription, $cvFilePath);
    }

    public function getUserApiJobResults()
    {

        $response = [];

        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $normalizers = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizers]);
        // Sérialiser l'entité
        $serializedUserjobApiSettings = $serializer->normalize($this->userApiSettings, null, ['groups' => ['apiSettingsGroup']]);
        // Calcul du nombre de secondes jusqu'à minuit
        $now = new DateTime();
        $midnight = new DateTime('tomorrow midnight');
        $secondsUntilMidnight = $midnight->getTimestamp() - $now->getTimestamp();

        $cacheKey = 'user_api_' . $this->userApiSettings->getUser()->getId();

        $cachedData = $this->cache->get($cacheKey, function (ItemInterface $item) use ($secondsUntilMidnight, $response,  $serializedUserjobApiSettings) {
            $item->expiresAfter($secondsUntilMidnight);
            foreach ($this->userApiSettings->getJobApiServices() as $userJobApiService) {
                $functionName = $userJobApiService->getFunctionName();
                $apiName = $userJobApiService->getName();

                if (method_exists($this, $functionName)) {
                    $response = array_merge($response, $this->{$functionName}());
                } else {
                    // Gérer l'erreur si la méthode n'existe pas
                    $response[] = [$apiName => 'Méthode inexistante: ' . $functionName];
                }
            }

            // Retourner les paramètres et la réponse de l'API
            return [
                'params' =>    $serializedUserjobApiSettings,
                'response' => $response,
            ];
        });

        // Comparer les paramètres actuels avec ceux en cache
        if (isset($cachedData['params']) && $cachedData['params'] !== $serializedUserjobApiSettings) {

            foreach ($this->userApiSettings->getJobApiServices() as $userJobApiService) {

                $functionName = $userJobApiService->getFunctionName();
                $apiName = $userJobApiService->getName();

                if (method_exists($this, $functionName)) {

                    $response = array_merge($response, $this->{$functionName}());
                } else {
                    // Gérer l'erreur si la méthode n'existe pas
                    $response[] = [$apiName => 'Méthode inexistante: ' . $functionName];
                }

                $cachedData = [
                    'params' =>    $serializedUserjobApiSettings,
                    'response' => $response,
                ];
            }
        }

        usort($cachedData['response'], function ($jobA, $jobB) {
            if ($jobA['created'] == $jobB['created']) {
                return 0;
            }

            return ($jobA['created'] < $jobB['created']) ? 1 : -1;
        });

        $apiResponse = array_map(
            function ($job) {
                $job['created']=  $job['created']->format('d/m/y');
                return $job;
            }
        , $cachedData['response']);
        return    $apiResponse;
    }
    private function findClosestValue($value, $array)
    {
        $closest = null;
        $minDiff = PHP_INT_MAX;

        foreach ($array as $item) {
            $diff = abs($item - $value);
            if ($diff < $minDiff) {
                $minDiff = $diff;
                $closest = $item;
            }
        }

        return $closest;
    }
}
