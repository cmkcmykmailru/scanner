<?php

namespace Scanner\Driver\Search;

use Scanner\Scanner;

interface SettingsInstaller
{
    public function install(Scanner $scanner): void;

    public function uninstall(Scanner $scanner): void;
}