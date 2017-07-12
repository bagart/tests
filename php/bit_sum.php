<?php
//calculate bit sum of random data

/**
 * result
 *

$ php bit_sum.php
array (
'count_chars' =>
array (
'time' => '0.00511s',
'mem_peak' => '10.42mb',
'checked' => true,
),
'chunk(1024) str_split' =>
array (
'time' => '0.57895s',
'mem_peak' => '10.47mb',
'checked' => true,
),
'str[i] chr' =>
array (
'time' => '0.59167s',
'mem_peak' => '10.47mb',
'checked' => true,
),
'str[i] ord' =>
array (
'time' => '0.8029s',
'mem_peak' => '10.47mb',
'checked' => true,
),
'substr 1 chr' =>
array (
'time' => '0.95857s',
'mem_peak' => '10.47mb',
'checked' => true,
),
'substr 1 ord' =>
array (
'time' => '1.31242s',
'mem_peak' => '10.47mb',
'checked' => true,
),
'substr ord map SplFixedArray' =>
array (
'time' => '2.41572s',
'mem_peak' => '10.47mb',
'checked' => true,
),
'substr ord map []' =>
array (
'time' => '2.02112s',
'mem_peak' => '10.47mb',
'checked' => true,
),
'sprintf without map' =>
array (
'time' => '4.32414s',
'mem_peak' => '10.47mb',
'checked' => true,
),
)

$ php bit_sum.php str
array (
'count_chars' =>
array (
'time' => '0.00619s',
'mem_peak' => '10.42mb',
'checked' => true,
),
'chunk(1024) str_split' =>
array (
'time' => '0.57007s',
'mem_peak' => '10.47mb',
'checked' => true,
),
'str[i] chr' =>
array (
'time' => '0.59385s',
'mem_peak' => '10.47mb',
'checked' => true,
),
'str[i] ord' =>
array (
'time' => '0.83155s',
'mem_peak' => '10.47mb',
'checked' => true,
),
'substr 1 chr' =>
array (
'time' => '0.94832s',
'mem_peak' => '10.47mb',
'checked' => true,
),
'substr 1 ord' =>
array (
'time' => '1.34276s',
'mem_peak' => '10.47mb',
'checked' => true,
),
'substr ord map SplFixedArray' =>
array (
'time' => '2.38495s',
'mem_peak' => '10.47mb',
'checked' => true,
),
'substr ord map []' =>
array (
'time' => '2.02575s',
'mem_peak' => '10.47mb',
'checked' => true,
),
'sprintf without map' =>
array (
'time' => '4.25052s',
'mem_peak' => '10.47mb',
'checked' => true,
),
)

 *
 */
ini_set('memory_limit', '256M');
$s_len = 10000000;//in-memory!


$ord_map = new SplFixedArray(256);
$chr_map = [];
for ($i = 0; $i < 256; ++$i) {
    $ord_map[$i] = ($i & 1)
        + (($i >> 1) & 1)
        + (($i >> 2) & 1)
        + (($i >> 3) & 1)
        + (($i >> 4) & 1)
        + (($i >> 5) & 1)
        + (($i >> 6) & 1)
        + (($i >> 7) & 1);
    $chr_map[chr($i)] = $ord_map[$i];
}
$rnd_str = false;
if (in_array('str', $argv)) {
    $rnd_str = true;
    $file_bit_name = 'tmp/str.rnd';
    $file_bit_calc_name = 'tmp/str.calc';
} else {
    $file_bit_name = 'tmp/bit.rnd';
    $file_bit_calc_name = 'tmp/bit.calc';
}

if (
    !file_exists($file_bit_name) || !file_exists($file_bit_calc_name) || filesize($file_bit_name) != $s_len
) {
    //prepare const random data
    @unlink($file_bit_name);
    @unlink($file_bit_calc_name);

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    $s = $rnd_str ? generateRandomString($s_len) : random_bytes($s_len);

    $calc = 0;
    foreach (count_chars($s, 1) as $cur => $val) {
        $calc += $ord_map[$cur] * $val;
    }
    file_put_contents($file_bit_name, $s);
    file_put_contents($file_bit_calc_name, $calc);

    echo "saved; please, repeat test\n";
    exit;
}

$s = file_get_contents($file_bit_name);
$s_calc = file_get_contents($file_bit_calc_name);

$result = [];


//----------------------
$calc = 0;
$t_start = microtime(true);
{
    foreach (count_chars($s, 1) as $cur => $val) {
        $calc += $ord_map[$cur] * $val;
    }
}

$result['count_chars'] = [
    'time' => round((microtime(true) - $t_start), 5) . 's',
    'mem_peak' => round(memory_get_peak_usage() / 1024 / 1024, 2) . 'mb',
    'checked' => $calc == $s_calc ? true : '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! not correct !!!!!!!!!!!!!!!!!!!!!!!!',
];

//----------------------


$calc = 0;
$t_start = microtime(true);
{
    $chunk = 1024;
    for ($i = 0; $i < $s_len; $i += $chunk) {
        foreach (str_split(substr($s, $i, $chunk)) as $cur) {
            $calc += $chr_map[$cur];
        }
    }
}

