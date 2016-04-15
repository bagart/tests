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
            $result[$num] = BinSearchRecursiveCopyChangedEqOrGtArray($someArr, $num);
        }

        return $result;
    },
    [
        $someArr,
        $searchList
    ],
    'BinSearchRecursiveCopyChangedEqOrGtArray'
);


/**
 * check mem for copy of array
 * @param float[] $array sorted array
 * @param float $find
 * @param int $left
 * @param int|null $right
 * @return int
 */
function BinSearchRecursiveCopyChangedEqOrGtArray(array $array, $find, $left = 0, $right = null) {
    $array[0] = $array[0];

    if ($left >= $right) {
        if ($right !== null) {
            return $left;   //check and return false for eq only
        }

        //check only 1 times
        assert(!empty($array));
        if ($find <= $array[0]) {
            return 0;
        }
        $right = count($array) - 1;
        if ($find >= $array[$right]) {
            return $right;
        }
    }
    $mid = (int) (($right + $left) / 2);
    if ($array[$mid] == $find) {
        return $mid;
    }
    return $find > $array[$mid]
        ? BinSearchRecursiveCopyEqOrGtArray($array, $find, $mid + 1, $right)   // exclude $mid
        : BinSearchRecursiveCopyEqOrGtArray($array, $find, $left, $mid);       // include $mid for miss return
}
