#!/usr/bin/env php
<?php

require './vendor/autoload.php';

use ThunderbirdDeveloper\Bifrost\ScriptCrafter;

$cli = new ScriptCrafter();
$argv = array_slice($argv, 1);
$cli->run($argv);
