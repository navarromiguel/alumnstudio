<?php
include 'gestor_alumnos.php';
$ga = new GestorAlumnos();
$ga->load_alumnos();
$ga->load_cursos();
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        header('Content-type: application/json');
        $alumnos = $ga->get_alumnos();
        echo json_encode($alumnos);
        break;
    case 'POST':
        if($_POST['query'] == 'historico_notas'){
            $alumno = $ga->get_alumno_by_id($_POST['alumno_id']);
            if($alumno){
                $notas = $ga->get_notas_alumno($alumno);
                header('Content-type: application/json');
                echo json_encode($notas);
            }
            else {
                echo "No existe alumno con el ID introducido.";
            }
        }
        else {
            echo "No query.";
        }
        break;
    default:
    
        break;
}

?>