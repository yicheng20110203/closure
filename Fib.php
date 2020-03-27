<?php

function fib(): Closure {
    $pre = 0;
    $next = 1;
    return function() use (&$pre, &$next) {
        $t = $pre;
        $pre = $next;
        $next = $pre + $t;
        return $t;
    };
}

$closure = fib();

for ($i = 0; $i < 20; $i++) {
    $v = $closure();
    printf("%-5.2d%-10d\n", $i + 1, $v);
}