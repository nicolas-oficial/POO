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
    
        echo ('Factura guardada.'.PHP_EOL);
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