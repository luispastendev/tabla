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
					'constraint'     => 50,
					'unsigned'       => true,
					'auto_increment' => true,
			],
			'num_fila'       => [
					'type'       => 'INT',
					'constraint' => '100',
					'null' => false
			],
			'campus'       => [
					'type'       => 'VARCHAR',
					'constraint' => '100',
					'default' => null
			],
			'modalidad'       => [
					'type'       => 'VARCHAR',
					'constraint' => '20',
					'default' => null
			],
			'carrera_responsable'       => [
					'type'       => 'VARCHAR',
					'constraint' => '150',
					'default' => null
			],
			'numero_clase'       => [
					'type'       => 'INT',
					'constraint' => '20',
					'default' => null
			],
			'seccion'       => [
					'type'       => 'VARCHAR',
					'constraint' => '20',
					'default' => null
			],
			'id_curso'       => [
					'type'       => 'INT',
					'constraint' => '30',
					'default' => null
			],
			'curso'       => [
					'type'       => 'VARCHAR',
					'constraint' => '300',
					'default' => null
			],
			'cod_docente'       => [
					'type'       => 'INT',
					'constraint' => '30',
					'default' => null
			],
			'docente'       => [
					'type'       => 'VARCHAR',
					'constraint' => '200',
					'default' => null
			],
			'cpte'       => [
					'type'       => 'VARCHAR',
					'constraint' => '20',
					'default' => null
			],
			'dia'       => [
					'type'       => 'VARCHAR',
					'constraint' => '30',
					'default' => null
			],
			'hora_inicio'       => [
					'type'       => 'TIME',
					'default' => null
			],
			'hora_final'       => [
					'type'       => 'TIME',
					'default' => null
			],
			'total_inscritos'       => [
					'type'       => 'INT',
					'constraint' => 30,
					'default' => null
			],
			'thoras'       => [
					'type'       => 'TIME',
					'default' => null
			],
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('tbl_programacion');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_programacion');
	}
}
