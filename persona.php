<?php

class Persona {
    
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
    
}


?>