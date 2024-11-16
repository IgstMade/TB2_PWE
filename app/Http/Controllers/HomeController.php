<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function ViewHome()
    {
        $isAdmin = Auth::user()->role == 'admin';

        $produkPerHariQuery = Produk::selectRaw('DATE(created_at) as date, COUNT(*) as total')
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

        if (!$isAdmin){
            $produkPerHariQuery->where('user_id', Auth::id());
        }



        // Memisahkan data untuk grafik
        $dates = [];
        $totals = [];

        foreach ($produkPerHariQuery as $item) {
            $dates[] = Carbon::parse($item->date)->format('Y-m-d'); // Format tanggal
            $totals[] = $item->total;

        }

        $chart = LarapexChart::barChart()
            ->setTitle('Produk Ditambahkan Per Hari')
            ->setSubtitle('Data Penambahan Produk Harian')
            ->addData('Jumlah Produk', $totals)
            ->setXAxis($dates);

        $totalProductsQuery = Produk::query();

        if (!$isAdmin) {
            $totalProductsQuery->where('user_id', Auth::id());
        }

        // Data tambahan untuk view
        $data = [
            'totalProducts' => $totalProductsQuery->count(), // Total produk
            'salesToday' => 130, //  data lainnya
            'totalRevenue' => 'Rp 75,000,000',
            'registeredUsers' => 350,
            'chart' => $chart // Pass chart ke view
        ];

        return view('home', $data);

    }
}
