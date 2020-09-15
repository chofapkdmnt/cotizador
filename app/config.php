<?php
/** Para poder usar las variables de secion en las funsiones se debe inicializar la sesion en este archivo
 * con la linea session_start() se accede a las propiedades de session
 */
session_start();
//Variable con la cual se define el tipo de servidor desde el que se ingresa. En las linea inferior
//hace mencion a un servidor local
define('IS_LOCAL' , in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']));

/* Para definir una url absoluta con la cual se trabajaran todos los enlaces. */
define('URL', (IS_LOCAL ? 'http://127.0.0.1:8848/Cotizador/' : 'LA URL DE SU SERVIDOR EN PRODUCCION'));

/** Constantes para definir directorios y cargar mas rapido las rutas  usando DS -> para separar directorio
*/
define('DS', DIRECTORY_SEPARATOR);
/**Escribir las rutas */
define('ROOT', getcwd().DS); //Toda la ruta de un archivos en escrips

//Se definen rutas de cada carpeta
define('APP', ROOT.'app'.DS);
define('ASSETS', ROOT.'assets'.DS);
define('TEMPLATES', ROOT.'templates'.DS);
define('INCLUDES', TEMPLATES.'includes'.DS);
define('MODULES', TEMPLATES.'modules'.DS);
define('VIEWS', TEMPLATES.'views'.DS);
//Ruta para archivos que se iran generando, con esta ruta se define donde se guardaran los archivos, sin 
//necesidad de escribirla una y otra vez
define('UPLOADS', ROOT.'uploads'.DS);

//Constantes que nos lleven a los css, js, img
define('CSS'      , URL.'assets/css/');
define('IMG'      , URL.'assets/img/');
define('JS'       , URL.'assets/js/');

//Constante para personalizacion de pagina
define('APP_NAME', 'Cotizador App');
define('TAXES_RATE', 16);  //Recordar que es un porcentaje
define('SHIPPING', 99.50);

//Cargar todas las funsiones.
require_once APP.'functions.php';