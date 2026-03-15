@section('description', 'معرض أعمال المعتصم لفلاتر ومحطات المياه')

<x-layouts.app title="معرض أعمالنا">
    <section class="relative isolate overflow-hidden py-16 md:py-20" aria-labelledby="gallery-page-heading">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(30,64,175,0.16),_transparent_46%),radial-gradient(circle_at_bottom_left,_rgba(14,165,233,0.14),_transparent_42%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-base-100/90 via-base-100 to-base-100"></div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="mx-auto max-w-5xl text-center mb-12 md:mb-14">
                <span class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-white/80 px-4 py-1.5 text-sm font-semibold text-primary shadow-sm">
                    <span class="inline-block h-2 w-2 rounded-full bg-primary"></span>
                    مشاريعنا المنفذة
                </span>

                <h1 id="gallery-page-heading" class="mt-4 text-4xl md:text-5xl lg:text-6xl font-bold text-[#2d3b61] leading-tight">
                    معرض أعمالنا
                </h1>

                <p class="mt-5 text-base md:text-lg text-slate-600 leading-8 max-w-3xl mx-auto">
                    صور حقيقية من أعمال تركيب وصيانة فلاتر ومحطات المياه، مع تنفيذ احترافي واهتمام بالتفاصيل.
                </p>

                <div class="mt-6 inline-flex items-center gap-2 rounded-2xl bg-white/90 border border-slate-200 px-4 py-2 text-sm text-slate-700 shadow-sm">
                    <span class="font-bold text-primary">{{ $galleryItems->total() }}</span>
                    <span>عملًا موثقًا</span>
                </div>
            </div>

            @if ($galleryItems->isEmpty())
                <div class="mx-auto max-w-2xl rounded-3xl border border-dashed border-slate-300 bg-white/90 p-12 text-center shadow-sm">
                    <h2 class="text-2xl font-bold text-[#2d3b61]">لا توجد أعمال مضافة حاليًا</h2>
                    <p class="mt-3 text-slate-600">نعمل على تحديث المعرض بصور جديدة قريبًا.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-6">العودة إلى الرئيسية</a>
                </div>
            @else
                <div class="gallery-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-7">
                    @foreach ($galleryItems as $galleryItem)
                        @php
                            $galleryCaption = filled($galleryItem->caption) ? $galleryItem->caption : 'بدون وصف';
                        @endphp
                        <article id="gallery-item-{{ $galleryItem->id }}"
                            class="gallery-card group relative overflow-hidden rounded-3xl border border-slate-300/30 bg-white/95 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                            <img
                                src="{{ asset('storage/' . $galleryItem->image_path) }}"
                                alt="{{ $galleryCaption }}"
                                loading="lazy"
                                decoding="async"
                                class="gallery-media aspect-[4/3] min-h-[260px] md:min-h-[300px] lg:min-h-[340px] h-auto w-full object-cover transition-transform duration-500 group-hover:scale-105" />

                            <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/20 to-transparent pointer-events-none"></div>

                            <div class="absolute inset-x-4 bottom-4 z-20 gallery-caption-backdrop rounded-xl border border-white/20 bg-black/55 p-4 md:p-5 shadow-xl backdrop-blur-md">
                                <p class="text-base md:text-lg font-semibold text-white leading-7 md:leading-8 drop-shadow-md">
                                    {{ $galleryCaption }}
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-12 flex justify-center">
                    {{ $galleryItems->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
