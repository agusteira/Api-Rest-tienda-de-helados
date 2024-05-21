<?php

class ManejarJSONS{

    private static $_ventasFile = "JSONs/ventas.json";
    private static $_heladeriaFile = "JSONs/heladeria.json";
    private static $_cuponFile = "JSONs/cupones.json";
    private static $_devolucionFile = "JSONs/devoluciones.json";

    private static function ObtenerValoresJSON(string $archivo){
        $data = [];
        if (file_exists($archivo)) {
            $data = json_decode(file_get_contents($archivo), true);
        }
        return $data;
    }

    private static function GuardarJSON($archivo, $data){
        try{
            file_put_contents($archivo, json_encode($data));
            $retorno = 1;
        }
        catch(Exception $e){
            $retorno = 0;
        }
        
        return $retorno;
    }

    private static function ObtenerJsonYGuardarloConNuevosDatos($archivoJSON,$dataIncluir){
        $dataJSON = ManejarJSONS::obtenerValoresJSON($archivoJSON);

        $dataJSON[] = $dataIncluir;

        $retorno = ManejarJSONS::GuardarJSON($archivoJSON, $dataJSON);

        return $retorno;
    }

/**********************************************************VENTAS**************************************************************/

    public static function guardarVentaEnJSON(Ventas $ventas) {
        $ventaData = [
            'id' => $ventas->_id,
            'numeroPedido' => $ventas->_numeroPedido,
            'fechaDeVenta' => $ventas->_fechaVenta,
            "sabor" => $ventas->_saborPedido,
            "vaso" => $ventas->_vasoPedido,
            "tipo" => $ventas->_tipoPedido,
            "usuario" => $ventas->_emailUsuario,
            "descuento" => $ventas->_descuento,
            "importeFinal" => $ventas->_importeFinal,
            "eliminado" => $ventas->_eliminado,
        ];

        $retorno = ManejarJSONS::ObtenerJsonYGuardarloConNuevosDatos(ManejarJSONS::$_ventasFile, $ventaData);
        
        return $retorno;
    }

    public static function ObtenerValoresJSONVenta(){
        $ventas = ManejarJSONS::ObtenerValoresJSON(ManejarJSONS::$_ventasFile);
        return $ventas;
    }

    public static function GuardarArrayDeVentas($ventas){
        ManejarJSONS::GuardarJSON(ManejarJSONS::$_ventasFile, $ventas);
    }

/**********************************************************HELADOS**************************************************************/

    public static function guardarHeladoEnJSON($Helado) {
        $heladoData = [
            'id' => $Helado->_ID,
            'sabor' => $Helado->_sabor,
            'precio' => $Helado->_precio,
            'tipo' => $Helado->_tipo,
            'vaso' => $Helado->_vaso,
            'stock' => $Helado->_stock
        ];

        $retorno = ManejarJSONS::ObtenerJsonYGuardarloConNuevosDatos(ManejarJSONS::$_heladeriaFile, $heladoData);
        
        return $retorno;
    }

    public static function ConvertirJSONEnHelados(){
        $data = ManejarJSONS::ObtenerValoresJSON(ManejarJSONS::$_heladeriaFile);
        $arrayHeladeria = [];

        if ($data != null){
            foreach($data as $helado){
                $altaVieja = new Helados( $helado["sabor"],  $helado["tipo"],$helado["id"], $helado["precio"], $helado["vaso"], $helado["stock"]);
                array_push($arrayHeladeria, $altaVieja);
            }
        }

        return $arrayHeladeria;
    }

    public static function ConvertirHeladosEnArrayYGuardar($helados){
        foreach ($helados as $helado){
            $heladoData = [
                'id' => $helado->_ID,
                'sabor' => $helado->_sabor,
                'precio' => $helado->_precio,
                'tipo' => $helado->_tipo,
                'vaso' => $helado->_vaso,
                'stock' => $helado->_stock
            ];
            $heladeriaData[] = $heladoData;
        }

        $retorno = ManejarJSONS::GuardarJSON(ManejarJSONS::$_heladeriaFile,$heladeriaData);

        return $retorno;
    }

    /*  Actualizar valores  */
    public static function ActualizarHelado($helado){
        $indice = ManejarJSONS::RevisarRegistroHelados($helado);
        $helados = ManejarJSONS::ConvertirJSONEnHelados();

        $helados[$indice]->_precio = $helado->_precio;
        $helados[$indice]->_stock = $helado->_stock;

        $retorno = ManejarJSONS::ConvertirHeladosEnArrayYGuardar($helados);

        if ($retorno == 1){
            //1 verifica que se haya subido un nuevo helado
            //2 verfica que se actualizo un helado, entonces
            //como estamos actualizando y se guardo el json
            //hay que cambiar el 1 por el 2
            $retorno = 2;
        }

        return $retorno;
    }

    public static function ActualizarStockPorVenta($indice){
        $helados = ManejarJSONS::ConvertirJSONEnHelados();

        $helados[$indice]->_stock = $helados[$indice]->_stock - 1;

        ManejarJSONS::ConvertirHeladosEnArrayYGuardar($helados);
    }

    /*  Realizar consultar  */

    public static function RevisarRegistroHelados($helado1){
        $retorno = -1;
        if($helado1 instanceof Helados){
            $helados = ManejarJSONS::ConvertirJSONEnHelados();
            $indice = 0;
            foreach ($helados as $helado2){
                if(Helados::Equal($helado1, $helado2)){
                    $retorno = $indice;
                }
                $indice++;
            }
        }
        return $retorno;
    }

/**********************************************************CUPONES Y DEVOLUCIONES**************************************************************/
    public static function GuardarCuponEnJSON($cupon){
        $cuponData = [
            'idCupon' => $cupon->_idCupon,
            'idDevolucion' => $cupon->_idDevolucion,
            'porcentajeDescuento' => $cupon->_porcentajeDescuento,
            "estado" => $cupon->_estado
        ];

        $retorno = ManejarJSONS::ObtenerJsonYGuardarloConNuevosDatos(ManejarJSONS::$_cuponFile, $cuponData);
        

        return $retorno;
    }
    public static function ObtenerValoresJSONCupones(){
        $cupones = ManejarJSONS::ObtenerValoresJSON(ManejarJSONS::$_cuponFile);
        return $cupones;
    }

    public static function GuardarDevolucionEnJSON($devolucion){
        $devolucionData = [
            'idDevolucion' => $devolucion->_idDevolucion,
            'NumeroPedido' => $devolucion->_numeroPedido,
            'CausaDeDevolucion' => $devolucion->_causaDeDevolucion,
            'cupon' => $devolucion->_cuponCreado,
        ];

        $retorno = ManejarJSONS::ObtenerJsonYGuardarloConNuevosDatos(ManejarJSONS::$_devolucionFile, $devolucionData);
        
        return $retorno;
    }
    public static function ObtenerValoresJSONDevoluciones(){
        $devoluciones = ManejarJSONS::ObtenerValoresJSON(ManejarJSONS::$_devolucionFile);
        return $devoluciones;
    }



    public static function ActualizarEstadoDeCupon($idCupon){
        $cuponesData= ManejarJSONS::ObtenerValoresJSON(ManejarJSONS::$_cuponFile);
        foreach($cuponesData as &$cupon){
            if ($cupon["idCupon"]==$idCupon){
                echo "hola";
                $cupon["estado"] = "usado";
                break;
            }
        }
        ManejarJSONS::GuardarJSON(ManejarJSONS::$_cuponFile, $cuponesData);
    }
}