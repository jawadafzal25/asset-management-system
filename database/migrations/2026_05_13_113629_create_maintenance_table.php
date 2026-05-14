<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;

return new class extends Migration

{

public function up(): void

{

Schema::create(

'maintenances',

function (Blueprint $table) {

$table->id();

$table->foreignId('asset_id')

->constrained('assets')

->cascadeOnDelete();

$table->foreignId('reported_by')

->constrained('users')

->cascadeOnDelete();

$table->text(

'issue_description'

);

$table->enum(

'maintenance_status',

[

'pending',

'in_progress',

'completed',

'rejected'

]

)->default('pending');

$table->date(

'reported_date'

);

$table->timestamps();

$table->softDeletes();

}

);

}

public function down(): void

{

Schema::dropIfExists(

'maintenances'

);

}

};