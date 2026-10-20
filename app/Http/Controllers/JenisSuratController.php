<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJenisSuratRequest;
use App\Http\Requests\UpdateJenisSuratRequest;
use App\Http\Requests\UploadTemplateRequest;
use App\Models\JenisSurat;
use App\Services\TemplateSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class JenisSuratController extends Controller
{
    public function index()
    {
        $jenisSuratList = JenisSurat::query()
            ->withCount('pengajuanSurat')
            ->latest()
            ->paginate(10);

        return view('jenis-surat.index', compact('jenisSuratList'));
    }

    public function create()
    {
        return view('jenis-surat.create');
    }

    public function store(StoreJenisSuratRequest $request)
    {
        JenisSurat::create($request->validated());

        return redirect()
            ->route('jenis-surat.index')
            ->with('success', 'Jenis surat berhasil disimpan.');
    }

    public function edit(JenisSurat $jenisSurat)
    {
        return view('jenis-surat.edit', compact('jenisSurat'));
    }

    public function update(UpdateJenisSuratRequest $request, JenisSurat $jenisSurat)
    {
        $jenisSurat->update($request->validated());

        return redirect()
            ->route('jenis-surat.index')
            ->with('success', 'Jenis surat berhasil diperbarui.');
    }

    public function destroy(JenisSurat $jenisSurat, TemplateSuratService $templateService)
    {
        if ($jenisSurat->pengajuanSurat()->exists()) {
            return back()->with('error', 'Jenis surat tidak dapat dihapus karena sudah memiliki pengajuan.');
        }

        $templateService->deleteTemplate($jenisSurat);

        $jenisSurat->delete();

        return back()->with('success', 'Jenis surat berhasil dihapus.');
    }

    public function templateForm(JenisSurat $jenisSurat, TemplateSuratService $templateService)
    {
        $templateContent = $templateService->content($jenisSurat);

        $knownPlaceholders = $templateService->knownPlaceholders($jenisSurat);

        $unknownPlaceholders = $templateContent
            ? $templateService->unknownPlaceholders($jenisSurat, $templateContent)
            : [];

        return view('jenis-surat.template', [
            'jenisSurat' => $jenisSurat,
            'knownPlaceholders' => $knownPlaceholders,
            'unknownPlaceholders' => $unknownPlaceholders,
        ]);
    }

    public function uploadTemplate(
        UploadTemplateRequest $request,
        JenisSurat $jenisSurat,
        TemplateSuratService $templateService
    ) {
        $validated = $request->validated();

        $jenisSurat->fill(Arr::only($validated, [
            'ttd_x',
            'ttd_y',
            'qr_x',
            'qr_y',
        ]));

        if ($request->hasFile('template')) {
            $path = $templateService->storeTemplate($jenisSurat, $request->file('template'));
            $jenisSurat->template_path = $path;
        } elseif (! $jenisSurat->template_path) {
            return back()
                ->withInput()
                ->with('error', 'Template belum ada. Unggah file HTML terlebih dahulu.');
        }

        $jenisSurat->save();

        return redirect()
            ->route('jenis-surat.template', $jenisSurat)
            ->with('success', 'Template dan posisi berhasil disimpan.');
    }

    public function deleteTemplate(JenisSurat $jenisSurat, TemplateSuratService $templateService)
    {
        $templateService->deleteTemplate($jenisSurat);

        $jenisSurat->update([
            'template_path' => null,
        ]);

        return back()->with('success', 'Template berhasil dihapus.');
    }

    public function previewTemplate(
        Request $request,
        JenisSurat $jenisSurat,
        TemplateSuratService $templateService
    ) {
        $pdf = $templateService->previewPdf($jenisSurat, $request->user());

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="preview-template-' . $jenisSurat->kode . '.pdf"');
    }
    public function editTemplate(JenisSurat $jenisSurat, TemplateSuratService $templateService)
{
    $content = $templateService->content($jenisSurat) ?? '';

    $knownPlaceholders = $templateService->knownPlaceholders($jenisSurat);

    $unknownPlaceholders = $content
        ? $templateService->unknownPlaceholders($jenisSurat, $content)
        : [];

    return view('jenis-surat.template-editor', [
        'jenisSurat' => $jenisSurat,
        'content' => $content,
        'knownPlaceholders' => $knownPlaceholders,
        'unknownPlaceholders' => $unknownPlaceholders,
    ]);
}

public function updateTemplate(Request $request, JenisSurat $jenisSurat, TemplateSuratService $templateService)
{
    $validated = $request->validate([
        'template_html' => [
            'required',
            'string',
            'max:200000',
        ],
    ]);

    $templateService->saveContent($jenisSurat, $validated['template_html']);

    return redirect()
        ->route('jenis-surat.template', $jenisSurat)
        ->with('success', 'Template berhasil disimpan dari editor.');
}
}