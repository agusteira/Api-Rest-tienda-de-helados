<?php

include_once "JSONs/ManejarJSON.php";

class Ventas{

    public $_fechaVenta;
    public $_numeroPedido;
    public $_id;
    public $_saborPedido;
    public $_tipoPedido;
    public $_vasoPedido;
    public $_emailUsuario;
    public $_eliminado;
    public $_descuento;
    public $_precio;
    public $_importeFinal;
    
    private static $ArrayVentas = [];

    public function __construct($sabor, $tipo, $vaso, $email, $eliminado, $cuponIngresado, $precio, $fechaVenta = null, $numeroDePedido = null,$id = null){
        $this->_fechaVenta = $fechaVenta;
        $this->_numeroPedido = $numeroDePedido;
        $this->_id = $id;
        $this->_saborPedido = $sabor;
        $this->_tipoPedido = $tipo;
        $this->_vasoPedido = $vaso;
        $this->_emailUsuario = $email;
        $this->_eliminado = $eliminado;

        if($cuponIngresado != null){
            $this->_descuento = $cuponIngresado["porcentajeDescuento"];
        }else{ $this->_descuento = 0; }
        
        $this->_precio = $precio;
        $this->_importeFinal = $precio - $precio * ($this->_descuento / 100);
    }

    public static function CrearYGuardarVenta($sabor, $tipo, $vaso, $email, $cuponIngresado, $precio){
        $fechaVenta = date("Y-m-d");
        $numeroDePedido = uniqid(); //unico para cada pedido
        $id = rand(1,10000);
        $eliminado = false;

        $venta = new Ventas($sabor, $tipo, $vaso, $email, $eliminado, $cuponIngresado,$precio,$fechaVenta, $numeroDePedido,$id);
        
        ManejarJSONS::guardarVentaEnJSON($venta);
        $venta->SubirFoto();
    }

    public static function ModificarVenta($numeroDePedido, $usuario, $sabor, $vaso){
        Ventas::InicilizarArrayDeVentas();
        foreach (Ventas::$ArrayVentas as &$venta){
            if ($venta["numeroPedido"]== $numeroDePedido){
                $venta["usuario"] = $usuario;
                $venta["sabor"] = $sabor;
                $venta["vaso"] = $vaso;
                break;
            }
        }
        ManejarJSONS::GuardarArrayDeVentas(Ventas::$ArrayVentas);
    }

    public static function BorrarVenta($numeroPedido){
        Ventas::InicilizarArrayDeVentas();
        foreach (Ventas::$ArrayVentas as &$venta){
            if ($venta["numeroPedido"]== $numeroPedido){
                $venta["eliminado"] = true;
                Ventas::MoverFoto($venta);
                break;
            }
        }
        ManejarJSONS::GuardarArrayDeVentas(Ventas::$ArrayVentas);
    }

    private function SubirFoto(){
        $foto = $_FILES["foto"];
        if (isset($foto) && $foto['error'] == 0) {
            $rutaTemporal = $foto['tmp_name'];

            //Email hasta el primer arroba
            $emailSinArroba = strstr($this->_emailUsuario, '@', true);

            // Genera un nombre único para la imagen 
            $nombreImagen = $this->_saborPedido . '_' . $this->_tipoPedido . '_' . $this->_vasoPedido . '_' . $emailSinArroba;

            // Mueve la imagen al directorio de destino
            $carpetaDestino = 'ImagenesDeLaVenta/2024/';

            if (move_uploaded_file($rutaTemporal, $carpetaDestino . $nombreImagen)) {
                echo "Imagen subida correctamente.";
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            echo "No se seleccionó ninguna imagen.";
        }
    }

    private static function MoverFoto($venta){
        $carpetaActual = 'ImagenesDeLaVenta/2024/';
        $carpetaDestino = 'ImagenesBackupVentas/2024/';

        //Email hasta el primer arroba
        $emailSinArroba = strstr($venta["usuario"], '@', true);

        // Genera un nombre único para la imagen 
        $nombreImagen = $venta["sabor"] . '_' . $venta["tipo"] . '_' . $venta["vaso"] . '_' . $emailSinArroba;

        // Mueve la imagen al directorio de destino
        
        

        if (file_exists($carpetaActual . $nombreImagen)){
            if (rename($carpetaActual . $nombreImagen, $carpetaDestino . $nombreImagen)) {
                echo "Imagen eliminada";
            }
        }
        else{
            echo "no existe imagen a eliminar";
        }
            
    }

    /**********************************************************REALIZAR CONSULTAS**************************************************/

    private static function InicilizarArrayDeVentas(){
        Ventas::$ArrayVentas = ManejarJSONS::ObtenerValoresJSONVenta();
    }

    public static function CantHeladosPorDia($fecha = NULL){
        if ($fecha == NULL){
            $fecha = date('Y-m-d', strtotime("-1 days"));
        }
        $arrayConsulta = [];
        Ventas::InicilizarArrayDeVentas();
        foreach (Ventas::$ArrayVentas as $venta){
            if ($venta["fechaDeVenta"]== $fecha){
                $arrayConsulta [] = $venta;
            }
        }

        return count($arrayConsulta);
    }

    public static function ListVentasDeUnUsuario($usuario){
        $arrayConsulta = [];
        Ventas::InicilizarArrayDeVentas();
        foreach (Ventas::$ArrayVentas as $venta){
            if ($venta["usuario"]== $usuario){
                $arrayConsulta [] = $venta;
            }
        }
        return $arrayConsulta;
    }

    public static function ListVentasEntreDosFechasOrdenadoPorNombre($fechaMenor, $fechaMayor){
        $arrayConsulta = [];
        Ventas::InicilizarArrayDeVentas();
        foreach (Ventas::$ArrayVentas as $venta){
            if ($venta["fechaDeVenta"] > $fechaMenor && $venta["fechaDeVenta"] < $fechaMayor){
                $arrayConsulta [] = $venta;
            }
        }
        
        //usort($arrayConsulta, ['ConsultaVentas', 'CompararPorNombres']);

        return $arrayConsulta;
    }

    public static function ListVentasPorSabor ($sabor){
        $arrayConsulta = [];
        Ventas::InicilizarArrayDeVentas();
        foreach (Ventas::$ArrayVentas as $venta){
            if ($venta["sabor"] == $sabor){
                $arrayConsulta [] = $venta;
            }
        }

        return $arrayConsulta;
    }

    public static function ListVentasVasoCucurucho(){
        $arrayConsulta = [];
        Ventas::InicilizarArrayDeVentas();
        foreach (Ventas::$ArrayVentas as $venta){
            if ($venta["vaso"] == "Cucurucho"){
                $arrayConsulta [] = $venta;
            }
        }

        return $arrayConsulta;
    }

    private static function CompararPorNombres($a, $b){
        return strcmp($a["nombre"], $b["nombre"]);
    }

    public static function MostrarListado($arrayVentasConsulta){
        foreach ($arrayVentasConsulta as $venta){
            echo "<br>" . $venta["id"] . "-" . $venta["numeroPedido"] ."-" . $venta["usuario"] . "-" . $venta["sabor"] . "-" . $venta["vaso"] . "-" . $venta["fechaDeVenta"]. "<br>";
        }
    }

    public static function ComprobarNumeroPedido($numeroDePedido){
        $retorno = false;
        Ventas::InicilizarArrayDeVentas();
        foreach (Ventas::$ArrayVentas as $venta){
            if ($venta["numeroPedido"]== $numeroDePedido){
                $retorno = true;
            }
        }
        return $retorno;
    }


}
