<?php
$a = new Gears();

print "\nCount is: " . $a->getCount();

class Gears
{
    protected $grid = [];

    protected $count = 0;

    public function __construct()
    {
        $myfile = fopen("./day3text.txt", "r") or die("Unable to open file!");
        while (($line = fgets($myfile)) !== false) {
            $this->buildGrid($line);
        }
        $this->walker();
    }

    /**
     * @return void
     *
     * set line, numbers and cols ocupied
     * number line indexes ocupoed
     */
    protected function buildGrid($line)
    {
        $line_array = str_split($line);
       unset($line_array[140]);
        $this->grid[] = $line_array;
    }

    protected function walker()
    {
        // Walk though array and map it out.
        foreach ($this->grid as $rkey => $row) {
            $current_number = '';
            $symbol_found = FALSE;
            print "Line " . ($rkey + 1) . ": ";
            foreach ($row as $ckey => $column) {
                if (is_numeric($this->grid[$rkey][$ckey])) {
                    $current_number .= $this->grid[$rkey][$ckey];
                    // check for surrounding symbols.
                    $this->checkForSymbols($rkey, $ckey, $symbol_found);
                } else {
                    // its not a number
                    if ($symbol_found) {
                        print $current_number . " ";
                        $this->count += (int)$current_number;
                    } else {
                        if (is_numeric($current_number)) {
                            print "<" . $current_number . ">" . " ";
                        }

                    }
                    $current_number = '';
                    $symbol_found = FALSE;
                }
                if($ckey == 139){
                    if($current_number && $symbol_found){
                        print $current_number . " ";
                        $this->count += (int)$current_number;
                        $current_number = '';
                        $symbol_found = FALSE;
                    }
                }
            }
            print "\n";
        }
    }

    protected function checkForSymbols($rkey, $ckey, &$symbol_found)
    {
        $coords = [-1, 0, 1];
        foreach ($coords as $x) {
            foreach ($coords as $y) {
                if ($x == 0 && $y == 0) continue;
                if ((isset($this->grid[$rkey + $x])
                    && isset($this->grid[$rkey + $x][$ckey + $y]))) {
                    if (!$this->checkIfNotSymbol($this->grid[$rkey + $x][$ckey + $y])) {
                        $symbol_found = TRUE;
                    }
                }
            }
        }
    }

    protected function checkIfNotSymbol($cell)
    {
        if (is_numeric($cell) || $cell === '.') {
            return TRUE;
        }
        return FALSE;
    }

    public function getCount()
    {
        return $this->count;
    }
}