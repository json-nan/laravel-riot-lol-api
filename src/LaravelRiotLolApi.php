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
    public function isValidRiotIdFormat(string $riotId): bool
    {
        return Str::of($riotId)->isMatch('/^.+#.+$/');
    }

    /**
     * Method to validate the Riot ID
     */
    public function validateRiotId(string $riotId): void
    {
        if (!$this->isValidRiotIdFormat($riotId)) {
            throw new InvalidArgumentException('Invalid Riot ID format');
        }
    }

    /**
     * Method to split the Riot ID
     */
    public function splitRiotId(string $riotId): array
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

        $url = $this->getApiUrl("riot/account/v1/accounts/by-riot-id/$gameName/$tagLine", $region);

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
        $url = $this->getApiUrl("riot/account/v1/accounts/by-puuid/$puuid", $region);

        $response = Http::riotLolApi()->get($url);

        return $this->returnResponse($response, $decode);
    }

    /**
     * Method to get player game information by user PUUID
     */
    public function getSummonerByPuuid(string $puuid,
                                       string $platform = 'la1',
                                       string $version = 'v4',
                                       bool   $decode = true): Collection|Response
    {
        $url = $this->getApiUrl("/lol/summoner/$version/summoners/by-puuid/$puuid", $platform);

        $response = Http::riotLolApi()->get($url);

        return $this->returnResponse($response, $decode);
    }

    /**
     * Method to get player game information by Riot ID
     */
    public function getSummonerByRiotId(string $riotId,
                                        string $platform = 'la1',
                                        string $version = 'v4',
                                        bool   $decode = true): Collection|Response
    {
        $playerAccount = $this->getPlayerAccountByRiotId($riotId);

        return $this->getSummonerByPuuid($playerAccount->get('puuid'), $platform, $version, decode: $decode);
    }

    /**
     * @deprecated Use getSummonerByRiotId instead
     *
     * Method to get player game information by deprecated summoner name
     */
    public function getSummonerBySummonerName(string $summonerName,
                                              string $platform = 'la1',
                                              string $version = 'v4',
                                              bool   $decode = true): Collection|Response
    {
        $url = $this->getApiUrl("/lol/summoner/$version/summoners/by-name/$summonerName", $platform);
        $response = Http::riotLolApi()->get($url)->json();

        return $this->returnResponse($response, $decode);
    }

    /**
     * Method to get player game leagues information by summoner ID
     * Ranked Solo/Duo, Flex 5v5, etc...
     */
    public function getLeaguesInfoBySummonerId(string $summonerId,
                                               string $platform = 'la1',
                                               string $version = 'v4',
                                               bool   $decode = true): Collection|Response
    {
        $url = $this->getApiUrl("/lol/league/$version/entries/by-summoner/$summonerId", $platform);
        $response = Http::riotLolApi()->get($url);

        return $this->returnResponse($response, $decode);
    }

    /**
     * Method to get player game leagues information by Riot ID
     * Ranked Solo/Duo, Flex 5v5, etc...
     *
     * This method makes three requests to the Riot API
     */
    public function getLeaguesInfoByRiotId(string $riotId,
                                           string $platform = 'la1',
                                           string $version = 'v4',
                                           bool   $decode = true): Collection|Response
    {
        $playerAccount = $this->getPlayerAccountByRiotId($riotId);

        $summoner = $this->getSummonerByPuuid($playerAccount->get('puuid'), $platform, $version);

        return $this->getLeaguesInfoBySummonerId($summoner->get('id'), $platform, $version, decode: $decode);
    }

    /**
     * Method to get match IDs by user PUUID
     *
     * @param array $params Optional parameters
     */

    public function getMatchIdsByPuuid(string $puuid,
                                       array  $params = [],
                                       string $region = 'americas',
                                       string $version = 'v5',
                                       bool   $decode = true): Collection|Response
    {
        $url = $this->getApiUrl("/lol/match/$version/matches/by-puuid/$puuid/ids", $region);
        $response = Http::riotLolApi()->get($url, $params);

        return $this->returnResponse($response, $decode);
    }

    /**
     * Method to get match IDs by Riot ID
     */
    public function getMatchInfoByMatchId(string $matchId,
                                          string $region = 'americas',
                                          string $version = 'v5',
                                          bool   $decode = true): Collection|Response
    {
        $url = $this->getApiUrl("/lol/match/$version/matches/$matchId", $region);
        $response = Http::riotLolApi()->get($url);

        return $this->returnResponse($response, $decode);
    }

    /**
     * Method to get match timeline by match ID
     */
    public function getMatchTimelineByMatchId(string $matchId,
                                              string $region = 'americas',
                                              string $version = 'v5',
                                              bool   $decode = true): Collection|Response
    {
        $url = $this->getApiUrl("/lol/match/$version/matches/$matchId/timeline", $region);
        $response = Http::riotLolApi()->get($url);

        return $this->returnResponse($response, $decode);
    }

    /**
     * Method to get current active game by summoner ID
     */
    public function getActiveGameBySummonerId(string $summonerId,
                                              string $platform = 'la1',
                                              string $version = 'v4',
                                              bool   $decode = true): Collection|Response
    {
        $url = $this->getApiUrl("/lol/spectator/$version/active-games/by-summoner/$summonerId", $platform);
        $response = Http::riotLolApi()->get($url);

        return $this->returnResponse($response, $decode);
    }

    public function getActiveGameByPuuid(string $puuid,
                                         string $platform = 'la1',
                                         string $version = 'v5',
                                         bool   $decode = true): Collection|Response
    {
        $url = $this->getApiUrl("/lol/spectator/$version/active-games/by-summoner/$puuid", $platform);
        $response = Http::riotLolApi()->get($url);

        return $this->returnResponse($response, $decode);
    }

    /**
     * Method to get current active game by Riot ID
     * This method makes two requests to the Riot API if the version is 'v5'
     * This method makes three requests to the Riot API if the version is 'v4'
     */

    public function getActiveGameByRiotId(string $riotId,
                                          string $platform = 'la1',
                                          string $version = 'v5',
                                          bool   $decode = true): Collection|Response
    {
        $playerAccount = $this->getPlayerAccountByRiotId($riotId);

        if($version === 'v5') {
            return $this->getActiveGameByPuuid($playerAccount->get('puuid'), $platform, $version, decode: $decode);
        }

        $summoner = $this->getSummonerByPuuid($playerAccount->get('puuid'), $platform);

        return $this->getActiveGameBySummonerId($summoner->get('id'), $platform, $version, decode: $decode);
    }
}
