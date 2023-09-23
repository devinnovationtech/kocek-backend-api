<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerifiedDocument extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    protected $enum = [
        'document_type' => ['national_id', 'passport'],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_has_verified_documents', 'verified_document_id', 'user_id')
            ->withPivot('verified_at', 'rejected_at', 'rejected_reason')
            ->withTimestamps();
    }
}
