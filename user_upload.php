<?php

use App\Console\Commands\UploadUserCommand;

require __DIR__ . '/vendor/autoload.php';

(new UploadUserCommand())->run();
