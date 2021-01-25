<?php


namespace Scanner\Driver\File\System;


use Scanner\Driver\Component;

interface FileRead
{
    public function read(Component $component);
}