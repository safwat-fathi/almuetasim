<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Message;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Message::create([
            'name' => 'أحمد محمد',
            'email' => 'ahmed@example.com',
            'phone' => '0501234567',
            'message' => 'أود الاستفسار عن المنتجات المتوفرة لديكم',
            'read' => false
        ]);

        Message::create([
            'name' => 'فاطمة علي',
            'email' => 'fatima@example.com',
            'phone' => '0507654321',
            'message' => 'هل تقدمون خدمة التوصيل إلى جميع أنحاء المملكة؟',
            'read' => true
        ]);

        Message::create([
            'name' => 'سارة عبدالله',
            'email' => 'sara@example.com',
            'phone' => null,
            'message' => 'شكراً لكم على المنتجات الرائعة. أريد شراء فلتر للمياه',
            'read' => false
        ]);
    }
}
