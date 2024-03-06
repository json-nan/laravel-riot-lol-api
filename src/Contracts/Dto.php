<?php

namespace JsonNaN\LaravelRiotLolApi\Contracts;

use Illuminate\Support\Collection;

interface Dto
{
    public function toArray(): array;

    public function toJson(): string;

    public function toCollection(): Collection;
}