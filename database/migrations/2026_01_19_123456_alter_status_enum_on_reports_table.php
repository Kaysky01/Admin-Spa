<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            ALTER TABLE reports 
            MODIFY status ENUM(
                'Diproses',
                'Ditindaklanjuti',
                'Selesai',
                'Ditolak'
            ) NOT NULL DEFAULT 'Diproses'
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE reports 
            MODIFY status ENUM(
                'Diproses',
                'Ditindaklanjuti',
                'Selesai'
            ) NOT NULL DEFAULT 'Diproses'
        ");
    }
};
