<?php
    require_once ('cliente.php');

    class ServiceCliente {

        private $clientes = [];


        public function __construct() {
            $this->cargarClientes();
        }
    
        private function cargarClientes() {
            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
    
            $query = "SELECT dni, nombre, apellido, tel, email FROM clientes";
            $resultado = $bd->query($query);
    
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    $cliente = new Cliente($fila['dni'], $fila['nombre'], $fila['apellido'], $fila['tel'], $fila['email']);
                    $this->clientes[$fila['dni']] = $cliente;
                }
            }
        }
        
        public function agregarCliente() {
            $dni = readline('Ingrese el DNI del cliente: ');       
            
            if (isset($this->clientes[$dni])) {
                echo ('El cliente ya existe.'. PHP_EOL);
                return;
            }

            $nombre = readline('Ingrese nombre del Cliente: ');
            $apellido = readline('Ingrese el apellido del Cliente: ');
            $telefono = readline('Ingrese tel del Cliente: ');
            $mail = readline('Ingrese email del cliente: ');
            $cliente = new Cliente($dni, $nombre, $apellido, $telefono, $mail);
            $this->clientes[$dni] = $cliente;

            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();

            $addCli = "INSERT INTO clientes (dni, nombre, apellido, tel, email) VALUES ('$dni', '$nombre', '$apellido', '$telefono', '$mail')";

            if ($bd->query($addCli) === TRUE) {
                echo(PHP_EOL);
                echo ('Cliente agregado correctamente a la base de datos.'.PHP_EOL);
            } else {
                echo ('Error al agregar el cliente. '. $bd->error .PHP_EOL);
                return;
            }
        }

        
        public function modificarCliente() {
            $dni = readline('El DNI del cliente a modificar es: ');
        
            if (isset($this->clientes[$dni])) {
                $cli = $this->clientes[$dni];
        
                $newdni = readline('Nuevo DNI: ');
                $newnombre = readline('Nuevo Nombre: ');
                $newapellido = readline('Nuevo Apellido: ');
                $newtel = readline('Nuevo Tel: ');
                $newmail = readline('Nuevo Email: ');
        
                $cli->setDni($newdni);
                $cli->setNombre($newnombre);
                $cli->setApellido($newapellido);
                $cli->setTelefono($newtel);
                $cli->setMail($newmail);
        
                if ($newdni !== $dni) {
                    unset($this->clientes[$dni]);
                    $this->clientes[$newdni] = $cli;
                }
        
                echo ('Cliente modificado.'. PHP_EOL);
        
                $conexion = ConexionBD::obtenerInstancia();
                $bd = $conexion->obtenerConexion();
                $modCli = "UPDATE clientes 
                    SET dni='$newdni', 
                        nombre='$newnombre', 
                        apellido='$newapellido', 
                        tel='$newtel',
                        email='$newmail'
                    WHERE dni='$dni'";
        
                if ($bd->query($modCli) === TRUE) {
                    echo ('El Cliente se ha modificado en la base de datos.' . PHP_EOL);
                    return true;
                } else {
                    echo ('Error al modificar el cliente en la base de datos.' . PHP_EOL);
                    return false;
                }
            } else {
                echo ('No se encontró el cliente.'. PHP_EOL);
                return false; 
            }
        }
        
        
        public function eliminarCliente() {          
            $dni = readline('El DNI del cliente a dar de baja es: ');
        
            if (isset($this->clientes[$dni])) {
                unset($this->clientes[$dni]);
                echo ('Cliente eliminado.' . PHP_EOL);
        
                $conexion = ConexionBD::obtenerInstancia();
                $bd = $conexion->obtenerConexion();
                
                $delCli = "DELETE FROM clientes WHERE dni = '$dni'";
                if ($bd->query($delCli) === TRUE) {
                    echo ('El cliente se ha eliminado de la base de datos.' . PHP_EOL);
                } else {
                    echo ('Error al eliminar el cliente de la base de datos: ' . $bd->error . PHP_EOL);
                    return false;
                }
            } else {
                echo ('Cliente no encontrado.' . PHP_EOL);
            }
        }

        public function mostrarClientes() {
            if (count($this->clientes) > 0) {
                echo ('Lista de Clientes:' . PHP_EOL);
                foreach ($this->clientes as $cliente) {
                    echo ('DNI: ' . $cliente->getDni() . '; ');
                    echo ('Nombre: ' . $cliente->getNombre() . '; ');
                    echo ('Apellido: ' . $cliente->getApellido() . '; ');
                    echo ('Teléfono: ' . $cliente->getTelefono() . '; ');
                    echo ('Email: ' . $cliente->getMail() . PHP_EOL);
                    echo ('--------------------------------------------------------------------------');
                    echo (PHP_EOL);
                }
            } else {
                echo "No existen clientes en el sistema." . PHP_EOL;
            }
        }

        
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
                    echo ('Tel: ' . $fila['tel'].'; ');
                    echo ('Mail: ' . $fila['email'] . PHP_EOL);
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

        public function getClientes() {
            return $this->clientes;
        }


        public function salida() {
            echo ('================================='); echo(PHP_EOL);
            echo ('Gracias por utilizar el Servicio.'); echo(PHP_EOL);
            echo ('================================='); echo(PHP_EOL);
        }
    }