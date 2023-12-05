<?php
$a = new Game();
print $a->getCount() . "\n";
print 'total power:' . $a->getTotalPower();

class Game
{
    protected $gameLimits = ['blue' => 14, 'green' => 13, 'red' => 12];
    protected $count = 0;
    protected $totalPower = 0;

    public function __construct()
    {
        $this->count = 0;
        $this->totalPower = 0;
        $myfile = fopen("./secondtest.txt", "r") or die("Unable to open file!");
        while (($line = fgets($myfile)) !== false) {
            preg_match_all('/\d+(?=:)/', $line, $matches);
            $index = $matches[0][0];
            $sets = $this->getItemsAsArrays($this->getSets($this->getAfterColon($line)));
            $pass = TRUE;
            $max_values = $this->getPowers($sets);
            $this->totalPower += $this->workOutPowers($max_values);
            foreach ($sets as $set) {
                if (!$this->doChecks($set)) {
                    $pass = FALSE;
                }
            }
            if ($pass) {
                $this->count += $index;
            }

        }
    }

    public function getCount()
    {
        return $this->count;
    }

    public function getTotalPower()
    {
        return $this->totalPower;
    }

    protected function getPowers($sets)
    {
        $max_values = [];
        foreach ($sets as $set) {
            foreach (array_keys($this->gameLimits) as $color) {
                if (isset($max_values[$color]) && isset($set[$color]) && $max_values[$color] < $set[$color]) {
                    $max_values[$color] = $set[$color];
                }
                if (isset($set[$color]) && !isset($max_values[$color])) {
                    $max_values[$color] = $set[$color];
                }
            }
        }
        return $max_values;
    }

    protected function workOutPowers($colors)
    {
        $power = 0;
        foreach ($colors as $value) {
            if ($power == 0) {
                $power = $value;
            } else {
                $power *= $value;
            }

        }
        return $power;
    }

    protected function doChecks($set)
    {
        foreach ($this->gameLimits as $color => $qty) {
            if (isset($set[$color]) && $set[$color] > $qty) {
                return FALSE;
            }
        }
        return TRUE;
    }

    protected function getAfterColon($data)
    {
        return substr($data, strpos($data, ":") + 1);
    }

    protected function getSets($sets)
    {
        return explode(';', $sets);
    }

    protected function getItemsAsArrays($set)
    {
        $return = [];
        foreach ($set as $key => $colors) {

            $split_out = explode(',', $colors);
            foreach ($split_out as $second_key => $color_number) {
                $cn = explode(' ', trim($color_number));
                $return[$key][$cn[1]] = $cn[0];
            }
        }
        return $return;
    }

}