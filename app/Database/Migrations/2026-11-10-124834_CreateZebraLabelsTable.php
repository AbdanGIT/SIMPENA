<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateZebraLabelsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_desain' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'ukuran_kertas' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'zpl_code' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('zebra_labels');
    }

    public function down()
    {
        $this->forge->dropTable('zebra_labels');
    }
}