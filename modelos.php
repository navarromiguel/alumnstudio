<?php

class Persona implements JsonSerializable {
    
    const SEXO_HOMBRE = 1;
    const SEXO_MUJER= 2;

    private $id;
    private $nombre;
    private $apellidos;
    private $fecha_nacimiento;
    private $sexo;
    
    public function __construct($id, $nombre, $ap, $fecha, $sexo){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $ap;
        $this->fecha_nacimiento = $fecha;
        $this->id = $id;
        $this->sexo = $sexo;
    }
    
    
    public Function get_id(){
        return $this->id;
    }
    
    public function get_sexo(){
        return $this->sexo;
    }
    
    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellidos' => $this->nombre,
            'fechaNacimiento' => $this->fecha_nacimiento,
            'genero' => $this->sexo
        );
    }
    
}


class Curso implements JsonSerializable {
    
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
    
    public function jsonSerialize() {
        return get_object_vars($this);
    }
}


class Nota implements JsonSerializable{
    
    private $id;
    private $valor;
    
    public function __construct($id, $valor){
        $this->id = $id;
        $this->valor = $valor;
    }
    
    public function getId(){
        return $this->id;
    }
    
     public function getValor(){
        return $this->valor;
    }
    
    public function jsonSerialize() {
        return get_object_vars($this);
    }
}

?>