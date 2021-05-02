<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class TableConfig extends BaseConfig
{
    public $fields  = [
        'id',
        'num_fila',
        'campus',
        'modalidad',
        'carrera_responsable',
        'numero_clase',
        'seccion',
        'id_curso',
        'curso',
        'cod_docente',
        'docente',
        'cpte',
        'dia',
        'hora_inicio',
        'hora_final',
        'total_inscritos',
        'thoras',
    ];
}