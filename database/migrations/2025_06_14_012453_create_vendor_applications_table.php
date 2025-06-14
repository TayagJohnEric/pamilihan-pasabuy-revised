<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_applications', function (Blueprint $table) {
             $table->id();
            $table->string('applicant_first_name');
            $table->string('applicant_last_name');
            $table->string('applicant_email');
            $table->string('applicant_phone_number');
            $table->string('proposed_vendor_name');
            $table->string('stall_number_preference')->nullable();
            $table->string('market_section_preference')->nullable();
            $table->text('business_description');
            $table->text('primary_product_categories_description');
            $table->string('business_permit_document_url')->nullable();
            $table->string('dti_registration_url')->nullable();
            $table->string('bir_registration_url')->nullable();
            $table->json('other_documents')->nullable();
            $table->boolean('agreed_to_terms')->default(false);
            $table->string('status')->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('reviewed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_applications');
    }
};
