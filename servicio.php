<?php

class Servicio {
    private $id;
    private $patente;
    private $descripcion;
    private $costo;
    private $estado;
    private $turno_id;
    private $fecha_turno; // Renombrado para mayor claridad

    public function __construct($id, $patente, $descripcion, $costo, $estado, $turno_id, $fecha_turno = null) {
        $this->id = $id;
        $this->patente = $patente;
        $this->descripcion = $descripcion;
        $this->costo = $costo;
        $this->estado = $estado;
        $this->turno_id = $turno_id;
        $this->fecha_turno = $fecha_turno;
    }

    public function getId() {
        return $this->id;
    }

    public function getPatente() {
        return $this->patente;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getCosto() {
        return $this->costo;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getTurnoId() {
        return $this->turno_id;
    }

    public function getFechaTurno() {
        return $this->fecha_turno;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setPatente($patente) {
        $this->patente = $patente;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setCosto($costo) {
        $this->costo = $costo;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setTurnoId($turno_id) {
        $this->turno_id = $turno_id;
    }

    public function setFechaTurno($fecha_turno) {
        $this->fecha_turno = $fecha_turno;
    }
}
