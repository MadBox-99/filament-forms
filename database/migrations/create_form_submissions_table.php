<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $formsTable = config('filament-forms.table_names.forms', 'forms');

        Schema::create(config('filament-forms.table_names.form_submissions', 'form_submissions'), function (Blueprint $table) use ($formsTable) {
            $table->id();
            $table->foreignId('form_id')->constrained($formsTable)->cascadeOnDelete();
            $table->json('data');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('filament-forms.table_names.form_submissions', 'form_submissions'));
    }
};
