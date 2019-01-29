<?php

class Grid
{
    public $letters;
    public $gridRows;
    public $gridCells; //Array containing 0, 1 or 2 representing what output should be displayed in each cell

    public function outputGrid($letters, $gridRows, $gridCells) {
        $gridColumns = sizeof($letters);

        echo(" ");
        foreach ($letters as $letter) {
            echo ("   " . $letter);
        };
        echo("\n");
        for ($j = 0; $j < $gridRows; $j++) {
            echo ("  ");
            foreach ($letters as $letter) {
                echo ("+---");
            };
            echo ("+\n" . $j . " ");
            foreach ($letters as $letter) {
                echo ("| ");       
                if ($gridCells[$letter][$j] == 2) {
                    echo("o ");
                } else {
                    echo("  ");
                };
            };
            echo ("|\n");
        };
        echo ("  ");
        for ($i = 0; $i < $gridColumns; $i++) {
            echo ("+---");
        };
        echo ("+\n");
    }
}