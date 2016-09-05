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
            $result[$num] = BinSearchRecursiveReferenceEqOrGtHash($someArr, $num);
        }

        return $result;
    },
    [
        $someArr,
        $searchList
    ],
    'BinSearchRecursiveReferenceEqOrGtHash'
);

/**
 * @param float[] $array sorted hash
 * @param float $find
 * @param int $left
 * @param int|null $right
 * @return string
 */
function BinSearchRecursiveReferenceEqOrGtHash(array &$array, $find, $left = 0, $right = null) {
    if ($left >= $right) {
        if ($right !== null) {
            //for eq only: check and return false
            return $left;
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
    $result = array_slice($array, $mid, 1, true);// for hash with any key.
    if (current($result) == $find) {
        return key($result);
    }

    return $find > current($result)
        ? BinSearchRecursiveReferenceEqOrGtHash($array, $find, $mid + 1, $right)   // exclude $mid
        : BinSearchRecursiveReferenceEqOrGtHash($array, $find, $left, $mid);       // include $mid for miss return
}
