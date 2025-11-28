@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
@endphp

@if(count($products) > 0)
    @foreach($products as $product)
        @php
            // Handle both array (from controller) and object (if passed directly)
            $prod = is_object($product) ? (array) $product : (array) $product;
            
            $images = $prod['images'] ?? [];
            $mainImage = null;
            if (!empty($images)) {
                $image = $images[0];
                if (Str::startsWith($image, ['http://', 'https://', '/'])) {
                    $mainImage = $image;
                } else {
                    $mainImage = Storage::url($image);
                }
            } else {
                $mainImage = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&h=150&fit=crop&crop=center';
            }
            $discount = (int) ($prod['discount'] ?? 0);
            $finalPrice = $discount > 0 ? round(($prod['price'] * (100 - $discount)) / 100, 2) : $prod['price'];
        @endphp
        <x-product.wishlist-inline :product="$prod" :mainImage="$mainImage" :finalPrice="$finalPrice" />
    @endforeach
@else
    <p class="text-base-content/70 text-sm">المفضلة فارغة</p>
@endif
