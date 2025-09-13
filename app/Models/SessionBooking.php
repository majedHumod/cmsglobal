<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SessionBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_session_id',
        'user_id',
        'booking_date',
        'booking_time',
        'status',
        'payment_status',
        'payment_amount',
        'payment_reference',
        'stripe_payment_intent_id',
        'notes'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'datetime:H:i',
        'payment_amount' => 'decimal:2',
    ];

    /**
     * Get the training session
     */
    public function trainingSession()
    {
        return $this->belongsTo(TrainingSession::class);
    }

    /**
     * Get the user who made the booking
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for paid bookings
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope for upcoming bookings
     */
    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>=', now()->toDateString());
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">في الانتظار</span>',
            'confirmed' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">مؤكد</span>',
            'completed' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">مكتمل</span>',
            'cancelled' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">ملغي</span>',
        ];

        return $badges[$this->status] ?? $badges['pending'];
    }

    /**
     * Get payment status badge
     */
    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">في الانتظار</span>',
            'paid' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">مدفوع</span>',
            'failed' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">فشل</span>',
            'refunded' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">مسترد</span>',
        ];

        return $badges[$this->payment_status] ?? $badges['pending'];
    }

    /**
     * Get formatted booking datetime
     */
    public function getFormattedBookingDatetimeAttribute()
    {
        return $this->booking_date->format('d/m/Y') . ' في ' . $this->booking_time->format('H:i');
    }

    /**
     * Check if booking is upcoming
     */
    public function getIsUpcomingAttribute()
    {
        $bookingDateTime = Carbon::parse($this->booking_date->format('Y-m-d') . ' ' . $this->booking_time->format('H:i:s'));
        return $bookingDateTime->isFuture();
    }

    /**
     * Check if booking can be cancelled
     */
    public function canBeCancelled()
    {
        return $this->status === 'pending' || $this->status === 'confirmed';
    }
}