<?php
$alumnos_finalizado = array(
    ["Ana García","Sistemas",7.50,"Aprobado"],
    ["Juan Pérez","Sistemas",4.50,"Desaprobado"],
    ["Maria López","Sistemas",9.00,"Aprobado"],
    ["Carlos Ruiz","Sistemas",5.00,"Desaprobado"]
);
$alumnos = array(
    ["Ana García","Sistemas",[10,10,5,7]],
    ["Juan Pérez","Sistemas",[1,4,5,1]],
    ["Maria López","Sistemas",[4,7,8,9]],
    ["Carlos Ruiz","Sistemas",[4,9,6,2]]
);


function verificar($alumnos){
    $i=0;
    $var_array=array();
    for ($i;$i<count($alumnos);$i++){
        $var_array[$i] = (array_sum($alumnos[$i][2]) / 4);
    }
    return $var_array;
}

$var_array=array( verificar($alumnos));
?>