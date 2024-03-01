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
                                    
                    echo ('El Cliente se ha modificado exitasamente.'.PHP_EOL); 
                    return true; 
                }     
            } 
                    echo ('El Cliente No existe.'.PHP_EOL);
                    return false;  
        }

        // Función para eliminar un cliente
        
        public function eliminarCliente() {          
            $dni = readline('El DNI del cliente a dar de baja es: ');           
            foreach ($this->clientes as $cli => $c) {
                if ($c->getDni() === $dni) {
                    unset($this->clientes[$cli]);
                    echo ('El cliente se ha eliminado correctamente.'.PHP_EOL);
                    return true;
                }
            }
            echo ('Cliente No encontrado.'.PHP_EOL);
            return false;
        }
        
        public function mostrarClientes() {
            foreach ($this->clientes as $cliente) {
            echo "DNI: {$cliente->getDni()}, Nombre: {$cliente->getNombre()}, Apellido: {$cliente->getApellido()}, Teléfono: {$cliente->getTelefono()}\n";
            }
        }       

        public function obtenerClientePorDNI($dni) {
            foreach ($this->clientes as $cliente) {
                if ($cliente->getDni() == $dni) {
                    return $cliente;
                }
            }
            return null; // Si no se encuentra el cliente
        }
        
        
        public function buscarCliente() {
            $dni = readline('El DNI a buscar es: ');
            foreach ($this->clientes as $c) {
                if ($c->getDni() == $dni) {
                    echo ('Cliente Encontrado.'.PHP_EOL);
                    echo ('DNI: '.$c->getDni().'; ');
                    echo ('Nombre: '.$c->getNombre().'; ');
                    echo ('Apellido: '.$c->getApellido().'; '); 
                    echo ('Tel: '.$c->getTelefono().PHP_EOL); 
                    return true;
                }
            }

            echo ('El cliente no existe.'); echo(PHP_EOL);
            return false;
        }
    
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

        public function salida() {
            echo ('================================='); echo(PHP_EOL);
            echo ('Gracias por utilizar el Servicio.'); echo(PHP_EOL);
            echo ('================================='); echo(PHP_EOL);
        }
    }