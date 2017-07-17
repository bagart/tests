<?php
$debug = false;
$unit_tests = true;
$compare = ['orig', 'basic'];
if (!empty($argv[1])) {
    $compare = [$argv[1], !empty($argv[2]) ? $argv[2] : ($argv[1] == 'orig' ? 'basic' : 'orig')];
}

require_once __DIR__ . "/func/{$compare[0]}.php";
require_once __DIR__ . "/func/{$compare[1]}.php";

//quick dev debug
//var_dump(rain_side([2,  1, 2]),rain_orig([2, 1, 2]));exit;

if ($unit_tests) {
    foreach ($compare as $func) {
        $func = "rain_{$func}";
        echo "\ntest {$func}\n";
        echo 10 == $func([2, 5, 1, 2, 3, 4, 7, 7, 6]) ? 1 : 0;
        echo 2 == $func([1, 1, 2, 1, 1, 2]) ? 1 : 0;
        echo 10 == $func([2, 5, 1, 2, 7, 4, 7, 7, 6]) ? 1 : 0;
        echo 10 == $func([2, 5, 1, 2, 10, 4, 7, 7, 6]) ? 1 : 0;
        echo 12 == $func([2, 5, 1, 2, 3, 7, 4, 7, 7, 6]) ? 1 : 0;
        echo 12 == $func([5, 1, 2, 3, 7, 4, 7]) ? 1 : 0;
        echo 10 == $func([5, 1, 3, 7, 1, 5]) ? 1 : 0;
        echo 3 == $func([5, 3, 4, 7, 7, 6]) ? 1 : 0;
        echo 1 == $func([2, 1, 6, 6, 6]) ? 1 : 0;
        echo 0 == $func([2, 3]) ? 1 : 0;
        echo 0 == $func([2]) ? 1 : 0;
        echo 0 == $func([]) ? 1 : 0;
        echo 0 == $func([1, 2, 1]) ? 1 : 0;
        echo 0 == $func([1, 2, 2, 1]) ? 1 : 0;
        echo 0 == $func([1, 2, 10, 1]) ? 1 : 0;
        echo 0 == $func([1, 2, 10, 3, 1]) ? 1 : 0;
        echo 1 == $func([1, 2, 1, 2, 1]) ? 1 : 0;
        echo 2 == $func([1, 2, 1, 1, 2, 1]) ? 1 : 0;
        echo 2 == $func([2, 1, 1, 2]) ? 1 : 0;
        echo 1 == $func([2, 3, 2, 3]) ? 1 : 0;
        echo 2 == $func([2, 1, 2, 1, 2]) ? 1 : 0;
        echo 2 == $func([2, 1, 100, 1, 2]) ? 1 : 0;
        echo 4 == $func([5, 4, 3, 4, 5]) ? 1 : 0;
        echo 4 == $func([3, 1, 100, 1, 3]) ? 1 : 0;
        echo 4 == $func([3, 1, 100, 100, 1, 3]) ? 1 : 0;
        echo 2 == $func([3, 2, 4, 3, 5]) ? 1 : 0;
        echo 2 == $func([5, 3, 4, 2, 3]) ? 1 : 0;
        echo 2 == $func([1, 3, 2, 4, 3, 5]) ? 1 : 0;
        echo 2 == $func([5, 3, 4, 2, 3, 1]) ? 1 : 0;

        echo 3 == $func([5, 3, 4, 6, 1, 1, 1]) ? 1 : 0;
        echo 3 == $func([5, 3, 4, 6, 1, 1, 1, 1]) ? 1 : 0;
        echo 3 == $func([5, 4, 3, 6, 1, 1, 1]) ? 1 : 0;
        echo 3 == $func([5, 4, 3, 6, 1, 1, 1, 1]) ? 1 : 0;
        echo 1 == $func([3, 4, 3, 6, 1, 1, 1]) ? 1 : 0;
        echo 1 == $func([3, 4, 3, 6, 1, 1, 1, 1]) ? 1 : 0;

        echo 4 == $func([2, 1, 0, 1, 2]) ? 1 : 0;
    }
}

$func_name_1 = "rain_{$compare[0]}";
$func_name_2 = "rain_{$compare[1]}";
echo "\n\n\ntests:  {$func_name_1} VS {$func_name_2}\n";
foreach (
    [
        [10000, 1000],
        [10000, 10000],
        [1000, 100000],
    ]
    as $params
) {
    $tests = 1;
    list($len, $range) = $params;
    $times = [];
    $m1 = $m2 = null;
    echo "\n$tests tests for $len elements with rand(0, $range)\n";
    while ($tests-- > 0) {
        $test = [];
        for ($i = 0; $i < $len; ++$i) {
            $test[] = rand(0, $range);
        }

        $t1 = microtime(1);
        $r1 = $func_name_1($test);
        $t1 = microtime(1) - $t1;
        if (!isset($m1)) {
            $m1 = memory_get_peak_usage();
        }

        $t2 = microtime(1);
        $r2 = $func_name_2($test);
        $t2 = microtime(1) - $t2;
        if (!isset($m2)) {
            $m2 = memory_get_peak_usage();
        }
        if ($r1 != $r2) {
            echo "ERROR $func_name_2 - $func_name_1", $debug ? ": " . json_encode($test) : null, "\n";
        }
        $times[] = ($t2 - $t1);
    }

    echo "avg time diff  $func_name_2 - $func_name_1:", round(array_sum($times) / count($times),
        2), "sec (LAST diff: ", round(100 * end($times) / $t1, 2), "%)\n";
    echo "first mem diff $func_name_2 - $func_name_1: ", round(($m2 - $m1) / 1024 / 1024,
        2), "mb (", round(100 * ($m2 - $m1) / min($m2, $m1), 2), "%)\n";
}