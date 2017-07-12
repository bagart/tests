### TASK: calculate bit sum of random data

# bit_map.php
test with building bit map
```
$ php bit_map.php
{
    "map_1 bit sum": "0.3568s",
    "map_2 bit while": "0.7565s",
    "map_3 sprintf substr_count": "0.85904s",
    "map_4 sprintf split sum": "1.95584s",
    "map_5 base_convert substr_count": "2.45762s",
    "map_6 base_convert split sum": "3.33767s"
}

```
# bit_sum.php
bit sum with diff way
```
$ php bit_sum.php
{
    "count_chars": {
        "time": "0.00507s",
        "mem_peak": "10.42mb",
        "checked": true
    },
    "chunk(1024) str_split": {
        "time": "0.58414s",
        "mem_peak": "10.47mb",
        "checked": true
    },
    "str[i] chr": {
        "time": "0.61553s",
        "mem_peak": "10.47mb",
        "checked": true
    },
    "str[i] ord": {
        "time": "0.94431s",
        "mem_peak": "10.47mb",
        "checked": true
    },
    "substr 1 chr": {
        "time": "1.0381s",
        "mem_peak": "10.47mb",
        "checked": true
    },
    "substr 1 ord": {
        "time": "1.3147s",
        "mem_peak": "10.47mb",
        "checked": true
    },
    "substr ord map SplFixedArray": {
        "time": "2.44353s",
        "mem_peak": "10.47mb",
        "checked": true
    },
    "substr ord map []": {
        "time": "2.00523s",
        "mem_peak": "10.47mb",
        "checked": true
    },
    "sprintf without map": {
        "time": "4.41628s",
        "mem_peak": "10.47mb",
        "checked": true
    }
}


$ php bit_sum.php str
{
    "count_chars": {
        "time": "0.00515s",
        "mem_peak": "10.42mb",
        "checked": true
    },
    "chunk(1024) str_split": {
        "time": "0.6002s",
        "mem_peak": "10.47mb",
        "checked": true
    },
    "str[i] chr": {
        "time": "0.57913s",
        "mem_peak": "10.47mb",
        "checked": true
    },
    "str[i] ord": {
        "time": "0.83091s",
        "mem_peak": "10.47mb",
        "checked": true
    },
    "substr 1 chr": {
        "time": "0.95877s",
        "mem_peak": "10.47mb",
        "checked": true
    },
    "substr 1 ord": {
        "time": "1.32542s",
        "mem_peak": "10.47mb",
        "checked": true
    },
    "substr ord map SplFixedArray": {
        "time": "2.46123s",
        "mem_peak": "10.47mb",
        "checked": true
    },
    "substr ord map []": {
        "time": "2.0552s",
        "mem_peak": "10.47mb",
        "checked": true
    },
    "sprintf without map": {
        "time": "4.34965s",
        "mem_peak": "10.47mb",
        "checked": true
    }
}

```