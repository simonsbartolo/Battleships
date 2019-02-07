<?php

require_once('Grid.php');

$letters = array('A', 'B', 'C', 'D', "E", 'F', 'G', 'H', 'I', 'J');
$cells = array();
$gridColumns = sizeof($letters);
$gridRows = 10;
$shipsizes = array
(
    'destroyer' => 2,
    'cruiser' => 3,
    'submarine' => 3,
    'battleship' => 4,
    'carrier' => 5
    );

$ships = array (
    'destroyer' => array(),
    'cruiser' => array(),
    'submarine' => array(),
    'battleship' => array(),
    'carrier' => array()
);
foreach ($letters as $letter) {
    $userBattleshipGrid[$letter] = array_fill(0, $gridRows, 0);
    $userGuesses[$letter] = array_fill(0, $gridRows, 0);
    for ($i=0;$i<$gridRows;$i++) {
        $compGuesses[$letter . $i] = 0;
    }
}

$placeShips = new Grid($letters, $gridRows, array(1 => $userBattleshipGrid), 1);

$username = readline("Enter username >> ");

echo("\nPlace your battleships from smallest to largest. First you choose the orientation of the battleship, 'h' for horizontal or 'v' for vertical.\n");
echo("Then choose the cell of either the leftmost (if you chose horizontal orientation) or topmost (if you chose vertical orientation) position of the ship.\n\n");
foreach ($shipsizes as $shipname => $shipsize) {

    $placeShips->gridCells = array(1 => $userBattleshipGrid);
    $placeShips->outputGrids();

    $userOrientation = readline("Choose the orientation of your " . $shipname . " (size " . $shipsize . ") >> ");
    while (strtoupper($userOrientation) != "H" && strtoupper($userOrientation) != "V") {
        $userOrientation = readline("Invalid orientation. Enter h' for horizontal or 'v' for vertical.\nChoose the orientation of your " . $shipname . " (size " . $shipsize . ") >> ");
    }
    
    foreach ($letters as $letter) {
        $horizontal[$letter] = array_fill(0, $gridRows, 0);
        $vertical[$letter] = array_fill(0, $gridRows, 0);
    };
    for ($k=0;$k<$gridColumns;$k++) {
        for ($i=0;$i<$gridRows;$i++) {
            if ($userBattleshipGrid[$letters[$k]][$i] == 2) {
                for ($j=$i;$j>$i-$shipsize;$j--) {
                    if ($j<0) {
                        break;
                    };
                    $vertical[$letters[$k]][$j] = 1;
                };
                for ($l=$k;$l>$k-$shipsize;$l--) {
                    if ($l<0) {
                        break;
                    };
                    $horizontal[$letters[$l]][$i] = 1;
                };
            };
            if ($shipsize + $k > 10) {
                $horizontal[$letters[$k]][$i] = 1;
            }
            if ($shipsize + $i > 10) {
                $vertical[$letters[$k]][$i] = 1;
            }
        };
    };
    if (strtoupper($userOrientation) === "H") {
        $startCell = readline("Choose the leftmost cell of your " . $shipname . " >> ");
        $startCellLetterKey = array_search(strtoupper(substr($startCell,0,1)),$letters);
        $startCellNumber= substr($startCell,1,1);
        while ($horizontal[$letters[$startCellLetterKey]][$startCellNumber] === 1) {
            $startCell = readline("This is an invalid start cell. Choose the leftmost cell of your " . $shipname . " again >> ");
            $startCellLetterKey = array_search(strtoupper(substr($startCell,0,1)),$letters);
            $startCellNumber= substr($startCell,1,1);
        }
        for ($insert = 0;$insert<$shipsize;$insert++) {
            $userBattleshipGrid[$letters[$startCellLetterKey+$insert]][$startCellNumber] = 2;
        }
    } else {
        $startCell = readline("Choose the topmost cell of your " . $shipname . " >> ");
        $startCellLetterKey = array_search(strtoupper(substr($startCell,0,1)),$letters);
        $startCellNumber= substr($startCell,1,1);
        while ($vertical[$letters[$startCellLetterKey]][$startCellNumber] === 1) {
        $startCell = readline("This is an invalid start cell. Choose the topmost cell of your " . $shipname . " again >> ");
        $startCellLetterKey = array_search(strtoupper(substr($startCell,0,1)),$letters);
        $startCellNumber= substr($startCell,1,1);
        }
        for ($insert = 0;$insert<$shipsize;$insert++) {
            $userBattleshipGrid[$letters[$startCellLetterKey]][$startCellNumber+$insert] = 2;
        }
    }      
}

$compRef = $userBattleshipGrid;

$battleGrids = new Grid($letters, $gridRows, array(1 => $userBattleshipGrid, 2 => $userGuesses), 2);

echo("              Your Battleships                              Computer Grid\n");
    
$battleGrids->outputGrids();

foreach ($letters as $letter) {
    $cells[$letter] = array_fill(0, $gridRows, 0);
};


