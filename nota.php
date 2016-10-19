<?php
class Nota{
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
}


?>