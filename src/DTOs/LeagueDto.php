<?php

namespace JsonNaN\LaravelRiotLolApi\DTOs;

use Illuminate\Support\Collection;
use JsonNaN\LaravelRiotLolApi\Contracts\Dto;

class LeagueDto implements Dto
{

    protected string $leagueId;
    protected string $queueType;
    protected string $tier;
    protected string $rank;
    protected string $summonerId;
    protected string $summonerName;
    protected int $leaguePoints;
    protected int $wins;
    protected int $losses;
    protected bool $veteran;
    protected bool $inactive;
    protected bool $freshBlood;
    protected bool $hotStreak;

    public function __construct(Collection $data) {
        $this->leagueId = $data->get('leagueId');
        $this->queueType = $data->get('queueType');
        $this->tier = $data->get('tier');
        $this->rank = $data->get('rank');
        $this->summonerId = $data->get('summonerId');
        $this->summonerName = $data->get('summonerName');
        $this->leaguePoints = $data->get('leaguePoints');
        $this->wins = $data->get('wins');
        $this->losses = $data->get('losses');
        $this->veteran = $data->get('veteran');
        $this->inactive = $data->get('inactive');
        $this->freshBlood = $data->get('freshBlood');
        $this->hotStreak = $data->get('hotStreak');
    }

    public function getLeagueId(): string
    {
        return $this->leagueId;
    }

    public function getQueueType(): string
    {
        return $this->queueType;
    }

    public function getTier(): string
    {
        return $this->tier;
    }

    public function getRank(): string
    {
        return $this->rank;
    }

    public function getSummonerId(): string
    {
        return $this->summonerId;
    }

    public function getSummonerName(): string
    {
        return $this->summonerName;
    }

    public function getLeaguePoints(): int
    {
        return $this->leaguePoints;
    }

    public function getWins(): int
    {
        return $this->wins;
    }

    public function getLosses(): int
    {
        return $this->losses;
    }

    public function isVeteran(): bool
    {
        return $this->veteran;
    }

    public function isInactive(): bool
    {
        return $this->inactive;
    }

    public function isFreshBlood(): bool
    {
        return $this->freshBlood;
    }

    public function isHotStreak(): bool
    {
        return $this->hotStreak;
    }

    public function toArray(): array
    {
        return [
            'leagueId' => $this->leagueId,
            'queueType' => $this->queueType,
            'tier' => $this->tier,
            'rank' => $this->rank,
            'summonerId' => $this->summonerId,
            'summonerName' => $this->summonerName,
            'leaguePoints' => $this->leaguePoints,
            'wins' => $this->wins,
            'losses' => $this->losses,
            'veteran' => $this->veteran,
            'inactive' => $this->inactive,
            'freshBlood' => $this->freshBlood,
            'hotStreak' => $this->hotStreak,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toCollection(): Collection
    {
        return new Collection($this->toArray());
    }

}