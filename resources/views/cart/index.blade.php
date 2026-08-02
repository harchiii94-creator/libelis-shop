@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-10">
    <div class="rounded-[2rem] bg-white p-8 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-[#2FA884]">Cart</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-900">Keranjang Belanja</h1>
            </div>
            <p class="rounded-full bg-[#F4FFF6] px-4 py-2 text-sm font-semibold text-[#2FA884]">{{ $items->count() }} item</p>
        </div>

        @if($items->isEmpty())
            <div class="mt-10 rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center text-slate-600">
                Keranjang masih kosong. Jelajahi produk dan tambahkan ke cart.
            </div>
        @else
                <div class="mt-8 overflow-hidden rounded-3xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 bg-white text-left text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="px-6 py-4 w-16">
                                    <label class="inline-flex cursor-pointer items-center gap-2 text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">
                                        <input id="select-all" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-[#6FCF97]" checked />
                                        Pilih
                                    </label>
                                </th>
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4">Harga</th>
                                <th class="px-6 py-4">Jumlah</th>
                                <th class="px-6 py-4">Subtotal</th>
                                <th class="px-6 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($items as $item)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-6 py-5 align-top">
                                        <input type="checkbox" name="selected_products[]" value="{{ $item->product->id }}" form="checkout-form" class="checkout-item-checkbox h-4 w-4 rounded border-slate-300 text-[#6FCF97]" checked />
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-4">
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="h-20 w-20 rounded-3xl object-cover" />
                                            <div>
                                                <p class="font-semibold text-slate-900">{{ $item->product->name }}</p>
                                                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $item->product->category }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-slate-900">{{ $item->product->formatted_price }}</td>
                                    <td class="px-6 py-5">
                                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item->product->id }}" />
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" class="w-20 rounded-3xl border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30" />
                                            <button type="submit" class="rounded-full bg-[#6FCF97] px-4 py-2 text-xs font-semibold text-white hover:bg-[#2FA884] transition">Update</button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-5 text-slate-900">Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                                    <td class="px-6 py-5">
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item->product->id }}" />
                                            <button type="submit" class="rounded-full bg-rose-500 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-600 transition">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <form id="checkout-form" action="{{ route('checkout.index') }}" method="GET">
                    <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_320px]">
                        <div class="rounded-[2rem] bg-[#F4FFF6] p-8 text-slate-700">
                            <p class="text-sm uppercase tracking-[0.3em] text-[#2FA884]">Info Keranjang</p>
                            <p class="mt-4 text-base leading-7">Pastikan jumlah dan produk sudah sesuai sebelum checkout.</p>
                        </div>
                        <div class="rounded-[2rem] bg-white p-8 shadow-sm">
                            <div class="flex items-center justify-between text-slate-600">
                                <p>Subtotal</p>
                                <p class="text-lg font-semibold text-slate-900">Rp{{ number_format($total, 0, ',', '.') }}</p>
                            </div>
                            <div class="mt-6 space-y-4">
                                @auth
                                    <button type="submit" class="w-full rounded-full bg-[#6FCF97] px-6 py-3 text-sm font-semibold text-white hover:bg-[#2FA884] transition">Checkout</button>
                                @else
                                    <a href="{{ route('login') }}" class="block rounded-full border border-[#2FA884] bg-white px-6 py-3 text-center text-sm font-semibold text-[#2FA884] hover:bg-[#F0FFF4] transition">Login untuk Checkout</a>
                                @endauth
                                <a href="{{ route('products.index') }}" class="block rounded-full border border-slate-300 px-6 py-3 text-center text-sm font-semibold text-slate-900 hover:border-[#2FA884] hover:text-[#2FA884] transition">Lanjut Belanja</a>
                            </div>
                        </div>
                    </div>
                </form>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all');
        const itemCheckboxes = document.querySelectorAll('.checkout-item-checkbox');

        if (!selectAllCheckbox) return;

        selectAllCheckbox.addEventListener('change', function () {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const allChecked = Array.from(itemCheckboxes).every(item => item.checked);
                selectAllCheckbox.checked = allChecked;
            });
        });
    });
</script>
@endsection
