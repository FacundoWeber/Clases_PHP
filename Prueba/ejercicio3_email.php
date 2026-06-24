<html>

<?php
$email1 = "  ANA.GARCIA@gmail.com  ";  // espacios y mayúsculas
$email2 = "juan.perezhotmail.com";    // falta el @
$email3 = "carlos@universidad.edu.ar"; // correcto
$email4 = "maria@";                  // @ pero sin dominio

function limpiar($email){
    $email=trim($email);
    $email=strtolower($email);
    return $email;
}


function Usuario($email){
    if (strpos($email,"@") == true){
        $email = substr($email,0,strpos($email,"@")); 
    }
    else{
        $email="El email no contiene arroba o usuario.";
    }
    return $email;
}


function Dominio($email){
    $offset = (strpos($email,("@"))+1);
    $email = substr($email,$offset);
    if ($email == "" || $offset == 1){
        $email = "No hay dominio";
    }
    else{
        $email = str_replace(".","-",$email);
    }
    
    return $email;
}

function seleccion_email($email1,$email2,$email3,$email4){
    $vector = ["","","",""];
    $vector[0]=limpiar($email1);
    $vector[1]=limpiar($email2);
    $vector[2]=limpiar($email3);
    $vector[3]=limpiar($email4);
    
    for ($i=0;$i<4;$i++){
        echo "-Usuario: "; echo Usuario($vector[$i]), "<br/>";
        echo " -Dominio: "; echo Dominio($vector[$i]),"<br/><br/>";
    }
}

seleccion_email($email1,$email2,$email3,$email4);
?>
</html>