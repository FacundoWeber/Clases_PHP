<?php
include 'ejercicio4.css';

$nombre=$_POST['nombre'];
$notas1=floatval($_POST['notas1']);
$notas2=floatval($_POST['notas2']);
$notas3=floatval($_POST['notas3']);
$notas4=floatval($_POST['notas4']);
$vector=[$notas1,$notas2,$notas3,$notas4];  

function promedio($vector){
    $promedio=0;
    $contador=0;
    for ($i=0;$i<4;$i++){
        if ($vector[$i] < 0 || $vector[$i] > 10){
            echo "Una nota no es correcta";
            $promedio=0;
            break;
        }
        if ($vector[$i] != 0){
            $promedio += $vector[$i];
            $contador++;
        }
    }
    if ($promedio){
        echo "El promedio de las notas es: ";
        $promedio = $promedio / $contador;
        
        if ($promedio < 6){
            echo '<p class=clase_desaprobado>'. $promedio .' Desaprobado </p>';
        }
    }
}

promedio($vector);
?>