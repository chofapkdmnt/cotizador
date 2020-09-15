<?php
/**En este archivo se establecen las funcones que seram llevadas a cabo */
function get_view($view_name){
    /**El simbolo de admiracion previo a is file es para evaluar lo contrario, es decir si la vista no existe
     * 
     */
    //Crear variable para nombre de la vista.
    $view = VIEWS.$view_name.'View.php';
    if(!is_file($view)){
        die('La vista no existe');
    }

    //Existe la vista.
     require_once $view;
}
/** variable para la cotizacion llamada new_quote[]
* Esta contara con varios datos.
* number - numero de cotizacion
* nombre - nombre de aquien se le cotiza
* company - es la empresa
* email - correo
* items - []   este sera un arreglo donde se icluyen los conpcetos
* Los siguientes datos se obtienen a travez de lo que incluye el arreglo
* subtotal
* taxes
* shiping
* total
*/

/** el item contiene lo siguiente
 * item
 * id
 * concept
 * tipo
 * quantity
 * price
 * taxes
 * total
 */

/** Se requerira una funsion para cargar la cotizacion
 * get_quote()
 * funsion para cargar todos los elementos que contenga todos los items de la cotizacion
 * get_intems()
 * funsion para cargar un solo item
 * get_item($id)
 * funsion para agrega un item la cual guardara toda la informacion del item
 * add_item($item)
 * funsion para borrar item
 * delete_item($id)
 * funsion para borrar todos los items
 * delete_items()
 * funsion para reiniciar la cotizacion una vez terminada la cotizacion o por alguna razon
 * restart_quote()
 */

/** Los valores almacenados en las funcione se guardan en el disco duro del equipo en la carpeta xampp/temp/
 * es importante no guardar en secion la mayoria de datos debido a que en un software con excesivos usuarios miles millones
 * las lecturas de los archivos de alentan
 *
 */

/**
 * Posibles codigos a utilizar
 * 200 OK
 * 201 Create
 * 300 Multiple Choise
 * 301 Moved Permanently
 * 302 Found
 * 304 Not Modified
 * 307 Temporary Redirect
 * 400 Bad Request
 * 401 Unauthorized
 * 403 Forbidden
 * 404 Not Found
 * 410 Gone
 * 500 Internal Server Error
 * 501 Not Implemented
 * 503 Service Unavailable
 * 550 Permission denied
 */
function get_quote(){
    //en la siguiente linea se valida si no esta seteada la variable de session new_quote
    if(!isset($_SESSION['new_quote'])){
        //si esta no esta inicializada, se agregan todos los valores de abajo ubicados en el
        // arreglo y se inicializa en la linea return $_SESSION['new_quote'] =
        // rand(111111,999999) esta linea se usa para establecerle un numero aleatorio de seis digitos
        return $_SESSION['new_quote'] =
        [
            'number' => rand(111111,999999),
            'name' => '',
            'company' => '',
            'email' => '',
            'items' => [],
            'sutotal' => 0,
            'taxes' => 0,
            'shipping' => 0,
            'total' => 0
        ];
        // Si la cotizaccion guardada en la session, se recalcula todos los totales guardados en la session
        //recalcular todos los totales
        recalculate_quote();
        return $_SESSION['new_quote'];
    }
}

function recalculate_quote(){
    //valores inicializados, en donde esta el array y los valores de dinero, para evitar errores
    $items = [];
    $subtotal = 0;
    $taxes = 0;
    $shipping = 0;
    $total = 0;

    //vailidaccion con la cual se revisa si esta setiada la seccion, mismo se regresa un false
    if(!isset($_SESSION['new_quote'])){
        return false;
    }

    //si si existe la session, se guardan los valores anteriores en el items, sin importar que esten vacios
    //validar items
    $items = $_SESSION['new_quote']['shipping'];

    //si la lista no esta vacia, se itera detro de cada uno de los conceptos, aqui se calcula el precio total,
    //realizando la operaccion, del total de precio, por la cntidad de articulos
    //si la lista  de items esta vacia no es necesario calcular nada
    if(!empty($items)){
        foreach ($items as $item){
            //se calcula el total multiplicando el presio del item por su cantidad
            $subtotal += $item['total'];
            //aqui se guardan los impuesto, el usar += es referente para ir sumando la cantidad y la guardada en el arreglo
            $taxes += $item['taxes'];
        }
    }

    //con esto se saca el valor del envio
    $shipping = $_SESSION['new_quote']['shipping'];
    //con esta linea se saca el total sumando impuestos, costos y envio
    $total = $subtotal + $taxes + $shipping;

    //se reasignan los valores a la variable de sesion remplazando los instanciados arriba
    $_SESSION['new_quote']['subtotal'] = $subtotal;
    $_SESSION['new_quote']['taxes'] = $taxes;
    $_SESSION['new_quote']['shipping'] = $shipping;
    $_SESSION['new_quote']['total'] = $total;
    //se regresa un true
    return true;
}

