<?php

// Ioc
class Container 
{
    public static $c = [];

    public static function set(string $name, Closure $closure) 
    {
        self::$c[$name] = $closure;
    }

    public static function get(string $name)
    {
        return call_user_func(self::$c[$name]);
    }
}

interface Target 
{
    function do(): string;
}

class TargetInstance implements Target
{
    function do(): string
    {
        return __CLASS__ . '->' . __FUNCTION__ . '()';
    }
}

class Execute
{
    public function exec(Target $t) 
    {
        echo $t->do() . PHP_EOL;
    }
}

$key = 'targer_instance';
Container::set($key, function(){
    return new TargetInstance();
});
(new Execute())->exec(Container::get($key));