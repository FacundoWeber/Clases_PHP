<html>
<body>

<h1>Cuantas vocales tiene la palabra?</h1>

<form action="test.php" method="post">
Palabra: <input type="text" name="palabra"><br>
<input type="submit">
</form>

<?php
function contar_vocal($palabra,$pattern) {
    if ($palabra){
        return preg_match_all($pattern, $palabra);
    }    
}

$palabra = $_POST["palabra"] ?? "";
$pattern = "/[aeiou]/i";

echo contar_vocal($palabra,$pattern);
?>

</body>
</html>