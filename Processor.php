<?php

class Objects {
    public $id = '';
    public $name = '';

    public function toString() {
        return sprintf('id = %s, name = %s', $this->id, $this->name);
    }
}

interface Porcessor {
    public function process(Objects $object);
}

class P1 implements Porcessor {
    public function process(Objects $object)
    {
        $object->id = 'P1';
        $object->name = 'N1';
        return $object;
    }
}

class P2 implements Porcessor {
    public function process(Objects $object)
    {
        $object->id = 'P2';
        $object->name = 'N2';
        return $object;
    }
}

class P3 implements Porcessor {
    public function process(Objects $object)
    {
        $object->id = 'P3';
        $object->name = 'N3';
        return $object;
    }
}

$callback = function(Objects $obj) {
    printf('obj info:[ %s ]', $obj->toString());
    return $obj;
};
$next = $callback;

$p1Middelware = function(Objects $obj, Closure $closure) use ($next) {
    echo '-------- 1 -------', PHP_EOL;
    $obj = (new P1())->process($obj);
    echo 'before closure:', PHP_EOL;
    print_r($obj);
    $resp = $closure($obj);
    echo 'after closure ', PHP_EOL;
    print_r($obj);
    echo '-------- 1 -------', PHP_EOL;
    return $resp;
};

$p2Middelware = function(Objects $obj, Closure $closure) {
    echo '-------- 2 -------', PHP_EOL;
    $obj = (new P2())->process($obj);
    echo 'before closure:', PHP_EOL;
    print_r($obj);
    $resp = $closure($obj);
    echo 'after closure ', PHP_EOL;
    print_r($obj);
    echo '-------- 2 -------', PHP_EOL;
    return $resp;
};

$p3Middelware = function(Objects $obj, Closure $closure) {
    echo '-------- 3 -------', PHP_EOL;
    $obj = (new P3())->process($obj);
    echo 'before closure:', PHP_EOL;
    print_r($obj);
    $resp = $closure($obj);
    echo 'after closure ', PHP_EOL;
    print_r($obj);
    echo '-------- 3 -------', PHP_EOL;
    return $resp;
};

$ps = [
    $p1Middelware,
    $p2Middelware,
    $p3Middelware,
];

foreach ($ps as $middleware) {
    $next = function (Objects $object) use ($middleware, $next) {
        return $middleware($object, $next);
    };
}

$obj = new Objects();
$data = $next($obj);
print_r($data);

// -------- 3 -------
// before closure:
// Objects Object
// (
//     [id] => P3
//     [name] => N3
// )
// -------- 2 -------
// before closure:
// Objects Object
// (
//     [id] => P2
//     [name] => N2
// )
// -------- 1 -------
// before closure:
// Objects Object
// (
//     [id] => P1
//     [name] => N1
// )
// obj info:[ id = P1, name = N1 ]after closure
// Objects Object
// (
//     [id] => P1
//     [name] => N1
// )
// -------- 1 -------
// after closure
// Objects Object
// (
//     [id] => P1
//     [name] => N1
// )
// -------- 2 -------
// after closure
// Objects Object
// (
//     [id] => P1
//     [name] => N1
// )
// -------- 3 -------
// Objects Object
// (
//     [id] => P1
//     [name] => N1
// )