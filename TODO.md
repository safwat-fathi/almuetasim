# SEO Tasks List (قائمة مهام SEO)

## English

1. **Add Metadata to Pages**
   - Add title, description, keywords to all pages.
   - For product/category pages, use dynamic data from controllers.
   - Files: layouts/app.blade.php, home.blade.php, product.blade.php, category.blade.php, ProductController.php, CategoryController.php.

2. **Lazy Load Images**
   - Add loading="lazy" to img tags.
   - Focus on product and category images.
   - Files: product-card.blade.php, featured-category-card.blade.php, home.blade.php, category.blade.php, product.blade.php.

3. **Use Semantic HTML Elements**
   - Change divs to header, nav, main, section, article, aside, footer.
   - Check layouts and partials.
   - Files: app.blade.php, navbar.blade.php, footer.blade.php, home.blade.php, product.blade.php.

4. **Add sitemap.xml**
   - Create XML sitemap with all URLs.
   - Use spatie/laravel-sitemap package.
   - Files: routes/web.php, new SitemapController.php.

5. **Optimize Images to WebP**
   - Use spatie/laravel-image-optimizer to convert images.
   - Update controllers and views.
   - Files: ProductController.php, product-card.blade.php, filesystems.php.

6. **Minify CSS & JS**
   - Use Vite to minify files.
   - Run build for production.
   - Files: vite.config.js, package.json.

7. **Add Open Graph & Twitter Tags**
   - Add og:title, og:description, etc. to layout.
   - Override in dynamic pages.
   - Files: app.blade.php, product.blade.php, ProductController.php.

8. **Add Robots.txt & Canonical Tags**
   - Update robots.txt.
   - Add canonical links to prevent duplicates.
   - Files: public/robots.txt, app.blade.php, product.blade.php.

9. **Add Breadcrumbs with JSON-LD**
   - Add breadcrumb schema to category/product pages.
   - Generate data in controllers.
   - Files: category.blade.php, product.blade.php, CategoryController.php.

10. **Add Schema.org Data**
    - Use spatie/schema-org for product schemas.
    - Add to product pages.
    - Files: ProductController.php, product.blade.php.

## العربي

1. **إضافة البيانات الوصفية للصفحات**
   - أضف عنوان، وصف، كلمات مفتاحية لكل الصفحات.
   - لصفحات المنتجات/الفئات، استخدم بيانات ديناميكية من المتحكمات.
   - الملفات: layouts/app.blade.php, home.blade.php, product.blade.php, category.blade.php, ProductController.php, CategoryController.php.

2. **تحميل الصور الكسول**
   - أضف loading="lazy" لعلامات img.
   - ركز على صور المنتجات والفئات.
   - الملفات: product-card.blade.php, featured-category-card.blade.php, home.blade.php, category.blade.php, product.blade.php.

3. **استخدام عناصر HTML دلالية**
   - غير div إلى header, nav, main, section, article, aside, footer.
   - تحقق من التخطيطات والجزئيات.
   - الملفات: app.blade.php, navbar.blade.php, footer.blade.php, home.blade.php, product.blade.php.

4. **إضافة sitemap.xml**
   - أنشئ خريطة موقع XML مع كل الروابط.
   - استخدم حزمة spatie/laravel-sitemap.
   - الملفات: routes/web.php, متحكم جديد SitemapController.php.

5. **تحسين الصور إلى WebP**
   - استخدم spatie/laravel-image-optimizer لتحويل الصور.
   - حدث المتحكمات والعروض.
   - الملفات: ProductController.php, product-card.blade.php, filesystems.php.

6. **تصغير CSS و JS**
   - استخدم Vite لتصغير الملفات.
   - شغل البناء للإنتاج.
   - الملفات: vite.config.js, package.json.

7. **إضافة علامات Open Graph و Twitter**
   - أضف og:title, og:description إلخ في التخطيط.
   - غير في الصفحات الديناميكية.
   - الملفات: app.blade.php, product.blade.php, ProductController.php.

8. **إضافة Robots.txt وعلامات Canonical**
   - حدث robots.txt.
   - أضف روابط canonical لمنع التكرار.
   - الملفات: public/robots.txt, app.blade.php, product.blade.php.

9. **إضافة Breadcrumbs بـ JSON-LD**
   - أضف مخطط breadcrumb لصفحات الفئات/المنتجات.
   - أنتج البيانات في المتحكمات.
   - الملفات: category.blade.php, product.blade.php, CategoryController.php.

10. **إضافة بيانات Schema.org**
    - استخدم spatie/schema-org لمخططات المنتجات.
    - أضف لصفحات المنتجات.
    - الملفات: ProductController.php, product.blade.php.
