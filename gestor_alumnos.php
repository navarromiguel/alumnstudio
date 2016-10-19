<?php
include 'persona.php';
include 'curso.php';
include 'nota.php';

class GestorAlumnos {
    
    const DIRECTORIO_DATOS = 'data';
    
    private $cursos;
    
    public function __construct($cursos=array()){
        $this->cursos = $cursos;
    }
    
    public function load_cursos($dir=null){
        $this->cursos = array();
        if(!$dir)
            $dir = self::DIRECTORIO_DATOS;
        $contenido = scandir($dir."/");
        foreach($contenido as $file){
            if(!is_dir($file)){
                $this->cursos[] = $this->loadCursoFromJSON(file_get_contents($dir."/".$file));
            }
        }
        return $cursos;
    }
    
    public function loadCursoFromJSON($json){
        $json = json_decode($json, true);
        $id = $json['id'];
        $curso = $json['curso'];
        $convocatoria = $json['convocatoria'];
        $notas = array_map(function($nota){
            return new Nota($nota['id'], $nota['valor']);
        } , $json['notas']);
        
        return new Curso($id, $curso, $convocatoria, $notas);
    }
    
    public function get_notas_alumno($alumno){
        $notas_alumno = array();
        
        foreach($this->cursos as $notas){
            foreach($notas as $nota){
                if($nota->getId() == $alumno->get_id()){
                    $notas_alumno[] = $nota->getValor();
                }
            }
        }
        return $notas_alumno;
    }
    
    public function getNotasDeCursoDeTodosLosAlumnos($curso){
        return $curso->getNotasDeTodosLosAlumnos();
    }
    
    public function get_cursos(){
        return $this->cursos;
    }
    
} 

?>