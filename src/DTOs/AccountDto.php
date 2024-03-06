<?php

namespace JsonNaN\LaravelRiotLolApi\DTOs;

use Illuminate\Support\Collection;
use JsonNaN\LaravelRiotLolApi\Contracts\Dto;

class AccountDto implements Dto
{
    protected string $puuid;
    protected string $gameName;
    protected string $tagLine;
    public function __construct(
        Collection $data
    )
    {
        $this->puuid = $data->get('puuid');
        $this->gameName = $data->get('gameName', 'N/A');
        $this->tagLine = $data->get('tagLine', 'N/A');
    }

    public function getPuuid(): string
    {
        return $this->puuid;
    }

    public function getGameName(): string
    {
        return $this->gameName;
    }

    public function getTagLine(): string
    {
        return $this->tagLine;
    }

    public function getRiotId(): string
    {
        return $this->gameName . '#' . $this->tagLine;
    }

    public function toArray(): array
    {
        return [
            'puuid' => $this->puuid,
            'gameName' => $this->gameName,
            'tagLine' => $this->tagLine,
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