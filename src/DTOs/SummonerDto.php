<?php

namespace JsonNaN\LaravelRiotLolApi\DTOs;

use Illuminate\Support\Collection;
use JsonNaN\LaravelRiotLolApi\Contracts\Dto;

class SummonerDto implements Dto
{
    protected string $accountId;
    protected int $profileIconId;
    protected int $revisionDate;
    protected string $name;
    protected string $id;
    protected string $puuid;
    protected int $summonerLevel;

    public function __construct(Collection $data)
    {
        $this->accountId = $data->get('accountId');
        $this->profileIconId = $data->get('profileIconId');
        $this->revisionDate = $data->get('revisionDate');
        $this->name = $data->get('name');
        $this->id = $data->get('id');
        $this->puuid = $data->get('puuid');
        $this->summonerLevel = $data->get('summonerLevel');
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getProfileIconId(): int
    {
        return $this->profileIconId;
    }

    public function getRevisionDate(): int
    {
        return $this->revisionDate;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPuuid(): string
    {
        return $this->puuid;
    }

    public function getSummonerLevel(): int
    {
        return $this->summonerLevel;
    }

    public function toArray(): array
    {
        return [
            'accountId' => $this->accountId,
            'profileIconId' => $this->profileIconId,
            'revisionDate' => $this->revisionDate,
            'name' => $this->name,
            'id' => $this->id,
            'puuid' => $this->puuid,
            'summonerLevel' => $this->summonerLevel,
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