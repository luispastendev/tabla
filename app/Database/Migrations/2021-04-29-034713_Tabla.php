<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tabla extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id'          => [
					'type'           => 'INT',
					'constraint'     => 12,
					'unsigned'       => true,
					'auto_increment' => true,
			],
			'name'       => [
					'type'       => 'VARCHAR',
					'constraint' => '100',
			],
			'country'       => [
					'type'       => 'VARCHAR',
					'constraint' => '100',
			],
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('programacion');
	}

	public function down()
	{
		$this->forge->dropTable('programacion');
	}
}
