<?php

function rain_orig($data)
{
    global $debug;
    if (count($data) % 2) {
        $data[] = 0;//just skip middle case for optimize
    }
    $result = 0;
    $left = -1;             //first idx-1 by x
    $right = count($data);  //last+1 idx by x
    $waterline = 0;         // min($left, $right)
    $left_mem = [];         //left mem by y
    $right_mem = [];        //right mem by y
    $left_max = 0;          //left max
    $right_max = 0;         //right max
    //move from different side in center    |-> .... <-|
    while (++$left <= --$right) {
        $waterline_cur = max(
            $waterline,
            min($data[$left], $data[$right]),
            min($data[$left], $right_max),
            min($data[$right], $left_max)
        );

        $result += max($waterline_cur - $data[$left], 0)
            + max($waterline_cur - $data[$right], 0);

        //just pre-calc
        $water_left = max($data[$left], $waterline_cur) + 1;
        $water_right = max($data[$right], $waterline_cur) + 1;

        for ($y = $waterline + 1; $y < $water_left; ++$y) {
            if (isset($left_mem[$y])) {
                $result += $left_mem[$y];
                unset($left_mem[$y]);
            }

        }
        for ($y = $waterline + 1; $y < $water_right; ++$y) {
            if (isset($right_mem[$y])) {
                $result += $right_mem[$y];
                unset($right_mem[$y]);
            }
        }

        for ($y = $water_left; $y <= $left_max; ++$y) {
            if (!isset($left_mem[$y])) {
                $left_mem[$y] = 1;
            } else {
                ++$left_mem[$y];
            }
        }

        for ($y = $water_right; $y <= $right_max; ++$y) {
            if (!isset($right_mem[$y])) {
                $right_mem[$y] = 1;
            } else {
                ++$right_mem[$y];
            }
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


