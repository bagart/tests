<?php
//calculate bit sum

$t_start = microtime(true);
{
    //map_1 bit sum
    for ($n = 0; $n < 100000; ++$n) {
        $map1 = new SplFixedArray(256);
        for ($i = 0; $i < 256; ++$i) {
            $map1[$i] = ($i & 1)
                + (($i >> 1) & 1)
                + (($i >> 2) & 1)
                + (($i >> 3) & 1)
                + (($i >> 4) & 1)
                + (($i >> 5) & 1)
                + (($i >> 6) & 1)
                + (($i >> 7) & 1);
        }
    }
}
$result['map_1 bit sum'] = round((microtime(true) - $t_start), 5) . 's';

$t_start = microtime(true);

{
    //map_2 bit while
    for ($n = 0; $n < 100000; ++$n) {
        $map2 = new SplFixedArray(256);
        for ($i = 0; $i < 256; ++$i) {
            $cnt = 0;
            $z = $i;
            while ($z) {
                $cnt += $z & 1;
                $z >>= 1;
            }
            $map2[$i] = $cnt;
        }
    }
}

$result['map_2 bit while'] = round((microtime(true) - $t_start), 5) . 's';
$t_start = microtime(true);
{
//sprintf substr_count
    for ($n = 0; $n < 100000; ++$n) {
        $map3 = new SplFixedArray(256);
        for ($i = 0; $i < 256; ++$i) {
            $map3[$i] = substr_count(sprintf('%b', $i), '1');
        }
    }
}
$result['map_3 sprintf substr_count'] = round((microtime(true) - $t_start), 5) . 's';
$t_start = microtime(true);
{
    //sprintf split sum
    for ($n = 0; $n < 100000; ++$n) {
        $map4 = new SplFixedArray(256);
        for ($i = 0; $i < 256; ++$i) {
            $map4[$i] = array_sum(str_split(sprintf('%b', $i), '1'));
        }
    }
}
$result['map_4 sprintf split sum'] = round((microtime(true) - $t_start), 5) . 's';
$t_start = microtime(true);


{
    //base_convert substr_count
    for ($n = 0; $n < 100000; ++$n) {
        $map5 = new SplFixedArray(256);
        for ($i = 0; $i < 256; ++$i) {
            $map5[$i] = substr_count(base_convert(unpack('H*', chr($i))[1], 16, 2), '1');
        }
    }
}
$result['map_5 base_convert substr_count'] = round((microtime(true) - $t_start), 5) . 's';
$t_start = microtime(true);

{
    //base_convert split sum
    for ($n = 0; $n < 100000; ++$n) {
        $map6 = new SplFixedArray(256);
        for ($i = 0; $i < 256; ++$i) {
            $map6[$i] = array_sum(str_split(base_convert(unpack('H*', chr($i))[1], 16, 2)));
        }
    }
}
$result['map_6 base_convert split sum'] = round((microtime(true) - $t_start), 5) . 's';

var_dump($result);
//check
for ($i = 0; $i < 256; ++$i) {
    if (
        $map1[$i] !== $map2[$i]
        || $map1[$i] !== $map3[$i]
        || $map1[$i] !== $map4[$i]
        || $map1[$i] !== $map5[$i]
        || $map1[$i] !== $map6[$i]
    ) {
        var_dump('diff', $i, $map1[$i], $map2[$i], $map3[$i], $map4[$i], $map5[$i], $map6[$i]);
    }
}
