<?php namespace App\Libraries;

class TableLibrary{

    protected $model;
    protected $configs; 

    public function __construct(){
        $this->model = model('Tabla');
        $this->configs = config('TableConfig');
    }

    public function getDataAllColumns() : array{
        $columns = [];
        foreach ($this->configs->fields as $k => $value) {
            $columns[$k] = array_column($this->model->getValuesColumn($value),$value);
        }
        return $columns;
        
    }


}