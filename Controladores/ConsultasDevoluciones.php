<?php
/*
    Datos a consultar:
    a- Listar las devoluciones con cupones.
    b- Listar solo los cupones y su estado.
    c- Listar devoluciones y sus cupones y si fueron usados
*/
include "Entidades/Devolucion.php";
include "Entidades/CuponDescuento.php";

$tipoDeConsulta = $_GET["consultaSobreDevoluciones"];

switch($tipoDeConsulta){
    case "a":
        Devolucion::MostrarListado(Devolucion::ListDevolucionesConCupones());
        break;
    case "b":
        CuponDescuento::ListEstadoCupones();
        break;
    case "c":
        CuponDescuento::ListEstadoCuponesConDevolucion();
        break;
}
