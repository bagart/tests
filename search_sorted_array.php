<?php
/**
  * @doc https://ru.wikipedia.org/wiki/%D0%94%D0%B2%D0%BE%D0%B8%D1%87%D0%BD%D1%8B%D0%B9_%D0%BF%D0%BE%D0%B8%D1%81%D0%BA
  * 
 */
require_once __DIR__ . '/test.inc.php';

if (!isset($argv[1]) || (!isset($argv[2]) && $argv[1] == 'simple')) {
    echo 'use: php ', basename(__FILE__), " [simple] NUM\n",
    "where NUM from -10000 (simple: -1.5 to 1.5)\n",
    'example: php ', basename(__FILE__), " -100001 -100000 -1.5 -2 -1 0 1 2 100000 100000\n",
    'example simple: php ', basename(__FILE__), " simple -3 -0.5 0 0.5 2 3\n";
    exit;
}

$test = Test::create();

if ($argv[1] == 'simple') {
    $someArr = array(-2, -1, 0, 1, 2);
    $searchList = array_slice($argv, 2);
} else {
    $someArr = $test->get_array([
        'type' => $test::TYPE_INT_SORTED,
        'from' => -100000,
        'size' => 50000,
    ]);
    $searchList = array_slice($argv, 1);
}
$someHash = array('some0' => -1.5,'some1' =>  -1,'some2' =>  0,'some3' =>  1,'some4' =>  1.5);

$test->test_func(
    function (array $someArr, $searchList)
    {
        $result = [];
        foreach ($searchList as $num) {
            $result[$num] = BinSearchSmart($someArr, $num, '>=');
        }
        
        return $result;
    },
    [
        $someArr,
        $searchList
    ],
    'BinSearchSmart'    
);

$test->test_func(
    function (array $someArr, $searchList)
    {
        $result = [];
        foreach ($searchList as $num) {
            $result[$num] = BinSearchRecursiveEqOrGt($someArr, $num);
        }

        return $result;
    },
    [
        $someArr,
        $searchList
    ],
    'BinSearchRecursiveEqOrGt'
);


$test->test_func(
    function (array $someArr, $searchList)
    {
        $result = [];
        foreach ($searchList as $num) {
            $result[$num] = BinSearchRecursiveEqOrGt($someArr, $num);
        }

        return $result;
    },
    [
        $someHash,
        $searchList
    ],
    'BinSearchRecursiveEqOrGt + hash'
);

/**
 * @param array $array
 * @param float $find
 * @param string $type
 * @return int|false
 */
function BinSearchSmart(array $array, $find, $type = '=') {
    assert(!empty($array), 'wrong argument by task');
    assert(preg_match('~^(=|[<>]=?|=?[<>])$~', $type));

    $exp_lt = strpos($type, '<') !== false;
    $exp_gt = strpos($type, '>') !== false;
    $exp_eq = strpos($type, '=') !== false;
    
    //when slice is possible
    reset($array);
    $min = key($array);
    end($array);
    $max = key($array);
    
    //check borderline value for optimization
    $border = null;
    if ($find <= $array[$min]) {
        $border = $min;
    } elseif ($find >= $array[$max]) {
        $border = $max;
    }

    if ($border !== null) {
        if ($find != $array[$border] && !$exp_lt && !$exp_gt) {
            return false;
        }
    
        if ($find == $array[$border] && !$exp_eq) {
            if ($exp_gt && !$border) {
                return  $border + 1;
            }
            if ($exp_lt && $border) {
                return  $border - 1;
            }
        }

        return $border;
    }

    while ($min + 1 < $max) {
        $pos = (int) (($min + $max) / 2);
        //var_dump([$min, $pos, $max]);
        if ($find < $array[$pos]) {
            if ($find > $array[$pos - 1]) {
                //is borderline
                $min = $pos - 1;
                $max = $pos;
            } else {
                $max = $pos = $pos - 1;
            }
        } elseif ($find > $array[$pos]) {
            if ($find < $array[$pos + 1]) {
                //is borderline
                $min = $pos;
                $max = $pos + 1;
            } else {
                $min = $pos = $pos + 1;
            }
        }

        //check with pos or next
        if ($find == $array[$pos]) {
            return $exp_eq
                ? $pos
                : $pos + ($exp_lt ? -1 : 1);
        }
    }
    
    if (!$exp_lt && !$exp_gt) {
        return false;
    }

    return $exp_lt ? $min : $max;
}




/**
 * @param array $array
 * @param float $find
 * @return array
 */
function BinSearchRecursiveEqOrGt(array $array, $find) {
    assert(!empty($array));
    if (count($array) == 1) {
        return key($array);
    }

    $pos = (int) ((count($array) - 1 )/2);
    $result = array_slice($array, $pos, 1, true);
    if (current($result) == $find) {
        return key($result);
    }

    return $find > current($result)
        ? BinSearchRecursiveEqOrGt(array_slice($array, $pos + 1, null, true), $find)// exclude $pos
        : BinSearchRecursiveEqOrGt(array_slice($array, 0, $pos + 1, true), $find);   //include $pos
}
