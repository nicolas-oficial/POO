<?php
    class Vehiculo {
       private $patente; 
       private $marca;
       private $modelo;
       private $dniCliTit;
       
       
       public function __construct($patente, $marca, $modelo, $dniCliTit){
            $this->patente = $patente;
            $this->marca = $marca;
            $this->modelo = $modelo;
            $this->dniCliTit = $dniCliTit; 
       }

        public function getPatente() {
            return $this-> patente;
        }

        public function getMarca(){
            return $this-> marca;
        }

        public function getModelo(){
            return $this-> modelo;
        }

        public function getDniCliTit() {
            return $this-> dniCliTit;
        }

       
        public function setPatente($patente) {
            $this->patente = $patente;
        } 

        public function setMarca($marca) {
           $this->marca = $marca;
        }

        public function setModelo($modelo) {
            $this->modelo = $modelo;
        }

        public function setDniCliTit($dniCliTit) {
            $this->dniCliTit = $dniCliTit;
        }
    }
