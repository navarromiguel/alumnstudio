<?php
include_once 'modelos.php';

class GestorAlumnos {
    
    const DIRECTORIO_CURSOS = 'data/cursos';
    const DIRECTORIO_PERSONAS = 'data/personas';
    
    private $cursos;
    private $alumnos;
    
    public function __construct($cursos=array(), $alumnos=array()){
        $this->cursos = $cursos;
        $this->alumnos = $alumnos;
    }
    
    public function get_alumno_by_id($id){
        foreach($this->alumnos as $alumno){
            if($id == $alumno->get_id())
                return $alumno;
        }
        return false;
    }
    
    public function load_cursos($dir=null){
        $this->cursos = array();
        if(!$dir)
            $dir = self::DIRECTORIO_CURSOS;
        $contenido = scandir($dir."/");
        foreach($contenido as $file){
            if(!is_dir($file)){
                $this->cursos[] = $this->loadCursoFromJSON(file_get_contents($dir."/".$file));
            }
        }
        return $this->cursos;
    }
    
    public function reset_notas($dir=null){
        if(!$dir)
            $dir = self::DIRECTORIO_CURSOS;
        $contenido = scandir($dir."/");
        foreach($contenido as $file){
            if(!is_dir($file)){
                unlink($dir."/".$file);
            }
        }
    }
    
    
    public function load_alumnos($dir=null){
        $this->alumnos = array();
        if(!$dir)
            $dir = self::DIRECTORIO_PERSONAS;
        $contenido = scandir($dir."/");
        if (is_array($contenido) || is_object($contenido))
        {
            foreach($contenido as $file){
                if(!is_dir($file)){
                    $this->alumnos = array_merge($this->alumnos, $this->load_personas_from_JSON(file_get_contents($dir."/".$file)));
                }
            }
        }
        return $this->alumnos;
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
    
    public function load_personas_from_JSON($json){
        $personas = array();
        $json = json_decode($json, true);
        $lista_personas = $json["personas"];
        if (is_array($lista_personas) || is_object($lista_personas))
        {
            foreach($lista_personas as $persona){
                $id = $persona['id'];
                $nombre = $persona['nombre'];
                $apellidos = $persona['apellidos'];
                $fecha_nacimiento = $persona['fechaNacimiento'];
                $sexo = $persona['genero'];
                $personas[] = new Persona($id, $nombre, $apellidos, $fecha_nacimiento, $sexo);
            }
        }
        return $personas;
    }
    
    public function get_notas_alumno($alumno){
        $notas_alumno = array();
        foreach($this->cursos as $curso){
            $notas = $curso->get_notas();
            foreach($notas as $nota){
                if($nota->getId() == $alumno->get_id()){
                    $notas_alumno[] = array(
                        'asignatura' => $curso->get_id(),
                        'anyo' => $curso->get_anyo(),
                        'convocatoria' => $curso->get_convocatoria(),
                        'valor' => $nota->getValor()
                    );
                }
            }
        }
        return $notas_alumno;
    }
    
    public function get_notas_alumno_en_curso($alumno, $curso){
        $notas_alumno = array();
        
                $notas = $curso->get_notas();
                foreach($notas as $nota){
                    if($nota->getId() == $alumno->get_id()){
                        $notas_alumno[] = $nota->getValor();
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
    
    public function get_notas_alumnos_en_curso($anyo){
        $notas = array();
        foreach($this->cursos as $curso){
            if($anyo == $curso->get_anyo()){
                $notas = array_merge($notas, $curso->getNotasDeTodosLosAlumnos());
            }
        }
        return $notas;
    }
    
    public function get_notas_de_asignatura($asignatura){
        $notas = array();
        foreach($this->cursos as $curso){
            if($asignatura == $curso->get_id()){
                $notas = array_merge($notas, $curso->getNotasDeTodosLosAlumnos());
            }
        }
        return $notas;
    }
    
    public function get_notas_de_cada_asignatura(){
        $notas = array();
        foreach($this->cursos as $curso){
            $asignatura = $curso -> get_id();
            if(array_key_exists($asignatura, $notas)){
                $notas[$asignatura] = array_merge($notas[$asignatura], $curso->getNotasDeTodosLosAlumnos());
            } else {
                $notas[$asignatura] = $curso->getNotasDeTodosLosAlumnos();
            }
        }
        return $notas;
    }
    
    public function get_notas_de_curso_por_sexo($asignatura, $sexo){
        $notas = array();
        
        $alumnos = array_filter($this->alumnos, function($alumno) use ($sexo){
            $aux_sexo = $alumno->get_sexo();
            return $alumno->get_sexo() == $sexo;
        });
        
        foreach ($alumnos as $alumno){
            foreach($this->cursos as $curso){
                if($asignatura == $curso->get_id()){
                    if(array_key_exists($curso->get_anyo(), $notas)){
                        $anyo_del_curso = $curso->get_anyo();
                        $notas[$anyo_del_curso] = array_merge($notas[$anyo_del_curso], $this->get_notas_alumno_en_curso($alumno, $curso));
                    } else {
                        $notas[$curso->get_anyo()] = $this->get_notas_alumno_en_curso($alumno, $curso);
                    }
                }
            }
        }
        return $notas;
    }
    
    public function get_alumnos(){
        return $this->alumnos;
    }
    
    public function filtrar_notas($sexos, $convocatorias){
        $cursos_filtrados = array();
        foreach($this->cursos as $curso){
            $notas_curso = array();
            if(in_array($curso->get_convocatoria(), $convocatorias)){
                foreach($curso->get_notas() as $nota){
                    if(in_array($this->get_alumno_by_id($nota->getId())->get_sexo(), $sexos)){
                        $notas_curso[] = $nota;
                    }
                }
                if(count($notas_curso)>0)
                    $cursos_filtrados[] = new Curso(
                        $curso->get_id(), $curso->get_anyo(), $curso->get_convocatoria(), $notas_curso
                     );
            }
        }
        return $cursos_filtrados;
    }
} 

?>