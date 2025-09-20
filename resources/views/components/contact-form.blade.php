<div class="bg-base-200 py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">تواصل معنا</h2>
        <p class="text-lg mb-8">
            هل لديك أي استفسارات؟ اتصل بنا وسنتواصل معك في أقرب وقت ممكن
        </p>
        <div class="max-w-md mx-auto">
            @if(session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                @csrf
                <div>
                    <input 
                        type="text" 
                        name="name"
                        class="input input-bordered w-full @error('name') input-error @enderror" 
                        placeholder="الأسم" 
                        value="{{ old('name') }}"
                        required
                    />
                    @error('name')
                        <div class="text-error text-sm mt-1 text-right">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <input 
                        type="email" 
                        name="email"
                        class="input input-bordered w-full @error('email') input-error @enderror" 
                        placeholder="بريدك الإلكتروني" 
                        value="{{ old('email') }}"
                        required
                    />
                    @error('email')
                        <div class="text-error text-sm mt-1 text-right">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <input 
                        type="tel" 
                        name="phone"
                        class="input input-bordered w-full @error('phone') input-error @enderror" 
                        placeholder="رقم الهاتف (اختياري)" 
                        value="{{ old('phone') }}"
                    />
                    @error('phone')
                        <div class="text-error text-sm mt-1 text-right">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <textarea 
                        name="message"
                        class="textarea textarea-bordered w-full resize-none @error('message') textarea-error @enderror" 
                        placeholder="رسالتك" 
                        rows="4"
                        required
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <div class="text-error text-sm mt-1 text-right">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <button type="submit" class="btn btn-primary w-full">إرسال الرسالة</button>
                </div>
            </form>
        </div>
    </div>
</div>