@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-5">
                <h1 class="text-2xl font-semibold text-slate-900">Chatbot LiBelLis SHOP</h1>
                <p class="mt-2 text-sm text-slate-600">Silakan tanyakan tentang produk, harga, stok, operasional, atau cara pemesanan.</p>
            </div>

            <div class="px-6 py-6">
                <div id="chat-log" class="space-y-4">
                    <div class="rounded-3xl bg-[#F0FDF4] p-4 text-sm text-slate-800 shadow-sm">
                        <p class="font-semibold">Halo Sobat LiBelLis!</p>
                        <p class="mt-2">Saya Asisten Virtual resmi dari LiBelLis SHOP. Tanyakan apa saja seputar produk, harga, stok, operasional toko, dan cara pemesanan.</p>
                    </div>
                </div>

                <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-900">Pertanyaan Cepat</p>
                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        <button type="button" class="quick-question rounded-3xl border border-slate-200 bg-white px-4 py-3 text-left text-sm text-slate-700 shadow-sm transition hover:border-[#2FA884] hover:bg-[#F0FDF4]" data-question="Buka jam berapa?">Buka jam berapa?</button>
                        <button type="button" class="quick-question rounded-3xl border border-slate-200 bg-white px-4 py-3 text-left text-sm text-slate-700 shadow-sm transition hover:border-[#2FA884] hover:bg-[#F0FDF4]" data-question="Berapa harganya?">Berapa harganya?</button>
                        <button type="button" class="quick-question rounded-3xl border border-slate-200 bg-white px-4 py-3 text-left text-sm text-slate-700 shadow-sm transition hover:border-[#2FA884] hover:bg-[#F0FDF4]" data-question="Stok masih ada?">Stok masih ada?</button>
                        <button type="button" class="quick-question rounded-3xl border border-slate-200 bg-white px-4 py-3 text-left text-sm text-slate-700 shadow-sm transition hover:border-[#2FA884] hover:bg-[#F0FDF4]" data-question="Gimana caranya pesan">Gimana caranya pesan</button>
                    </div>
                </div>

                <form id="chat-form" class="mt-6 grid gap-3 sm:grid-cols-[1fr_auto]">
                    <textarea id="chat-input" name="message" rows="3" class="min-h-[98px] w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-[#2FA884] focus:outline-none focus:ring-2 focus:ring-[#2FA884]/20" placeholder="Tulis pertanyaan kamu di sini, Kak..."></textarea>
                    <button id="chat-submit" type="submit" class="inline-flex items-center justify-center rounded-3xl bg-[#2FA884] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1d8a6e]">Kirim</button>
                </form>

                <div id="toast" class="hidden fixed bottom-6 right-6 z-50 rounded-2xl px-4 py-3 text-sm text-white shadow-lg transition opacity-0"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('chat-form');
            const input = document.getElementById('chat-input');
            const chatLog = document.getElementById('chat-log');

            const quickButtons = document.querySelectorAll('.quick-question');

            quickButtons.forEach(button => {
                button.addEventListener('click', () => {
                    input.value = button.dataset.question;
                    input.focus();
                });
            });

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const message = input.value.trim();
                if (!message) {
                    showToast('Mohon isi pesan terlebih dahulu, Kak.', 'error');
                    return;
                }

                const userBubble = document.createElement('div');
                userBubble.className = 'rounded-3xl bg-[#EFF6FF] p-4 text-sm text-slate-900 shadow-sm';
                userBubble.innerHTML = '<p class="font-semibold">Kamu</p><p class="mt-2">' + message.replace(/\n/g, '<br>') + '</p>';
                chatLog.appendChild(userBubble);
                chatLog.scrollTop = chatLog.scrollHeight;

                input.disabled = true;
                document.getElementById('chat-submit').disabled = true;

                try {
                    const response = await fetch('{{ route('chatbot.message') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message })
                    });

                    const data = await response.json();
                    let reply = data.reply || 'Maaf Kak, terjadi kesalahan.';
                    if (!response.ok && data.debug) {
                        reply += '\n\nDebug: ' + data.debug;
                        console.error('Chatbot API debug:', data.debug);
                    }

                    const botBubble = document.createElement('div');
                    botBubble.className = 'rounded-3xl bg-[#F0FDF4] p-4 text-sm text-slate-900 shadow-sm';
                    botBubble.innerHTML = '<p class="font-semibold">Sobat LiBelLis</p><p class="mt-2">' + reply.replace(/\n/g, '<br>') + '</p>';
                    chatLog.appendChild(botBubble);
                    chatLog.scrollTop = chatLog.scrollHeight;
                } catch (error) {
                    console.error('Chatbot fetch error:', error);
                    showToast('Gagal mengirim pesan. Coba lagi nanti, Kak.', 'error');
                } finally {
                    input.disabled = false;
                    document.getElementById('chat-submit').disabled = false;
                    input.focus();
                }
            });
        });
    </script>
@endsection
