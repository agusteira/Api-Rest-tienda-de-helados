<?php
/*
    2-
    (1pt.) HeladoConsultar.php: (por POST) Se ingresa Sabor y Tipo, si coincide con algún registro del archivo
    heladeria.json, retornar “existe”. De lo contrario informar si no existe el tipo o el nombre.
*/

include "Entidades/helados.php";

$consulta = Helados::RealizarConsultaSobreHelado();

if ($consulta){
    echo "<br> Existe<br>";
}
else{
    echo "<br> No existe<br>";
}

