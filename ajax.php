<?php
require_once 'app/config.php';
//Parametro action enviado desde nuestro frontend (javascript)
//Debe ser recibido en ajax.php
//Validamos que el valor de action, concuerde con el nombre de una funcion
//si existe la funcion, se ejecuta dicha funcion y listo

//en caso de no existir la funcion o no recibir el parametro
//por defecto mandaremos un error 401 de accesso no autorizado

try{
    if (!isset($_POST['action']) && !isset($_GET['action'])) {
        throw new Exception("El acceso no esta autorizado");
    }

    //Guardar el valor de action
    $action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];
    $action = str_replace('-', '_', $action);
    $function = sprintf('hook_%s', $action);

    //validar la existencia de la funcion
    if (!function_exists($function)) {
        throw new Exception("El acceso no esta autorizado");
    }
    //Se ejecuta la funcion
}catch(Exception $e){
    json_output(json_build(403, null, $e->getMessage()));
}