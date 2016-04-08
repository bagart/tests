<?php

class Test
{
    const LOG_LVL_INFO = 1;
    const LOG_LVL_NOTICE = 3;
    const LOG_LVL_ERROR = 5;

    const TYPE_ARRAY = 'get_array';
    const TYPE_INT = 'get_int';
    const TYPE_INT_NEAR = 'get_int_near';
    const TYPE_INT_SORTED = 'get_int_sorted';

    const TYPE_RANDOM = 'get_random';
    private $start_time;
    private $last_time;
    private $last_mem;

    public static function create()
    {
        return new static;
    }

    protected function  __construct()
    {
        $this->last_time = $this->start_time = microtime(true);
    }

    public $log_lvl = self::LOG_LVL_INFO;

    public function log($test, $lvl = self::LOG_LVL_NOTICE, $new_line = true, $tab = 0)
    {
        if ($lvl >= $this->log_lvl) {
            echo str_repeat(empty($_SERVER['TERM'])? "&nbsp;&nbsp;&nbsp;&nbsp;" : "\t", $tab) . $test , $new_line ? (empty($_SERVER['TERM'])? "<br />" : null) . "\n" : null;
        }
    }

    protected function get_by_type($type, array $param = [])
    {
        assert(strpos('get_', $type) === 1);

        return call_user_func_array(
            [$this, $type],
            [$param]
        );
    }

    public function get_int(
        array $param = []
    )
    {
        return $this->get_int_near(
            ['last' => null] + $param
        );
    }

    public function get_int_sorted(
        array $param = []
    )
    {
        $param += [
            'last' => null,
            'from' => -100000,
        ];
        return (
            $param['last'] === null
                ? $param['from']
                : rand(1 + $param['last'], $param['last'] + 4)
        );
    }

    
    public function get_int_near(
        array $param = []
    )
    {
        $param += [
            'from' => -1000,
            'to' => 1000,
            'last' => null,
            'range_delimiter' => 20
        ];
        
        if ($param['last'] === null) {
            $range = (int)(($param['to'] - $param['from']) / $param['range_delimiter']);
            $param['from'] = max($param['from'], $param['last'] - $range);
            $param['to'] = min($param['to'], $param['last'] + $range);
        }
   
        return rand($param['from'], $param['to']);
    }

    public function get_random($param)
    {
        $param += [
            'length' => 256,
        ];

        return random_bytes($param['length']);
    }

    public function get_array(array $param = [])
    {
        $param += [
            'type' => self::TYPE_INT, 
            'size' => 5000,
        ];

        $temp_file = '/tmp/test_serialize' . $param['type'] . '_' . $param['size'] . '.tmp';
        
        if (is_readable($temp_file) && filesize($temp_file)) {
            //$this->log("debug new range", 1);
            return unserialize(file_get_contents($temp_file));
        }

        $test = [];
        $last = null;
        for ($i = 0; $i < $param['size']; ++$i) {
            $test[] = $last = $this->get_by_type(
                $param['type'],
                ['last' => $last, 'type' => null] + $param
            );
        }

        file_put_contents($temp_file, serialize($test));
        $this->log("1st run", 1);

        return $test;
    }

    public function log_time($last = false, $lvl = self::LOG_LVL_NOTICE)
    {
        $tmp_time = microtime(true);
        $this->log(
            ($last ? 'last' : 'full') 
                . '_time: ' 
                . round($tmp_time - ($last ? $this->last_time : $this->start_time), 2) 
                . 's', 
            $lvl
        );
        $this->last_time = $tmp_time;
    }
    
    public function get_size($size, $precision = 2)
    {
        $group = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];
        while ($size > 10240) {
            $size = $size / 1024;
            array_shift($group);
        }
        
        return round($size, $precision) . current($group);
    }
    
    /**
     * @param string $type all|peak|current
     */
    public function log_mem($type = 'all', $lvl = self::LOG_LVL_NOTICE)
    {
        $mem = [];
        if ($type == 'diff' || $type == 'all') {
            $mem[] = '+'.$this->get_size(memory_get_usage() - $this->last_mem);
        }        
        if ($type == 'usage' || $type == 'all') {
            $mem[] = $this->get_size(memory_get_usage());
        }
        if ($type == 'peak' || $type == 'all') {
            $mem[] = $this->get_size(memory_get_peak_usage());
        }
        $this->log(
            'memory: ' . implode(' / ', $mem),
            $lvl
        );
    }
    
    public function __destruct()
    {
        $this->log(null, self::LOG_LVL_NOTICE);//new line
    }

    public function reset()
    {
        $this->last_time = microtime(true);
        $this->last_mem = memory_get_usage();
    }

    public function test_func($func, $param, $name = null) 
    {
        $this->reset();
        $this->log(
            ($name ? $name : (is_string($func) ? $func : 'callback')) . ': ' 
                . var_export(call_user_func_array($func, $param), true)
        );
        $this->log(null, self::LOG_LVL_NOTICE, false, 1);
        $this->log_time('last');
        $this->log(null, self::LOG_LVL_NOTICE, false, 1);
        $this->log_mem();
    }        
}
