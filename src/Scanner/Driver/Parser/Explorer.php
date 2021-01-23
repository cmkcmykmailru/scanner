<?php


namespace Scanner\Driver\Parser;


interface Explorer
{
    public function setDetect($detect): void;

    public function isLeaf($found): bool;
}