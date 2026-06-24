<html>
<body>

<h1>Codigo</h1>

<?php
$codigo1 = " prod-1234 ";
$codigo2 = "PROD1234"; 
$codigo3 = "PROD-12";
$codigo4 = "PROD-5678";
$numeros = "1234567890";

function verificar($codigo,$numeros) {
    $palabra = strtoupper(trim($codigo));
    if (strpos($palabra,"-")){
        if ((preg_match_all($numeros, substr($palabra,5))) == 4){
            return true;
        }
    }
}    


function seleccion_codigo($codigo1,$codigo2,$codigo3,$codigo4,$numeros){
    echo verificar($codigo1,$numeros),"<br/>";
    echo verificar($codigo2,$numeros),"<br/>";
    echo verificar($codigo3,$numeros),"<br/>";
    echo verificar($codigo4,$numeros),"<br/>";
}

seleccion_codigo($codigo1,$codigo2,$codigo3,$codigo4,$numeros);
?>

</body>
</html>