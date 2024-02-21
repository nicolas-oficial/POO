<?php

    class TurnoServicio {

        private $fecha;
        private $hora;
        private $tipoServicio;
        private $patente;
        private $dniCli;

        public function __construct($fecha, $hora, $tipoServicio, $patente, $dniCli) {
            $this->fecha = $fecha;
            $this->hora = $hora;
            $this->tipoServicio = $tipoServicio;
            $this->patente = $patente;
            $this->dniCli = $dniCli;
        }

        public function getFecha() {
            return $this->fecha;
        }

        public function getHora() {
            return $this->hora;
        }

        public function getTipoServicio() {
            return $this->tipoServicio;
        }

        public function getPatente() {
            return $this->patente;
        }

        public function getDniCli() {
            return $this->dniCli;
        }

        public function setFecha($fecha) {
            $this->fecha = $fecha;
        }

        public function setHora($hora) {
            $this->hora = $hora;
        }

        public function setTipoServicio($tipoServicio) {
            $this->tipoServicio = $tipoServicio;
        }

        public function setPatente($patente) {
            $this->patente = $patente;
        } 

        public function setDniCli($dniCli) {
            $this->dniCli = $dniCli;
        }

        public function mostrarTurno() {

            echo ('Fecha del turno: '.$this->fecha); echo(PHP_EOL);
            echo ('Hora del turno: '.$this->hora); echo(PHP_EOL);
            echo ('Tipo de servicio: '.$this->tipoServicio); echo(PHP_EOL);
            echo ('Patente: '.$this->patente); echo(PHP_EOL);
            echo ('DNI de Titular: '.$this->dniCli); echo(PHP_EOL);
        }
    }