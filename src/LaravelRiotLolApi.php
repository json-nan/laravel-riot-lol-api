<?php

namespace JsonNaN\LaravelRiotLolApi;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;
use JsonNaN\LaravelRiotLolApi\DTOs\AccountDto;
use JsonNaN\LaravelRiotLolApi\DTOs\LeagueDto;
use JsonNaN\LaravelRiotLolApi\DTOs\SummonerDto;

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
     * Method to get player account information by Riot ID
     * @throws RequestException
     */
    public function getAccountByRiotId(string $riotId, string $region = 'americas'): AccountDto
    {
        [$gameName, $tagLine] = $this->splitRiotId($riotId);

        $url = $this->getApiUrl("riot/account/v1/accounts/by-riot-id/$gameName/$tagLine", $region);

        $response = Http::riotLolApi()->get($url);

        if ($response->failed()) {
            $response->throw();
        }

        return new AccountDto(collect($response->json()));
    }

    /**
     * Method to get player account information by PUUID
     * @throws RequestException
     */
    public function getAccountByPuuid(string $puuid, string $region = 'americas'): AccountDto
    {
        $url = $this->getApiUrl("riot/account/v1/accounts/by-puuid/$puuid", $region);

        $response = Http::riotLolApi()->get($url);

        if ($response->failed()) {
            $response->throw();
        }

        return new AccountDto(collect($response->json()));
    }

    /**
     * Method to get player game information by user PUUID
     * @throws RequestException
     */
    public function getSummonerByPuuid(string $puuid, string $platform = 'la1', string $version = 'v4'): SummonerDto
    {
        $url = $this->getApiUrl("/lol/summoner/$version/summoners/by-puuid/$puuid", $platform);

        $response = Http::riotLolApi()->get($url);

        if ($response->failed()) {
            $response->throw();
        }

        return new SummonerDto(collect($response->json()));
    }

    /**
     * Method to get player game information by Riot ID
     * @throws RequestException
     */
    public function getSummonerByRiotId(string $riotId, string $platform = 'la1', string $version = 'v4'): SummonerDto
    {
        $playerAccount = $this->getAccountByRiotId($riotId);

        return $this->getSummonerByPuuid($playerAccount->getPuuid(), $platform, $version);
    }

    /**
     * @throws RequestException
     * @deprecated Use getSummonerByRiotId instead
     *
     * Method to get player game information by deprecated summoner name
     */
    public function getSummonerBySummonerName(string $summonerName, string $platform = 'la1', string $version = 'v4')
    {
        $url = $this->getApiUrl("/lol/summoner/$version/summoners/by-name/$summonerName", $platform);
        $response = Http::riotLolApi()->get($url);

        if ($response->failed()) {
            $response->throw();
        }

        return new SummonerDto(collect($response->json()));
    }

    /**
     * Method to get player game leagues information by summoner ID
     * Ranked Solo/Duo, Flex 5v5, etc...
     * @throws RequestException
     */
    public function getLeaguesInfoBySummonerId(string $summonerId, string $platform = 'la1', string $version = 'v4'): Collection
    {
        $url = $this->getApiUrl("/lol/league/$version/entries/by-summoner/$summonerId", $platform);
        $response = Http::riotLolApi()->get($url);

        if ($response->failed()) {
            $response->throw();
        }

        return collect($response->json())->map(function ($data) {
            return new LeagueDto(collect($data));
        });
    }

    /**
     * Method to get player game leagues information by Riot ID
     * Ranked Solo/Duo, Flex 5v5, etc...
     *
     * This method makes three requests to the Riot API
     * @throws RequestException
     */
    public function getLeaguesInfoByRiotId(string $riotId, string $platform = 'la1', string $version = 'v4'): Collection
    {
        $playerAccount = $this->getAccountByRiotId($riotId);

        $summoner = $this->getSummonerByPuuid($playerAccount->getPuuid(), $platform, $version);

        return $this->getLeaguesInfoBySummonerId($summoner->getId(), $platform, $version);
    }

    /**
     * Method to get match IDs by user PUUID
     *
     * @param array $params Optional parameters
     * @throws RequestException
     */

    public function getMatchIdsByPuuid(string $puuid, array $params = [], string $region = 'americas', string $version = 'v5'): Collection
    {
        $url = $this->getApiUrl("/lol/match/$version/matches/by-puuid/$puuid/ids", $region);
        $response = Http::riotLolApi()->get($url, $params);

        if ($response->failed()) {
            $response->throw();
        }

        return collect($response->json());
    }

    /**
     * Method to get match IDs by Riot ID
     * @throws RequestException
     */
    public function getMatchInfoByMatchId(string $matchId, string $region = 'americas', string $version = 'v5'): Collection
    {
        $url = $this->getApiUrl("/lol/match/$version/matches/$matchId", $region);
        $response = Http::riotLolApi()->get($url);

        if ($response->failed()) {
            $response->throw();
        }

        return collect($response->json());
    }

    /**
     * Method to get match timeline by match ID
     * @throws RequestException
     */
    public function getMatchTimelineByMatchId(string $matchId,
                                              string $region = 'americas',
                                              string $version = 'v5'): Collection
    {
        $url = $this->getApiUrl("/lol/match/$version/matches/$matchId/timeline", $region);
        $response = Http::riotLolApi()->get($url);

        if ($response->failed()) {
            $response->throw();
        }

        return collect($response->json());
    }

    /**
     * Method to get current active game by summoner ID
     * @throws RequestException
     */
    public function getActiveGameBySummonerId(string $summonerId,
                                              string $platform = 'la1',
                                              string $version = 'v4'): Collection
    {
        $url = $this->getApiUrl("/lol/spectator/$version/active-games/by-summoner/$summonerId", $platform);
        $response = Http::riotLolApi()->get($url);

        if ($response->failed()) {
            $response->throw();
        }

        return collect($response->json());
    }

    /**
     * Method to get current active game by PUUID
     * @throws RequestException
     */

    public function getActiveGameByPuuid(string $puuid,
                                         string $platform = 'la1',
                                         string $version = 'v5'): Collection
    {
        $url = $this->getApiUrl("/lol/spectator/$version/active-games/by-summoner/$puuid", $platform);
        $response = Http::riotLolApi()->get($url);

        if ($response->failed()) {
            $response->throw();
        }

        return collect($response->json());
    }

    /**
     * Method to get current active game by Riot ID
     * This method makes two requests to the Riot API if the version is 'v5'
     * This method makes three requests to the Riot API if the version is 'v4'
     * @throws RequestException
     */

    public function getActiveGameByRiotId(string $riotId,
                                          string $platform = 'la1',
                                          string $version = 'v5'): Collection
    {
        $playerAccount = $this->getAccountByRiotId($riotId);

        if ($version === 'v5') {
            return $this->getActiveGameByPuuid($playerAccount->getPuuid(), $platform, $version);
        }

        $summoner = $this->getSummonerByPuuid($playerAccount->getPuuid(), $platform);

        return $this->getActiveGameBySummonerId($summoner->getId(), $platform, $version);
    }
}
