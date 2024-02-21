<?php

class Cliente {

    private $dni;
    private $nombre;
    private $apellido;
    private $telefono;

    public function __construct($dni, $nombre, $apellido, $telefono) {
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->telefono = $telefono;
    }

    public function getDni() {
        return $this->dni;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    
    public function setDni($dni) {
        $this->dni = $dni;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }
}
