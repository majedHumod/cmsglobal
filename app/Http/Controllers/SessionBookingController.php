<?php

namespace App\Http\Controllers;

use App\Models\SessionBooking;
use App\Models\TrainingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SessionBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of bookings
     */
    public function index()
    {
        $bookings = SessionBooking::with(['trainingSession', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.session-bookings.index', compact('bookings'));
    }

    /**
     * Show the form for editing the booking
     */
    public function edit(SessionBooking $sessionBooking)
    {
        return view('admin.session-bookings.edit', compact('sessionBooking'));
    }

    /**
     * Update the booking
     */
    public function update(Request $request, SessionBooking $sessionBooking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $sessionBooking->update($validated);

            return redirect()->route('admin.session-bookings.index')
                ->with('success', 'تم تحديث الحجز بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error updating booking: ' . $e->getMessage());
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث الحجز: ' . $e->getMessage());
        }
    }

    /**
     * Remove the booking
     */
    public function destroy(SessionBooking $sessionBooking)
    {
        try {
            $sessionBooking->delete();

            return redirect()->route('admin.session-bookings.index')
                ->with('success', 'تم حذف الحجز بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error deleting booking: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حذف الحجز: ' . $e->getMessage());
        }
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, SessionBooking $sessionBooking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        try {
            $sessionBooking->update($validated);

            $statusText = [
                'pending' => 'في الانتظار',
                'confirmed' => 'مؤكد',
                'completed' => 'مكتمل',
                'cancelled' => 'ملغي'
            ];

            return redirect()->route('admin.session-bookings.index')
                ->with('success', 'تم تحديث حالة الحجز إلى: ' . $statusText[$validated['status']]);
        } catch (\Exception $e) {
            Log::error('Error updating booking status: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحديث حالة الحجز: ' . $e->getMessage());
        }
    }
}