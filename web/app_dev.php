<?php

putenv('APP_ENV=dev');
$app = require __DIR__.'/../app/app.php';
$app->run();
