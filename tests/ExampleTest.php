<?php

use JsonNaN\LaravelRiotLolApi\LaravelRiotLolApi;

beforeEach(function () {
    $this->api = new LaravelRiotLolApi();
});

it('validates correct Riot ID format', function () {
    expect($this->api->isValidRiotIdFormat('JsonNaN#1234'))->toBeTrue();
});

it('validates incorrect Riot ID format', function () {
    expect($this->api->isValidRiotIdFormat('JsonNaN1234'))->toBeFalse();
});

it('invalidates empty Riot ID', function () {
    expect($this->api->isValidRiotIdFormat(''))->toBeFalse();
});