<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanScore extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plan_scores';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'weekly_plan_id',
        'base_score',
        'multiplier',
        'final_score',
        'calculated_at',
    ];

    protected $casts = [
        'multiplier' => 'decimal:2',
        'final_score' => 'decimal:2',
        'calculated_at' => 'datetime',
    ];

    /**
     * Get the weekly plan that owns the score.
     */
    public function weeklyPlan(): BelongsTo
    {
        return $this->belongsTo(WeeklyPlan::class);
    }
}
