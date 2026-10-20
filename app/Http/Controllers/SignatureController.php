<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class SignatureController extends Controller
{
    public function create()
    {
        return view('signature.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'signature' => [
                'required',
                'file',
                'mimes:png,jpg,jpeg',
                'max:1024',
            ],
        ]);

        $user = $request->user();

        $manager = extension_loaded('imagick')
            ? ImageManager::imagick()
            : ImageManager::gd();

        $image = $manager->read($request->file('signature')->getPathname());
        $image->scale(height: 160);

        $encoded = (string) $image->toPng();

        $path = sprintf(
            'signatures/%s-%s.png',
            $user->id,
            Str::uuid()
        );

        Storage::disk(config('surat.signature_disk', 'local'))
            ->put($path, $encoded);

        $oldSignature = $user->signature_path;

        $user->update([
            'signature_path' => $path,
        ]);

        if ($oldSignature) {
            $disk = Storage::disk(config('surat.signature_disk', 'local'));

            if ($disk->exists($oldSignature)) {
                $disk->delete($oldSignature);
            }
        }

        return back()->with('success', 'Tanda tangan digital berhasil disimpan.');
    }
}