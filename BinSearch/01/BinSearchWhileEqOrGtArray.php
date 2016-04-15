<?php
/**
 * @var Test $test
 * @var float[] $someArr
 * @var float[] $searchList
 */
$test->test_func(
    function (array $someArr, $searchList)
    {
        $result = [];
        foreach ($searchList as $num) {
            $result[$num] = BinSearchWhileEqOrGtArray($someArr, $num);
        }

        return $result;
    },
    [
        $someArr,
        $searchList
    ],
    'BinSearchWhileEqOrGtArray'
);


/**
 * @param float[] $array sorted array
 * @param float $find
 * @return int
 */
function BinSearchWhileEqOrGtArray(array &$array, $find) {
    $left = 0;
    $right = count($array) - 1;

    if ($find <= $array[$left]) {
        return $left;
    }

    if ($find >= $array[$right]) {
        return $right;
    }

    while ($left < $right) {
        $mid = (int) (($right + $left) / 2);
        if ($find > $array[$mid]) { //most possible
            $left = $mid + 1;      // exclude $mid
        } elseif ($find == $array[$mid]) {
            return $mid;
        } else {
            $right = $mid;          // include $mid for miss return
        }
    }

    return $right;
}
