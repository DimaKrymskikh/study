<?php 

use base\App;

require_once __DIR__ . '/vendor/autoload.php';

$config = require_once __DIR__ . '/protected/config/web.php';

(new App($config))->run();
