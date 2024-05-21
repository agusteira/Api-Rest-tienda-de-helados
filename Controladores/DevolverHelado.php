<?php
/*
    6- (2 pts.) DevolverHelado.php (por POST),
    Guardar en el archivo (devoluciones.json y cupones.json):
    a- Se ingresa el número de pedido y la causa de la devolución. El número de pedido debe existir, se ingresa una
    foto del cliente enojado,esto debe generar un cupón de descuento (id, devolucion_id, porcentajeDescuento,
    estado[usado/no usado]) con el 10% de descuento para la próxima compra.
*/

include_once "Entidades/Ventas.php";
include_once "Entidades/CuponDescuento.php";
include_once "Entidades/Devolucion.php";

$numeroDePedido = $_POST['numeroPedido']; 



if(Ventas::ComprobarNumeroPedido($numeroDePedido)){
    $causaDevolucion = $_POST['causaDevolucion']; 
    $creacionCupon = false;

    if (isset($_FILES["fotoClienteEnojado"])){
        $cupon = new CuponDescuento($devolucion->_idDevolucion, 10);
        echo "<br>Se genero un cupon de descuento por el cliente enojado<br>";
        $creacionCupon = true;
    }

    $devolucion = new Devolucion($numeroDePedido, $causaDevolucion, $creacionCupon);
    echo "<br>devolucion echa con exito<br>";
}
else{
    echo "No existe el numero de pedido";
}