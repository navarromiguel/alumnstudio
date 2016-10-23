<?php
include '../gestor_alumnos.php';
use PHPUnit\Framework\TestCase;

class AlumnosTest extends TestCase {
    
    public function test_get_notas_de_asignatura(){
        $ga = new GestorAlumnos();
        $ga->load_cursos($dir='dir_test/cursos');
        $notas = $ga->get_notas_de_asignatura("C1");        
        
        $this->assertEquals(12, count($notas));
        $this->assertEquals(5.2, $notas[10]);
    }
    
    public function test_get_notas_de_cada_asignatura(){
        $ga = new GestorAlumnos();
        $ga->load_cursos($dir='dir_test/cursos');
        $notas = $ga->get_notas_de_cada_asignatura();        
                
        $this->assertEquals(2, count($notas));
        $this->assertEquals(45, count($notas['C2']));
        $this->assertEquals(5.2, $notas['C1'][10]);
    }
    
    public function test_listar_ficheros_de_directorio(){
        $ga = new GestorAlumnos();
        $ga->load_cursos($dir='dir_test/cursos');
        $cursos = $ga->get_cursos();
        
        $this->assertEquals(2, count($cursos));
        $this->assertEquals("C1", $cursos[0]->get_id());
        $this->assertEquals("2014-15", $cursos[0]->get_anyo());
        $this->assertEquals(3, $cursos[0]->get_convocatoria());
        $this->assertEquals(12, count($cursos[0]->get_notas()));
    }
    
    public function test_cargar_JSON_personas(){
        $ga = new GestorAlumnos();
        $alumnos = $ga->load_alumnos("dir_test");

        $this->assertEquals(200, count($alumnos));
    }
    
    public function test_cargar_JSON_curso(){
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
        $cursos = array(new Curso('C1', '2012-2013', 1, array(new Nota('1', 5), new Nota('1', 7), new Nota('2', 4))));
        $ga = new GestorAlumnos($cursos=$cursos);
        
        $this->assertEquals(2, count($ga->get_notas_alumno($alumno)));
        $this->assertEquals(7, $ga->get_notas_alumno($alumno)[1]['valor']);
    }
    
    public function test_get_notas_del_curso(){
        $curso = new Curso("C1","2014","Primera",array(new Nota('1', 5), new Nota('1', 7), new Nota('2', 4)));
        $ga = new GestorAlumnos($cursos=array($curso));
        
        $this->assertEquals(3, count($ga->getNotasDeCursoDeTodosLosAlumnos($curso)));
        $this->assertEquals(5, $ga->getNotasDeCursoDeTodosLosAlumnos($curso)[0]);
    }
    
    public function test_get_notas_de_curso_por_sexo(){
        $cursos = array(new Curso("C1", "2014", 1, array(new Nota('1', 5), new Nota('2', 7), new Nota('3', 4))));
        $personas = array(new Persona("1", "p1", "ap1", "1-1-1991", Persona::SEXO_MUJER),
                             new Persona("2", "p1", "ap1", "2-1-1991", Persona::SEXO_MUJER),
                             new Persona("3", "p1", "ap1", "3-1-1991", Persona::SEXO_HOMBRE));       
        $ga = new GestorAlumnos($cursos=$cursos, $alumnos=$personas);
        $notas = $ga->get_notas_de_curso_por_sexo('C1', Persona::SEXO_MUJER);
        
        $this->assertEquals(1, count($notas));
        $this->assertEquals(2, count($notas["2014"]));
        $this->assertEquals(7, $notas["2014"][1]);
    }
}
?>