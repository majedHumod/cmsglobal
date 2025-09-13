<?php

namespace App\Http\Controllers;

use App\Models\TrainingSession;
use App\Models\SessionBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class TrainingSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin'])->except(['show', 'book', 'processPayment']);
        $this->middleware('auth')->only(['book', 'processPayment']);
    }

    /**
     * Display a listing of training sessions for admin
     */
    public function index()
    {
        $sessions = TrainingSession::with('user')->ordered()->get();
        return view('admin.training-sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new training session
     */
    public function create()
    {
        return view('admin.training-sessions.create');
    }

    /**
     * Store a newly created training session
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1|max:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('training-sessions', 'public');
                $validated['image'] = $imagePath;
            }

            // Set default values
            $validated['user_id'] = auth()->id();
            $validated['is_visible'] = $request->has('is_visible') ? true : false;
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            // Create training session
            TrainingSession::create($validated);

            // Clear cache
            TrainingSession::clearCache();

            return redirect()->route('admin.training-sessions.index')
                ->with('success', 'تم إنشاء جلسة التدريب بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error creating training session: ' . $e->getMessage());
            return back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء جلسة التدريب: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified training session for booking
     */
    public function show(TrainingSession $trainingSession)
    {
        if (!$trainingSession->is_visible) {
            abort(404);
        }

        return view('training-sessions.show', compact('trainingSession'));
    }

    /**
     * Show the form for editing the training session
     */
    public function edit(TrainingSession $trainingSession)
    {
        return view('admin.training-sessions.edit', compact('trainingSession'));
    }

    /**
     * Update the training session
     */
    public function update(Request $request, TrainingSession $trainingSession)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1|max:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($trainingSession->image) {
                    Storage::disk('public')->delete($trainingSession->image);
                }
                $imagePath = $request->file('image')->store('training-sessions', 'public');
                $validated['image'] = $imagePath;
            }

            // Set boolean values
            $validated['is_visible'] = $request->has('is_visible') ? true : false;
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            // Update training session
            $trainingSession->update($validated);

            // Clear cache
            TrainingSession::clearCache();

            return redirect()->route('admin.training-sessions.index')
                ->with('success', 'تم تحديث جلسة التدريب بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error updating training session: ' . $e->getMessage());
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث جلسة التدريب: ' . $e->getMessage());
        }
    }

    /**
     * Remove the training session
     */
    public function destroy(TrainingSession $trainingSession)
    {
        try {
            // Check if there are any bookings
            if ($trainingSession->bookings()->count() > 0) {
                return back()->with('error', 'لا يمكن حذف جلسة التدريب لوجود حجوزات مرتبطة بها.');
            }

            // Delete image if exists
            if ($trainingSession->image) {
                Storage::disk('public')->delete($trainingSession->image);
            }

            $trainingSession->delete();

            // Clear cache
            TrainingSession::clearCache();

            return redirect()->route('admin.training-sessions.index')
                ->with('success', 'تم حذف جلسة التدريب بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error deleting training session: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حذف جلسة التدريب: ' . $e->getMessage());
        }
    }

    /**
     * Toggle session visibility
     */
    public function toggleVisibility(TrainingSession $trainingSession)
    {
        try {
            $trainingSession->update(['is_visible' => !$trainingSession->is_visible]);

            // Clear cache
            TrainingSession::clearCache();

            $status = $trainingSession->is_visible ? 'إظهار' : 'إخفاء';
            return redirect()->route('admin.training-sessions.index')
                ->with('success', "تم {$status} جلسة التدريب بنجاح.");
        } catch (\Exception $e) {
            Log::error('Error toggling training session visibility: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تغيير حالة جلسة التدريب: ' . $e->getMessage());
        }
    }

    /**
     * Book a training session
     */
    public function book(Request $request, TrainingSession $trainingSession)
    {
        $validated = $request->validate([
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            // Check if slot is available
            if (!$trainingSession->isAvailableAt($validated['booking_date'], $validated['booking_time'])) {
                return back()->with('error', 'هذا الموعد محجوز بالفعل. يرجى اختيار موعد آخر.');
            }

            // Create booking
            $booking = SessionBooking::create([
                'training_session_id' => $trainingSession->id,
                'user_id' => auth()->id(),
                'booking_date' => $validated['booking_date'],
                'booking_time' => $validated['booking_time'],
                'payment_amount' => $trainingSession->price,
                'notes' => $validated['notes'],
                'status' => 'pending',
                'payment_status' => $trainingSession->price > 0 ? 'pending' : 'paid'
            ]);

            // If free session, confirm immediately
            if ($trainingSession->price == 0) {
                $booking->update(['status' => 'confirmed']);
                
                // Send confirmation email
                $this->sendBookingConfirmationEmail($booking);
                
                return redirect()->route('training-sessions.booking-success', $booking)
                    ->with('success', 'تم تأكيد حجز الجلسة المجانية بنجاح.');
            }

            // Redirect to payment
            return redirect()->route('training-sessions.payment', $booking);

        } catch (\Exception $e) {
            Log::error('Error booking training session: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حجز الجلسة: ' . $e->getMessage());
        }
    }

    /**
     * Process payment for booking
     */
    public function processPayment(Request $request, SessionBooking $booking)
    {
        try {
            // Initialize Stripe
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            // Create payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $booking->payment_amount * 100, // Convert to cents
                'currency' => 'sar',
                'metadata' => [
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'session_id' => $booking->training_session_id
                ]
            ]);

            // Update booking with payment intent ID
            $booking->update(['stripe_payment_intent_id' => $paymentIntent->id]);

            return view('training-sessions.payment', compact('booking', 'paymentIntent'));

        } catch (\Exception $e) {
            Log::error('Error processing payment: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء معالجة الدفع: ' . $e->getMessage());
        }
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess(SessionBooking $booking)
    {
        try {
            // Update booking status
            $booking->update([
                'status' => 'confirmed',
                'payment_status' => 'paid'
            ]);

            // Send confirmation email
            $this->sendBookingConfirmationEmail($booking);

            return view('training-sessions.booking-success', compact('booking'));

        } catch (\Exception $e) {
            Log::error('Error handling payment success: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تأكيد الحجز.');
        }
    }

    /**
     * Send booking confirmation email
     */
    private function sendBookingConfirmationEmail(SessionBooking $booking)
    {
        try {
            // Here you would implement email sending
            // For now, we'll just log it
            Log::info('Booking confirmation email sent', [
                'booking_id' => $booking->id,
                'user_email' => $booking->user->email,
                'session_title' => $booking->trainingSession->title
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending confirmation email: ' . $e->getMessage());
        }
    }
}