$result['chunk(' . $chunk . ') str_split'] = [
    'time' => round((microtime(true) - $t_start), 5) . 's',
    'mem_peak' => round(memory_get_peak_usage() / 1024 / 1024, 2) . 'mb',
    'checked' => $calc == $s_calc ? true : '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! not correct !!!!!!!!!!!!!!!!!!!!!!!!',
];


//----------------------
$calc = 0;
$t_start = microtime(true);
{
    for ($i = 0; $i < $s_len; ++$i) {
        $calc += $chr_map[$s[$i]];
    }
}

$result['str[i] chr'] = [
    'time' => round((microtime(true) - $t_start), 5) . 's',
    'mem_peak' => round(memory_get_peak_usage() / 1024 / 1024, 2) . 'mb',
    'checked' => $calc == $s_calc ? true : '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! not correct !!!!!!!!!!!!!!!!!!!!!!!!',
];


//----------------------
$calc = 0;
$t_start = microtime(true);
{
    for ($i = 0; $i < $s_len; ++$i) {
        $calc += $ord_map[ord($s[$i])];
    }
}

$result['str[i] ord'] = [
    'time' => round((microtime(true) - $t_start), 5) . 's',
    'mem_peak' => round(memory_get_peak_usage() / 1024 / 1024, 2) . 'mb',
    'checked' => $calc == $s_calc ? true : '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! not correct !!!!!!!!!!!!!!!!!!!!!!!!',
];

//----------------------
$calc = 0;
$t_start = microtime(true);
{
    //substr 1 chr
    for ($i = 0; $i < $s_len; $i += 1) {
        $calc += $chr_map[substr($s, $i, 1)];
    }
}

$result['substr 1 chr'] = [
    'time' => round((microtime(true) - $t_start), 5) . 's',
    'mem_peak' => round(memory_get_peak_usage() / 1024 / 1024, 2) . 'mb',
    'checked' => $calc == $s_calc ? true : '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! not correct !!!!!!!!!!!!!!!!!!!!!!!!',
];

//----------------------
$calc = 0;
$t_start = microtime(true);
{
    //substr 1 ord
    for ($i = 0; $i < $s_len; $i += 1) {
        $calc += $ord_map[ord(substr($s, $i, 1))];
    }
}

$result['substr 1 ord'] = [
    'time' => round((microtime(true) - $t_start), 5) . 's',
    'mem_peak' => round(memory_get_peak_usage() / 1024 / 1024, 2) . 'mb',
    'checked' => $calc == $s_calc ? true : '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! not correct !!!!!!!!!!!!!!!!!!!!!!!!',
];

//----------------------
$calc = 0;
$t_start = microtime(true);
{
    //substr ord map
    $map = new SplFixedArray(256);
    for ($i = 0; $i < $s_len; $i += 1) {
        $b = ord(substr($s, $i, 1));
        //$map[$b] = isset($map[$b]) ? $map[$b] + 1 : 1;
        $map[$b] = ($map[$b] ?? 0) + 1;
    }

    foreach ($map as $cur => $val) {
        $calc += $ord_map[$cur] * $val;
    }
}

$result['substr ord map SplFixedArray'] = [
    'time' => round((microtime(true) - $t_start), 5) . 's',
    'mem_peak' => round(memory_get_peak_usage() / 1024 / 1024, 2) . 'mb',
    'checked' => $calc == $s_calc ? true : '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! not correct !!!!!!!!!!!!!!!!!!!!!!!!',
];


//----------------------
$calc = 0;
$t_start = microtime(true);
{
    //substr ord map
    $map = [];
    for ($i = 0; $i < $s_len; $i += 1) {
        $b = ord(substr($s, $i, 1));
        //$map[$b] = isset($map[$b]) ? $map[$b] + 1 : 1;
        $map[$b] = ($map[$b] ?? 0) + 1;
    }

    foreach ($map as $cur => $val) {
        $calc += $ord_map[$cur] * $val;
    }
}

$result['substr ord map []'] = [
    'time' => round((microtime(true) - $t_start), 5) . 's',
    'mem_peak' => round(memory_get_peak_usage() / 1024 / 1024, 2) . 'mb',
    'checked' => $calc == $s_calc ? true : '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! not correct !!!!!!!!!!!!!!!!!!!!!!!!',
];

//----------------------
$calc = 0;
$t_start = microtime(true);
{
    //sprintf without map
    for ($i = 0; $i < $s_len; ++$i) {
        $calc += substr_count(sprintf('%b', ord($s[$i])), '1');
    }
}

$result['sprintf without map'] = [
    'time' => round((microtime(true) - $t_start), 5) . 's',
    'mem_peak' => round(memory_get_peak_usage() / 1024 / 1024, 2) . 'mb',
    'checked' => $calc == $s_calc ? true : '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! not correct !!!!!!!!!!!!!!!!!!!!!!!!',
];

var_export($result);