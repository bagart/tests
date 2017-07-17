<?php

function rain_basic($data)
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
            if (!isset($mem[$y])) {
                $mem[$y] = 1;
            } else {
                ++$mem[$y];
            }
        }

        for ($y = $last + 1; $y <= min($cur, $max); ++$y) {
            if (isset($mem[$y])) {
                $result += $mem[$y];
                unset($mem[$y]);
            }
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
