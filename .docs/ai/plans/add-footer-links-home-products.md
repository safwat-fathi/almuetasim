# Plan: إضافة روابط الرئيسية والمنتجات في الفوتر

## الهدف
- إضافة رابطين في الفوتر: "الرئيسية" و"المنتجات" بجانب رابط "معلومات عنا".

## الملف المتأثر
- `resources/views/components/layouts/partials/app/footer.blade.php`

## التغييرات المقترحة
- ضمن كتلة `<nav class="grid grid-flow-col gap-4">`:
  - إضافة `<a href="{{ route('home') }}" class="link link-hover">الرئيسية</a>`.
  - إضافة `<a href="{{ route('products.public.list') }}" class="link link-hover">المنتجات</a>`.
- الحفاظ على نفس أسلوب Tailwind/DaisyUI المستخدم حالياً.

## التحقق
- فتح أي صفحة عمومية والتأكد من ظهور الروابط وعملها.

## خطة الرجوع
- إزالة الرابطين في حال عدم الحاجة.

