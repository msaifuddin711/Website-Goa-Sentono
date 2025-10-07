<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\KknMember;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KontakPageController extends Controller
{
    // Allowed roles for KKN members
    private $allowedRoles = [
        'Dosen Pembimbing Lapangan',
        'Ketua',
        'Wakil Ketua',
        'Sekretaris',
        'Bendahara',
        'Publikasi, Desain, dan Dokumentasi',
        'Hubungan Masyarakat',
        'Logistik'
    ];

    // Default order mapping for roles
    private function getDefaultOrderByRole($role) 
    {
        $roleOrder = [
            'Dosen Pembimbing Lapangan' => 0,
            'Ketua' => 1,
            'Wakil Ketua' => 2,
            'Sekretaris' => 3,
            'Bendahara' => 4,
            'Publikasi, Desain, dan Dokumentasi' => 5,
            'Hubungan Masyarakat' => 6,
            'Logistik' => 7
        ];
        
        return $roleOrder[$role] ?? 99;
    }

    public function index()
    {
        $messages = Message::latest()->paginate(20);
        $kknMembers = KknMember::orderBy('is_dpl', 'desc')->orderBy('order', 'asc')->get();
        
        return view('admin.kontak.index', compact('messages', 'kknMembers'));
    }

    // Kelola Pesan
    public function destroyMessage(Message $message)
    {
        $message->delete();
        return redirect()->back()->with('success', 'Pesan berhasil dihapus!');
    }

    public function bulkDeleteMessages(Request $request)
    {
        $ids = $request->input('ids');
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal satu pesan untuk dihapus.');
        }

        Message::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', count($ids) . ' pesan berhasil dihapus!');
    }

    public function markAsRead(Message $message)
    {
        $message->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Pesan ditandai sudah dibaca!');
    }

    public function markAsUnread(Message $message)
    {
        $message->update(['is_read' => false]);
        return redirect()->back()->with('success', 'Pesan ditandai belum dibaca!');
    }

    // Kelola Anggota KKN
    public function storeMember(Request $request)
    {
        $allowedRoles = $this->allowedRoles;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'role' => ['required', 'string', Rule::in($allowedRoles)],
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp',
            'is_dpl' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ], [
            'role.in' => 'Posisi yang dipilih tidak valid.',
            'photo.required' => 'Foto anggota wajib diupload.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 5MB.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Auto-detect DPL based on role
        $isDpl = $request->role === 'Dosen Pembimbing Lapangan';

        // Check if DPL already exists
        if ($isDpl && KknMember::where('is_dpl', true)->exists()) {
            return redirect()->back()->with('error', 'DPL sudah ada. Hanya boleh ada satu DPL.');
        }

        $photoPath = $request->file('photo')->store('kkn-members', 'public');

        // Auto-assign order based on role if not provided
        $order = $request->order;
        if (is_null($order)) {
            $order = $this->getDefaultOrderByRole($request->role);
            
            // Check if order already exists and increment
            while (KknMember::where('order', $order)->where('is_dpl', $isDpl)->exists()) {
                $order++;
            }
        }

        KknMember::create([
            'name' => $request->name,
            'role' => $request->role,
            'photo_path' => $photoPath,
            'is_dpl' => $isDpl,
            'order' => $order
        ]);

        return redirect()->back()->with('success', 'Anggota KKN berhasil ditambahkan!');
    }

    public function updateMember(Request $request, KknMember $member)
    {
        $allowedRoles = $this->allowedRoles;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'role' => ['required', 'string', Rule::in($allowedRoles)],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_dpl' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ], [
            'role.in' => 'Posisi yang dipilih tidak valid.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Auto-detect DPL based on role
        $isDpl = $request->role === 'Dosen Pembimbing Lapangan';

        // Check if trying to set DPL when another DPL exists
        if ($isDpl && !$member->is_dpl && KknMember::where('is_dpl', true)->exists()) {
            return redirect()->back()->with('error', 'DPL sudah ada. Hanya boleh ada satu DPL.');
        }

        $data = [
            'name' => $request->name,
            'role' => $request->role,
            'is_dpl' => $isDpl,
            'order' => $request->order ?? $member->order
        ];

        // If role changed, update order to default for that role
        if ($request->role !== $member->role && is_null($request->order)) {
            $data['order'] = $this->getDefaultOrderByRole($request->role);
            
            // Check if order already exists and increment
            while (KknMember::where('order', $data['order'])
                            ->where('is_dpl', $isDpl)
                            ->where('id', '!=', $member->id)
                            ->exists()) {
                $data['order']++;
            }
        }

        if ($request->hasFile('photo')) {
            // Hapus foto lama
            if ($member->photo_path) {
                Storage::disk('public')->delete($member->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('kkn-members', 'public');
        }

        $member->update($data);

        return redirect()->back()->with('success', 'Data anggota KKN berhasil diperbarui!');
    }

    public function destroyMember(KknMember $member)
    {
        if ($member->photo_path) {
            Storage::disk('public')->delete($member->photo_path);
        }
        
        $member->delete();

        return redirect()->back()->with('success', 'Anggota KKN berhasil dihapus!');
    }

    public function reorderMembers(Request $request)
    {
        $items = $request->input('items');
        
        foreach ($items as $item) {
            KknMember::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true, 'message' => 'Urutan anggota berhasil diperbarui']);
    }

}