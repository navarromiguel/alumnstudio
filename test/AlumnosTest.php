<?php
include '../gestor_alumnos.php';
use PHPUnit\Framework\TestCase;

class AlumnosTest extends TestCase {
    
    public function test_listar_ficheros_de_directorio(){
        $ga = new GestorAlumnos();
        $ga->load_cursos($dir='dir_test');
        $cursos = $ga->get_cursos();
        
        $this->assertEquals(2, count($cursos));
        $this->assertEquals("C1", $cursos[0]->get_id());
        $this->assertEquals("2014-15", $cursos[0]->get_anyo());
        $this->assertEquals(3, $cursos[0]->get_convocatoria());
        $this->assertEquals(12, count($cursos[0]->get_notas()));
    }
    
    public function testGetCargarJSONCurso(){
        $ga = new GestorAlumnos();
        $json = file_get_contents('test_cursos.json');
        $curso = $ga->loadCursoFromJSON($json);
        
        $this->assertEquals('C1', $curso->get_id());
        $this->assertEquals('2014-15', $curso->get_anyo());
        $this->assertEquals(1, $curso->get_convocatoria());
        $this->assertEquals(43, count($curso->get_notas()));
    }
    
    public function test_get_notas_alumno(){
        $alumno = new Persona("1", "pepe", "garcia diaz", "1-1-1993", 1);
        $curso = array(new Nota('1', 5), new Nota('1', 7), new Nota('2', 4));
        $ga = new GestorAlumnos($cursos=array($curso));
        
        $this->assertEquals(2, count($ga->get_notas_alumno($alumno)));
        $this->assertEquals(7, $ga->get_notas_alumno($alumno)[1]);
    }
    
    public function test_get_notas_del_curso(){
        $curso = new Curso("C1","2014","Primera",array(new Nota('1', 5), new Nota('1', 7), new Nota('2', 4)));
        $ga = new GestorAlumnos($cursos=array($curso));
        
        $this->assertEquals(3, count($ga->getNotasDeCursoDeTodosLosAlumnos($curso)));
        $this->assertEquals(5, $ga->getNotasDeCursoDeTodosLosAlumnos($curso)[0]);
    }
}


?>