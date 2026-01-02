<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandingContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('landing_features')->insert([
            [
                'features_image' => 'uploads/front-assets/img/feature1.png',
                'features_card_heading' => 'Pembayaran Realtime',
                'features_card_sort_desc' => 'Status pembayaran langsung terupdate otomatis setelah transfer berhasil.',
                'sort_order' => 1
            ],
            [
                'features_image' => 'uploads/front-assets/img/feature2.png',
                'features_card_heading' => 'Aman & Terpercaya',
                'features_card_sort_desc' => 'Data transaksi dilindungi dengan enkripsi tingkat tinggi.',
                'sort_order' => 2
            ],
            [
                'features_image' => 'uploads/front-assets/img/feature3.png',
                'features_card_heading' => 'Notifikasi WhatsApp',
                'features_card_sort_desc' => 'Dapatkan info tagihan dan bukti bayar langsung ke nomor WA Anda.',
                'sort_order' => 3
            ],
        ]);

        DB::table('landing_steps')->insert([
            [
                'how_icon' => 'fas fa-user-plus',
                'how_item_heading' => 'Login Akun',
                'how_item_sort_desc' => 'Masuk menggunakan NISN dan Password yang telah diberikan sekolah.',
                'sort_order' => 1
            ],
            [
                'how_icon' => 'fas fa-file-invoice-dollar',
                'how_item_heading' => 'Cek Tagihan',
                'how_item_sort_desc' => 'Lihat daftar tagihan SPP atau Uang Gedung yang belum lunas.',
                'sort_order' => 2
            ],
            [
                'how_icon' => 'fas fa-money-bill-wave',
                'how_item_heading' => 'Lakukan Pembayaran',
                'how_item_sort_desc' => 'Transfer sesuai nominal unik atau upload bukti transfer manual.',
                'sort_order' => 3
            ],
        ]);

        DB::table('landing_faqs')->insert([
            [
                'faq_card_question' => 'Bagaimana cara login jika lupa password?',
                'faq_card_answer' => 'Silakan hubungi bagian Tata Usaha atau Admin IT sekolah untuk reset password.',
                'faq_card_icon' => 'fas fa-headset',
                'faq_card_title' => 'Hubungi Admin',
                'sort_order' => 1
            ],
            [
                'faq_card_question' => 'Apakah bisa bayar lewat Indomaret?',
                'faq_card_answer' => 'Saat ini pembayaran hanya tersedia melalui Transfer Bank (BCA, BRI, Mandiri).',
                'faq_card_icon' => 'fas fa-credit-card',
                'faq_card_title' => 'Metode Pembayaran',
                'sort_order' => 2
            ],
        ]);
    }
}
