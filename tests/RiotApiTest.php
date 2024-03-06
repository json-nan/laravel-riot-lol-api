<?php

use Illuminate\Support\Collection;
use JsonNaN\LaravelRiotLolApi\DTOs\AccountDto;
use JsonNaN\LaravelRiotLolApi\DTOs\LeagueDto;
use JsonNaN\LaravelRiotLolApi\DTOs\SummonerDto;
use JsonNaN\LaravelRiotLolApi\LaravelRiotLolApi;

beforeEach(function () {
    $this->api = new LaravelRiotLolApi();

    config()->set('riot-lol-api.api_key', '');
    $this->riotIdToTest = '';
    $this->puuidToTest = '';
    $this->summonerIdToTest = '';
    $this->matchIdToTest = '';
})->skip();

it('Get riot account by riot id', function () {
    $response = $this->api->getAccountByRiotId($this->riotIdToTest);
    expect($response)->toBeInstanceOf(AccountDto::class);
});

it('Get riot account by puuid', function () {
    $response = $this->api->getAccountByPuuid($this->puuidToTest);
    expect($response)->toBeInstanceOf(AccountDto::class);
});

it('Get summoner information by puuid', function () {
    $response = $this->api->getSummonerByPuuid($this->puuidToTest);
    expect($response)->toBeInstanceOf(SummonerDto::class);
});

it('Get summoner information by riot id', function () {
    $response = $this->api->getSummonerByRiotId($this->riotIdToTest);
    expect($response)->toBeInstanceOf(SummonerDto::class);
});

it('Get league entries information by summoner id', function () {
    $response = $this->api->getLeaguesInfoBySummonerId($this->summonerIdToTest);
    expect($response)->toBeInstanceOf(Collection::class)
        ->each()
        ->toBeInstanceOf(LeagueDto::class);
});

it('Get league entries information by riot id', function () {
    $response = $this->api->getLeaguesInfoByRiotId($this->riotIdToTest);
    expect($response)->toBeInstanceOf(Collection::class)
        ->each()
        ->toBeInstanceOf(LeagueDto::class);
});

it('Get match ids by puuid', function () {
    $response = $this->api->getMatchIdsByPuuid($this->puuidToTest);
    expect($response)->toBeInstanceOf(Collection::class);
});

it('Get match info by match id', function () {
    $response = $this->api->getMatchInfoByMatchId($this->matchIdToTest);
    expect($response)->toBeInstanceOf(Collection::class);
});

it('Get match timeline by match id', function () {
    $response = $this->api->getMatchTimelineByMatchId($this->matchIdToTest);
    expect($response)->toBeInstanceOf(Collection::class);
});

it('Get current game information by summoner id', function () {
    $response = $this->api->getActiveGameBySummonerId($this->summonerIdToTest);
    expect($response)->toBeInstanceOf(Collection::class);
});

it('Get current game information by puuid', function () {
    $response = $this->api->getActiveGameByPuuid($this->puuidToTest);
    expect($response)->toBeInstanceOf(Collection::class);
});

it('Get active game information by riot id v4', function () {
    $response = $this->api->getActiveGameByRiotId($this->riotIdToTest, version: 'v4');
    expect($response)->toBeInstanceOf(Collection::class);
});

it('Get active game information by riot id v5', function () {
    $response = $this->api->getActiveGameByRiotId($this->riotIdToTest, version: 'v5');
    expect($response)->toBeInstanceOf(Collection::class);
});