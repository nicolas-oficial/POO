<?php
    require_once ('vehiculo.php');

    class ServiceVehiculo {          
                                    
        private $cars = [];           

        // Función para agregar un nuevo auto

        public function agregarAuto($serviceCliente) {
            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
        
            $patente = readline('Ingrese la patente del vehículo: ');
        
            $checkPatente = "SELECT * FROM vehiculos WHERE patente = '$patente'";
            $result = $bd->query($checkPatente);
        
            if ($result && $result->num_rows > 0) {
                echo ('La patente ya está registrada en otro vehículo.' . PHP_EOL);
                return false;
            }
        
            $marca = readline('Ingrese marca del vehículo: ');
            $modelo = readline('Ingrese modelo del Vehículo: ');
            $dniCliente = readline('Ingrese DNI del cliente titular: ');
        
            if ($serviceCliente->buscarCliente($dniCliente)) {
                $autos = new Vehiculo($patente, $marca, $modelo, $dniCliente);
                $this->cars[] = $autos;
                echo ('El vehiculo se ha cargado correctamente.'.PHP_EOL);
            } else {
                echo ('El cliente No existe, ingrese Cliente.'.PHP_EOL);
                return false;
            }
        
            $addVeh = "INSERT INTO vehiculos (patente, marca, modelo, dni_cliente) VALUES ('$patente', '$marca', '$modelo', '$dniCliente')";
        
            if ($bd->query($addVeh) === TRUE) {
                echo(PHP_EOL);
                echo ('Vehículo agregado correctamente a la base de datos.'.PHP_EOL);
            } else {
                echo ('Error al agregar el Vehiculo. ' . $bd->error.PHP_EOL);
            }
        }
       /*
        public function agregarAuto($serviceCliente) {
            $patente = readline('Ingrese la patente del vehículo: ');            
            $marca = readline('Ingrese marca del vehículo: ');
            $modelo = readline('Ingrese modelo del Vehículo: ');
            $dniCliente = readline('Ingrese DNI del cliente titular: ');

            if ($serviceCliente->buscarCliente($dniCliente)) {
                $autos = new Vehiculo($patente, $marca, $modelo, $dniCliente);
                $this->cars[] = $autos;
                echo ('El vehiculo se ha cargado correctamente.'.PHP_EOL);
                //return true;
            } else {
                echo ('El cliente No existe, ingrese Cliente.'.PHP_EOL);
                return false;
            }

            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
            $addVeh = "INSERT INTO vehiculos (patente, marca, modelo, dni_cliente) VALUES ('$patente', '$marca', '$modelo', '$dniCliente')";
        
            if ($bd->query($addVeh) === TRUE) {
                echo(PHP_EOL);
                echo ('Vehículo agregado correctamente a la base de datos.'.PHP_EOL);
            } else {
                echo ('Error al agregar el Vehiculo. ' . $bd->error.PHP_EOL);
            }
        }
        */

        // Función para modificar los datos de un auto
        public function modificarAuto() {
            $dniCliente = readline('Ingrese el DNI del cliente: ');
            $patente = readline ('Ingrese la patente del Vehículo a modificar: ');
        
            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
        
            $newpat = readline('Nueva Patente: ');
            $newmarca = readline('Ingrese Marca: ');
            $newmodelo = readline('Ingrese Modelo: ');
        
            $modVeh = "UPDATE vehiculos 
                SET patente = '$newpat', 
                    marca = '$newmarca', 
                    modelo = '$newmodelo' 
                WHERE dni_cliente = '$dniCliente' AND patente = '$patente'";
        
            if ($bd->query($modVeh) === TRUE) {
                echo ('El vehículo fue modificado en la base de datos.' . PHP_EOL);
                return true;
            } else {
                echo ('Error al modificar el vehiculo en la base de datos. ' . $bd->error . PHP_EOL);
                return false;
            }
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
                }
            }
            echo ('Vehículo No encontrado.'.PHP_EOL);
            
            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
            $delVeh = "DELETE FROM vehiculos WHERE patente = '$patente'";   

            if ($bd->query($delVeh) === TRUE) {
                echo ('El vehículo se ha eliminado de la base de datos.'.PHP_EOL);
                return true;
            } else {
                echo ('Error, el vehículo no fue eliminado.' . $bd->error .PHP_EOL);
                return false;
            }
        }

        public function mostrarVehiculos() {
            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
        
            $getVehiculos = "SELECT * FROM vehiculos";
            $result = $bd->query($getVehiculos);
        
            if ($result->num_rows === 0) {
                echo ('No hay Vehículos cargados.'.PHP_EOL); 
                return false;
            } 
                echo ('Lista de Vehículos:'.PHP_EOL);
                echo(PHP_EOL);
                
                while ($resultados = $result->fetch_assoc()) {
                    echo ('Patente: '.$resultados['patente'].'; ');
                    echo ('Marca: '.$resultados['marca'].'; ');
                    echo ('Modelo: '.$resultados['modelo'].'; ');
                    echo ('DNI de Cliente: '.$resultados['dni_cliente'].PHP_EOL);
                    echo ('------------------------------------------------------------'); echo(PHP_EOL);
                }
                return true;
            
        }
        
        public function buscarAuto() {
            $patente = readline('Ingrese Patente: ');
        
            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
        
            $getVehiculo = "SELECT * FROM vehiculos 
                WHERE patente = '$patente'";

            $result = $bd->query($getVehiculo);
        
            if ($result->num_rows > 0) {
                while ($fila = $result->fetch_assoc()) {
                    echo ('Vehículo encontrado:' . PHP_EOL);
                    echo ('---------------------------------------------------------------------------'.PHP_EOL);
                    echo ('Patente: ' . $fila['patente'].'; ');
                    echo ('Marca: ' . $fila['marca'].'; ');
                    echo ('Modelo: ' . $fila['modelo'].'; ');
                    echo ('DNI de Cliente: ' . $fila['dni_cliente'].PHP_EOL);
                    echo ('---------------------------------------------------------------------------'.PHP_EOL);
                }
                return true;
            } else {
                echo ('El Vehículo No existe.' . PHP_EOL);
                return false;
            }
        }

        /*
        public function obtenerVehiculoPorPatente($patente) {
            foreach ($this->cars as $vehiculo) {
                if ($vehiculo->getPatente() == $patente) {
                    return $vehiculo;
                }
            }
            return null; 
        }
        */
        
        public function mostrarAutosClientes() {

            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
        
            $dniCliente = readline('Ingrese DNI del Cliente: ');
        
            $getAutosCliente = "SELECT * FROM vehiculos WHERE dni_cliente = '$dniCliente'";
            $result = $bd->query($getAutosCliente);
        
            if ($result->num_rows > 0) {

                echo (PHP_EOL);
                echo ('Vehículos del Cliente con DNI ' . $dniCliente . ':' . PHP_EOL);
                
                while ($auto = $result->fetch_assoc()) {
                    echo ('Patente: ' . $auto['patente'] . '; ');
                    echo ('Marca: ' . $auto['marca'] . '; ');
                    echo ('Modelo: ' . $auto['modelo'] . PHP_EOL);
                    echo ('------------------------------------------------------------'.PHP_EOL);
                }
            } else {
                echo ('No se encontraron vehículos para el cliente con DNI: ' . $dniCliente . PHP_EOL);
            }
        }

        
        /*
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
        */
    }