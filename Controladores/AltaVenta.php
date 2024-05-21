<?php
/*
    3-
    a- (1 pts.) AltaVenta.php: (por POST) se recibe el email del usuario y el Sabor, Tipo y Stock, si el ítem existe en
    heladeria.json, si hay stock guardar en la base de datos (con la fecha, número de pedido y id autoincremental ) .
    Se debe descontar la cantidad vendida del stock.
    (1 pt) Completar el alta de la venta con imagen de la venta (ej:una imagen del usuario), guardando la imagen
    con el sabor+tipo+vaso+mail(solo usuario hasta el @) y fecha de la venta en la carpeta /ImagenesDeLaVenta/2024.
*/ 

include_once "Entidades/Ventas.php";
include_once "Entidades/Helados.php";
include_once "Entidades/CuponDescuento.php";


$emailUsuario = $_POST['email']; 
$sabor = $_POST['sabor']; 



if(Helados::RealizaConsultaSobreStock()){
    
    //Se obtiene el tipo de helado que quiere
    if ($_POST['tipo'] == "Agua" || $_POST['tipo'] == "Crema"){
        $tipo = $_POST['tipo']; 
    }else {$tipo = NULL;}

    //Se obtiene el tipo de vaso que quiere
    if ($_POST['vaso'] == "Cucurucho" || $_POST['vaso'] == "Plastico"){
        $vaso = $_POST['vaso']; 
    }else { $vaso = NULL; }

    //Si esta seteado el cupon, se comprueba su validez, se obtiene el cupon, y se utiliza
    //(se cambia el estado)
    if (isset($_POST['cuponID'])){
        if (CuponDescuento::ComprobarValidez($_POST['cuponID'])){
            $cupon = CuponDescuento::ObtenerCupon($_POST['cuponID']); 
            if (CuponDescuento::UsarCupon($cupon["idCupon"])){
                echo "Se uso el cupon";
            }
            else{
                echo "No se pudo usar el cupon";
            }
        }else { 
            echo "Cupon INVALIDO";
            $cupon = null;
        }
    }else{ $cupon = null; }
    


    $precio = Helados::ConsultarPrecioPorSabor($sabor);

    Ventas::CrearYGuardarVenta($sabor, $tipo, $vaso, $emailUsuario, $cupon, $precio);
}
