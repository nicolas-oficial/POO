<?php
require_once('Servicio.php');
require_once('Vehiculo.php'); // Asegúrate de incluir el archivo de la clase Vehiculo

class ServiceServicio {
    private $servicios = [];
    private $vehiculos = []; // Array para almacenar los vehículos

    public function __construct() {
        $this->cargarServicios();
        $this->cargarVehiculos(); // Cargar vehículos al inicializar
    }

    private function cargarServicios() {
        $conexion = ConexionBD::obtenerInstancia();
        $bd = $conexion->obtenerConexion();
        $query = "SELECT s.id, v.patente, s.descripcion, s.costo, s.estado, s.turno_id, t.fecha AS fecha_turno
                  FROM servicios s
                  JOIN turnos t ON s.turno_id = t.id
                  JOIN vehiculos v ON v.patente = t.patente";

        $resultado = $bd->query($query);

        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $servicio = new Servicio(
                    $fila['id'], 
                    $fila['patente'], 
                    $fila['descripcion'], 
                    $fila['costo'], 
                    $fila['estado'], 
                    $fila['turno_id'], 
                    $fila['fecha_turno']
                );
                $this->servicios[$fila['id']] = $servicio;
            }
        }
    }

    private function cargarVehiculos() {
        $conexion = ConexionBD::obtenerInstancia();
        $bd = $conexion->obtenerConexion();
        $query = "SELECT patente FROM vehiculos";
        $resultado = $bd->query($query);

        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $this->vehiculos[$fila['patente']] = true; // Simple validación usando array
            }
        }
    }

    public function agregarServicio() {
        $turnoId = readline('Ingrese el ID del turno: ');
    
        $conexion = ConexionBD::obtenerInstancia();
        $bd = $conexion->obtenerConexion();
        $getTurno = "SELECT id, patente, fecha FROM turnos WHERE id = '$turnoId'";
        $resultTurno = $bd->query($getTurno);
    
        if ($resultTurno->num_rows > 0) {
            $filaTurno = $resultTurno->fetch_assoc();
            $patente = $filaTurno['patente'];
            $fechaTurno = $filaTurno['fecha'];
        } else {
            echo ('No se encontró un turno para el ID proporcionado.' . PHP_EOL);
            return;
        }
    
        while (true) {
            $descripcion = readline('Ingrese la descripción del servicio: ');
            $costo = readline('Ingrese el precio del servicio: ');
            $estado = readline('Ingrese el estado del servicio (cerrado/abierto): ');
    
            $servicio = new Servicio(null, $patente, $descripcion, $costo, $estado, $turnoId, $fechaTurno);
    
            $addServ = "INSERT INTO servicios (descripcion, costo, estado, turno_id, fecha) VALUES ('$descripcion', '$costo', '$estado', '$turnoId', '$fechaTurno')";
    
            if ($bd->query($addServ) === TRUE) {
                $servicio->setId($bd->insert_id);
                $this->servicios[$servicio->getId()] = $servicio;
                echo ('Servicio agregado correctamente a la base de datos.' . PHP_EOL);
            } else {
                echo ('Error al agregar el servicio: ' . $bd->error . PHP_EOL);
            }
    
            $continuar = readline('¿Desea agregar otro servicio? (s/n): ');
            if (strtolower($continuar) != 's') {
                break;
            }
        }
    }
    
    

    public function eliminarServicio() {
        $id = readline('Ingrese el id del servicio a eliminar: ');

        if (isset($this->servicios[$id])) {
            unset($this->servicios[$id]);
            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
            $delServ = "DELETE FROM servicios WHERE id = '$id'";

            if ($bd->query($delServ) === TRUE) {
                echo ('Servicio eliminado correctamente de la base de datos.' . PHP_EOL);
            } else {
                echo ('Error al eliminar el servicio: ' . $bd->error . PHP_EOL);
            }
        } else {
            echo ('Servicio no encontrado.' . PHP_EOL);
        }
    }
    public function modificarServicio() {
        $id = readline('Ingrese el id del servicio a modificar: ');
    
        if (isset($this->servicios[$id])) {
            $servicio = $this->servicios[$id];
    
            // Verificar si el servicio está cerrado
            if (strtolower($servicio->getEstado()) === 'cerrado') {
                echo ('El servicio está cerrado y no se puede modificar.' . PHP_EOL);
                return;
            }
    
            $nuevaDescripcion = readline('Nueva descripción: ');
            $nuevoCosto = readline('Nuevo precio: ');
            $nuevoEstado = readline('Nuevo estado del servicio (cerrado/abierto): ');
    
            $servicio->setDescripcion($nuevaDescripcion);
            $servicio->setCosto($nuevoCosto);
            $servicio->setEstado($nuevoEstado);
    
            $conexion = ConexionBD::obtenerInstancia();
            $bd = $conexion->obtenerConexion();
            $modServ = "UPDATE servicios 
                        SET descripcion='$nuevaDescripcion', 
                            costo='$nuevoCosto', 
                            estado='$nuevoEstado' 
                        WHERE id='$id'";
    
            if ($bd->query($modServ) === TRUE) {
                echo ('Servicio modificado correctamente en la base de datos.' . PHP_EOL);
            } else {
                echo ('Error al modificar el servicio: ' . $bd->error . PHP_EOL);
            }
        } else {
            echo ('Servicio no encontrado.' . PHP_EOL);
        }
    }
    
    public function mostrarServicios() {
        if (count($this->servicios) > 0) {
            echo ('Lista de Servicios:' . PHP_EOL);
            foreach ($this->servicios as $servicio) {
                echo ('Id: ' . $servicio->getId() . '; ');
                echo ('Fecha del Servicio: ' . $servicio->getFechaTurno() . PHP_EOL);
                echo ('Descripción: ' . $servicio->getDescripcion() . '; ');
                echo ('Precio: ' . $servicio->getCosto() . '; ');
                echo ('Patente: ' . $servicio->getPatente() . '; ');
                echo ('Estado: ' . $servicio->getEstado() . '; ');
                echo ('--------------------------------------------------------------------------');
                echo (PHP_EOL);
            }
        } else {
            echo "No existen servicios en el sistema." . PHP_EOL;
        }
    }

    public function calcularCostoServiciosCerrados() {
        $costosPorVehiculo = [];

        foreach ($this->servicios as $servicio) {
            if ($servicio->getEstado() === 'cerrado') {
                $patente = $servicio->getPatente();
                if (!isset($costosPorVehiculo[$patente])) {
                    $costosPorVehiculo[$patente] = [
                        'Costo Total' => 0,
                        'Servicios' => []
                    ];
                }
                $costosPorVehiculo[$patente]['Costo Total'] += $servicio->getCosto();
                $costosPorVehiculo[$patente]['Servicios'][] = [
                    'Descripción' => $servicio->getDescripcion(),
                    'Costo' => $servicio->getCosto()
                ];
            }
        }

        return $costosPorVehiculo;
    }

    public function Facturacion() {
        $facturacion = $this->calcularCostoServiciosCerrados();
    
        if (count($facturacion) > 0) {
            echo "Facturación detallada por vehículo:" . PHP_EOL;
            foreach ($facturacion as $patente => $datos) {
                echo "Patente: $patente" . PHP_EOL;
                echo "Descripción:" . PHP_EOL;
    
                $totalServicios = 0;
    
                foreach ($datos['Servicios'] as $servicio) {
                    echo "  " . $servicio['Descripción'] . ", Costo Unitario: " . number_format($servicio['Costo'], 2) . PHP_EOL;
                    $totalServicios += $servicio['Costo'];
                }
    
                echo "Costo Total (suma de servicios): " . number_format($totalServicios, 2) . PHP_EOL;
                echo '--------------------------------------------------------------------------' . PHP_EOL;
            }
        } else {
            echo "No hay servicios cerrados para mostrar." . PHP_EOL;
        }
    }
}

