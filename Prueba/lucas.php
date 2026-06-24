<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nota1 = $_POST['nota1'];
    $nota2 = $_POST['nota2'];
    $nota3 = $_POST['nota3'];
    $nota4 = $_POST['nota4'];
if ($nota1>=1 && $nota2>=1 && $nota3>=1 && $nota4>=1 && $nota1<=10 && $nota2<=10 && $nota3<=10 && $nota4<=10 ){
$promedio = ($nota1 + $nota2 + $nota3 + $nota4) / 4;
    echo "<h3>El promedio final es: " . $promedio . "</h3>";

}
else {
    echo "Las calificaciones deben estar entre 1 y 10";
}



}
?>
