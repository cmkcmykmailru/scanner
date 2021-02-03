<?php

namespace Scanner\Filter;

interface Filter
{
    public function filter($found): bool;
}