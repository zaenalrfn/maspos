<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UpdateProfileController extends Controller
{
    public function getImage($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'image' => $user->image
        ]);
    }

    public function updateImage(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg|max:2048'
        ]);

        $user = User::findOrFail($id);

        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        $user->image = $request->file('image')->store('profil_image', 'public');
        $user->save();

        return redirect()->back()->with('success', 'Gambar berhasil diupdate');
    }

    public function removeImage($id)
    {
        $user = User::findOrFail($id);

        if ($user->image) {
            Storage::disk('public')->delete($user->image);
            $user->image = null;
            $user->save();
        }

        return redirect()->back()->with('success', 'Gambar berhasil dihapus');
    }
}
