<?php
    class user{
        public $id;
        public $nombre;
        public $apellido;
        public $fecha_nacimiento;
        public $fecha_alta;
        public $fecha_ultimoPago;
        public $fecha_sigPago;
        public $sexo;
        public $email;
        public $telefono;
        public $estatus;
        public $idRol;
        public $picRoute;
        public $barcode;
        public $usrName;
        public $pswd;
        public $poc_name;
        public $poc_phone;
        public $LASTLOGIN;
        public $rfid;
        public $balance;

        public function set($data) {
            foreach ($data AS $key => $value) $this->{$key} = $value;
        }
    }
?>