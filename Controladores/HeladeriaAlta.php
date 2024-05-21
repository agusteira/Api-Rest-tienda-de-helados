<?php
/*
    B- (1 pt.) HeladeriaAlta.php: (por POST) se ingresa Sabor, Precio, Tipo (“Agua” o “Crema”), Vaso (“Cucurucho”,
    “Plástico”), Stock (unidades).

    Se guardan los datos en en el archivo de texto heladeria.json, tomando un id autoincremental como
    identificador(emulado) .Sí el nombre y tipo ya existen , se actualiza el precio y se suma al stock existente.
    completar el alta con imagen del helado, guardando la imagen con el sabor y tipo como identificación en la
    carpeta /ImagenesDeHelados/2024.
*/

include "Entidades/helados.php";

switch (Helados::CrearHelado()){
    case 0:
        echo "<br> ERROR NO SE PUDO SUBIR EL HELADO";
        break;
    case 1:
        echo "<br>Se dio de alta el nuevo helado<br>";
        break;
    case 2:
        echo "<br>Se actualizo el helado<br>";
        break;
}

