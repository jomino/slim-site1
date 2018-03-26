<?php

set_time_limit(600);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
date_default_timezone_set('Europe/Brussels');

require __DIR__ . '/../vendor/autoload.php';

new \Core\Bootstrap();
