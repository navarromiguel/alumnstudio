<?php

include_once 'gestor_alumnos.php';

$ga = new GestorAlumnos();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        
        break;
    case 'POST':
        echo "aaaa";
        print("aa\n");
        $ga->reset_notas();
        header("Location: http://$_SERVER[HTTP_HOST]/notas.html");
        break;
    default:
    
        break;
}

?>