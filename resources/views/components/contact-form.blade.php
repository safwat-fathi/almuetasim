<div class="bg-gradient-to-r from-[#f1f5f9] to-[#e2e8f0] py-8">
    <div class="container mx-auto px-4 text-center">
        <div class="flex items-center justify-center gap-3 mb-4">
            <i data-lucide="message-circle" class="w-8 h-8 text-primary"></i>
            <h2 class="text-2xl font-bold text-primary">تواصل معنا</h2>
           
        </div>
        <p class="text-lg mb-6 text-base-content">
            هل لديك أي استفسارات؟ اترك رسالة وسنرد عليك في أقرب وقت ممكن
        </p>
        <div class="max-w-md mx-auto">
            @if(session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('contact.store') }}" class="space-y-2">
                @csrf
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <i data-lucide="user" class="w-4 h-4 text-primary"></i>
                        <label class="text-sm font-medium">الاسم</label>
                    </div>
                    <input
                        type="text"
                        name="name"
                        class="input input-bordered input-md w-full @error('name') input-error @enderror"
                        placeholder="أدخل اسمك الكامل"
                        value="{{ old('name') }}"
                        required
                    />
                    @error('name')
                        <div class="text-error text-sm mt-1 text-right">{{ $message }}</div>
                    @enderror
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-4 h-4 text-primary"></i>
                        <label class="text-sm font-medium">البريد الإلكتروني</label>
                    </div>
                    <input
                        type="email"
                        name="email"
                        class="input input-bordered input-md w-full @error('email') input-error @enderror"
                        placeholder="example@email.com"
                        value="{{ old('email') }}"
                        required
                    />
                    @error('email')
                        <div class="text-error text-sm mt-1 text-right">{{ $message }}</div>
                    @enderror
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-4 h-4 text-primary"></i>
                        <label class="text-sm font-medium">رقم الهاتف</label>
                    </div>
                    <input
                        type="tel"
                        name="phone"
                        class="input input-bordered input-md w-full @error('phone') input-error @enderror"
                        placeholder="+20 123 456 7890"
                        value="{{ old('phone') }}"
                    />
                    @error('phone')
                        <div class="text-error text-sm mt-1 text-right">{{ $message }}</div>
                    @enderror
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <i data-lucide="message-square" class="w-4 h-4 text-primary"></i>
                        <label class="text-sm font-medium">الرسالة</label>
                    </div>
                    <textarea
                        name="message"
                        class="textarea textarea-bordered textarea-md w-full resize-none @error('message') textarea-error @enderror"
                        placeholder="اكتب رسالتك هنا..."
                        rows="4"
                        required
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <div class="text-error text-sm mt-1 text-right">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <button type="submit" class="btn btn-lg w-full text-white bg-[#2d3b61] hover:bg-[#1e293b] flex items-center justify-center gap-2 transition-colors duration-200">
                        <i data-lucide="send" class="w-5 h-5"></i>
                        إرسال الرسالة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>