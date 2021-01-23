<?php


namespace Scanner\Driver\Parser;


interface Parser
{
    public function parese($source): array;
}