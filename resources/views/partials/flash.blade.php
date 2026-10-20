@if (session('success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        class="mb-4 rounded-md border border-success-border bg-success-background px-4 py-3 text-sm text-success-text"
    >
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        class="mb-4 rounded-md border border-danger-border bg-danger-background px-4 py-3 text-sm text-danger-text"
    >
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded-md border border-danger-border bg-danger-background px-4 py-3 text-sm text-danger-text">
        <p class="font-semibold">Terdapat kesalahan input:</p>

        <ul class="mt-2 list-inside list-disc space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif