<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // <-- TAMBAHKAN INI

class ReviewAndRatingSeeder extends Seeder
{
    public function run(): void
    {
        // (Data review tetap sama persis seperti sebelumnya)
        $reviews = [
            ['id' => 1, 'product_id' => 1, 'visitor_name' => 'Charlie', 'visitor_phone' => '0811111111', 'visitor_email' => 'charlie@gmail.com', 'rating' => 5, 'review' => 'Mantap, laptopnya kencang!',],
            ['id' => 2, 'product_id' => 1, 'visitor_name' => 'Dana', 'visitor_phone' => '0822222222', 'visitor_email' => 'dana@gmail.com', 'rating' => 4, 'review' => 'Agak panas, tapi oke.',],
            ['id' => 3, 'product_id' => 1, 'visitor_name' => 'Eka', 'visitor_phone' => '0833333333', 'visitor_email' => 'eka@gmail.com', 'rating' => 5, 'review' => 'Pengiriman super cepat!',],
            ['id' => 4, 'product_id' => 2, 'visitor_name' => 'Fani', 'visitor_phone' => '0844444444', 'visitor_email' => 'fani@gmail.com', 'rating' => 5, 'review' => 'Bahannya adem, motifnya bagus.',],
            ['id' => 5, 'product_id' => 2, 'visitor_name' => 'Gita', 'visitor_phone' => '0855555555', 'visitor_email' => 'gita@gmail.com', 'rating' => 4, 'review' => 'Ukurannya pas.',],
            ['id' => 6, 'product_id' => 3, 'visitor_name' => 'Charlie', 'visitor_phone' => '0811111111', 'visitor_email' => 'charlie@gmail.com', 'rating' => 5, 'review' => 'RGB-nya keren!',],
            ['id' => 7, 'product_id' => 3, 'visitor_name' => 'Hadi', 'visitor_phone' => '0866666666', 'visitor_email' => 'hadi@gmail.com', 'rating' => 4, 'review' => 'Tombolnya agak keras.',],
            ['id' => 8, 'product_id' => 5, 'visitor_name' => 'Indah', 'visitor_phone' => '0877777777', 'visitor_email' => 'indah@gmail.com', 'rating' => 5, 'review' => 'Tajam banget pisaunya.',],
            ['id' => 9, 'product_id' => 8, 'visitor_name' => 'Joko', 'visitor_phone' => '0888888888', 'visitor_email' => 'joko@gmail.com', 'rating' => 5, 'review' => 'Beliin buat istri, dia suka.',],
            ['id' => 10, 'product_id' => 8, 'visitor_name' => 'Kania', 'visitor_phone' => '0899999999', 'visitor_email' => 'kania@gmail.com', 'rating' => 5, 'review' => 'Bahan adem, seller ramah.',],
            ['id' => 11, 'product_id' => 8, 'visitor_name' => 'Lina', 'visitor_phone' => '0812121212', 'visitor_email' => 'lina@gmail.com', 'rating' => 4, 'review' => 'Warnanya sedikit beda dari foto.',],
            ['id' => 12, 'product_id' => 8, 'visitor_name' => 'Mona', 'visitor_phone' => '0813131313', 'visitor_email' => 'mona@gmail.com', 'rating' => 5, 'review' => 'Suka banget!',],
            ['id' => 13, 'product_id' => 9, 'visitor_name' => 'Nina', 'visitor_phone' => '0814141414', 'visitor_email' => 'nina@gmail.com', 'rating' => 5, 'review' => 'Mewah banget dressnya.',],
            ['id' => 14, 'product_id' => 10, 'visitor_name' => 'Kania', 'visitor_phone' => '0899999999', 'visitor_email' => 'kania@gmail.com', 'rating' => 5, 'review' => 'Bakal langganan di sini.',],
            ['id' => 15, 'product_id' => 10, 'visitor_name' => 'Opik', 'visitor_phone' => '0815151515', 'visitor_email' => 'opik@gmail.com', 'rating' => 4, 'review' => 'Ukurannya agak ngepas.',],
            ['id' => 16, 'product_id' => 12, 'visitor_name' => 'Putra', 'visitor_phone' => '0816161616', 'visitor_email' => 'putra@gmail.com', 'rating' => 5, 'review' => 'Bahan kaosnya top.',],
            ['id' => 17, 'product_id' => 12, 'visitor_name' => 'Qori', 'visitor_phone' => '0817171717', 'visitor_email' => 'qori@gmail.com', 'rating' => 5, 'review' => 'Murah tapi ga murahan.',],
            ['id' => 18, 'product_id' => 12, 'visitor_name' => 'Rian', 'visitor_phone' => '0818181818', 'visitor_email' => 'rian@gmail.com', 'rating' => 5, 'review' => 'Beli 3 langsung.',],
            ['id' => 19, 'product_id' => 14, 'visitor_name' => 'Sinta', 'visitor_phone' => '0819191919', 'visitor_email' => 'sinta@gmail.com', 'rating' => 5, 'review' => 'Mudah diatur banget!',],
            ['id' => 20, 'product_id' => 14, 'visitor_name' => 'Tia', 'visitor_phone' => '0821212121', 'visitor_email' => 'tia@gmail.com', 'rating' => 4, 'review' => 'Agak tipis ya.',],
            ['id' => 21, 'product_id' => 16, 'visitor_name' => 'Indah', 'visitor_phone' => '0877777777', 'visitor_email' => 'indah@gmail.com', 'rating' => 5, 'review' => 'Lucu warnanya, bahannya bagus.',],
            ['id' => 22, 'product_id' => 16, 'visitor_name' => 'Umi', 'visitor_phone' => '0822222222', 'visitor_email' => 'umi@gmail.com', 'rating' => 5, 'review' => 'Tahan panas, mantap.',],
            ['id' => 23, 'product_id' => 17, 'visitor_name' => 'Vino', 'visitor_phone' => '0823232323', 'visitor_email' => 'vino@gmail.com', 'rating' => 5, 'review' => 'Empuk beneran dagingnya.',],
            ['id' => 24, 'product_id' => 17, 'visitor_name' => 'Umi', 'visitor_phone' => '0822222222', 'visitor_email' => 'umi@gmail.com', 'rating' => 4, 'review' => 'Agak ngeri pakainya pertama kali.',],
            ['id' => 25, 'product_id' => 19, 'visitor_name' => 'Wawan', 'visitor_phone' => '0824242424', 'visitor_email' => 'wawan@gmail.com', 'rating' => 5, 'review' => 'Kopinya wangi banget.',],
            ['id' => 26, 'product_id' => 19, 'visitor_name' => 'Xena', 'visitor_phone' => '0825252525', 'visitor_email' => 'xena@gmail.com', 'rating' => 5, 'review' => 'Aroma dan rasanya pas.',],
            ['id' => 27, 'product_id' => 20, 'visitor_name' => 'Yudi', 'visitor_phone' => '0826262626', 'visitor_email' => 'yudi@gmail.com', 'rating' => 4, 'review' => 'Praktis buat dibawa.',],
            ['id' => 28, 'product_id' => 20, 'visitor_name' => 'Zizi', 'visitor_phone' => '0827272727', 'visitor_email' => 'zizi@gmail.com', 'rating' => 3, 'review' => 'Tenaganya kurang kuat buat es batu.',],
            ['id' => 29, 'product_id' => 20, 'visitor_name' => 'Vino', 'visitor_phone' => '0823232323', 'visitor_email' => 'vino@gmail.com', 'rating' => 4, 'review' => 'Oke lah buat jus buah.',],
            ['id' => 30, 'product_id' => 4, 'visitor_name' => 'Yudi', 'visitor_phone' => '0826262626', 'visitor_email' => 'yudi@gmail.com', 'rating' => 5, 'review' => 'Tendanya mantap, sudah dicoba.',],
        ];
        
        $data = [];
        $baseTime = Carbon::parse('2025-10-15 09:00:00'); // Review mulai H+10

        foreach ($reviews as $index => $review) {
            // Setiap review dibuat dengan jeda 4 jam
            $review['created_at'] = $baseTime->copy()->addHours($index * 4);
            $review['updated_at'] = $review['created_at'];
            $data[] = $review;
        }

        DB::table('review_and_ratings')->insert($data);
    }
}