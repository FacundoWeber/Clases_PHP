<!DOCTYPE html>
<html>
<body>

<?php
$palabra = "hola mundo";

function vocales($palabra){
    $cantidad = 0;
    for ($i=0; $i<strlen($palabra); $i++){
        if ($palabra[$i] == "a" || $palabra[$i] == "e" || $palabra[$i] == "i" || $palabra[$i] == "o" || $palabra[$i] == "u"){
            $cantidad++;
        }
        elseif ($palabra[$i] == "A" || $palabra[$i] == "E" || $palabra[$i] == "I" || $palabra[$i] == "O" || $palabra[$i] == "U"){
        $cantidad++;
        } 
    }
    return $cantidad;
}

$cantidad = vocales($palabra);

print($cantidad);
?>

</body>
</html>