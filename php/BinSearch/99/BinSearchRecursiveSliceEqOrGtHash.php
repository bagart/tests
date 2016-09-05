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
            $result[$num] = BinSearchRecursiveSliceEqOrGtHash($someArr, $num);
        }

        return $result;
    },
    [
        $someArr,
        $searchList
    ],
    'BinSearchRecursiveSliceEqOrGtHash'
);

/**
 * size => 20kk
 * last_time: 0.886s
 * memory: +288.01mb
 * @param float[] $array sorted hash
 * @param float $find
 * @return string
 */
function BinSearchRecursiveSliceEqOrGtHash(array $array, $find) {
    assert(!empty($array));
    if (count($array) == 1) {
        return key($array);
    }

    $mid = (int) ((count($array) - 1 ) / 2);
    $result = array_slice($array, $mid, 1, true);
    if (current($result) == $find) {
        return key($result);
    }

    return $find > current($result)
        ? BinSearchRecursiveSliceEqOrGtHash(array_slice($array, $mid + 1, null, true), $find)// exclude $mid
        : BinSearchRecursiveSliceEqOrGtHash(array_slice($array, 0, $mid + 1, true), $find);  // include $mid for miss return
}
