<?php

namespace App;

class ActiveRecord{
     //base de datos
     protected static $db;
     protected static $columnasDB = [];
     protected static $tabla='';
     //Errores
     protected static $errores = [];
 

 
     //definir la conexion ala bd
     public static function setDB( $database ) {
         self::$db = $database;
     }
 

 
     public function guardar(){
         if (!is_null( $this->id )) {
             //Actualizar
             $this->actualizar();
         }else{
             //Crear
             $this->crear();
         }
     }
 
     public function crear() {
 
         //sanitisar los datos
         $atributos = $this->sanitizarAtributos();
 
         //Isertar en la bd
         $query = "INSERT INTO" . static::$tabla ." (";
         $query .= join( ', ', array_keys( $atributos ) );
         $query .= " ) VALUES (' ";
         $query .= join( "', '", array_values( $atributos ) );
         $query .= " ') ";
 
         $resultado = self::$db->query( $query );
 
               //Mensaje de exito o error
         if ( $resultado ) {
                 //redireccionar al usuario
         header( 'Location:/admin?resultado=1' );
         }
     }
 
     public function actualizar(){
         //sanitisar los datos
         $atributos = $this->sanitizarAtributos();
         // $query = "  ";
         $valores=[];
         foreach($atributos as $key => $value){
             $valores[]="{$key}='$value'";
         }
         $query ="UPDATE " . static::$tabla . " SET ";
         $query .= join(', ',$valores);
         $query .=" WHERE id= '" . self::$db->escape_string($this->id). "' ";
         $query .=" LIMIT 1";
 
         $resultado = self::$db->query( $query );
         if ( $resultado ) {
             //redireccionar al usuario
             header( 'Location:/admin?resultado=2' );
         }
     }
 
     //Eliminar un registro
     public function eliminar(){
         $query = "DELETE FROM " . static::$tabla . "  WHERE id = " . self::$db->escape_string($this->id). " LIMIT 1";
         $resultado = self::$db->query( $query );
         if ( $resultado ) {
             $this->borrarImagen();
             header( 'Location: /admin?resultado=3' );
         }
     }
     //identificar y unir los atributos de la BD
 
     public function atributos() {
         $atributos = [];
         foreach ( self::$columnasDB as $columna ) {
             if ( $columna === 'id' ) continue;
             $atributos[ $columna ] = $this->$columna;
         }
         return $atributos;
     }
 
     //Subida de archivos
 
     public function setImagen( $imagen ) {
         //elimina la imagen previa
         if (!is_null( $this->id )) {
             $this->borrarImagen();
         }
         //Asignar al atributo de imagen el nombre de la imagen
         if ( $imagen ) {
             $this->imagen = $imagen;
         }
     }
 
     //Elimna el archivo
     public function borrarImagen(  ) {
        //comprobar si existe el archivo
        $existeArchivo = file_exists( CARPETA_IMAGENES.$this->imagen );
        if ( $existeArchivo ) {
            unlink( CARPETA_IMAGENES.$this->imagen );
        }
     }
 
     //SANITIZAR DATOS
 
     public function sanitizarAtributos() {
         $atributos = $this->atributos();
         $sanitizado = [];
         foreach ( $atributos as $key=>$value ) {
             $sanitizado[ $key ] = self::$db->escape_string( $value );
         }
         return $sanitizado;
     }
 
     //Validacion
 
     public static function getErrores() {
         return self::$errores;
     }
 
     public function validar() {
 
         //Validaciones
         if ( !$this->titulo ) {
             self::$errores[] = 'Debes añadir un titulo';
         }
         if ( !$this->precio ) {
             self::$errores[] = 'El precio es obligatorio';
         }
         if ( strlen( $this->descripcion )<50 ) {
             self::$errores[]  = 'La descripción es obligatoria y debe tener al menos 50 caracteres';
         }
         if ( !$this->habitaciones ) {
             self::$errores[] = 'El número de habitación es obligatorio';
         }
         if ( !$this->wc ) {
             self::$errores[] = 'El número de baños es obligatorio';
         }
         if ( !$this->estacionamiento ) {
             self::$errores[] = 'El número de lugares de estacionamiento es obligatorio';
         }
         if ( !$this->vendedorId ) {
             self::$errores[] = 'Elige un vendedor';
         }
         if ( !$this->imagen ) {
             self::$errores[] = 'La imagen es obligatoria';
         }
         return self::$errores;
 
     }
 
     //Lista todos los registris
 
     public static function all() {
 
         $query = 'SELECT * FROM '. static::$tabla;
         $resultado = self::consultarSQL( $query );
         return $resultado;
 
     }
 
     //Busca un registro por su id
     public static function find ( $id ) {
 
         $query = "SELECT * FROM " . static::$tabla . " WHERE id = ${id}";
         $resultado = self::consultarSQL( $query );
         return array_shift( $resultado );
 
     }
     public static function consultarSQL( $query ) {
         //Consultar la base de datos
 
         $resultado = self::$db->query( $query );
 
         //Iterar los resultados
         $array = [];
         while( $registro = $resultado->fetch_assoc() ) {
             $array[] = self::crearObjecto( $registro );
 
         }
         //Liberar la memoria
         $resultado->free();
 
         //retornar resultado
         return $array;
     }
 
     protected static function crearObjecto ( $registro ) {
         $objeto = new static;
         foreach ( $registro as $key => $value ) {
             if ( property_exists( $objeto, $key ) ) {
                 $objeto->$key = $value;
             }
         }
         return $objeto;
     }
 
     //sincronizar el objecto en memoria con los cambios del usuario
 
     public function sincronizar ( $args = [] ) {
         foreach ( $args as $key =>$value ) {
             if ( property_exists( $this, $key ) && !is_null( $value ) ) {
                 $this->$key = $value;
             }
         }
     }
}