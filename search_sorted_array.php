<?php
/**
 * @doc https://ru.wikipedia.org/wiki/%D0%94%D0%B2%D0%BE%D0%B8%D1%87%D0%BD%D1%8B%D0%B9_%D0%BF%D0%BE%D0%B8%D1%81%D0%BA
 * find near equal or great from sorted array and hash
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

$test->setShowResult(false);

if ($argv[1] == 'simple') {
    $someArr = array(-2, -1, 0, 1, 2);
    $searchList = array_slice($argv, 2);
} else {
    $someArr = $test->get_array([
        'type' => $test::TYPE_INT_SORTED,
        'from' => -100000,
        
        //'size' => 1000000,    //32mb *2
        //'size' => 5000000,    //256mb *2
        'size' => 20000000,     //1gb *2
        
    ]);
    $searchList = array_slice($argv, 1);
}

//for hash test
$someHash = array('some0' => -1.5,'some1' =>  -1,'some2' =>  0,'some3' =>  1,'some4' =>  1.5);

$test->log_result('prepare data');
foreach ($test->getScripts(__DIR__ . '/BinSearch') as $script) {
    include $script;
}