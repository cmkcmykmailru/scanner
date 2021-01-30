<?php

namespace Scanner\Driver\File;

use Scanner\Driver\Driver;
use Scanner\Driver\Search\AbstractSearchSettings;

/**
 * Class FilesSearchSettings
 * @package Scanner\Driver\File
 */
class FilesSearchSettings extends AbstractSearchSettings
{
    /**
     * 'ALL' | 'FILE' | 'DIRECTORY'
     * 'source' - initial path
     * @return string[]|null
     */
    public function getSearchCriteria(): ?array
    {
        if (empty($this->searchCriteria)) {
            return ['ALL', 'source' => '/'];
        }
        return $this->searchCriteria;
    }

    public function getFilter(): ?array
    {
        if (empty($this->filterSettings)) {
            return [];
        }
        return $this->filterSettings;
    }

    public function getStrategy(): ?array
    {
        if (empty($this->strategySettings)) {
            return [];
        }
        return $this->strategySettings;
    }

    public function getHandle(): ?array
    {
        if (empty($this->handleSettings)) {
            return [];
        }
        return $this->handleSettings;
    }

    public function getSupport(): ?array
    {
        if (empty($this->supportSettings)) {
            return [];
        }
        return $this->supportSettings;
    }

    public function getDriver(): Driver
    {
        return new FileDriver(new FileFactory());
    }
}