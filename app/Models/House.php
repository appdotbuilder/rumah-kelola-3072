<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\House
 *
 * @property int $id
 * @property string $block_number
 * @property string $address
 * @property string $house_type
 * @property float $land_area
 * @property float $building_area
 * @property string $status
 * @property string|null $owner_name
 * @property string|null $owner_phone
 * @property \Illuminate\Support\Carbon|null $handover_date
 * @property float|null $selling_price
 * @property int $bedrooms
 * @property int $bathrooms
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Complaint> $complaints
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resident> $residents
 * @property-read \App\Models\Resident|null $activeResident
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|House newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|House newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|House query()
 * @method static \Illuminate\Database\Eloquent\Builder|House whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereBathrooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereBedrooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereBlockNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereBuildingArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereHandoverDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereHouseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereLandArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereOwnerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereOwnerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House available()
 * @method static \Illuminate\Database\Eloquent\Builder|House sold()
 * @method static \Database\Factories\HouseFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class House extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'block_number',
        'address',
        'house_type',
        'land_area',
        'building_area',
        'status',
        'owner_name',
        'owner_phone',
        'handover_date',
        'selling_price',
        'bedrooms',
        'bathrooms',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'land_area' => 'decimal:2',
        'building_area' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'handover_date' => 'date',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the residents for the house.
     */
    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class);
    }

    /**
     * Get the active resident for the house.
     */
    public function activeResident()
    {
        return $this->hasOne(Resident::class)->where('is_active', true);
    }

    /**
     * Get the payments for the house.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the complaints for the house.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Scope a query to only include available houses.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to only include sold houses.
     */
    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }

    /**
     * Get formatted selling price.
     */
    public function getFormattedSellingPriceAttribute(): string
    {
        return $this->selling_price ? 'Rp ' . number_format($this->selling_price, 0, ',', '.') : 'N/A';
    }

    /**
     * Get house status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'available' => 'green',
            'sold' => 'blue',
            'reserved' => 'yellow',
            'maintenance' => 'red',
            default => 'gray'
        };
    }
}