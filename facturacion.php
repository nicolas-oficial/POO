<?php
require_once('lib/conexion.php');

class Facturacion {

    private $facturas = [];
    
    public function hacerFactura() {
        $factura = [];
        
        $fecha = date('Y-m-d');
        $patente = readline('Ingrese la patente del vehículo: ');
        $total = 0;
        $servicio = readline('Ingrese el servicio realizado o presione "0" para terminar: ');
    
        while ($servicio !== '0') {
            if ($servicio !== '0') {
                $costo = readline('Costo ($): ');
                $total += $costo;
                $factura[] = ['servicio' => $servicio, 'costo' => $costo, 'fecha' => $fecha];
            }
            $servicio = readline('Servicio: ');
        }
        
        $this->facturas[$patente] = $factura;
    
        $conexion = ConexionBD::obtenerInstancia();
        $bd = $conexion->obtenerConexion();
    
        $addFac = "INSERT INTO facturacion (fecha, patente, total) VALUES ('$fecha', '$patente', '$total')";
        if ($bd->query($addFac) === TRUE) {
            $facturaId = $bd->insert_id;  // obtengo id de la factura insertada
    
            foreach ($factura as $ft) {           // Insertar los servicios asociados a la factura
                $servicio = $ft['servicio'];
                $costo = $ft['costo'];
                $addServicio = "INSERT INTO servicios_factura (factura_id, servicio, costo) VALUES ('$facturaId', '$servicio', '$costo')";
                if ($bd->query($addServicio) !== TRUE) {
                    echo ('Error al guardar el servicio: ' . $bd->error . PHP_EOL);
                }
            }
            echo ('Factura guardada correctamente para la patente ' . $patente . '.' . PHP_EOL);
        } else {
            echo ('Error al guardar la factura: ' . $bd->error . PHP_EOL);
        }
    }
    
    
    public function mostrarFactura() {
        $patente = readline('Ingrese patente del vehículo: ');
    
        $conexion = ConexionBD::obtenerInstancia();
        $bd = $conexion->obtenerConexion();
    
        $getFactura = "SELECT f.id AS factura_id, DATE_FORMAT(f.fecha, '%d-%m-%Y') AS fecha, f.patente, sf.servicio, sf.costo 
            FROM facturacion f 
            JOIN servicios_factura sf ON f.id = sf.factura_id 
            WHERE f.patente = '$patente'";

        $result = $bd->query($getFactura);
    
        if ($result->num_rows > 0) {
            $fila = $result->fetch_assoc();
            $facturaId = $fila['factura_id'];
            $fecha = $fila['fecha']; 
            $patente = $fila['patente'];
    
            echo (PHP_EOL);
            echo ('Servicio Pos Venta AUTOMOTION.' . PHP_EOL);
            echo ('Factura Nº: ' .$facturaId. PHP_EOL);
            echo ('Fecha: ' . $fecha . PHP_EOL); 
            echo ('Vehiculo Patente: ' . $patente . PHP_EOL);
            echo ('-----------------------------' . PHP_EOL);
            echo ('Servicios realizados:' . PHP_EOL);
            $total = 0;
            do {
                echo "- {$fila['servicio']}: $ {$fila['costo']}" . PHP_EOL;
                $total += $fila['costo'];
            } while ($fila = $result->fetch_assoc());
           
            echo ('Costo total: $ ' . $total . PHP_EOL);
        } else {
            echo ('No se encontró ninguna factura para la patente: ' . $patente . PHP_EOL);
        }
    }
    

    public function eliminarFactura() {
        $patente = readline('Ingrese patente a eliminar Factura: ');
    
        $conexion = ConexionBD::obtenerInstancia();
        $bd = $conexion->obtenerConexion();
    
        $getFacturaId = "SELECT id FROM facturacion WHERE patente = '$patente'";
        $result = $bd->query($getFacturaId);
    
        if ($result) {
            $fila = $result->fetch_assoc();
            if ($fila) {
                $facturaId = $fila['id'];
            } else {
                echo ('No se encontró ninguna factura para la patente: ' . $patente . PHP_EOL);
                return;
            }
        } else {
            echo ('Error al obtener el ID de la factura: ' . $bd->error . PHP_EOL);
            return;
        }
    
        $delServicios = "DELETE FROM servicios_factura WHERE factura_id = '$facturaId'";
        if ($bd->query($delServicios) === TRUE) {
            //echo ('Registros de servicios asociados a la factura eliminados de la base de datos.' . PHP_EOL);
        } else {
            echo ('Error al eliminar los servicios de la factura: ' . $bd->error . PHP_EOL);
            return;
        }
    
        $delFac = "DELETE FROM facturacion WHERE id = '$facturaId'";
        if ($bd->query($delFac) === TRUE) {
            echo ('Factura eliminada de la base de datos.' . PHP_EOL);
        } else {
            echo ('Error al eliminar la factura de la base de datos: ' . $bd->error . PHP_EOL);
            return;
        }
    
        // Eliminar la factura de la aplicación
        if (isset($this->facturas[$patente])) {
            unset($this->facturas[$patente]);
            echo ('La Factura fue eliminada.' . PHP_EOL);
        } else {
            echo ('No existe Factura para patente: ' . $patente . PHP_EOL);
        }
    }
    
    /*
    public function guardar() {
        $arrSer = serialize($this->facturas);
        file_put_contents("facturas.json", $arrSer);
        //print_r ($arrSer); echo(PHP_EOL);
    }

    public function leer() {
        $recArr = file_get_contents("facturas.json");
        $arrOrig = unserialize($recArr);
        //print_r ($arrOrig);
        $this->facturas = $arrOrig;
    }
    */
}