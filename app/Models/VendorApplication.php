<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorApplication extends Model
{
    protected $table = 'vendor_applications';

    protected $fillable = [
        'applicant_first_name',
        'applicant_last_name',
        'applicant_email',
        'applicant_phone_number',
        'proposed_vendor_name',
        'stall_number_preference',
        'market_section_preference',
        'business_description',
        'primary_product_categories_description',
        'business_permit_document_url',
        'dti_registration_url',
        'bir_registration_url',
        'other_documents',
        'agreed_to_terms',
        'status',
        'admin_notes',
        'reviewed_by_user_id',
    ];

    protected $casts = [
        'other_documents' => 'array',
        'agreed_to_terms' => 'boolean',
        'status' => 'string',
    ];

    // Relationships
    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }
}
