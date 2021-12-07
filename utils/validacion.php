<?php 

function validarRol($rol) {
    return $rol === 'secretaria' || $rol === 'bioanalista';
}