<?php

namespace Scanner\Driver\Search;

use Scanner\Driver\Driver;

interface SearchSettings
{
    public function search(array $criteria): SearchSettings;

    public function filter(array $parameters): SearchSettings;

    public function strategy(array $parameters): SearchSettings;

    public function support(array $parameters): SearchSettings;

    public function getSearchCriteria(): ?array;

    public function getFilter(): ?array;

    public function getStrategy(): ?array;

    public function getSupport(): ?array;

    public function getDriver(): Driver;
}