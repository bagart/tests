<?php
/**
 * Suppose you have an array of integers, both positive and negative, in no particular order.  Find the largest possible sum of any continuous subarray.  For example, if you have all positive numbers, the largest sum would be the sum of the whole array; if you have all negative numbers, the largest sum is 0 (the null subarray)
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
