<?php

namespace JsonNaN\LaravelRiotLolApi;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;

class LaravelRiotLolApi
{
    protected string $domain = '';

    public function __construct()
    {
        $this->domain = config('riot-lol-api.api_domain');
    }

    /**
     * Method to get the API URL
     */
    protected function getApiUrl(string $path, string $prefix): string
    {
        $path = Str::of($path)->trim('/')->toString();

        return Str::of('https://:prefix.:domain/:path')
            ->replace(':prefix', $prefix)
            ->replace(':domain', $this->domain)
            ->replace(':path', $path)
            ->toString();
    }

    /**
     * Method to check if the Riot ID format is valid
     */
    protected function isValidRiotIdFormat(string $riotId): bool
    {
        return Str::of($riotId)->isMatch('/^.+#.+$/');
    }

    /**
     * Method to validate the Riot ID
     */
    protected function validateRiotId(string $riotId): void
    {
        if (!$this->isValidRiotIdFormat($riotId)) {
            throw new InvalidArgumentException('Invalid Riot ID format');
        }
    }

    /**
     * Method to split the Riot ID
     */
    protected function splitRiotId(string $riotId): array
    {
        $this->validateRiotId($riotId);

        return Str::of($riotId)->explode('#', 2)->toArray();
    }

    /**
     * Method to return the response depending on the decode parameter
     */
    private function returnResponse(Response $response, bool $decode): Response|Collection
    {
        return $decode ? collect($response->json()) : $response;
    }

    /**
     * Method to get player account information by Riot ID
     */
    public function getPlayerAccountByRiotId(string $riotId,
                                             string $region = 'americas',
                                             bool   $decode = true): Collection|Response
    {
        [$gameName, $tagLine] = $this->splitRiotId($riotId);

        $url = $this->getApiUrl("riot/account/v1/accounts/by-riot-id/{$gameName}/{$tagLine}", $region);

        $response = Http::riotLolApi()->get($url);

        return $this->returnResponse($response, $decode);
    }

    /**
     * Method to get player account information by PUUID
     */
    public function getPlayerAccountByPuuid(string $puuid,
                                            string $region = 'americas',
                                            bool   $decode = true): Collection|Response
    {
        $url = $this->getApiUrl("riot/account/v1/accounts/by-puuid/{$puuid}", $region);

        $response = Http::riotLolApi()->get($url);

        return $this->returnResponse($response, $decode);
    }

    /**
     * Method to get player game information by user PUUID
     */
    public function getSummonerByPuuid(string $puuid,
                                       string $region = 'la1',
                                       string $version = 'v4',
                                       bool   $decode = true): Collection|Response
    {
        $url = $this->getApiUrl("/lol/summoner/{$version}/summoners/by-puuid/{$puuid}", $region);

        $response = Http::riotLolApi()->get($url);

        return $this->returnResponse($response, $decode);
    }

    /**
     * Method to get player game information by Riot ID
     */
    public function getSummonerByRiotId(string $riotId,
                                        string $region = 'la1',
                                        string $version = 'v4',
                                        bool   $decode = true): Collection|Response
    {
        $playerAccount = $this->getPlayerAccountByRiotId($riotId);

        return $this->getSummonerByPuuid($playerAccount->get('puuid'), $region, $version, decode: $decode);
    }

    /**
     * @deprecated Use getSummonerByRiotId instead
     *
     * Method to get player game information by deprecated summoner name
     */
    public function getSummonerBySummonerName(string $summonerName,
                                              string $region = 'la1',
                                              string $version = 'v4',
                                              bool   $decode = true): Collection|Response
    {
        $url = $this->getApiUrl("/lol/summoner/{$version}/summoners/by-name/{$summonerName}", $region);
        $response = Http::riotLolApi()->get($url)->json();

        return $this->returnResponse($response, $decode);
    }
}
