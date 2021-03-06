<?php namespace App\Libraries;

class TableLibrary{

    protected $model; 
    protected $configs;  // ver app/config/TableConfig.php

    /**
     * Instanciar modelo y archivos de configuración
     */
    public function __construct(){
        $this->model   = model('Tabla');
        $this->configs = config('TableConfig');
    }

    /**
     * Obtiene los datos de todas las columnas para renderizar
     * los filtros de busqueda por columna.
     * 
     * @return array
     */
    public function getDataAllColumns() : array{

        $columns = [];
        foreach ($this->configs->fields as $k => $value) {
            $columns[$k] = array_column($this->model->getValuesColumn($value),$value);
        }
        return $columns;
    }

    /**
     * Regresa los posibles filtros de la tabla segun los
     * filtros aplicados a la columna
     *
     * @param array $filters
     * @return array
     */
    public function getColumnsByMatches(array $filters) : array{
        
        // Reutiliza getTable para obtener los datos de la tabla con los filtros aplicados
        $data = $this->getTable($filters, false);
        
        $columns = [];
        foreach ($this->configs->fields as $k => $v) {
            $columns[$k] = array_unique(array_column($data, $v));
        }
        return $columns;
    }

    /**
     * Método principal para obtener los registros de las tabla
     * paginados
     *
     * @param array $filters
     * @return array
     */
    public function getTable(array $filters, $paginate = true) : array{

        [
            'page' => $page, 
            'len' => $len,
            'start' => $start,
            'column_search' => $cSearch,
            'sort' => $sort,
            'search' => $search,
        ] = $filters;

        // Aplicando filtros de distintos controles (columna, busqueda general, ordenamiento)
        $this->makeSearch($search);
        $this->applyFiltersColumn($cSearch);
        $this->sortColumn($sort);
        
        
        if(!$paginate){
            // Búsqueda normal para exportacion a excel
            return $this->model->select(implode(', ',$this->configs->fields))->findAll();

        }

        $data = $this->model
            ->select(implode(', ',$this->configs->fields))
            ->paginate($len, 'gp1', ($start / $len) + 1); // registros de la pagina    
        // $total = $this->model->pager->getTotal('gp1'); // total de registros since 4.1.1
        $total = $this->model->pager->getDetails('gp1')['total']; // support 4.0.4

        return [
			"sEcho" => $page,
			"iTotalRecords" => $total,
			"iTotalDisplayRecords" => $total,
			"aaData" => $this->getFormatedRows($data)
		];

    }

    /**
     * Método para ordenar la columna de mayor a menor (ASC/DESC) por 
     * columna de la tabla
     *
     * @param array $sort
     * @return void
     */
    protected function sortColumn(array $sort) : void{

        $column = (int) $sort[0]['column'] ?? 0;
        $dir    =  $sort[0]['dir'] ?? 'desc';
        $this->model->orderBy(
            $this->configs->fields[$column], 
            $dir
        );
    }

    /**
     * Aplica los filtros de búsqueda según los vaya seleccionando
     * el usuario
     *
     * @param array $cSearch
     * @return void
     */
    protected function applyFiltersColumn(array $search) : void{

        $filters = [];
        // obteniendo filtros requeridos por el usuario 
        foreach ($search as $key => $column) {
            if(!empty($column['search']['value'])){
                $filters[$this->configs->fields[$key]] = $column['search']['value'];
            }
        }
        // aplicando filtros
        if(!empty($filters)){
            foreach ($filters as $column => $filter) {
                $this->model->where($column, $filter);
                // $this->model->like($column, $filter,'both', null, true);
            }
        }
    }

    /**
     * Realiza la búsqueda general de registros por todas las columnas
     *
     * @param string $match
     * @return void
     */
    protected function makeSearch(string $match) : void{

        if($match){
            foreach ($this->configs->fields as $k => $field) {
                $this->model->orLike($field, $match, 'both', null, true);
            }
        }

    }

    /**
     * Regresa el formato de datos adecuado para el cuerpo de la 
     * respuesta aaData en datatables
     *
     * @param array $data
     * @return array
     */
    protected function getFormatedRows(array $data) : array{

        foreach($data as $k => $v){
			$data[$k] = array_values($v);
		}
        return $data;
    }


}