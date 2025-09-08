<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600 text-right">
            قبل المتابعة، يرجى التحقق من عنوان بريدك الإلكتروني بالنقر على الرابط الذي أرسلناه لك. إذا لم تستلم البريد الإلكتروني، سنرسل لك آخر بكل سرور.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600 text-right">
                تم إرسال رابط تحقق جديد إلى عنوان البريد الإلكتروني الذي قدمته في إعدادات ملفك الشخصي.
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-button type="submit">
                        إعادة إرسال بريد التحقق
                    </x-button>
                </div>
            </form>

            <div>
                <a
                    href="{{ route('profile.show') }}"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-right"
                >
                    تعديل الملف الشخصي</a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ms-2">
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
