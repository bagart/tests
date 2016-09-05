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
            $result[$num] = BinSearchWhileEqOrGtHash($someArr, $num);
        }

        return $result;
    },
    [
        $someArr,
        $searchList
    ],
    'BinSearchWhileEqOrGtHash'
);

/**
 * @param float[] $array sorted hash
 * @param float $find
 * @return int
 */
function BinSearchWhileEqOrGtHash(array &$array, $find) {
    $left = 0;
    $right = count($array) - 1;

    $result = array_slice($array, 0, 1, true);
    if ($find <= current($result)) {
        return key($result);
    }
    $result = array_slice($array, $right, 1, true);
    if ($find >= current($result)) {
        return key($result);
    }

    while ($left < $right) {
        $mid = (int) (($right + $left) / 2);
        $result = array_slice($array, $mid, 1, true);
        if ($find > current($result)) { //most possible
            $left = $mid + 1;      // exclude $mid
        } elseif ($find == current($result)) {
            return key($result);
        } else {
            $right = $mid;          // include $mid for miss return
        }
    }

    return $right;
}
