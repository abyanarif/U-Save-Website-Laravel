<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article; // Pastikan model Article sudah dibuat
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Mengambil semua artikel untuk ditampilkan di halaman publik.
     */
    public function index()
    {
        $articles = Article::all();
        // Cek jika tidak ada artikel sama sekali
        if ($articles->isEmpty()) {
            return response()->json(['message' => 'Tidak ada artikel yang ditemukan di database.'], 404);
        }
        return response()->json($articles);
    }

    /**
     * Mengambil data satu artikel spesifik untuk ditampilkan di form edit admin.
     * Metode ini dimodifikasi untuk debugging.
     */
    public function show($section_id)
    {
        // Cari artikel berdasarkan section_id, TAPI JANGAN gunakan firstOrFail() untuk sekarang
        $article = Article::where('section_id', $section_id)->first();

        // Jika artikel TIDAK ditemukan, kembalikan pesan error yang jelas
        if (!$article) {
            return response()->json([
                'message' => 'Artikel tidak ditemukan di database.',
                'searched_for_section_id' => $section_id
            ], 404);
        }

        // Jika artikel DITEMUKAN, kembalikan datanya
        return response()->json($article);
    }

    /**
     * Memperbarui data artikel dari form admin.
     */
    public function update(Request $request, $section_id)
    {
        $article = Article::where('section_id', $section_id)->first();

        if (!$article) {
            return response()->json([
                'message' => 'Artikel yang ingin diupdate tidak ditemukan.',
                'searched_for_section_id' => $section_id
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $article->update($validator->validated());

        return response()->json(['message' => 'Artikel berhasil diperbarui!', 'article' => $article]);
    }
}
