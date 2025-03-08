<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmsService;

class SmsController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function sendTestSms()
    {
        $to = '+13392176736'; // ضع رقم المستلم هنا
        $message = 'مرحبا! هذه رسالة اختبار من Laravel باستخدام Twilio.';

        try {
            $this->smsService->sendSms($to, $message);
            return response()->json(['message' => 'تم إرسال الرسالة بنجاح!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
