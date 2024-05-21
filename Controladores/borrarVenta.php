<?php
/*
    borrarVenta.php (por DELETE), debe recibir un número de pedido,se borra la venta(soft-delete, no
    físicamente) y la foto relacionada a esa venta debe moverse a la carpeta /ImagenesBackupVentas/2024. 
*/

include_once "Entidades/Ventas.php";

$numeroPedido = $_DELETE["numeroPedido"];

if(Ventas::ComprobarNumeroPedido($numeroPedido)){
    Ventas::BorrarVenta($numeroPedido);
}
else{
    echo "no existe ese numero de venta, elegir uno correcto";
}
