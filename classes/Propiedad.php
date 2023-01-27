<?php

namespace App;

class Propiedad {

    //base de datos
    protected static $db;
    protected static $columnasDB = [ 'id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId' ];

    //Errores
    protected static $errores = [];

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

    //definir la conexion ala bd
    public static function setDB( $database ) {
        self::$db = $database;
    }

    public function __construct( $args = [] ) {
        $this->id = $args[ 'id' ] ?? '';
        $this->titulo = $args[ 'titulo' ] ?? '';
        $this->precio = $args[ 'precio' ] ?? '';
        $this->imagen = $args[ 'imagen' ] ?? '';
        $this->descripcion = $args[ 'descripcion' ] ?? '';
        $this->habitaciones = $args[ 'habitaciones' ] ?? '';
        $this->wc = $args[ 'wc' ] ?? '';
        $this->estacionamiento = $args[ 'estacionamiento' ] ?? '';
        $this->creado = date( 'Y/m/d' );
        $this->vendedorId = $args[ 'vendedorId' ] ?? 1;
    }

    public function guardar(){
        if (isset( $this->id )) {
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
        $query = 'INSERT INTO propiedades (';
        $query .= join( ', ', array_keys( $atributos ) );
        $query .= " ) VALUES (' ";
        $query .= join( "', '", array_values( $atributos ) );
        $query .= " ') ";

        $resultado = self::$db->query( $query );

        return $resultado;
    }

    public function actualizar(){
        //sanitisar los datos
        $atributos = $this->sanitizarAtributos();
        // $query = "  ";
        $valores=[];
        foreach($atributos as $key => $value){
            $valores[]="{$key}='$value'";
        }
        $query ="UPDATE propiedades SET ";
        $query .= join(', ',$valores);
        $query .=" WHERE id= '" . self::$db->escape_string($this->id). "' ";
        $query .=" LIMIT 1";

        $resultado = self::$db->query( $query );
        if ( $resultado ) {
            //redireccionar al usuario
            header( 'Location:/admin?resultado=2' );
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
        if (isset( $this->id )) {
            //comprobar si existe el archivo
            $existeArchivo = file_exists( CARPETA_IMAGENES.$this->imagen );
            if ( $existeArchivo ) {
                unlink( CARPETA_IMAGENES.$this->imagen );
            }
        }
        //Asignar al atributo de imagen el nombre de la imagen
        if ( $imagen ) {
            $this->imagen = $imagen;
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

    //Lista todas las propiedades

    public static function all() {

        $query = 'SELECT * FROM propiedades';
        $resultado = self::consultarSQL( $query );
        return $resultado;

    }

    //Busca una propiedad por su id
    public static function find ( $id ) {

        $query = "SELECT * FROM propiedades WHERE id = ${id}";
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
        $objeto = new self;
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