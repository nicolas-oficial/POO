<?php
    require_once ('cliente.php');

    class ServiceCliente {

        private $clientes = [];

        // Función para agregar un nuevo cliente
        
        public function agregarCliente() {
            $dni = readline('Ingrese el DNI del cliente: ');            
            $nombre = readline('Ingrese nombre del Cliente: ');
            $apellido = readline('Ingrese el apellido del Cliente: ');
            $telefono = readline('Ingrese tel del Cliente: ');
            $cliente = new Cliente($dni, $nombre, $apellido, $telefono);
            $this->clientes[$dni] = $cliente;

            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
            $addCli = "INSERT INTO clientes (dni, nombre, apellido, tel) VALUES ('$dni', '$nombre', '$apellido', '$telefono')";

            if ($bd->query($addCli) === TRUE) {
                echo(PHP_EOL);
                echo ('Cliente agregado correctamente a la base de datos.'.PHP_EOL);
            } else {
                echo ('Error al agregar el cliente. '. $bd->error .PHP_EOL);
                return;
            }
        }

        // Función para modificar los datos de un cliente existente
        
        public function modificarCliente() {
            $dni = readline('El Dni del cliente a modificar es: ');
        
            foreach ($this->clientes as $cli) {
                if ($cli->getDni() == $dni) {
                    $newdni = readline('Nuevo Dni: ');
                    $newnombre = readline('Nuevo Nombre: ');
                    $newapellido = readline('Nuevo Apellido: ');
                    $newtel = readline('Nuevo Tel: ');
                    $cli->setDni($newdni);
                    $cli->setNombre($newnombre);
                    $cli->setApellido($newapellido);
                    $cli->setTelefono($newtel);
        
                    $conexion = ConexionBD::obtenerInstancia();
                    $bd = $conexion->obtenerConexion();
                    $modCli = "UPDATE clientes 
                        SET dni='$newdni', 
                            nombre='$newnombre', 
                            apellido='$newapellido', 
                            tel='$newtel' 
                        WHERE dni='$dni'";
        
                    
                    if ($bd->query($modCli) === TRUE) {
                        echo ('El Cliente se ha modificado en la base de datos.' . PHP_EOL);
                        return true;
                    } else {
                        echo ('Error al modificar el cliente en la base de datos. ' .PHP_EOL);
                        return false;
                    }
                }
            }
                    echo ('El Cliente No existe.'.PHP_EOL);
                    return false;  
        }

        // Función para eliminar un cliente
        
        public function eliminarCliente() {          
            $dni = readline('El DNI del cliente a dar de baja es: ');           
            
            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
            
            $delCli = "DELETE FROM clientes WHERE dni = '$dni'";
            if ($bd->query($delCli) === TRUE) {
                echo ('El cliente se ha eliminado de la base de datos.'.PHP_EOL);

                foreach ($this->clientes as $cli => $c) {
                    if ($c->getDni() === $dni) {
                        unset($this->clientes[$cli]);
                        echo ('El cliente se ha eliminado de la aplicación.'.PHP_EOL);
                        return true;
                    }
                } 

            } else {
                echo ('Error al eliminar el cliente.'. $bd->error .PHP_EOL);
                return false;
            }
            
            echo ('Cliente No encontrado.' . PHP_EOL);
            return false;
        }
        

        public function mostrarClientes() {
            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
        
            $getClientes = "SELECT * FROM clientes";
            $result = $bd->query($getClientes);
        
            if ($result->num_rows === 0) {
                echo ('No hay Clientes cargados.' . PHP_EOL);
                return false;
            }
        
            echo ('Lista de Clientes:' . PHP_EOL);
            echo (PHP_EOL);
        
            while ($resultados = $result->fetch_assoc()) {
                echo ('DNI: ' . $resultados['dni'] . '; ');
                echo ('Nombre: ' . $resultados['nombre'] . '; ');
                echo ('Apellido: ' . $resultados['apellido'] . '; ');
                echo ('Tel: ' . $resultados['tel'] . PHP_EOL);
                echo ('------------------------------------------------------------');
                echo (PHP_EOL);
            }
            return true;
        }
    
        /*
        public function obtenerClientePorDNI($dni) {
            foreach ($this->clientes as $cliente) {
                if ($cliente->getDni() == $dni) {
                    return $cliente;
                }
            }
            return null; 
        }
        */

        
        public function buscarCliente() {
            $dni = readline('El DNI a buscar es: ');
        
            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
        
            $getCliente = "SELECT * FROM clientes 
                WHERE dni = '$dni'";
            $result = $bd->query($getCliente);
        
            if ($result->num_rows > 0) {
                while ($fila = $result->fetch_assoc()) {
                    echo ('Cliente encontrado:' . PHP_EOL);
                    echo ('---------------------------------------------------------------------------'.PHP_EOL);
                    echo ('DNI: ' . $fila['dni'].'; ');
                    echo ('Nombre: ' . $fila['nombre'].'; ');
                    echo ('Apellido: ' . $fila['apellido'].'; ');
                    echo ('Tel: ' . $fila['tel'].PHP_EOL);
                    echo ('---------------------------------------------------------------------------'.PHP_EOL);
                }
                return true;
            } else {
                echo ('El Cliente No existe.' . PHP_EOL);
                return false;
            }
        }
        
        
        /*
        public function grabar() {
            $arrSer = serialize($this->clientes);
            file_put_contents("clientes.json", $arrSer);
            //print_r ($arrSer); echo(PHP_EOL);
        }

        public function leer() {
            $recArr = file_get_contents("clientes.json");
            $arrOrig = unserialize($recArr);
            //print_r ($arrOrig);
            $this->clientes = $arrOrig;
        }
        */

        public function salida() {
            echo ('================================='); echo(PHP_EOL);
            echo ('Gracias por utilizar el Servicio.'); echo(PHP_EOL);
            echo ('================================='); echo(PHP_EOL);
        }
    }