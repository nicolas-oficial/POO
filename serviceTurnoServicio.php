<?php
    require_once ('turnoServicio.php');         // agregar funcion para grabar y leer json

    class ServiceTurnoServicio {

        private $turnos = [];
        
        public function reservaTurno($servicioCliente, $servicioVehiculo) {
          
            $fecha = readline('Ingrese la fecha del turno (dd-mm-yyyy): ');
            $hora = readline('Ingrese la hora del turno (HH:MM): ');
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

        public function eliminarTurno() {   // Revisar No elimina el turno.
            $patente = readline ('Ingrese patente: ');
            $dniCli = readline ('Ingrese DNI de Titular: ');

            foreach ($this->turnos as $t  => $turno) {
                if ($turno->getPatente() === $patente && $turno->getDniCli() === $dniCli) {
                    unset ($this->turno[$t]);
                    echo ('El turno fue eliminado.'.PHP_EOL);
                    return;
                }
            }
            echo ('Turno No encontrado para vehículo patente: '.$patente. ' y cliente DNI: '.$dniCli); echo(PHP_EOL);

        }
        
        public function mostrarTurnos() {
            if (count($this->turnos) === 0) {
                echo ('No hay turnos programados.'.PHP_EOL); 
            } else {
                echo ('Turnos programados.'.PHP_EOL);
                foreach ($this->turnos as $turno) {
                    echo ('Fecha: '.$turno->getFecha().'; ');
                    echo ('Hora: '.$turno->getHora().'; ');
                    echo ('Servicio: '.$turno->getTipoServicio().'; ');
                    echo ('Patente: '.$turno->getPatente().'; ');
                    echo ('DNI de Titular: '.$turno->getDniCli().PHP_EOL);
                    return true;
                }
            }
        }
    }