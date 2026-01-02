<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['key' => 'logo', 'value' => 'uploads/logo.png', 'type' => 'image'],
            ['key' => 'favicon', 'value' => 'uploads/favicon.ico', 'type' => 'image'],
            ['key' => 'phone', 'value' => '081234567890', 'type' => 'text'],
            ['key' => 'footer_description', 'value' => 'Aplikasi pembayaran sekolah modern.', 'type' => 'text'],
            ['key' => 'footer_copyright', 'value' => '© 2025 SMK Budi Bakti Ciwidey', 'type' => 'text'],
            ['key' => 'hero_icon_title', 'value' => 'fas fa-bolt', 'type' => 'text'],
            ['key' => 'hero_title', 'value' => 'Pembayaran Cepat & Aman', 'type' => 'text'],
            ['key' => 'hero_heading', 'value' => 'Kelola Pembayaran Sekolah Jadi Lebih Mudah', 'type' => 'text'],
            ['key' => 'hero_sort_desc', 'value' => 'Pantau tagihan, bayar online, dan dapatkan kwitansi otomatis.', 'type' => 'longtext'],
            ['key' => 'hero_button_name', 'value' => 'Login Siswa', 'type' => 'text'],
            ['key' => 'hero_button_link', 'value' => '/login', 'type' => 'text'],
            ['key' => 'hero_button_name2', 'value' => 'Panduan', 'type' => 'text'],
            ['key' => 'hero_button_link2', 'value' => '#howItWorks', 'type' => 'text'],
            ['key' => 'features_title', 'value' => 'Kenapa Kami?', 'type' => 'text'],
            ['key' => 'features_heading', 'value' => 'Fitur Unggulan', 'type' => 'text'],
            ['key' => 'features_sub_heading', 'value' => 'Kami memberikan pengalaman terbaik.', 'type' => 'text'],
            ['key' => 'how_title', 'value' => 'Panduan', 'type' => 'text'],
            ['key' => 'how_heading', 'value' => 'Cara Pembayaran Mudah', 'type' => 'text'],
            ['key' => 'how_sub_heading', 'value' => 'Ikuti langkah-langkah berikut untuk menyelesaikan administrasi sekolah.', 'type' => 'text'],
            ['key' => 'how_image', 'value' => 'uploads/front-assets/img/how-it-works.png', 'type' => 'image'],
            ['key' => 'faq_icon_title', 'value' => 'fas fa-question-circle', 'type' => 'text'],
            ['key' => 'faq_title', 'value' => 'Bantuan', 'type' => 'text'],
            ['key' => 'faq_heading', 'value' => 'Pertanyaan Umum', 'type' => 'text'],
            ['key' => 'faq_sub_heading', 'value' => 'Temukan jawaban atas pertanyaanmu disini.', 'type' => 'text'],
            ['key' => 'faq_tips_icon', 'value' => 'fas fa-lightbulb', 'type' => 'text'],
            ['key' => 'faq_tips_heading', 'value' => 'Butuh bantuan lebih lanjut?', 'type' => 'text'],
            ['key' => 'faq_tips_description', 'value' => 'Hubungi admin via WhatsApp.', 'type' => 'text'],
            ['key' => 'footer_sosmed_name', 'value' => 'https://facebook.com', 'type' => 'text'],
            ['key' => 'footer_sosmed_icon', 'value' => 'fab fa-facebook', 'type' => 'text'],
        ];

        DB::table('settings')->insert($data);
    }
}
