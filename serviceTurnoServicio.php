<?php
    require_once ('turnoServicio.php');         

    class ServiceTurnoServicio {

        private $turnos = [];
        
        public function reservaTurno($servicioCliente, $servicioVehiculo) {
          
            $fecha = readline('Ingrese la fecha del turno (dd-mm-yyyy): ');
            $fecha_mysql = date('Y-m-d', strtotime($fecha));
            $hora = readline('Ingrese la hora del turno (HH:MM): ');
            foreach ($this->turnos as $t) {
                if ($t->getFecha() === $fecha && $t->getHora() === $hora) {
                    echo ('El turno ya existe, elija otra Fecha u Hora.'.PHP_EOL);
                    echo (PHP_EOL);
                    return false;
                }
            }
            $tipoServicio = readline('Describa el servicio a realizar: ');
            $patente = readline('Ingrese la patente del vehículo: ');
            $dniCli = readline('Ingrese el DNI del cliente titular: '); echo(PHP_EOL);
            
            echo ('Verificación de existencia para Cliente y Vehículo'); echo(PHP_EOL);
            
            if (!$servicioCliente->buscarCliente($dniCli)) {
                echo ('El cliente con DNI '.$dniCli.' no existe.'.PHP_EOL);
                return false;
            }
    
            $vehiculo = $servicioVehiculo->buscarAuto($patente);
            if (!$vehiculo) {
                echo ('El vehículo con patente '.$patente.' no existe.'.PHP_EOL);
                return false;
            }
    
            $turno = new TurnoServicio($fecha, $hora, $tipoServicio, $patente, $dniCli);
            $this->turnos[] = $turno;

            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
            $addTurno = "INSERT INTO turnos (fecha, hora, tipo_servicio, patente, dni_cliente) VALUES ('$fecha_mysql', '$hora', '$tipoServicio', '$patente', '$dniCli')";

            if ($bd->query($addTurno) === TRUE) {
                echo(PHP_EOL);
                echo ('Turno agregado correctamente a la base de datos.'.PHP_EOL);
            } else {
                echo ('Error al agregar el turno. '. $bd->error .PHP_EOL);
                return;
            }
    
            echo ('El turno fue reservado correctamente.'.PHP_EOL);
            return true;
        }


        public function modificarTurno() {
            echo ('Modificar Turno reservado.'.PHP_EOL);
            $patente = readline ('Ingrese patente: ');
            foreach ($this->turnos as $t) {
                if ($t->getPatente() == $patente) {
                  $newFecha = readline ('Ingrese nueva Fecha (dd-mm-yyyy): ');
                  $fecha_mysql = date('Y-m-d', strtotime($newFecha));
                  $newHora = readline ('Ingrese Hora (HH:MM): ');
                  foreach ($this->turnos as $turno) {
                    if ($turno !== $t && $turno->getFecha() === $newFecha && $turno->getHora() === $newHora) {
                        echo ('La Fecha y la Hora ya están asignadas a otro turno.'); echo(PHP_EOL);
                        echo(PHP_EOL);
                        return false;
                    }
                }
                $newServicio = readline ('Describa Servicio a realizar: ');
                $newPatente = readline ('Ingrese nueva patente: ');
                $newDniCli = readline ('DNI de cliente Titular: ');
                $t->setFecha($newFecha);
                $t->setHora($newHora);
                $t->setTipoServicio($newServicio);
                $t->setPatente($newPatente);
                $t->setDniCli($newDniCli);

                $conexion = ConexionBD::obtenerInstancia();
                $bd = $conexion->obtenerConexion();
                $modTurno = "UPDATE turnos 
                    SET fecha ='$fecha_mysql', 
                        hora ='$newHora', 
                        tipo_servicio ='$newServicio', 
                        patente ='$newPatente',
                        dni_cliente ='$newDniCli'
                    WHERE patente ='$patente'";
        
                    
                if ($bd->query($modTurno) === TRUE) {
                    echo ('El Turno se ha modificado en la base de datos.' . PHP_EOL);
                    return true;
                } else {
                    echo ('Error al modificar Turno en la base de datos. ' .PHP_EOL);
                    return false;
                }

                  echo ('El turno se ha modificado.'.PHP_EOL);
                  return true;
                }
            }
                echo ('El turno no existe o no fue moddificado.'.PHP_EOL);
                return false;
        }

        
        public function eliminarTurno() {
            $patente = readline('Ingrese la patente del vehículo: ');
            $buscaTurno = false;
        
            foreach ($this->turnos as $key => $turno) {
                if ($turno->getPatente() === $patente) {
            
                    unset($this->turnos[$key]);
                    $buscaTurno = true;
                    //break;

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
                echo ('No se encontró un turno para el vehículo con patente: '. $patente .' y cliente DNI: '. $dniCli); echo(PHP_EOL);
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
                    echo ('Tipo de Servicio: '.$fila['tipo_servicio'].PHP_EOL);
                    echo ('Patente: ' . $fila['patente'].'; ');
                    echo ('DNI Titular: ' . $fila['dni_cliente'] . '; '. PHP_EOL);
                    echo ('---------------------------------------------------------------------------'.PHP_EOL);
                }
                return true;
            } else {
                echo ('El turno No existe.' . PHP_EOL);
                return false;
            }
        }


        public function mostrarTurnos() {
            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
        
            $getTurnos = "SELECT fecha, DATE_FORMAT(hora, '%H:%i') AS hora, tipo_servicio, patente, dni_cliente 
                FROM turnos";
            $result = $bd->query($getTurnos);
        
            if ($result->num_rows === 0) {
                echo ('No hay turnos programados.'.PHP_EOL); 
                return false;
            } else {
                echo ('Turnos programados:'.PHP_EOL);
                echo(PHP_EOL);
                
                while ($resultados = $result->fetch_assoc()) {
                    $fecha = date('d-m-Y', strtotime($resultados['fecha']));
                    echo ('Fecha: '.$fecha.'; ');
                    echo ('Hora: '.$resultados['hora'].'; ');
                    echo ('Servicio: '.$resultados['tipo_servicio'].PHP_EOL);
                    echo ('Patente: '.$resultados['patente'].'; ');
                    echo ('DNI de Titular: '.$resultados['dni_cliente'].PHP_EOL);
                    echo ('------------------------------------------------------------'); echo(PHP_EOL);
                }
                return true;
            }
        }
        
        /*
        public function guardar() {
            $arrSer = serialize($this->turnos);
            file_put_contents("turnos.json", $arrSer);
        }

        public function leer() {
            $recArr = file_get_contents("turnos.json");
            $arrOrig = unserialize($recArr);
            $this->turnos = $arrOrig;
        }
        */
    }