<?php

class UserInfo
{
    public $username = '';
    public $userAddress = '';
    public $age = 0;
}

$callback = function (UserInfo $userInfo) {
    printf('user info: username = %s, address = %s, age = %d', $userInfo->username, $userInfo->userAddress, $userInfo->age);
    echo PHP_EOL;
    $userInfo->age++;

    return $userInfo;
};

$hello = 'hello name';
$usernameMiddleware = function (UserInfo $userInfo, Closure $call) use ($hello) {
    echo 'before set username = ' . $userInfo->username, PHP_EOL;
    $userInfo->username = $hello;
    $resp = $call($userInfo);
    echo 'after set username = ' . $userInfo->username, PHP_EOL;

    return $resp;
};

$addressMiddleware = function (UserInfo $userInfo, Closure $call) {
    echo 'before set address = ' . $userInfo->userAddress, PHP_EOL;
    $userInfo->userAddress = 'XinYang';
    $resp = $call($userInfo);
    echo 'after set address = ' . $userInfo->userAddress, PHP_EOL;

    return $resp;
};

$ageMiddleware = function (UserInfo $userInfo, Closure $call) {
    echo 'before set age = ' . $userInfo->age, PHP_EOL;
    $userInfo->age = 18;
    $resp = $call($userInfo);
    echo 'after set age = ' . $userInfo->age, PHP_EOL;

    return $resp;
};

$ms = [
    $usernameMiddleware,
    $addressMiddleware,
    $ageMiddleware,
];

$call = $callback;

foreach ($ms as $middleware) {
    $call = function (UserInfo $userInfo) use ($middleware, $call) {
        $resp = $middleware($userInfo, $call);
        return $resp;
    };
}

$data = $call(new UserInfo());
print_r($data);

// outputs

//before set age = 0
//before set address =
//before set username =
//user info: username = Cyy, address = XinYang, age = 18
//after set username = Cyy
//after set address = XinYang
//after set age = 19
//UserInfo Object
//(
//    [username] => Cyy
//    [userAddress] => XinYang
//    [age] => 19
//)