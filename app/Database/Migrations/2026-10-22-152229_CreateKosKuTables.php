<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKosKuTables extends Migration
{
    public function up()
    {
        // 1. Table users
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'admin',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true);

        // 2. Table wilayah
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'tipe' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Kecamatan',
            ],
            'geojson' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'center_lat' => [
                'type' => 'DOUBLE PRECISION',
            ],
            'center_lng' => [
                'type' => 'DOUBLE PRECISION',
            ],
            'zoom_default' => [
                'type'    => 'INT',
                'default' => 14,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('wilayah', true);

        // 3. Table fasilitas
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ikon' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'bi-check-circle',
            ],
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'kamar',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('fasilitas', true);

        // 4. Table kos
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'unique'     => true,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'alamat' => [
                'type' => 'TEXT',
            ],
            'wilayah_id' => [
                'type' => 'INT',
            ],
            'latitude' => [
                'type' => 'DOUBLE PRECISION',
            ],
            'longitude' => [
                'type' => 'DOUBLE PRECISION',
            ],
            'harga_min' => [
                'type'       => 'NUMERIC',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'harga_max' => [
                'type'       => 'NUMERIC',
                'constraint' => '12,2',
                'null'       => true,
            ],
            'tipe' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'campur', // putra, putri, campur
            ],
            'jumlah_kamar' => [
                'type'    => 'INT',
                'default' => 1,
            ],
            'kamar_tersedia' => [
                'type'    => 'INT',
                'default' => 1,
            ],
            'status_ketersediaan' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'tersedia', // tersedia, penuh
            ],
            'status_publikasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'aktif', // aktif, draft, nonaktif
            ],
            'kontak_nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'kontak_wa' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'kontak_telepon' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'peraturan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_unggulan' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('wilayah_id');
        $this->forge->addKey('status_publikasi');
        $this->forge->addKey('harga_min');
        $this->forge->addForeignKey('wilayah_id', 'wilayah', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('kos', true);

        // 5. Table kos_fasilitas
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'kos_id' => [
                'type' => 'INT',
            ],
            'fasilitas_id' => [
                'type' => 'INT',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kos_id', 'kos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('fasilitas_id', 'fasilitas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kos_fasilitas', true);

        // 6. Table kos_foto
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'kos_id' => [
                'type' => 'INT',
            ],
            'path_file' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'is_utama' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'urutan' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kos_id', 'kos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kos_foto', true);

        // 7. Table kos_kunjungan
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'kos_id' => [
                'type' => 'INT',
            ],
            'jenis' => [
                'type'       => 'VARCHAR',
                'constraint' => 30, // view, klik_wa, klik_maps
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'user_agent' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kos_id', 'kos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kos_kunjungan', true);
    }

    public function down()
    {
        $this->forge->dropTable('kos_kunjungan', true);
        $this->forge->dropTable('kos_foto', true);
        $this->forge->dropTable('kos_fasilitas', true);
        $this->forge->dropTable('kos', true);
        $this->forge->dropTable('fasilitas', true);
        $this->forge->dropTable('wilayah', true);
        $this->forge->dropTable('users', true);
    }
}
