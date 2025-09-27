

@section('title', 'تسجيل دخول المسؤول')

<x-layouts.auth>
    <div class="min-h-screen flex items-center justify-center bg-base-200 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-base-100 p-8 rounded-lg shadow-lg">
            <div dir="rtl">
                <h2 class="mt-6 text-center text-3xl font-extrabold text-base-content">
                    تسجيل دخول المسؤول
                </h2>
                <p class="mt-2 text-center text-sm text-base-content/70">
                    سجّل الدخول للوصول إلى لوحة التحكم الخاصة بك
                </p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-base-content">البريد الألكتروني</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="input input-bordered w-full" value="{{ old('email') }}"
                                placeholder="Enter your email">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-base-content">كلمة المرور</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                class="input input-bordered w-full" placeholder="Enter your password">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <input id="remember" name="remember" type="checkbox" class="checkbox checkbox-primary"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="block text-sm text-base-content">
														تذكرني
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary w-full">
												تسجيل دخول
                    </button>
                </div>
            </form>
        </div>
    </div>
	</x-layouts.auth>
