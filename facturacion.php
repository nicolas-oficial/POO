<?php

class Facturacion {

    private $facturas = [];
    
    public function hacerFactura() {
        $factura = [];
        
        $patente = readline('Ingrese la patente del vehículo: ');
        $servicio = readline('Ingrese el servicio realizado o presione "0" para terminar: ');
    
        while ($servicio !== '0') {
            $costo = readline('Costo ($): ');
            $factura[$servicio] = $costo;
            $servicio = readline('Servicio: ');
        }
    
        $this->facturas[$patente] = $factura;
    
        echo "Factura guardada correctamente para la patente $patente.\n";
    }
    
    
    public function mostrarFactura() {
        // Verificar si hay alguna factura guardada
        if (!$this->facturas) {
            echo "No hay facturas guardadas.\n";
            return;
        }
    
        echo ('Mostrar Factura.'.PHP_EOL);
        $patente = readline('Ingrese patente del vehículo: ');

        // Verificar si la patente existe en las facturas guardadas
        if (isset($this->facturas[$patente])) {
            echo (PHP_EOL);
            echo ('Servicio Pos Venta AUTOMOTION.'.PHP_EOL);
            echo ('Factura Nº: '); echo(PHP_EOL);
            echo ('Vehiculo Patente: '.$patente); echo(PHP_EOL);
            echo ('-----------------------------'.PHP_EOL);
            echo ('Servicios realizados:'.PHP_EOL);
            $total = 0;
            foreach ($this->facturas[$patente] as $servicio => $costo) {
                echo "- $servicio: $" . $costo . PHP_EOL;
                $total += $costo;
            }
            // Calcular y mostrar el total
            echo ('Costo total: $ '. $total . PHP_EOL);
        } else {
            echo ('No se encontró ninguna factura para la patente: ' .$patente .PHP_EOL);
        }
    }

    public function eliminarFactura() {
        $patente = readline ('Ingrese patente a eliminar Factura: ');
    
        if (isset($this->facturas[$patente])) {
            unset($this->facturas[$patente]);
            echo ('La Factura fue eliminada.'.PHP_EOL);
            return true;
        } else {
            echo ('No existe Factura para patente: '.$patente); echo (PHP_EOL);
            return false;
        }
    }
    
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
    
}