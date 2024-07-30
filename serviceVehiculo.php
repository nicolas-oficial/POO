<?php
    require_once ('vehiculo.php');

    class ServiceVehiculo {          
                                    
        private $cars = [];  
        private $serviceCliente;
        
        public function agregarAuto() {
            $patente = readline('Ingrese la patente del vehículo: ');
    
            foreach ($this->cars as $vehiculo) {
                if ($vehiculo->getPatente() === $patente) {
                    echo ('El vehículo patente '. $patente . ' ya existe.' . PHP_EOL);
                    return;
                }
            }
    
            $marca = readline('Ingrese la marca del vehículo: ');
            $modelo = readline('Ingrese el modelo del vehículo: ');
            $dniCliTit = readline('Ingrese el DNI del titular del vehículo: ');
    
            if (!isset($this->serviceCliente->getClientes()[$dniCliTit])) {
                echo ('El cliente con el DNI proporcionado no existe.' . PHP_EOL);
                return;
            }
    
            $vehiculo = new Vehiculo($patente, $marca, $modelo, $dniCliTit);
            $this->cars[$patente] = $vehiculo;
    
            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
    
            $addVeh = "INSERT INTO vehiculos (patente, marca, modelo, dni_cliente) VALUES ('$patente', '$marca', '$modelo', '$dniCliTit')";
    
            if ($bd->query($addVeh) === TRUE) {
                echo ('Vehículo agregado correctamente a la base de datos.' . PHP_EOL);
            } else {
                echo ('Error al agregar el vehículo. ' . $bd->error . PHP_EOL);
            }
        }

        
        public function modificarAuto() {
            $patente = readline('Ingrese patente: ');

            if (isset($this->cars[$patente])) {
                $auto = $this->cars[$patente];

                $newPatente = readline('Ingrese nueva patente: ');
                $newMarca = readline('Ingrese nueva Marca: ');
                $newModelo = readline('Ingrese nuevo Modelo: ');
                $newDniTit = readline('Ingrese nuevo DNI de Titular: ');

                $clientes = $this->serviceCliente->getClientes();
                if (!isset($clientes[$newDniTit])) {
                    echo 'El DNI del nuevo titular no existe. No se puede modificar el vehículo.'.PHP_EOL;
                    return;
                }

                $auto->setPatente($newPatente);
                $auto->setMarca($newMarca);
                $auto->setModelo($newModelo);
                $auto->setDniCliTit($newDniTit);

                if ($newPatente !== $patente) {
                    unset($this->cars[$patente]);
                    $this->cars[$newPatente] = $auto;
                }

                echo ('Vehículo modificado.'. PHP_EOL);

                $conexion = ConexionBD::obtenerInstancia();
                $bd = $conexion->obtenerConexion();
                $modVeh = "UPDATE vehiculos 
                    SET patente='$newPatente', 
                        marca='$newMarca', 
                        modelo='$newModelo', 
                        dni_cliente='$newDniTit'
                    WHERE patente='$patente'";
        
                if ($bd->query($modVeh) === TRUE) {
                    echo ('El Vehículo se ha modificado en la base de datos.' . PHP_EOL);
                    return true;
                } else {
                    echo ('Error al modificar el vehículo en la base de datos.' . PHP_EOL);
                    return false;
                }
            } else {
                echo ('No se encontró el vehículo.'. PHP_EOL);
                return false; 
            }
            
        }
    
        
        public function eliminarAuto() {          
            $patente = readline('Ingrese Patente: ');    
            
            if (isset($this->cars[$patente])) {
                unset($this->cars[$patente]);
                echo ('El vehículo se ha eliminado.'.PHP_EOL);

                $conexion = ConexionBD::obtenerInstancia();
                $bd = $conexion->obtenerConexion();
                $delVeh = "DELETE FROM vehiculos WHERE patente = '$patente'";   

                if ($bd->query($delVeh) === TRUE) {
                    echo ('Vehículo eliminado de la base de datos.'.PHP_EOL);
                    return true;
                } else {
                    echo ('Error, el vehículo no fue eliminado.' . $bd->error .PHP_EOL);
                    return false;
                }
            } else {
                echo ('Vehículo no encontrado.' . PHP_EOL);
            }          
        }

        public function mostrarVehiculos() {

            if (count($this->cars) > 0) {
                echo ('Lista de Vehículos:' . PHP_EOL);
                foreach ($this->cars as $auto) {
                    echo ('Patente: ' . $auto->getPatente() . '; ');
                    echo ('Marca: ' . $auto->getMarca() . '; ');
                    echo ('Modelo: ' . $auto->getModelo() . '; ');
                    echo ('DNI Titular: ' . $auto->getDniCliTit()); echo (PHP_EOL);
                    echo ('-------------------------------------------------------------');
                    echo (PHP_EOL);
                }
            } else {
                echo "No existen Vehículos en el sistema." . PHP_EOL;
            }
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
        
        public function __construct($serviceCliente) {
            $this->serviceCliente = $serviceCliente; 
            $this->cargarAutos(); 
        }

    
        private function cargarAutos() {
            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
    
            $query = "SELECT patente, marca, modelo, dni_cliente FROM vehiculos";
            $resultado = $bd->query($query);
    
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    $auto = new Vehiculo($fila['patente'], $fila['marca'], $fila['modelo'], $fila['dni_cliente']);
                    $this->cars[$fila['patente']] = $auto;
                }
            }
        }
        
    }