<?php
/*
Se debe realizar una aplicación para dar de ingreso con imagen del item.
Se deben respetar los nombres de los archivos y de las clases.
Se debe crear una clase en PHP por cada entidad y los archivos PHP solo deben llamar a métodos de las clases.

1era parte

1-
A- (1 pt.) index.php:Recibe todas las peticiones que realiza el postman, y administra a qué archivo se debe incluir.
*/




$peticion = $_SERVER["REQUEST_METHOD"];

switch ($peticion) {
    case "GET":
        switch($_GET['tipoDeIngreso'] ){
            case "ConsultasVentas":
                include "Controladores/ConsultasVentas.php";
                break;
            case "ConsultasDevoluciones":
                include "Controladores/ConsultasDevoluciones.php";
                break;
            }
            break;
    case "POST":
        switch($_POST['tipoDeIngreso'] ){
            case "altaHelado":
                //Verifico que haya valores en las casillas requeridas
                if (isset($_POST["tipo"]) && isset($_POST["sabor"]) && isset($_POST["vaso"]) && isset($_POST["precio"]) && isset($_POST["stock"])){
                    include "Controladores/HeladeriaAlta.php";
                }
                break;
            case "consultaHelado":
                
                //Verifico que haya valores en las casillas requeridas
                if (isset($_POST["tipo"]) && isset($_POST["sabor"])){
                    include "Controladores/HeladoConsultar.php";
                }
                break;
            case "altaVenta":
                
                if (isset($_POST["tipo"]) && isset($_POST["sabor"]) && isset($_POST["vaso"]) && isset($_POST["email"])){
                    
                    include "Controladores/AltaVenta.php";
                }
                break;
            case "DevolverHelado":
                
                if (isset($_POST["numeroPedido"]) && isset($_POST["causaDevolucion"])){
                    include "Controladores/DevolverHelado.php";
                }
                break;
            default:
                echo "Seleccione una opcion correcta";
                break;
        }
        break;
    case "PUT":
        $putData = file_get_contents('php://input');
        parse_str($putData, $_PUT);
        if (isset($_PUT['numeroPedido'])){
            include "Controladores/ModificarVenta.php";
        }
        break;
    case "DELETE":
        $deleteData = file_get_contents('php://input');
        parse_str($deleteData, $_DELETE);
        if (isset($_DELETE['numeroPedido'])){
            include "Controladores/borrarVenta.php";
        }
        break;
    default:
        echo "Request invalido";
        break;
}
