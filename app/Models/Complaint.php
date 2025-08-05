<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Complaint
 *
 * @property int $id
 * @property int $house_id
 * @property int $reported_by
 * @property string $title
 * @property string $description
 * @property string $category
 * @property string $priority
 * @property string $status
 * @property int|null $assigned_to
 * @property string|null $response
 * @property \Illuminate\Support\Carbon|null $target_resolution_date
 * @property \Illuminate\Support\Carbon|null $resolved_date
 * @property float|null $estimated_cost
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $assignedTo
 * @property-read \App\Models\House $house
 * @property-read \App\Models\User $reportedBy
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint query()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereEstimatedCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereHouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereReportedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereResolvedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereTargetResolutionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint open()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint resolved()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint highPriority()
 * @method static \Database\Factories\ComplaintFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Complaint extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'house_id',
        'reported_by',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'assigned_to',
        'response',
        'target_resolution_date',
        'resolved_date',
        'estimated_cost',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'target_resolution_date' => 'date',
        'resolved_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the house that the complaint belongs to.
     */
    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    /**
     * Get the user who reported the complaint.
     */
    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the user assigned to handle the complaint.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Scope a query to only include open complaints.
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    /**
     * Scope a query to only include resolved complaints.
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Scope a query to only include high priority complaints.
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    /**
     * Get formatted estimated cost.
     */
    public function getFormattedEstimatedCostAttribute(): string
    {
        return $this->estimated_cost ? 'Rp ' . number_format($this->estimated_cost, 0, ',', '.') : 'N/A';
    }

    /**
     * Get complaint status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'open' => 'red',
            'in_progress' => 'yellow',
            'resolved' => 'green',
            'closed' => 'blue',
            'cancelled' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Get complaint priority badge color.
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'gray'
        };
    }
}