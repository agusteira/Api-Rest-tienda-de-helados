<?php
/*
    Datos a consultar:
    a- La cantidad de Helados vendidos en un día en particular(se envía por parámetro), si no se pasa fecha, se
    muestran las del día de ayer.
    b- El listado de ventas de un usuario ingresado.
    c- El listado de ventas entre dos fechas ordenado por nombre.
    d- El listado de ventas por sabor ingresado.
    e- El listado de ventas por vaso Cucurucho.
*/
include "Entidades/Ventas.php";

$tipoDeConsulta = $_GET["consultaSobreVentas"];

switch($tipoDeConsulta){
    case "a":
        if (isset($_GET["fechaAConsultar"])){
            $cantHeladosPorDia = Ventas::CantHeladosPorDia($_GET["fechaAConsultar"]);
        }
        else{
            $cantHeladosPorDia = Ventas::CantHeladosPorDia();
        }
        echo "La cantidad de helados vendidos ese dia fue de: " . $cantHeladosPorDia;
        break;
    case "b":
        if (isset($_GET["usuario"])){
            Ventas::MostrarListado(Ventas::ListVentasDeUnUsuario($_GET["usuario"]));
        }
        break;
    case "c":
        if (isset($_GET["fechaMenor"]) && isset($_GET["fechaMayor"])){
            Ventas::MostrarListado(Ventas::ListVentasEntreDosFechasOrdenadoPorNombre($_GET["fechaMenor"], $_GET["fechaMayor"]));
        }
        break;
    case "d":
        if (isset($_GET["sabor"])){
            Ventas::MostrarListado(Ventas::ListVentasPorSabor($_GET["sabor"]));
        }
        break;
    case "e":
        Ventas::MostrarListado(Ventas::ListVentasVasoCucurucho());
        break;
}
