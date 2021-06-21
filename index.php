<?php
require_once 'vendor/autoload.php';
use Kyy\Tools\Security;

$data = [
    '1','2'
];
echo Security::getInstance()->encrypt(json_encode($data));