//Aqui se realiza una funcion para reiniciar la lista
function restart_quote(){
    return $_SESSION['new_quote'] =
        [
            'number' => rand(111111,999999),
            'name' => '',
            'company' => '',
            'email' => '',
            'items' => [],
            'sutotal' => 0,
            'taxes' => 0,
            'shipping' => 0,
            'total' => 0
        ];
    return true;
}

//funsion para obtener todos los conceptos
function get_items(){
    //se inicializa la funsion con un array vacio para evitar problemas
    $items =[];

    //sino existe la cotizacion y obviamente esta vacio el array
    if(!isset($_SESSION['new_quote']['items'])){
        //entonces se regresa la variable items con el array vacio
        return $items;
    }
    //la cotizacion existe, se asigna el valor se reglresa el valor items con la key items y new_quote
    $items = $_SESSION['new_quote']['items'];
    return items;
}

//funsion para obtener un solo item usando su id
function get_item($id){
    //se necesitan cargar el total de items guardados usando la funsion anterior get_items()
    $items = get_items();

    //si no hay items, aqui se valida si esta vacio, si esta vacio se regresa un false
    if(empty($items)){
        return false;
    }

    //si hay  items iteramos, se pasan los items, los cuales se combierten a item es decir individual
    foreach ($items as $item){
        //se valida el ide de items con el id de item nos retorne el item que se itera en la iteraccion
        if($item['id'] === $id){
            return $item;
        }
    }

    //no hubo un match o un resultados, si no existe el item al iterar, regresa un false
    return false;
}

//funcion para borrar todos los items
function delete_items(){
    $_SESSION['new_quote']['items'] = [];

    //Aqui se manda a llamar la funsion de recalcular para volver a tener el total
    recalculate_quote();
    return true;
}

//funsion para borrar un solo item usando su id
function delete_item($id){
    //se necesitan mandar a traer todos los items para saber que se tiene en la cotizacion
    $items = get_items();

    //si no hay items, aqui se valida si esta vacio, si esta vacio se regresa un false
    if(empty($items)){
        return false;
    }

    //se busca el item de la iteraccion que hace match, con el cual se requiere la posision del elemento
    foreach ($items as $i => $item){
        //se valida el ide de items con el id de item nos retorne el item que se itera en la iteraccion
        if($item['id'] === $id){
            unset($_SESSION['new_quote']['items'][$i]);
            return true;
        }
    }

    //no hubo un match o un resultados, si no existe el item al iterar, regresa un false
    return false;
}

//funcion para agregar un item a la lista de conceptos
function add_item($item){
    $items = get_items();
    //Si existe el ide ya en nuestros items
    //podemos actualizar la informacion en lugar de agregarlo
    if(get_item($item['id']) !== false){
        foreach ($items as $i => $e_item){
            if($item['id'] === $e_item['id']){
                $_SESSION['new_quote']['item'][$i]= $item;
                return true;
            }
        }
    }
    //No existe en la lista, se agrega simplemente
    $_SESSION['new_quote']['items'] = $item;
    return true;
}

//Funsion json para regresar el valor de las peticiones asincronas
function json_build($status = 200, $data = null, $msg = ''){
    //Si la variable msg esta vacia, esta se sustituye por un mensaje por defecto
    //Deacuerdo al codigo que regrese es el mensaje que se muestra
    if(empty($msg) || $msg == ''){
        switch ($status){
            case 200:
                $msg = 'OK';
                break;
            case 201:
                $msg = 'Created';
                break;
            case 400:
                $msg = 'Invalid request';
                break;
            case 403:
                $msg = 'Access denied';
                break;
            case 404:
                $msg = 'Not found';
                break;
            case 500:
                $msg = 'Internal Server Error';
                break;
            case 550:
                $msg = 'Permision denied';
                break;
            default:
                break;
        }
    }
    $json =
        [
            'status' => $status,
            'data' => $data,
            'msg' => $msg
        ];
    return json_encode($json);
}

//funsion para mostrar en pantalla el json de forma correcta
function json_output($json){
    header('Access-Control-Allow-Origin: *');
    header('Content-type: application/json;charset=utf-8');

    if(is_array($json)){
        $json = json_encode($json);
    }
    echo $json;
    return true;
}