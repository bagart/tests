<?php

function rain_short($data)
{
    global $debug;
    $result = 0;
    if (count($data) % 2) {
        $data[] = 0;//just skip middle case for optimize
    }
    $work['left'] = $work['right'] = [
        'x' => -1,//first idx-1 by x
        'max' => 0,
        'mem' => [],
    ];
    $work['right']['x'] = count($data);//last+1 idx by x

    $waterline = 0;         // last min($left, $right)

    //micro optimization
    $left = &$work['left']['x'];
    $right = &$work['right']['x'];

    //move from different side in center    |-> .... <-|
    while (++$left <= --$right) {
        $waterline_cur = max(
            $waterline,
            min($data[$left], $data[$right]),
            min($data[$left], $work['right']['max']),
            min($data[$right], $work['left']['max'])
        );
        foreach ($work as $side => &$side_param) {
            $cur_val = &$data[$side_param['x']];

            if ($waterline_cur > $cur_val) {
                $result += $waterline_cur - $cur_val;
                $side_y = $waterline_cur; //just pre-calc max($waterline_cur,$cur_val)
            } else {
                $side_y = $cur_val;
            }

            $mem = &$side_param['mem'];
            for ($y = $waterline + 1; $y <= $side_param['max']; ++$y) { // max($side_y, $side_param['max'])?
                if ($y > $side_y) {
                    $mem[$y] = isset($mem[$y]) ? $mem[$y] + 1 : 1;
                } elseif (isset($mem[$y])) {
                    $result += $mem[$y];
                    unset($mem[$y]);
                }
            }

            if ($debug) {
                echo json_encode([
                    'result' => '>>> ' . $result . ' <<<',
                    'side' => $side,
                    'side_param' => $side_param,
                    'cur_val' => $cur_val,
                    'waterline' => $waterline,
                    'waterline_cur' => $waterline_cur,
                ], JSON_PRETTY_PRINT), "\n";
            }

            $side_param['max'] = max($side_param['max'], $cur_val);
        }
        $waterline = $waterline_cur;
    }

    return $result;
}
