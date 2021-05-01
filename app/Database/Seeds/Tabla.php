<?php

namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;

use CodeIgniter\Test\Fabricator;


class Tabla extends Seeder
{
	public function run()
	{
		$model = model('Tabla');
		$fabricator = new Fabricator($model);
		// $fabricator = new Fabricator($model);
		$fabricator->create(100);
	}
}
