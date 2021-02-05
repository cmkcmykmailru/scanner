<?php

namespace Scanner\Filter;

interface Filter
{
    public function filter($found): bool;

    public function setConfiguration($config): void;
}