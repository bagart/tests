<?php

$debug = 0;

var_dump(10 == rain([2, 5, 1, 2, 3, 4, 7, 7, 6]));
var_dump(10 == rain([2, 5, 1, 2, 7, 4, 7, 7, 6]));
var_dump(10 == rain([2, 5, 1, 2, 10, 4, 7, 7, 6]));
var_dump(12 == rain([2, 5, 1, 2, 3, 7, 4, 7, 7, 6]));
var_dump(12 == rain([5, 1, 2, 3, 7, 4, 7]));
var_dump(10 == rain([5, 1, 3, 7, 1, 5]));
var_dump(3 == rain([5, 3, 4, 7, 7, 6]));
var_dump(1 == rain([2, 1, 6, 6, 6]));
var_dump(0 == rain([2, 3]));
var_dump(0 == rain([2]));
var_dump(0 == rain([]));
var_dump(0 == rain([1, 2, 1]));
var_dump(0 == rain([1, 2, 2, 1]));
var_dump(0 == rain([1, 2, 10, 1]));
var_dump(0 == rain([1, 2, 10, 3, 1]));
var_dump(1 == rain([1, 2, 1, 2, 1]));
var_dump(2 == rain([1, 2, 1, 1, 2, 1]));
var_dump(2 == rain([2, 1, 1, 2]));
var_dump(1 == rain([2, 3, 2, 3]));
var_dump(2 == rain([2, 1, 2, 1, 2]));
var_dump(2 == rain([2, 1, 100, 1, 2]));
var_dump(4 == rain([5, 4, 3, 4, 5]));
var_dump(4 == rain([3, 1, 100, 1, 3]));
var_dump(4 == rain([3, 1, 100, 100, 1, 3]));
var_dump(2 == rain([3, 2, 4, 3, 5]));
var_dump(2 == rain([5, 3, 4, 2, 3]));
var_dump(2 == rain([1, 3, 2, 4, 3, 5]));
var_dump(2 == rain([5, 3, 4, 2, 3, 1]));

function rain($data)
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

        for ($y = $waterline + 1; $y <= min($data[$left], $left_max); ++$y) {
            $result += ($left_mem[$y] ?? 0);
            unset($left_mem[$y]);
        }

        for ($y = $waterline + 1; $y <= min($data[$right], $right_max); ++$y) {
            $result += ($right_mem[$y] ?? 0);
            unset($right_mem[$y]);
        }


        for ($y = $data[$left] + 1; $y <= $left_max; ++$y) {
            $left_mem[$y] = ($left_mem[$y] ?? 0) + 1;
        }


        for ($y = $data[$right] + 1; $y <= $right_max; ++$y) {
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

