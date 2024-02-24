<?php
    require_once('cliente.php');
    require_once('serviceCliente.php');
    require_once('vehiculo.php');
    require_once('serviceVehiculo.php');
    require_once('turnoServicio.php');
    require_once('serviceTurnoServicio.php');
      
    $servicioCliente = new ServiceCliente();
    $servicioVehiculo = new ServiceVehiculo();
    $serviceTurnoServicio = new ServiceTurnoServicio();
    
    $servicioCliente->leer();
    $servicioVehiculo->leer();
    $serviceTurnoServicio->leer();
    
    
    function menuPrincipal() {
        echo ('========= Bienvenidos =========='); echo(PHP_EOL);
        echo ('===== PosService AutoMotion ===='); echo(PHP_EOL);
        echo ('================='); echo(PHP_EOL);
        echo ('Menú de opciones'); echo(PHP_EOL);
        echo ('================='); echo(PHP_EOL);
        echo ('1-Clientes.'); echo(PHP_EOL);
        echo ('2-Vehículos.'); echo(PHP_EOL);
        echo ('3-Turnos.'); echo(PHP_EOL);
        echo ('4-Facturación.'); echo(PHP_EOL);
        echo ('0-Salir.'); echo(PHP_EOL);
    }
            
           
    function menuCliente() {

        echo(PHP_EOL);
        echo ('================='); echo(PHP_EOL);
        echo ('Menú de Clientes.'); echo(PHP_EOL);
        echo ('================='); echo(PHP_EOL);
        echo ('1 - Alta de Clientes.'); echo(PHP_EOL);
        echo ('2 - Modificar Clientes.'); echo(PHP_EOL);
        echo ('3 - Baja de Clientes.'); echo(PHP_EOL);
        echo ('4 - Buscar un Cliente.'); echo(PHP_EOL);
        echo ('5 - Mostrar Lista de Clientes.'); echo(PHP_EOL);
        echo ('6 - Mostrar vehículo de Cliente.'); echo(PHP_EOL);
        echo ('0 - Salir.'); echo(PHP_EOL);
    }
        
    function menuVehiculo() {
        
        echo ('================='); echo(PHP_EOL);
        echo ('Menú de Vehículos.'); echo(PHP_EOL);
        echo ('================='); echo(PHP_EOL);
        echo ('1 - Alta de Vehículos.'); echo(PHP_EOL);
        echo ('2 - Modificar Vehículo.'); echo(PHP_EOL);
        echo ('3 - Baja de Vehículo.'); echo(PHP_EOL);
        echo ('4 - Buscar un Vehiculo.'); echo(PHP_EOL);
        echo ('5 - Mostrar Lista de Vehículos.'); echo(PHP_EOL);
        echo ('0 - Salir.'); echo(PHP_EOL);
    }

    function menuTurnos() {

        echo ('============================'); echo(PHP_EOL);
        echo ('Menú Turnos de Servicios.'); echo(PHP_EOL);
        echo ('============================'); echo(PHP_EOL);
        echo ('1 - Reserva de Turno.'); echo(PHP_EOL);
        echo ('2 - Modificar Turno.'); echo(PHP_EOL);
        echo ('3 - Eliminar Turno.'); echo(PHP_EOL);
        echo ('4 - Buscar un Turno.'); echo(PHP_EOL);
        echo ('5 - Mostrar Turnos.'); echo(PHP_EOL);
        echo ('0 - Salir.'); echo(PHP_EOL);
    }
    

    $opcion = " ";
    while ($opcion != 0) {
        menuPrincipal();
        $opcion = readline('Ingrese una opción: ');

        switch ($opcion) {
            case 1:
                echo('Seleccionaste Menú de clientes.'.PHP_EOL); 
                $opcionC = "";
                while ($opcionC != 0) {
                    menuCliente();
                    $opcionC = readline('Ingrese una opción: ');
                    switch ($opcionC) {
                        case 1: 
                            echo('Seleccionaste dar de alta a un cliente.'.PHP_EOL);
                            $servicioCliente->agregarCliente(); break;
                           
                        case 2: 
                            echo('Seleccionaste modificar un cliente.'.PHP_EOL);
                            $servicioCliente->modificarCliente(); break;
                        case 3: 
                            echo('Seleccionaste dar de baja a un cliente.'.PHP_EOL);
                            $servicioCliente->eliminarCliente(); break;
                        case 4: 
                            echo('Seleccionaste buscar un cliente.'.PHP_EOL);
                            $servicioCliente->buscarCliente(); break;
                        case 5: 
                            echo('Lista de clientes.'.PHP_EOL);
                            $servicioCliente->mostrarClientes(); break;
                        case 6:
                            echo('Vehículo de Cliente: '.PHP_EOL);
                            $servicioVehiculo->mostrarAutosClientes(); break;
                        case 0: 
                            $servicioCliente->grabar(); break;
                            echo ('Regresar al Menú Principal.'.PHP_EOL);
                            echo (PHP_EOL); break;
                        default: 
                            echo('Opción inválida.'.PHP_EOL);
                    }
                }
                break;
        
            
            case 2: 
                echo('Seleccionaste Menú de vehículos.'.PHP_EOL);
                $opcionV = "";
                while ($opcionV != 0) {
                    menuVehiculo();
                    $opcionV = readline('Ingrese una opción: ');
                    switch ($opcionV) {
                        case 1: 
                            echo('Seleccionaste dar de alta a un vehículo.'.PHP_EOL);
                            $servicioVehiculo->agregarAuto($servicioCliente); break;
                        case 2: 
                            echo('Seleccionaste modificar un vehículo.'.PHP_EOL);
                            $servicioVehiculo->modificarAuto($servicioCliente); break;
                        case 3: 
                            echo('Seleccionaste eliminar un vehículo.'.PHP_EOL);
                            $servicioVehiculo->eliminarAuto(); break;
                        case 4: 
                            echo('Seleccionaste buscar un vehículo.'.PHP_EOL);
                            $servicioVehiculo->buscarAuto(); break;
                        case 5: 
                            echo('Seleccionaste lista de vehículos.'.PHP_EOL);
                            $servicioVehiculo->mostrarVehiculos(); break;
                        case 0: 
                            $servicioVehiculo->grabar(); break;
                            echo ('Regresar al Menú Principal.'.PHP_EOL); break;
                        default: 
                            echo ('Opción inválida.'.PHP_EOL);
                    }
                }
                break;

            case 3:
                echo ('seleccionaste Menú de Turnos.'.PHP_EOL);
                $opcionT = "";
                while ($opcionT != 0) {
                    menuTurnos();
                    $opcionT = readline ('Ingrese una opción: ');
                    switch ($opcionT) {
                        case 1:
                            echo ('Reservar Turno.'.PHP_EOL);
                            $serviceTurnoServicio->reservaTurno($servicioCliente, $servicioVehiculo); break;
                        
                        case 2:
                            $serviceTurnoServicio->modificarTurno(); break;

                        case 3:
                            echo ('Eliminar turno.'.PHP_EOL);
                            $serviceTurnoServicio->eliminarTurno(); break;
                        
                        case 4:
                            $serviceTurnoServicio->buscarTurno(); break;
                        
                        case 5:
                            echo ('Lista de turnos.'.PHP_EOL);
                            $serviceTurnoServicio->mostrarTurnos(); break;
                        case 0:
                            $serviceTurnoServicio->guardar(); break;
                            echo ('Regresar al Menú Principal.'.PHP_EOL); break;
                        default:
                            echo ('Opción inválida.'.PHP_EOL);
                    }
                }
                break;

            case 0: $servicioCliente->salida(); break;
        }
       
    }
    