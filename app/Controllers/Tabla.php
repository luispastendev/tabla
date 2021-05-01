<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Libraries\TableLibrary;

class Tabla extends BaseController
{
	
	use ResponseTrait; 

	protected $TableLibrary;

	public function __construct(){
		$this->TableLibrary = new TableLibrary;
	}

	public function index()
	{
			
		return view('tabla', ['columns' => $this->TableLibrary->getDataAllColumns()]);
	}

	public function getData(){

		// echo '<pre>';
		// var_dump($this->request->getVar('columns'));
		// echo '</pre>';
		// exit();
		
		$page  = $this->request->getVar('draw');
		$len   = $this->request->getVar('length');
		$start = $this->request->getVar('start');


		$model = model('Tabla');
		$data = $model->paginate(10, 'gp1', ($start / $len) + 1 );
		$total = $model->pager->getTotal('gp1');

		foreach($data as $k => $v){
			$data[$k] = array_values($v);
		}

		return $this->respond([
			"sEcho" => $page,
			"iTotalRecords" => $total,
			"iTotalDisplayRecords" => $total,
			"aaData" => $data
		]);

	}
}
