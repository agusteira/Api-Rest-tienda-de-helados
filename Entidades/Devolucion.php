<?php
include_once "JSONs/ManejarJSON.php";

class Devolucion{
    public $_idDevolucion;
    public $_numeroPedido;
    public $_causaDeDevolucion;
    public $_cuponCreado;

    private static $ArrayDevoluciones = [];

    public function __construct($numeroDePedido, $causaDevolucion, $cuponCreado){
        $this->_idDevolucion =  rand(1,10000);
        $this->_numeroPedido = $numeroDePedido;
        $this->_causaDeDevolucion = $causaDevolucion;
        $this->_causaDeDevolucion = $cuponCreado;
        $this->GuardarDevolucion();
    }

    public function GuardarDevolucion(){
        ManejarJSONS::GuardarDevolucionEnJSON($this);
    }

    /**********************************************************REALIZAR CONSULTAS**************************************************/

    private static function InicilizarArrayDeDevoluciones(){
        Devolucion::$ArrayDevoluciones = ManejarJSONS::ObtenerValoresJSONDevoluciones();
    }

    public static function ListDevolucionesConCupones(){
        $arrayConsulta = [];
        Devolucion::InicilizarArrayDeDevoluciones();
        foreach (Devolucion::$ArrayDevoluciones as $devolucion){
            if ($devolucion["cupon"] == true){
                $arrayConsulta [] = $devolucion;
            }
        }
        return $arrayConsulta;
    }
    public static function MostrarListado($arrayDevoluciones){
        foreach ($arrayDevoluciones as $devolucion){
            Devolucion::MostrarDevolucion($devolucion);
        }
    }

    public static function MostrarDevolucion($devolucion){
        echo "<br>" . $devolucion["idDevolucion"] . "-" . $devolucion["NumeroPedido"]  . "-" . $devolucion["CausaDeDevolucion"]. "-" . $devolucion["cupon"]. "<br>";
    }

}