{{-- resources/views/reservasi.blade.php --}}

@extends('layouts.app')

@section('title', 'Reservasi Tiket - Goa Sentono')
@section('description', 'Pesan tiket kunjungan Anda ke Goa Sentono dengan mudah melalui halaman reservasi online kami. Pilih paket dan tanggal kunjungan Anda sekarang.')

@section('content')
    <section class="hero-short text-white py-24 sm:py-32 flex items-center bg-cover bg-center" style="background-image: url('{{ asset('images/IMG_1719.jpg') }}');">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full text-center hero-content animate-fade-in">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold leading-tight">
                Reservasi Kunjungan
            </h1>
            <p class="mt-4 text-lg md:text-xl max-w-3xl mx-auto">
                Rencanakan kunjungan Anda dan pastikan ketersediaan tiket dan tempat dengan memesan secara online.
            </p>
        </div>
    </section>

    <section class="py-16 md:py-24 section-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-12">
                
                {{-- Kolom Kiri: Form Input --}}
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-lg p-6 md:p-10">
                        <div class="mb-8">
                            <h2 class="text-2xl md:text-3xl font-bold text-primary">Formulir Pemesanan</h2>
                            <p class="text-md text-accent mt-2">Lengkapi data kunjungan Anda di bawah ini.</p>
                        </div>
                        <form id="form-reservasi" class="space-y-6">
                            {{-- Data Diri --}}
                            <div>
                                <label for="nama" class="text-sm font-medium text-gray-700 mb-2 flex items-center"><i class="fas fa-user mr-2 text-primary"></i>Nama Pemesan (Perwakilan)</label>
                                <input type="text" id="nama" name="nama" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Contoh: Budi Santoso" required>
                            </div>
                            <div>
                                <label for="whatsapp" class="text-sm font-medium text-gray-700 mb-2 flex items-center"><i class="fab fa-whatsapp mr-2 text-primary"></i>No. WhatsApp Aktif</label>
                                <input type="tel" id="whatsapp" name="whatsapp" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Contoh: 0812..." required>
                            </div>
                            <div>
                                <label for="tanggal" class="text-sm font-medium text-gray-700 mb-2 flex items-center"><i class="fas fa-calendar-alt mr-2 text-primary"></i>Tanggal Berkunjung</label>
                                <input type="date" id="tanggal" name="tanggal" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                            </div>

                            {{-- Pilihan Paket (Kartu Interaktif) --}}
                            <div class="pt-4">
                                <label class="text-sm font-medium text-gray-700 mb-3 flex items-center"><i class="fas fa-box-open mr-2 text-primary"></i>Pilih Paket Kunjungan</label>
                                <div id="paket-container" class="space-y-3">
                                    @foreach($paketWisata as $index => $paket)
                                        <label class="block p-4 border-2 rounded-lg cursor-pointer transition duration-300 package-card">
                                            <div class="flex items-center">
                                                <input type="radio" name="paket" value="{{ $paket['nama'] }}" 
                                                       data-harga-weekday="{{ $paket['harga']['weekday'] }}" 
                                                       data-harga-weekend="{{ $paket['harga']['weekend'] }}" 
                                                       data-fasilitas="{{ json_encode($paket['fasilitas']) }}" 
                                                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300" {{ $index == 0 ? 'checked' : '' }}>
                                                <div class="ml-4 flex-grow">
                                                    <span class="font-bold text-primary">{{ $paket['nama'] }}</span>
                                                    <p class="text-sm text-accent">
                                                        Rp{{ number_format($paket['harga']['weekday'], 0, ',', '.') }} (Weekday) | Rp{{ number_format($paket['harga']['weekend'], 0, ',', '.') }} (Weekend)
                                                    </p>
                                                    <div class="text-xs text-gray-500 mt-1 facility-list">
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Kolom Kanan: Rincian & Tombol Aksi --}}
                <div class="lg:col-span-2">
                    <div class="sticky top-24">
                        <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                            <h3 class="text-xl font-bold text-primary mb-6">Rincian & Konfirmasi</h3>
                            
                            <div class="mb-6">
                                <label for="jumlah" class="text-sm font-medium text-gray-700 mb-2 flex items-center"><i class="fas fa-ticket-alt mr-2 text-primary"></i>Jumlah Tiket / Orang</label>
                                <input type="number" id="jumlah" name="jumlah" min="1" value="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">Harga per Tiket <span id="harga-per-tiket-label" class="font-semibold text-primary"></span>:</span>
                                        <span id="harga-per-tiket" class="font-semibold text-gray-800">Rp0</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xl">
                                        <span class="font-bold text-primary">Total Estimasi:</span>
                                        <span id="total-harga" class="font-bold text-primary">Rp0</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" id="kirim-whatsapp" class="w-full inline-block bg-primary text-white px-12 py-4 rounded-full text-lg font-semibold hover:bg-opacity-90 transform hover:scale-105 transition duration-300 shadow-lg">
                                    <i class="fab fa-whatsapp mr-2"></i> Kirim Reservasi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
{{-- Kode JavaScript Anda sudah benar dan tidak perlu diubah --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tombolKirim = document.getElementById('kirim-whatsapp');
    const inputTanggal = document.getElementById('tanggal');
    const paketContainer = document.getElementById('paket-container');
    const inputJumlah = document.getElementById('jumlah');
    const displayHargaPerTiket = document.getElementById('harga-per-tiket');
    const displayTotalHarga = document.getElementById('total-harga');
    const displayHargaLabel = document.getElementById('harga-per-tiket-label'); 

    const hariIni = new Date().toISOString().split('T')[0];
    inputTanggal.setAttribute('min', hariIni);

    function isWeekend(dateString) {
        if (!dateString) return false;
        const date = new Date(dateString + 'T00:00:00');
        const day = date.getDay();
        return day === 6 || day === 0;
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency', currency: 'IDR', minimumFractionDigits: 0
        }).format(angka);
    }

    function updateCardStyles() {
        const packageCards = document.querySelectorAll('.package-card');
        packageCards.forEach(card => {
            const radio = card.querySelector('input[type="radio"]');
            if (radio.checked) {
                card.classList.add('border-primary', 'bg-blue-50');
            } else {
                card.classList.remove('border-primary', 'bg-blue-50');
            }
        });
    }

    function updateFormDetails() {
        const selectedRadio = document.querySelector('input[name="paket"]:checked');
        if (!selectedRadio) return;

        const tanggalValue = inputTanggal.value;
        const isWeekendDay = isWeekend(tanggalValue);

        const hargaAttribute = isWeekendDay ? 'data-harga-weekend' : 'data-harga-weekday';
        const hargaPerTiket = parseFloat(selectedRadio.getAttribute(hargaAttribute)) || 0;

        displayHargaLabel.textContent = isWeekendDay ? '(Weekend)' : '(Weekday)';

        const jumlahTiket = parseInt(inputJumlah.value) || 0;
        const totalHarga = hargaPerTiket * jumlahTiket;

        displayHargaPerTiket.textContent = formatRupiah(hargaPerTiket);
        displayTotalHarga.textContent = formatRupiah(totalHarga);

        updateCardStyles();
    }

    document.querySelectorAll('.package-card').forEach(card => {
        const radio = card.querySelector('input[type="radio"]');
        const facilityListContainer = card.querySelector('.facility-list');
        const fasilitasJson = radio.getAttribute('data-fasilitas');
        if (fasilitasJson) {
            const fasilitas = JSON.parse(fasilitasJson);
            facilityListContainer.textContent = fasilitas.join(' • ');
        }
    });

    paketContainer.addEventListener('change', updateFormDetails);
    inputJumlah.addEventListener('input', updateFormDetails);
    inputTanggal.addEventListener('change', updateFormDetails);
    
    updateFormDetails();

    tombolKirim.addEventListener('click', function (event) {
        event.preventDefault();
        
        const nomorAdmin = '6281909561200';
        const nama = document.getElementById('nama').value;
        const whatsapp = document.getElementById('whatsapp').value;
        const tanggal = document.getElementById('tanggal').value;
        const paketInput = document.querySelector('input[name="paket"]:checked');
        const jumlah = document.getElementById('jumlah').value;
        
        if (!nama || !whatsapp || !tanggal || !jumlah || !paketInput) {
            alert('Mohon lengkapi semua kolom yang wajib diisi.');
            return;
        }

        const paket = paketInput.value;
        const totalHargaTeks = displayTotalHarga.textContent;
        
        const tanggalObjek = new Date(tanggal + 'T00:00:00');
        const tanggalDiformat = tanggalObjek.toLocaleDateString('id-ID', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        });

        const pesan = `
✨ *-- RESERVASI BARU --* ✨

Halo Admin Goa Sentono, saya ingin melakukan reservasi kunjungan dengan rincian berikut:

👤 *Nama Pemesan:* ${nama}
📱 *No. WhatsApp:* ${whatsapp}
📅 *Tanggal Kunjungan:* ${tanggalDiformat}
📦 *Paket Dipilih:* ${paket}
🎟️ *Jumlah Orang:* ${jumlah} orang

💰 *Total Estimasi Biaya:* ${totalHargaTeks}

Mohon konfirmasi ketersediaannya. Terima kasih!
        `;

        const whatsappUrl = `https://api.whatsapp.com/send?phone=${nomorAdmin}&text=${encodeURIComponent(pesan)}`;
        window.open(whatsappUrl, '_blank');
    });
});
</script>
@endpush