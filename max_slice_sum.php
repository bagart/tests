<?php
/**
 * max slice sum from random[]
 */
require_once __DIR__ . '/test.inc.php';

$test = Test::create();

$array = $test->get_array([
    'type' => $test::TYPE_INT_NEAR,
    'size' => 500000,
]);

$test->test_func(
    function (array $array)
    {
        $max = 0;
        $cur = 0;
        reset($array);
        while (null !== $key = key($array)) {
            $cur += current($array);
            if ($cur < 0) {
                $cur = 0;
            }
            $max = max($max, $cur);
            next($array);
        }

        return $max;
    },
    [
        $array
    ]
);