foreach($shipsizes as $shipname => $shipsize) {
    //update the arrays horizontal and vertical which represent which cells are disallowed ship start points
    //when placed horizontally or vertically respectively
    foreach ($letters as $letter) {
        $horizontal[$letter] = array_fill(0, $gridRows, 0);
        $vertical[$letter] = array_fill(0, $gridRows, 0);
    };
    for ($k=0;$k<$gridColumns;$k++) {
        for ($i=0;$i<$gridRows;$i++) {
            if ($cells[$letters[$k]][$i] == 1) {
                for ($j=$i;$j>$i-$shipsize;$j--) {
                    if ($j<0) {
                        break;
                    };
                    $vertical[$letters[$k]][$j] = 1;
                };
                for ($l=$k;$l>$k-$shipsize;$l--) {
                    if ($l<0) {
                        break;
                    };
                    $horizontal[$letters[$l]][$i] = 1;
                };
            };
        };
    };

    
    $orientation = rand(0, 1);
//when orientation is 0 ship is placed vertically, when orientation is 1 ship is placed horizontally

    if ($orientation == 0) {
        //choose which cell to place the top of the ship
        do {
            $x = $letters[rand(0, 9)];
            $y = rand(0, 10-$shipsize);
        } while ($vertical[$x][$y] == 1);
        
        //place the ship on the grid
        for ($i=$y; $i<$y+$shipsize; $i++) {
            $cells[$x][$i] = 1;
            $ships[$shipname][$x . $i] = 1;
        };
    } else {
        //choose which cell to place the left of the ship
        do {
            $x = rand(0, 10-$shipsize);
            $y = rand(0, 9);
        } while($horizontal[$letters[$x]][$y] == 1);
       
        //place the ship on the grid
        for ($i=$x; $i<$x+$shipsize; $i++) {
            $cells[$letters[$i]][$y] = 1;
            $ships[$shipname][$letters[$i] . $y] = 1;
        };
    }

};
while (array_sum($compRef['A'])+array_sum($compRef['B'])+array_sum($compRef['C'])+array_sum($compRef['D'])+array_sum($compRef['E'])+array_sum($compRef['F'])+array_sum($compRef['G'])+array_sum($compRef['H'])+array_sum($compRef['I'])+array_sum($compRef['J'])>0 && array_sum($cells['A'])+array_sum($cells['B'])+array_sum($cells['C'])+array_sum($cells['D'])+array_sum($cells['E'])+array_sum($cells['F'])+array_sum($cells['G'])+array_sum($cells['H'])+array_sum($cells['I'])+array_sum($cells['J'])>0) {
    $fire = strtoupper(readline("Name the cell you would like to shoot: "));
    $fireLetter = substr($fire,0,1);
    $fireNumber = substr($fire,1,1);
    while($userGuesses[$fireLetter][$fireNumber]!==0) {
        $fire = strtoupper(readline("You can't shoot the same place twice!\nName the cell you would like to shoot: "));
        $fireLetter = substr($fire,0,1);
        $fireNumber = substr($fire,1,1);
    };
    if ($cells[$fireLetter][$fireNumber] == 1) {
        $userGuesses[$fireLetter][$fireNumber] = 1;
        $cells[$fireLetter][$fireNumber] -= 1;
        echo ($username . ": HIT!\n");
        foreach ($ships as $shipname => $position) {
            if($position[$fire] == 1) {
                $ships[$shipname][$fire]=0;
                if(array_sum($ships[$shipname]) == 0) {
                    echo (strtoupper($shipname) . " SUNK!\n");
                    $position = array(1);
                }
                break;
            }
        }
    } else {
        $userGuesses[$fireLetter][$fireNumber] = -1;
        echo ($username . ": Miss...\n");
    };
    
    if (array_sum($cells['A'])+array_sum($cells['B'])+array_sum($cells['C'])+array_sum($cells['D'])+array_sum($cells['E'])+array_sum($cells['F'])+array_sum($cells['G'])+array_sum($cells['H'])+array_sum($cells['I'])+array_sum($cells['J'])==0){
        echo("              Your Battleships                              Computer Grid\n");
        $battleGrids->gridCells = array(1 => $userBattleshipGrid, 2 => $userGuesses);
        $battleGrids->outputGrids();
        break;
    }
    //Computer shot
    $compGuess = array_rand($compGuesses);
    $compGuessLetter = strtoupper(substr($compGuess,0,1));
    $compGuessNumber= substr($compGuess,1,1);
    if ($compRef[$compGuessLetter][$compGuessNumber] == 2) {
        $userBattleshipGrid[$compGuessLetter][$compGuessNumber] = 1;
        $compRef[$compGuessLetter][$compGuessNumber] = 0;
        echo("Computer: HIT!\n");
    } else {
        $userBattleshipGrid[$compGuessLetter][$compGuessNumber] = -1;
        echo("Computer: Miss...\n");
    }
    unset($compGuesses[$compGuessLetter][$compGuessNumber]);
    //End Computer shot
    echo("              Your Battleships                              Computer Grid\n");
    $battleGrids->gridCells = array(1 => $userBattleshipGrid, 2 => $userGuesses);
    $battleGrids->outputGrids();
};

if (array_sum($compRef['A'])+array_sum($compRef['B'])+array_sum($compRef['C'])+array_sum($compRef['D'])+array_sum($compRef['E'])+array_sum($compRef['F'])+array_sum($compRef['G'])+array_sum($compRef['H'])+array_sum($compRef['I'])+array_sum($compRef['J']) == 0) {
    echo ("Computer wins.\n");
} else {
    echo ($username . ", you win!\n");
}
