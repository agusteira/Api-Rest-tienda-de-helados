<?php

include_once "JSONs/ManejarJSON.php";

class CuponDescuento{
    public $_idDevolucion;
    public $_idCupon;
    public $_porcentajeDescuento;
    public $_estado;
    private static $ArrayCupones = [];

    public function __construct($_idDevolucion, $porcentajeDescuento){
        $this->_idDevolucion = $_idDevolucion;
        $this->_idCupon = "DESC" . rand(1,10000);
        $this->_estado = "no usado";
        $this->_porcentajeDescuento = $porcentajeDescuento;
        $this->GuardarCupon();
    }

    public function GuardarCupon(){
        ManejarJSONS::GuardarCuponEnJSON($this);
    }

    public static function UsarCupon($idCupon){
        $retorno = false;
        try{
            ManejarJSONS::ActualizarEstadoDeCupon($idCupon);
            $retorno = true;
        }
        finally{
            return $retorno;    
        }
        
    }

    /**********************************************************REALIZAR CONSULTAS**************************************************/

    private static function InicilizarArrayDeCupones(){
        CuponDescuento::$ArrayCupones = ManejarJSONS::ObtenerValoresJSONCupones();
    }
    public static function ComprobarValidez($cuponID){
        $retorno = false;
        CuponDescuento::InicilizarArrayDeCupones();
        foreach (CuponDescuento::$ArrayCupones as $cupon){
            if ($cupon["idCupon"]== $cuponID && $cupon["estado"] == "no usado"){
                $retorno = true;
                break;
            }
        }
        return $retorno;
    }

    public static function ObtenerCupon($cuponID){
        $retorno = [];
        CuponDescuento::InicilizarArrayDeCupones();
        foreach (CuponDescuento::$ArrayCupones as $cupon){
            if ($cupon["idCupon"]== $cuponID){
                $retorno = $cupon;
                break;
            }
        }
        return $retorno;
    }

    public static function ListEstadoCupones(){
        CuponDescuento::InicilizarArrayDeCupones();
        CuponDescuento::MostrarListado(CuponDescuento::$ArrayCupones);
    }

    public static function ListEstadoCuponesConDevolucion(){
        CuponDescuento::InicilizarArrayDeCupones();
        $devoluciones = ManejarJSONS::ObtenerValoresJSONDevoluciones();
        foreach(CuponDescuento::$ArrayCupones as $cupon){
            foreach ($devoluciones as $devolucion){
                if ($devolucion["idDevolucion"] == $cupon["idDevolucion"]){
                    echo "<br>Devolucion y su cupon: <br>";
                    Devolucion::MostrarDevolucion($devolucion);
                    CuponDescuento::MostrarCupon($cupon);
                }
            }
        }
    }
    public static function MostrarListado($arrayCupones){
        foreach ($arrayCupones as $cupon){
            CuponDescuento::MostrarCupon($cupon);
        }
    }

    public static function MostrarCupon($cupon){
        echo "<br>" . $cupon["idCupon"] . "-" . $cupon["estado"]  . "<br>";
    }


}