<div class="bg-gradient-to-r from-[#e2e8f0] to-[#cbd5e1] py-4">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-base font-bold mb-1 text-[#2d3b61]">تواصل معنا</h2>
        <p class="text-sm mb-3 text-[#1e293b]">
            هل لديك أي استفسارات؟ اترك رسالة وسنرد عليك
        </p>
        <div class="max-w-xs mx-auto">
            @if(session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('contact.store') }}" class="space-y-2">
                @csrf
                <div>
                    <input 
                        type="text" 
                        name="name"
                        class="input input-bordered input-sm w-full @error('name') input-error @enderror" 
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
                        class="input input-bordered input-sm w-full @error('email') input-error @enderror" 
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
                        class="input input-bordered input-sm w-full @error('phone') input-error @enderror" 
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
                        class="textarea textarea-bordered textarea-sm w-full resize-none @error('message') textarea-error @enderror" 
                        placeholder="رسالتك" 
                        rows="3"
                        required
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <div class="text-error text-sm mt-1 text-right">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <button type="submit" class="btn btn-sm w-full text-white" style="background-color: #2d3b61; border-color: #2d3b61;">إرسال</button>
                </div>
            </form>
        </div>
    </div>
</div>