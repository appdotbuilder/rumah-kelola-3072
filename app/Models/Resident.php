<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Resident
 *
 * @property int $id
 * @property int $house_id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $email
 * @property string $phone
 * @property string|null $id_number
 * @property string $relationship
 * @property \Illuminate\Support\Carbon|null $move_in_date
 * @property \Illuminate\Support\Carbon|null $move_out_date
 * @property bool $is_active
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\House $house
 * @property-read \App\Models\User|null $user
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Resident newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resident newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resident query()
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereHouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereMoveInDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereMoveOutDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereRelationship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident active()
 * @method static \Database\Factories\ResidentFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Resident extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'house_id',
        'user_id',
        'name',
        'email',
        'phone',
        'id_number',
        'relationship',
        'move_in_date',
        'move_out_date',
        'is_active',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'move_in_date' => 'date',
        'move_out_date' => 'date',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the house that the resident lives in.
     */
    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    /**
     * Get the user associated with the resident.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active residents.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get relationship badge color.
     */
    public function getRelationshipColorAttribute(): string
    {
        return match($this->relationship) {
            'owner' => 'blue',
            'tenant' => 'green',
            'family_member' => 'purple',
            default => 'gray'
        };
    }
}