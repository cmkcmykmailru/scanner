<?php


namespace Scanner\Driver\Search;


interface SearchSettings
{
    public function search(array $criteria): SearchSettings;

    public function filter(array $parameters): SearchSettings;

    public function strategy(array $parameters): SearchSettings;

    public function handle(array $parameters): SearchSettings;

    public function support(array $parameters): SearchSettings;

    public function visit($client): void;
}