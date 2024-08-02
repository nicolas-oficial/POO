<?php
require_once('turnoServicio.php');

class ServiceTurnoServicio {
    private $turnos = [];
    
    public function reservaTurno($servicioCliente, $servicioVehiculo) {
        $patente = readline('Ingrese la patente del vehículo: ');
    
        // Verificar si la patente existe en la base de datos
        $conexion = ConexionBD::obtenerInstancia();
        $bd = $conexion->obtenerConexion();
        $checkPatente = "SELECT patente FROM vehiculos WHERE patente = '$patente'";
        $result = $bd->query($checkPatente);
    
        if ($result->num_rows === 0) {
            echo ('El vehículo con patente ' . $patente . ' no existe en la base de datos.' . PHP_EOL);
            return false;
        }
    
        // Verificar si la patente ya tiene un turno asignado
        foreach ($this->turnos as $t) {
            if ($t->getPatente() === $patente) {
                echo ('El vehículo con patente ' . $patente . ' ya tiene un turno asignado.' . PHP_EOL);
                return false;
            }
        }
    
        // Solicitar la fecha, hora y descripción del turno
        $fecha = readline('Ingrese la fecha del turno (dd-mm-yyyy): ');
        $fecha_mysql = date('Y-m-d', strtotime($fecha));
        $hora = readline('Ingrese la hora del turno (HH:MM): ');
        $descripcion = readline('Descripción: ');
        echo(PHP_EOL);
    
        // Insertar el nuevo turno en la base de datos
        $addTurno = "INSERT INTO turnos (fecha, hora, descripcion, patente) 
                     VALUES ('$fecha_mysql', '$hora', '$descripcion', '$patente')";
    
        if ($bd->query($addTurno) === TRUE) {
            echo(PHP_EOL);
            echo ('Turno agregado correctamente a la base de datos.' . PHP_EOL);
            $turno = new TurnoServicio($bd->insert_id, $fecha_mysql, $hora, $descripcion, $patente);
            $this->turnos[] = $turno;
            echo ('El turno fue reservado correctamente.' . PHP_EOL);
            return true;
        } else {
            echo ('Error al agregar el turno. ' . $bd->error . PHP_EOL);
            return false;
        }
    }
    

    public function modificarTurno() {
        echo ('Modificar Turno reservado.'.PHP_EOL);
        $patente = readline('Ingrese patente: ');
        
        $turnoEncontrado = null;
        foreach ($this->turnos as $t) {
            if ($t->getPatente() == $patente) {
                $turnoEncontrado = $t;
                break;
            }
        }

        if ($turnoEncontrado === null) {
            echo ('El turno con la patente especificada no existe.'.PHP_EOL);
            return false;
        }

        $newFecha = readline('Ingrese nueva Fecha (dd-mm-yyyy): ');
        $fecha_mysql = date('Y-m-d', strtotime($newFecha));
        $newHora = readline('Ingrese Hora (HH:MM): ');
        
        foreach ($this->turnos as $turno) {
            if ($turno !== $turnoEncontrado && $turno->getFecha() === $fecha_mysql && $turno->getHora() === $newHora) {
                echo ('La Fecha y la Hora ya están asignadas a otro turno.'.PHP_EOL);
                return false;
            }
        }

        $newDescripcion = readline('Descripción: ');

        $turnoEncontrado->setFecha($fecha_mysql);
        $turnoEncontrado->setHora($newHora);
        $turnoEncontrado->setDescripcion($newDescripcion);

        $conexion = ConexionBD::obtenerInstancia();
        $bd = $conexion->obtenerConexion();
        $modTurno = "UPDATE turnos 
                     SET fecha = '$fecha_mysql', 
                         hora = '$newHora', 
                         descripcion = '$newDescripcion'
                     WHERE patente = '$patente'";

        if ($bd->query($modTurno) === TRUE) {
            echo ('El Turno se ha modificado en la base de datos.' . PHP_EOL);
            return true;
        } else {
            echo ('Error al modificar Turno en la base de datos. ' . $bd->error . PHP_EOL);
            return false;
        }
    }

