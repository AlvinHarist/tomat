<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'phone'      => 'required|string|max:15',
            'email'      => 'required|email|max:255',
            'province'   => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'comment'    => 'nullable|string',
            'rating'     => 'required|integer|min:1|max:5',
        ]);

        // Cek apakah email sudah pernah memberikan ulasan untuk produk ini
        $existingReview = Review::where('product_id', $validated['product_id'])
                                ->where('email', $validated['email'])
                                ->first();

        if ($existingReview) {
            return redirect()
                ->back()
                ->with('error', 'Email ini sudah pernah memberikan ulasan untuk produk ini.');
        }

        try {
            $validated['id'] = (string) Str::uuid();

            Review::create($validated);

            return redirect()
                ->back()
                ->with('success', 'Terima kasih, ulasan kamu berhasil dikirim!');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan pada server, silakan coba lagi.');
        }
    }
}

