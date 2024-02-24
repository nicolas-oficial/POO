<?php
    require_once ('turnoServicio.php');         // agregar funcion para grabar y leer json

    class ServiceTurnoServicio {

        private $turnos = [];
        
        public function reservaTurno($servicioCliente, $servicioVehiculo) {
          
            $fecha = readline('Ingrese la fecha del turno (dd-mm-yyyy): ');
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
    
            echo ('El turno fue reservado correctamente.'.PHP_EOL);
            return true;
        }


        public function modificarTurno() {
            echo ('Modificar Turno reservado.'.PHP_EOL);
            $patente = readline ('Ingrese patente: ');
            foreach ($this->turnos as $t) {
                if ($t->getPatente() == $patente) {
                  $newFecha = readline ('Ingrese nueva Fecha (dd-mm-yyyy): ');
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

                  echo ('El turno se ha modificado.'.PHP_EOL);
                  return true;
                }
            }
                echo ('El turno no existe o no fue moddificado.'.PHP_EOL);
                return false;
        }

        
        public function eliminarTurno() {
            $patente = readline('Ingrese la patente del vehículo: ');
            $dniCli = readline('Ingrese el DNI del cliente titular: ');
        
            $buscaTurno = false;
        
            foreach ($this->turnos as $key => $turno) {
                if ($turno->getPatente() === $patente && $turno->getDniCli() === $dniCli) {
                    unset($this->turnos[$key]);
                    $buscaTurno = true;
                    break;
                }
            }
        
            if ($buscaTurno) {
                echo ('El turno fue eliminado.' . PHP_EOL);
            } else {
                echo ('No se encontró un turno para el vehículo con patente: '. $patente .' y cliente DNI: '. $dniCli); echo(PHP_EOL);
            }
        }

        
        public function buscarTurno() {
            $patente = readline ('Ingrese Patente: ');
            $turnoBuscado = false;
        
            foreach ($this->turnos as $t) {
                if ($t->getPatente() == $patente) {
                    echo ('Turno encontrado:'.PHP_EOL);
                    echo ('Fecha: '.$t->getFecha().'; ');
                    echo ('Hora: '.$t->getHora().'; ');
                    echo ('Tipo de Servicio: '.$t->getTipoServicio()) . PHP_EOL;
                    echo ('Patente: '.$t->getPatente().'; ');
                    echo ('DNI Titular: '.$t->getDniCli().'; ') . PHP_EOL;
                    $turnoBuscado = true;
                }
            }
        
            if (!$turnoBuscado) {
                echo ('El turno No existe.' . PHP_EOL);
                return false;
            }
        
            return true;
        }
        
        
        public function mostrarTurnos() {
            if (count($this->turnos) === 0) {
                echo ('No hay turnos programados.'.PHP_EOL); 
            } else {
                echo ('Turnos programados:'.PHP_EOL);
                echo(PHP_EOL);
                foreach ($this->turnos as $turno) {
                    echo ('Fecha: '.$turno->getFecha().'; ');
                    echo ('Hora: '.$turno->getHora().'; ');
                    echo ('Servicio: '.$turno->getTipoServicio()); echo(PHP_EOL);
                    echo ('Patente: '.$turno->getPatente().'; ');
                    echo ('DNI de Titular: '.$turno->getDniCli().PHP_EOL);
                    echo ('------------------------------------------------------------'); echo(PHP_EOL);
                }
                return true;
            }
        }

        public function guardar() {
            $arrSer = serialize($this->turnos);
            file_put_contents("turnos.json", $arrSer);
        }

        public function leer() {
            $recArr = file_get_contents("turnos.json");
            $arrOrig = unserialize($recArr);
            $this->turnos = $arrOrig;
        }
    }