@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Pengaturan</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-900">Pengaturan Operasional</h1>
        </div>
    </div>

    <section class="rounded-[2rem] bg-slate-50 p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Jam Operasional</p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-900">Atur jawaban jam buka chatbot</h2>
            </div>
        </div>

        <form action="{{ route('admin.settings.operational-hours.update') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="operational_hours" class="block text-sm font-medium text-slate-700">Jam buka yang akan terlihat di chatbot</label>
                <textarea id="operational_hours" name="operational_hours" rows="4" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-[#2FA884] focus:outline-none focus:ring-2 focus:ring-[#2FA884]/20">{{ old('operational_hours', $operationalHours) }}</textarea>
                @error('operational_hours')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="inline-flex items-center rounded-full bg-[#2FA884] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#239272]">Simpan jam operasional</button>
        </form>
    </section>
</div>
@endsection
