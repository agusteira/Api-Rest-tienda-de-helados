<?php
/*
    ModificarVenta.php (por PUT)
    Debe recibir el número de pedido, el email del usuario, el nombre, tipo, vaso y cantidad, si existe se modifica , de
    lo contrario informar que no existe ese número de pedido. 
*/

include_once "Entidades/Ventas.php";



$numeroDePedido = $_PUT['numeroPedido']; 

var_dump($numeroDePedido);

if(Ventas::ComprobarNumeroPedido($numeroDePedido)){

    $usuarioModificado = $_PUT['usuario']; 
    $saborModificado = $_PUT['sabor'];
    $vasoModificado = $_PUT['vaso'];

    Ventas::ModificarVenta($numeroDePedido, $usuarioModificado, $saborModificado, $vasoModificado);
}   
else{
    echo "<br>No existe el numero de pedido<br>";
}