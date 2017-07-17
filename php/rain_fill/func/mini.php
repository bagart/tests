<?php

function rain_mini($data)
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

        $water_left = max($data[$left], $waterline_cur);//just pre-calc
        for ($y = $waterline + 1; $y <= $left_max; ++$y) { // max($water_left, $left_max)
            if ($y > $water_left) {
                $left_mem[$y] = isset($left_mem[$y]) ? $left_mem[$y] + 1 : 1;//($left_mem[$y]??0) + 1;
            } elseif (isset($left_mem[$y])) {
                $result += $left_mem[$y];
                unset($left_mem[$y]);
            }
        }

        $water_right = max($data[$right], $waterline_cur);//just pre-calc
        for ($y = $waterline + 1; $y <= $right_max; ++$y) { // max($water_left, $left_max)
            if ($y > $water_right) {
                $right_mem[$y] = isset($right_mem[$y]) ? $right_mem[$y] + 1 : 1;//($right_mem[$y]??0) + 1;
            } elseif (isset($right_mem[$y])) {
                $result += $right_mem[$y];
                unset($right_mem[$y]);
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
