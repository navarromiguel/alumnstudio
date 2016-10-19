<?php

class Curso {
    
    private $id;
    private $anyo;
    private $convocatoria;
    private $notas;
    
    public function __construct($id, $anyo, $convocatoria, $notas){
        $this->id = $id;
        $this->anyo = $anyo;
        $this->convocatoria = $convocatoria;
        $this->notas = $notas;
    }
    
    public function get_id(){
        return $this->id;
    }
    
    public function get_anyo(){
        return $this->anyo;
    }
    
    public function get_convocatoria(){
        return $this->convocatoria;
    }
    
    public function get_notas(){
        return $this->notas;
    }
    
    public function getNotasDeTodosLosAlumnos(){
        return array_map(function($nota){return $nota->getValor();}, $this->notas);
    }
}

?>