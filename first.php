<?php
$a = new Counter();
$a->getCount();

class Counter
{
    public $count;

    public function __construct()
    {
        $myfile = fopen("./ftest.txt", "r") or die("Unable to open file!");
        while (($line = fgets($myfile)) !== false) {
            $firstNumber = $this->firstdiget($this->all_digits($line));
            $lastNumber = $this->lastdigit($this->all_digits($line, TRUE));
            $this->count = $this->count + intval($firstNumber . $lastNumber);
        }
    }

    public function getCount()
    {
        print 'Count is:' . $this->count;
    }

    public function firstdiget($match)
    {
        return $this->convertWordToNumber($match);
    }

    public function lastdigit($match)
    {
        return $this->convertWordToNumber($match, TRUE);
    }

    public function getWords($reverse = FALSE)
    {
        $numbers = ["one", "two", "three", "four", "five", "six", "seven", "eight", "nine"];
        if (!$reverse) {
            return $numbers;
        }

        foreach ($numbers as $number) {
            $return[] = strrev($number);
        }
        return $return;
    }

    function all_digits($line, $end = FALSE)
    {
        $line = $end ? strrev($line) : $line;
        $pattern = '/(?:' . implode('|', $this->getWords($end)) . '|\d)/';
        preg_match_all($pattern, $line, $matches);
        return $matches[0][0];
    }

    function convertWordToNumber($word, $reverse = FALSE)
    {
        if (is_numeric($word)) {
            return $word;
        }
        $wordToNumberMap = [
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5,
            'six' => 6,
            'seven' => 7,
            'eight' => 8,
            'nine' => 9,
        ];
        $return = [];
        if ($reverse) {
            foreach ($wordToNumberMap as $key => $value) {
                $return[strrev($key)] = $value;
            }
            return $return[$word];
        }

        return $wordToNumberMap[$word];
    }
}

