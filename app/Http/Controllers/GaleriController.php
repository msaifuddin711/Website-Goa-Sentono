<?php

namespace App\Http\Controllers;

use App\Models\GaleriItem;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    public function index()
    {
        $galeriItems = GaleriItem::visible()->latest()->paginate(6);

        $initialGalleryData = [
            'data' => $galeriItems->items(),
            'currentPage' => $galeriItems->currentPage(),
            'lastPage' => $galeriItems->lastPage(),
        ];

        return view('galeri', [
            'galeriItems' => $galeriItems,
            'initialGalleryData' => $initialGalleryData, 
        ]);
    }

    public function apiIndex(Request $request)
    {
        $perPage = $request->get('per_page', 6);
        $search = $request->get('search');
        
        $query = GaleriItem::visible()->latest();
        
        if ($search) {
            $query->search($search);
        }
        
        $galeriItems = $query->paginate($perPage);
        
        return response()->json([
            'data' => $galeriItems->items(),
            'current_page' => $galeriItems->currentPage(),
            'last_page' => $galeriItems->lastPage(),
            'per_page' => $galeriItems->perPage(),
            'total' => $galeriItems->total(),
        ]);
    }
}