    public function eliminarTurno() {
        $patente = readline('Ingrese la patente del vehículo: ');
        $buscaTurno = false;

        foreach ($this->turnos as $key => $turno) {
            if ($turno->getPatente() === $patente) {
                unset($this->turnos[$key]);
                $buscaTurno = true;

                $conexion = ConexionBD::obtenerInstancia();
                $bd = $conexion->obtenerConexion();
                $delPat = "DELETE FROM turnos WHERE patente = '$patente'";
                
                if ($bd->query($delPat) === TRUE) {
                    echo ('El Turno se ha eliminado de la base de datos.'.PHP_EOL);
                    return true;
                } else {
                    echo ('Error al eliminar Turno.'. $bd->error .PHP_EOL);
                    return false;
                }
            }
        }

        if ($buscaTurno) {
            echo ('El turno fue eliminado.' . PHP_EOL);
        } else {
            echo ('No se encontró un turno para el vehículo con patente: '. $patente . PHP_EOL);
        }
    }

    public function buscarTurno() {
        $patente = readline('Ingrese Patente: ');
        
        $conexion = ConexionBD::obtenerInstancia();
        $bd = $conexion->obtenerConexion();
        
        $getTurno = "SELECT * FROM turnos WHERE patente = '$patente'";
        $result = $bd->query($getTurno);
        
        if ($result->num_rows > 0) {
            while ($fila = $result->fetch_assoc()) {
                $fecha = date('d-m-Y', strtotime($fila['fecha']));
                $hora = date('H:i', strtotime($fila['hora']));
                
                echo ('Turno encontrado:' . PHP_EOL);
                echo ('---------------------------------------------------------------------------'.PHP_EOL);
                echo ('Fecha: ' . $fecha. '; ');
                echo ('Hora: ' . $hora.'; ');
                echo ('Descripcion: '.$fila['descripcion'].PHP_EOL);
                echo ('Patente: ' . $fila['patente'].'; ');
               echo ('---------------------------------------------------------------------------'.PHP_EOL);
            }
            return true;
        } else {
            echo ('El turno No existe.' . PHP_EOL);
            return false;
        }
    }

    public function mostrarTurnos() {
        if (count($this->turnos) > 0) {
            foreach ($this->turnos as $turno) {
                $fecha = $turno->getFecha();
                $fechaFormato = date('d-m-Y', strtotime($fecha));
                $hora = date('H:i', strtotime($turno->getHora()));

                echo ('ID: ' . $turno->getId() . '; ');
                echo ('Fecha: ' . $fechaFormato . '; ');
                echo ('Hora: ' . $hora . '; ');
                echo ('Servicio: ' . $turno->getDescripcion() . '; '); echo (PHP_EOL);
                echo ('Patente: ' . $turno->getPatente() . '; ' . PHP_EOL);
                echo ('--------------------------------------------------------------------------');
                echo (PHP_EOL);
            }
        } else {
            echo "No existen Turnos en el sistema." . PHP_EOL;
        }
    }

    public function __construct() {
        $this->cargarTurnos();
    }

    public function cargarTurnos() {
        $conexion = ConexionBD::obtenerInstancia();
        $bd = $conexion->obtenerConexion();
        
        $getTurnos = "SELECT * FROM turnos";
        $result = $bd->query($getTurnos);
        
        if ($result->num_rows > 0) {
            while ($fila = $result->fetch_assoc()) {
                $turno = new TurnoServicio($fila['id'], $fila['fecha'], $fila['hora'], $fila['descripcion'], $fila['patente']);
                $this->turnos[] = $turno;
            }
        } else {
            echo ('No hay turnos programados en la base de datos.'.PHP_EOL);
        }
    }
}
?>

