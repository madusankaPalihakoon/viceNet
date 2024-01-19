<?php

require_once __DIR__.'/vendor/autoload.php';
use Ramsey\Uuid\Uuid;

$uuid = Uuid::uuid4()->toString();
echo $uuid;

if(uuid::isValid($uuid)){
    echo '<br>';
    echo 'is valid '.$uuid;
}
