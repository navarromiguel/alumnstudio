<?php

include_once 'gestor_alumnos.php';

$ga = new GestorAlumnos();
$cursos = $ga->load_cursos();
$alumnos = $ga->load_alumnos();


switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        header('Content-type: application/json');
        echo json_encode($cursos);
        break;
    case 'POST':
        if($_POST['tipo'] == 'filtrado'){
            header('Content-type: application/json');
            $sexos=$_POST['sexo'];
            $convocatorias=$_POST['convocatoria'];
            $cursos_filtrados = $ga->filtrar_notas($sexos, $convocatorias);
            echo json_encode($cursos_filtrados);
        }
        else{
            $nombre = $_FILES["fichero"]["name"];
            $str = file_get_contents($_FILES["fichero"]["tmp_name"]);
            $json = json_decode($str,true);
            file_put_contents(GestorAlumnos::DIRECTORIO_CURSOS . "/" . $nombre, json_encode($json));   
            header("Location: http://$_SERVER[HTTP_HOST]/notas.html");
        }
       break;
    default:
    
        break;
}


?>