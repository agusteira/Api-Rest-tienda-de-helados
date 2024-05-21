<?php

include_once "JSONs/ManejarJSON.php";



class Helados{

    /**********************************************************ATRIBUTOS**************************************************************/
    public $_ID;
    public $_sabor; 
    public $_precio; 
    public $_tipo; 
    public $_vaso; 
    public $_stock; 
    

    /************************************************************************************************************************/
    /************************************************************************************************************************/
    public function __construct( $sabor, $tipo = NULL, $ID = NULL, $precio = NULL, $vaso = NULL, $stock = NULL){
        $this->_ID = $ID;
        $this->_sabor = $sabor;
        $this->_precio = $precio;
        $this->_tipo = $tipo;
        $this->_vaso = $vaso;
        $this->_stock = $stock;
    }
    public static function CrearHelado(){
        $idAlta = rand(1,10000);
        $sabor = $_POST['sabor']; 
        $precio = $_POST['precio']; 

        if ($_POST['tipo'] == "Agua" || $_POST['tipo'] == "Crema"){
            $tipo = $_POST['tipo']; 
        }else {$tipo = NULL;}
        
        if ($_POST['vaso'] == "Cucurucho" || $_POST['vaso'] == "Plastico"){
            $vaso = $_POST['vaso']; 
        }else { $vaso = NULL; }
        
        if (is_numeric($_POST['stock'])){
            $stock = $_POST['stock']; 
        }else {$stock = 0;}
        

        $altaNueva = new Helados($sabor, $tipo, $idAlta, $precio, $vaso, $stock);
        $retorno = $altaNueva->Agregar();
        //var_dump($retorno);
        return $retorno;
    }
    private function Agregar(){
        if (ManejarJSONS::RevisarRegistroHelados($this)==-1){
            $retorno = ManejarJSONS::guardarHeladoEnJSON($this);
            $this->SubirFoto();
        }
        else{
            $retorno = ManejarJSONS::ActualizarHelado($this);
        }
        return $retorno;
    }

    public static function RealizarConsultaSobreHelado(){
        $sabor = $_POST['sabor']; 

        if ($_POST['tipo'] == "Agua" || $_POST['tipo'] == "Crema"){
            $tipo = $_POST['tipo']; 
        }else {$tipo = NULL;}

        $heladoConsulta = new Helados($sabor, $tipo);
        $resultado = ManejarJSONS::RevisarRegistroHelados($heladoConsulta);

        if ($resultado != -1){
            $retorno = true;
        }else{
            $retorno = false;
        }

        return $retorno;
    }

    /**********************************************************Realizar consultas**************************************************/

    public static function RealizaConsultaSobreStock(){
        $SaborAComprobar = $_POST['sabor']; 

        $helados = ManejarJSONS::ConvertirJSONEnHelados();
        $retorno = false;
        $indice = 0;
        foreach ($helados as $heladoJSON){
            //echo $heladoJSON->_sabor . "_" . $SaborAComprobar . "<br>";
            if ($heladoJSON->_sabor == $SaborAComprobar && $heladoJSON->_stock> 0){
                $retorno = true;
                ManejarJSONS::ActualizarStockPorVenta($indice);
            }
            $indice++;
        }
        return $retorno;
    }

    public static function ConsultarPrecioPorSabor($sabor){
        $helados = ManejarJSONS::ConvertirJSONEnHelados();
        $retorno = null;
        foreach ($helados as $heladoJSON){
            if ($heladoJSON->_sabor == $sabor){
                $retorno = $heladoJSON->_precio;
            }
        }
        return $retorno;
    }

    /************************************************************************************************************************/


    private function SubirFoto(){
        $foto = $_FILES["foto"];
        if (isset($foto) && $foto['error'] == 0) {
            $rutaTemporal = $foto['tmp_name'];

            // Genera un nombre único para la imagen 
            $nombreImagen = $this->_sabor . '_' . $this->_tipo;

            // Mueve la imagen al directorio de destino
            $carpetaDestino = 'ImagenesDeHelados/2024/';

            if (move_uploaded_file($rutaTemporal, $carpetaDestino . $nombreImagen)) {
                echo "Imagen subida correctamente.";
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            echo "No se seleccionó ninguna imagen.";
        }
    }


    /* Comparar helados */
    public static function Equal($helado1, $helado2){

        $retorno = false;
        
        if($helado1 instanceof Helados && $helado2 instanceof Helados){
            if ($helado1->_sabor == $helado2->_sabor && $helado1->_tipo == $helado2->_tipo){
                $retorno = true;
            }
        }
        return $retorno;
    }

}