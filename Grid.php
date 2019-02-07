<?php

class Grid
{
    public $letters;
    public $gridRows;
    public $gridCells; //Array containing 0, 1 or 2 representing what output should be displayed in each cell
    public $numberOfGrids;

    public function __construct($letters, $gridRows, $gridCells, $numberOfGrids)
    {
        $this->letters = $letters;
        $this->gridRows = $gridRows;
        $this->gridCells = $gridCells;
        $this->numberOfGrids = $numberOfGrids;
    }

    public function outputGrids()
    {
        $gridColumns = sizeof($this->letters);

        echo(" ");
        for ($n=1;$n<=$this->numberOfGrids;$n++) {
            foreach ($this->letters as $letter) {
                echo ("   " . $letter);
            }
            if ($n !== $this->numberOfGrids) {
                echo("     ");
            }
        };
        echo("\n");
        for ($j = 0; $j < $this->gridRows; $j++) {
            for ($n=1;$n<=$this->numberOfGrids;$n++) {
                echo ("  ");
                foreach ($this->letters as $letter) {
                    echo ("+---");
                };
                if ($n !== $this->numberOfGrids) {
                    echo ("+  ");
                }
            }
            echo ("+\n" . $j . " ");
            for ($n=1;$n<=$this->numberOfGrids;$n++) {
                foreach ($this->letters as $letter) {
                    echo ("| ");       
                    if ($this->gridCells[$n][$letter][$j] == 2) {
                        echo("o ");
                    } elseif ($this->gridCells[$n][$letter][$j] == 1) {
                        echo("x ");
                    } elseif ($this->gridCells[$n][$letter][$j] == -1) {
                        echo("- ");
                    } else {
                        echo("  ");
                    };
                };
                if ($n !== $this->numberOfGrids) {
                    echo("|  " . $j . " ");
                }
            }
            echo ("|\n");
        };
        echo ("  ");
        for ($n=1;$n<=$this->numberOfGrids;$n++) {
            for ($i = 0; $i < $gridColumns; $i++) {
                echo ("+---");
            };
            if ($n !== $this->numberOfGrids) {
                echo ("+    ");
            }
        }
        echo ("+\n");
    }
}
