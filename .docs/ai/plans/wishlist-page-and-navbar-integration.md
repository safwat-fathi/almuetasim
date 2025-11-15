# خطة تنفيذ صفحة Wishlist منفصلة وتكاملها مع Navbar

## الهدف
- إنشاء صفحة منفصلة للـ wishlist تحتوي على جميع المتحكمات
- إضافة رابط للـ wishlist في الـ navbar
- تحسين الـ routes والـ controller

## الخطوات

### 1. إنشاء WishlistController
- إنشاء controller جديد باستخدام `php artisan make:controller WishlistController`
- إضافة method `index()` لعرض صفحة الـ wishlist
- نقل منطق الـ routes الحالي إلى الـ controller

### 2. تحديث Routes
- نقل routes الـ wishlist من closures إلى الـ controller
- إضافة route جديد لعرض صفحة الـ wishlist: `GET /wishlist`

### 3. إنشاء صفحة Wishlist
- إنشاء view جديد: `resources/views/wishlist/index.blade.php`
- عرض المنتجات الموجودة في الـ wishlist
- إضافة أزرار لإزالة المنتجات من الـ wishlist
- إضافة أزرار للانتقال لصفحة المنتج
- إضافة رسالة عند عدم وجود منتجات

### 4. تحديث Navbar
- إضافة رابط للـ wishlist في الـ navbar
- إضافة badge يعرض عدد المنتجات في الـ wishlist
- تحديث العدد ديناميكياً عند إضافة/إزالة منتجات

### 5. تحديث Product Page
- التأكد من أن أزرار الـ wishlist تعمل بشكل صحيح
- تحديث العدد في الـ navbar بعد إضافة/إزالة منتج

## الملفات المطلوب تعديلها/إنشاؤها

### ملفات جديدة:
- `app/Http/Controllers/WishlistController.php`
- `resources/views/wishlist/index.blade.php`

### ملفات للتعديل:
- `routes/web.php`
- `resources/views/components/layouts/partials/app/navbar.blade.php`
- `resources/views/product.blade.php` (إذا لزم الأمر)

