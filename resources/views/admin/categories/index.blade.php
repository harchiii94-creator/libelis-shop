@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Kategori</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-900">Kelola Kategori</h1>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center rounded-full bg-[#6FCF97] px-5 py-3 text-sm font-semibold text-white hover:bg-[#2FA884] transition">Tambah Kategori</a>
    </div>

    <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-6 py-4">Nama Kategori</th>
                    <th class="px-6 py-4">Slug</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($categories as $category)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-900">{{ ucfirst($category->name) }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $category->slug }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="mr-2 rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:border-[#2FA884] hover:text-[#2FA884] transition">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-full bg-rose-500 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-600 transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-slate-500">Belum ada kategori tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end">
        {{ $categories->links() }}
    </div>
</div>
@endsection
