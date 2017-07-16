<?php
$debug = false;
$unit_tests = false;

if ($unit_tests) {
    foreach (['rain1', 'rain2'] as $func) {
        echo "\ntest {$func}\n";
        echo(2 == $func([1, 1, 2, 1, 1, 2]) ? 1 : 0);
        echo(10 == $func([2, 5, 1, 2, 7, 4, 7, 7, 6]) ? 1 : 0);
        echo(10 == $func([2, 5, 1, 2, 10, 4, 7, 7, 6]) ? 1 : 0);
        echo(12 == $func([2, 5, 1, 2, 3, 7, 4, 7, 7, 6]) ? 1 : 0);
        echo(12 == $func([5, 1, 2, 3, 7, 4, 7]) ? 1 : 0);
        echo(10 == $func([5, 1, 3, 7, 1, 5]) ? 1 : 0);
        echo(3 == $func([5, 3, 4, 7, 7, 6]) ? 1 : 0);
        echo(1 == $func([2, 1, 6, 6, 6]) ? 1 : 0);
        echo(0 == $func([2, 3]) ? 1 : 0);
        echo(0 == $func([2]) ? 1 : 0);
        echo(0 == $func([]) ? 1 : 0);
        echo(0 == $func([1, 2, 1]) ? 1 : 0);
        echo(0 == $func([1, 2, 2, 1]) ? 1 : 0);
        echo(0 == $func([1, 2, 10, 1]) ? 1 : 0);
        echo(0 == $func([1, 2, 10, 3, 1]) ? 1 : 0);
        echo(1 == $func([1, 2, 1, 2, 1]) ? 1 : 0);
        echo(2 == $func([1, 2, 1, 1, 2, 1]) ? 1 : 0);
        echo(2 == $func([2, 1, 1, 2]) ? 1 : 0);
        echo(1 == $func([2, 3, 2, 3]) ? 1 : 0);
        echo(2 == $func([2, 1, 2, 1, 2]) ? 1 : 0);
        echo(2 == $func([2, 1, 100, 1, 2]) ? 1 : 0);
        echo(4 == $func([5, 4, 3, 4, 5]) ? 1 : 0);
        echo(4 == $func([3, 1, 100, 1, 3]) ? 1 : 0);
        echo(4 == $func([3, 1, 100, 100, 1, 3]) ? 1 : 0);
        echo(2 == $func([3, 2, 4, 3, 5]) ? 1 : 0);
        echo(2 == $func([5, 3, 4, 2, 3]) ? 1 : 0);
        echo(2 == $func([1, 3, 2, 4, 3, 5]) ? 1 : 0);
        echo(2 == $func([5, 3, 4, 2, 3, 1]) ? 1 : 0);

        echo(3 == $func([5, 3, 4, 6, 1, 1, 1]) ? 1 : 0);
        echo(3 == $func([5, 3, 4, 6, 1, 1, 1, 1]) ? 1 : 0);
        echo(3 == $func([5, 4, 3, 6, 1, 1, 1]) ? 1 : 0);
        echo(3 == $func([5, 4, 3, 6, 1, 1, 1, 1]) ? 1 : 0);
        echo(1 == $func([3, 4, 3, 6, 1, 1, 1]) ? 1 : 0);
        echo(1 == $func([3, 4, 3, 6, 1, 1, 1, 1]) ? 1 : 0);
    }
}

