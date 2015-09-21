<?php

$provider = require __DIR__ . '/provider.php';

unset($_SESSION['token'], $_SESSION['state'],$_SESSION['username'],$_SESSION['expiredTime']);

//header('Location: /');
print_r($_SESSION);