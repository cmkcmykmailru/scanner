<?php

namespace Scanner\Driver\Search;

abstract class AbstractSearchSettings implements SearchSettings
{
    protected array $searchCriteria;
    protected array $filterSettings;
    protected array $strategySettings;
    protected array $supportSettings;

    public function search(array $criteria): SearchSettings
    {
        $this->searchCriteria = $criteria;
        return $this;
    }

    public function filter(array $parameters): SearchSettings
    {
        $this->filterSettings = $parameters;
        return $this;
    }

    public function strategy(array $parameters): SearchSettings
    {
        $this->strategySettings = $parameters;
        return $this;
    }

    public function support(array $parameters): SearchSettings
    {
        $this->supportSettings = $parameters;
        return $this;
    }

}