foreach (
    [
        [100000, 100],
        [10000, 10000],
        [100, 100000]
    ]
    as $params) {
    $tests = 10;
    list($len, $range) = $params;
    $times = [];
    $m1 = $m2 = null;
    echo "\n$tests test for $len elements with rand(0, $range)\n";
    while ($tests-- > 0) {
        $test = [];
        for ($i = 0; $i < $len; ++$i) {
            $test[] = rand(0, $range);
        }

        $t1 = microtime(1);
        $r1 = rain1($test);
        $t1 = microtime(1) - $t1;
        if (!isset($m1)) {
            $m1 = memory_get_peak_usage();
        }

        $t2 = microtime(1);
        $r2 = rain2($test);
        $t2 = microtime(1) - $t2;
        if (!isset($m2)) {
            $m2 = memory_get_peak_usage();
        }
        if ($r1 != $r2) {
            echo "ERROR range1 !=range2", $debug ? ": " . json_encode($test) : null, "\n";
        }
        $times[] = ($t2 - $t1);
    }
    echo "avg time diff range2 - range1:", round(array_sum($times) / count($times),
        2), "sec (LAST diff: ", round(100 * end($times) / $t1, 2), "%)\n";
    echo "first mem diff range2 - range1: ", round(($m2 - $m1) / 1024 / 1024,
        2), "mb (", round(100 * ($m2 - $m1) / min($m2, $m1), 2), "%)\n";
}

function rain1($data)
{
    global $debug;
    $left = -1;//first idx-1 by x
    $right = count($data); //last+1 idx by x
    $result = 0;
    $waterline = 0;// min($left, $right)
    $left_mem = [];//left mem by y
    $right_mem = [];//right mem by y
    $left_max = 0;//left max
    $right_max = 0;//right max
    //move from different side |-> .... <-|
    while (++$left <= --$right) {
        $waterline_cur = max(
            $waterline,
            min($data[$left], $data[$right]),
            min($data[$left], $right_max),
            min($data[$right], $left_max)
        );

        $result += ($waterline_cur > $data[$left] ? $waterline_cur - $data[$left] : 0)
            + ($right != $left && $waterline_cur > $data[$right] ? $waterline_cur - $data[$right] : 0);

        //just pre-calc
        $water_left = max($data[$left], $waterline_cur) + 1;
        $water_right = max($data[$right], $waterline_cur) + 1;

        for ($y = $waterline + 1; $y < $water_left; ++$y) {
            $result += ($left_mem[$y] ?? 0);
            unset($left_mem[$y]);
        }
        for ($y = $waterline + 1; $y < $water_right; ++$y) {
            $result += ($right_mem[$y] ?? 0);
            unset($right_mem[$y]);
        }

        for ($y = $water_left; $y <= $left_max; ++$y) {
            $left_mem[$y] = ($left_mem[$y] ?? 0) + 1;
        }

        for ($y = $water_right; $y <= $right_max; ++$y) {
            $right_mem[$y] = ($right_mem[$y] ?? 0) + 1;
        }

        if ($debug) {
            echo json_encode([
                'result' => '>>> ' . $result . ' <<<',
                'left' => [
                    $left => $data[$left],
                    'max' => $left_max,
                    'mem' => $left_mem,
                ],
                'right' => [
                    $right => $data[$right],
                    'max' => $right_max,
                    'mem' => $right_mem,
                ],
                'waterline' => $waterline,
                'waterline_cur' => $waterline_cur,
            ], JSON_PRETTY_PRINT), "\n";
        }

        $left_max = max($left_max, $data[$left]);
        $right_max = max($right_max, $data[$right]);
        $waterline = $waterline_cur;

    }

    return $result;
}

function rain2($data)
{
    global $debug;
    $idx = count($data);
    $result = 0;
    $mem = [];//mem by y
    $max = 0;
    $last = 0;
    while ($idx--) {
        $cur = $data[$idx];
        for ($y = $cur + 1; $y <= $max; ++$y) {
            $mem[$y] = ($mem[$y] ?? 0) + 1;
        }

        for ($y = $last + 1; $y <= min($cur, $max); ++$y) {
            $result += ($mem[$y] ?? 0);
            unset($mem[$y]);
        }

        if ($debug) {
            echo json_encode([
                'result' => '>>> ' . $result . ' <<<',
                'cur' => $cur,
                'last' => $last,
                'max' => $max,
                'mem' => $mem,

            ], JSON_PRETTY_PRINT), "\n";
        }
        $max = max($max, $cur);
        $last = $cur;
    }

    return $result;
}
