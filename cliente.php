<?php

class Cliente {

    private $dni;
    private $nombre;
    private $apellido;
    private $telefono;
    private $mail;

    public function __construct($dni, $nombre, $apellido, $telefono, $mail) {
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->telefono = $telefono;
        $this->mail = $mail;
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

    public function getMail() {
        return $this->mail;
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

    public function setMail($mail) {
        $this->mail = $mail;
    }
}
