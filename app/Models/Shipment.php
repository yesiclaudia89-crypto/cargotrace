<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shipment_number',
        'user_id',
        'carrier_id',
        'origin_id',
        'destination_id',
        'cargo_type',
        'total_weight',
        'total_volume',
        'status',
        'payment_status',
        'freight_cost',
        'payment_reference',
        'expected_departure',
        'expected_arrival',
        'estimated_arrival',
        'delay_hours',
        'is_delayed',
        'delay_severity',
        'notes',
    ];

    protected $casts = [
        'total_weight' => 'float',
        'total_volume' => 'float',
        'freight_cost' => 'float',
        'expected_departure' => 'datetime',
        'expected_arrival' => 'datetime',
        'estimated_arrival' => 'datetime',
        'is_delayed' => 'boolean',
        'delay_hours' => 'integer',
    ];

    protected $appends = [
        'delay_status',
    ];

    /**
     * Deterministic Delay Calculation Engine (Slide 9)
     */
    public function getDelayHoursAttribute($value): int
    {
        if ($value !== null && (int) $value > 0) {
            return (int) $value;
        }

        if ($this->estimated_arrival && $this->expected_arrival) {
            $diff = $this->expected_arrival->diffInHours($this->estimated_arrival, false);
            return $diff > 0 ? (int) $diff : 0;
        }

        return 0;
    }

    public function getDelayStatusAttribute(): string
    {
        $hours = $this->delay_hours;

        if ($hours <= 0) {
            return 'ON_TIME';
        }
        if ($hours <= 4) {
            return 'MINOR_DELAY';
        }
        if ($hours <= 12) {
            return 'MEDIUM_DELAY';
        }

        return 'CRITICAL_DELAY';
    }

    /**
     * State machine valid transitions (Slide 6)
     */
    public const VALID_TRANSITIONS = [
        'CREATED'    => ['BOOKED', 'CANCELLED'],
        'BOOKED'     => ['PICKED_UP', 'CANCELLED'],
        'PICKED_UP'  => ['IN_TRANSIT', 'CANCELLED'],
        'IN_TRANSIT' => ['DELIVERED'],
        'DELIVERED'  => [], // terminal state
        'CANCELLED'  => [], // terminal state
    ];

    public function canTransitionTo(string $newStatus): bool
    {
        $allowed = self::VALID_TRANSITIONS[$this->status] ?? [];
        return in_array($newStatus, $allowed, true);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class);
    }

    public function origin(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'origin_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'destination_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }

    public function trackingEvents(): HasMany
    {
        return $this->hasMany(TrackingEvent::class)->orderBy('event_time', 'asc');
    }

    public function latestTrackingEvent(): HasOne
    {
        return $this->hasOne(TrackingEvent::class)->latestOfMany('event_time');
    }

    public function exceptions(): HasMany
    {
        return $this->hasMany(ShipmentException::class)->orderBy('logged_at', 'desc');
    }
}
