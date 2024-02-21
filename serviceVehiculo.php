<?php
    require_once ('vehiculo.php');

    class ServiceVehiculo {          
                                    
        private $cars = [];           

        // Función para agregar un nuevo auto
        public function agregarAuto($serviceCliente) {
            $patente = readline('Ingrese la patente del vehículo: ');            
            $marca = readline('Ingrese marca del vehículo: ');
            $modelo = readline('Ingrese modelo del Vehículo: ');
            $dniCliente = readline('Ingrese DNI del cliente titular: ');

            if ($serviceCliente->buscarCliente($dniCliente)) {
                $autos = new Vehiculo($patente, $marca, $modelo, $dniCliente);
                $this->cars[] = $autos;
                echo ('El vehiculo se ha cargado correctamente.'.PHP_EOL);
                return true;
            } else {
                echo ('El cliente No existe, ingrese Cliente.'.PHP_EOL);
                return false;
            }
        }

        // Función para modificar los datos de un auto
        public function modificarAuto() {
            $dniCliente = readline('Ingrese el DNI del cliente: ');
        
            foreach ($this->cars as $auto) {
                if ($auto->getDniCliTit() === $dniCliente) {
                    $newpat = readline('Nueva Patente: ');
                    $newmarca = readline('Ingrese Marca: ');
                    $newmodelo = readline('Ingrese Modelo: ');
        
                    $auto->setPatente($newpat);
                    $auto->setMarca($newmarca);
                    $auto->setModelo($newmodelo);
        
                    echo ('El Vehículo se ha modificado exitosamente.' . PHP_EOL);
                    return true;
                }
            }
        
            echo ('No se encontraron vehículos asociados al cliente con DNI: ' . $dniCliente . PHP_EOL);
            return false;
        }
        
        // Función para eliminar un vehiculo
        
        public function eliminarAuto() {          
            $patente = readline('Ingrese Patente: ');           
            foreach ($this->cars as $auto => $c) {
                if ($c->getPatente() === $patente) {
                    if ($c->getDniCliTit() !== null) {       // verifico auto asociado a cliente
                        $c->setDniCliTit(null);              // desvinculo auto de cliente
                    }
                    unset($this->cars[$auto]);
                    echo ('El vehículo se ha eliminado.'.PHP_EOL);
                    return true;
                }
            }
            echo ('Vehículo No encontrado.'.PHP_EOL);
            return false;
        }
        
        public function mostrarVehiculos() {
            foreach ($this->cars as $autos) {
            echo "Patente: {$autos->getPatente()}, Marca: {$autos->getMarca()}, Modelo: {$autos->getModelo()}, DNI Titular: {$autos->getDniCliTit()} \n";
            }
        }


        public function buscarAuto() {
            $patente = readline('Ingrese Patente: ');
            foreach ($this->cars as $auto) {
                if ($auto->getPatente() == $patente) {
                    echo ('Vehículo Encontrado.'.PHP_EOL);
                    echo ('Patente: '.$auto->getPatente().'; ');
                    echo ('Marca: '.$auto->getMarca().'; ');
                    echo ('Modelo: '.$auto->getModelo()); echo(PHP_EOL);
                    return true;
                }
            }

            echo ('El vehículo no existe.'); echo(PHP_EOL);
            return false;
        }

        public function mostrarAutosClientes() {
            $autosClientes = [];
            $dniCliente = readline ('Ingrese DNI del Cliente: ');

            foreach ($this->cars as $auto) {
                if ($auto->getDniCliTit() === $dniCliente) {
                    $autosClientes[] = $auto;
                }
            }

            if (count($autosClientes) > 0) {
                foreach ($autosClientes as $auto) {
                    echo ('Patente: '.$auto->getPatente().'; '); 
                    echo ('Marca: '.$auto->getMarca().'; '); 
                    echo ('Modelo: '.$auto->getModelo()); echo(PHP_EOL);
                }
            } else {
                echo ('No se encontraron vehículos para el cliente DNI: '.$dniCliente);
            }
        }
        
    
        public function grabar() {
            $arrSerVeh = serialize($this->cars);
            file_put_contents("autos.json", $arrSerVeh);
            //print_r ($arrSer); echo(PHP_EOL);
        }

        public function leer() {
            $recArrVeh = file_get_contents("autos.json");
            $arrOrigVeh = unserialize($recArrVeh);
            //print_r ($arrOrig);
            $this->cars = $arrOrigVeh;
        }

    }