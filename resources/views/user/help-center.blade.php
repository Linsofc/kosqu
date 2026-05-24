@extends('layouts.user')

@section('content')
<style>
    .help-card {
        background: #FFFFFF;
        border: 1px solid var(--border-light);
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }
    .help-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border-color: var(--primary-light);
    }
    
    .faq-item {
        border-bottom: 1px solid var(--border-light);
        padding: 1rem 0;
    }
    .faq-item:last-child {
        border-bottom: none;
    }
    .faq-question {
        font-weight: 700;
        color: var(--text-main);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: color 0.2s;
    }
    .faq-question:hover {
        color: var(--primary);
    }
    .faq-answer {
        margin-top: 0.75rem;
        color: var(--text-muted);
        font-size: 0.9rem;
        line-height: 1.6;
        display: none;
    }
    .faq-answer.active {
        display: block;
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .btn-wa {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: #25D366;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
    }
    .btn-wa:hover {
        background: #128C7E;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
    }
</style>

<div class="dashboard-title">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Pusat Bantuan</h1>
            <p>Butuh bantuan? Temukan jawaban Anda di bawah ini atau hubungi admin kami.</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 300px; gap: 1.5rem;">
    {{-- Kolom Kiri: FAQ --}}
    <div>
        <div class="widget">
            <div class="widget-header">
                <div class="widget-title">Frequently Asked Questions (FAQ)</div>
            </div>
            
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        1. Bagaimana cara melakukan pembayaran kos?
                        <i class="fa-solid fa-chevron-down" style="font-size: 0.8rem; color: var(--slate);"></i>
                    </div>
                    <div class="faq-answer">
                        Anda dapat melakukan transfer ke rekening bank pengelola kos yang tertera. Setelah transfer, silakan buka menu <strong>Pembayaran</strong>, isi nominal, dan unggah bukti transfer Anda. Admin akan memvalidasinya.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        2. Apakah saya bisa mengunduh bukti lunas pembayaran?
                        <i class="fa-solid fa-chevron-down" style="font-size: 0.8rem; color: var(--slate);"></i>
                    </div>
                    <div class="faq-answer">
                        Tentu. Jika pembayaran Anda telah berstatus "Lunas" (Divalidasi), Anda bisa membuka menu <strong>Riwayat & Invoice</strong> lalu klik ikon Unduh (Download) untuk mencetak Kwitansi PDF resmi.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        3. Kapan saya harus memperpanjang sewa?
                        <i class="fa-solid fa-chevron-down" style="font-size: 0.8rem; color: var(--slate);"></i>
                    </div>
                    <div class="faq-answer">
                        Batas jatuh tempo dapat Anda lihat di <strong>Dashboard</strong>. Sistem akan mengirimkan pesan pengingat otomatis ke WhatsApp Anda beberapa hari sebelum waktu sewa Anda habis.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        4. Bagaimana jika saya lupa kata sandi portal ini?
                        <i class="fa-solid fa-chevron-down" style="font-size: 0.8rem; color: var(--slate);"></i>
                    </div>
                    <div class="faq-answer">
                        Silakan keluar (Logout) dari aplikasi. Di halaman awal Login, terdapat tautan "Lupa Password?". Masukkan NIK Anda, dan sistem akan mengirimi Anda sandi baru ke nomor WhatsApp yang terdaftar.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        5. Apakah saya boleh membawa hewan peliharaan?
                        <i class="fa-solid fa-chevron-down" style="font-size: 0.8rem; color: var(--slate);"></i>
                    </div>
                    <div class="faq-answer">
                        Demi kenyamanan bersama, penghuni tidak diizinkan membawa hewan peliharaan ke dalam area kos. Mohon patuhi peraturan dasar yang telah ditetapkan saat pertama kali Anda mendaftar.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Hubungi Admin --}}
    <div>
        <div class="help-card" style="text-align: center; display: flex; flex-direction: column; align-items: center;">
            <div style="width: 60px; height: 60px; background: #DCFCE7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                <i class="fa-brands fa-whatsapp text-3xl" style="color: #22C55E;"></i>
            </div>
            <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem;">Layanan Keluhan</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem; line-height: 1.5;">
                Fasilitas kamar rusak? AC tidak dingin? Atau atap bocor? Segera laporkan kepada pengelola kos.
            </p>
            
            <a href="https://wa.me/{{ $adminPhone }}?text=Halo%20Admin%20KOSQU,%20saya%20penghuni%20ingin%20melaporkan%20sesuatu..." 
               target="_blank" class="btn-wa" style="width: 100%; justify-content: center;">
                <i class="fa-brands fa-whatsapp text-lg"></i>
                Hubungi Admin
            </a>
            
            <div style="font-size: 0.7rem; color: var(--slate); margin-top: 1rem;">
                Jam Operasional: 08.00 - 20.00 WIB
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFaq(element) {
        const answer = element.nextElementSibling;
        const icon = element.querySelector('i');
        
        if (answer.classList.contains('active')) {
            answer.classList.remove('active');
            icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
        } else {
            answer.classList.add('active');
            icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
        }
    }
</script>
@endsection
