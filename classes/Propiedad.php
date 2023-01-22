<?php

namespace App;

class Propiedad {

    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedorId;

    public function __construct($args =[]){
         $this->id=$argc['id'] ?? '';
         $this->titulo=$argc['titulo'] ?? '';
         $this->precio=$argc['precio'] ?? '';
         $this->imagen=$argc['imagen'] ?? '';
         $this->descripcion=$argc['descripcion'] ?? '';
         $this->habitaciones=$argc['habitaciones'] ?? '';
         $this->wc=$argc['wc'] ?? '';
         $this->estacionamiento=$argc['estacionamiento'] ?? '';
         $this->creado=$argc['creado'] ?? '';
         $this->vendedorId=$argc['vendedorId'] ?? '';
    }

}