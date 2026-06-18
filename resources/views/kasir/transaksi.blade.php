<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Transaksi Kasir – Kashy</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          terra:      '#C8966C',
          'terra-l':  '#E5B18A',
          'terra-ll': '#F0D7C7',
          'terra-xs': '#FAF2EC',
          muted:      '#9C8B7E',
          border:     '#EAE0D6',
          bg:         '#F5F0EB',
        },
        fontFamily: { poppins: ['Poppins', 'sans-serif'] },
      }
    }
  }
  
</script>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: 'Poppins',sans-serif; background:#f8f8f8; }
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
  input[type=number] { -moz-appearance: textfield; }
  .modal-transition { transition: opacity 0.2s ease, visibility 0.2s ease; }

  .diskon-card { border:2px solid transparent; transition:all .2s; cursor:pointer; }
  .diskon-card:hover:not(.diskon-disabled) { border-color:#C8966C; background:#FAF2EC; }
  .diskon-card.diskon-selected { border-color:#C8966C; background:#FAF2EC; }
  .diskon-card.diskon-disabled { opacity:0.45; cursor:not-allowed; }

  .prod-tag { display:inline-flex; align-items:center; font-size:10px; font-weight:600; padding:2px 8px; border-radius:20px; background:#F0D7C7; color:#7B4F2E; }
  .prod-tag.semua { background:#EDE5DB; color:#5a4a3a; }
  .prod-tag.aktif-untuk { background:#d4f0e0; color:#1a6641; }
  .prod-tag.tidak-relevan { background:#f5f5f5; color:#aaa; }
</style>
</head>

<body class="bg-[#f8f8f8] min-h-screen font-poppins">

<!-- TOPBAR -->
<nav class="sticky top-0 z-50 bg-gray-900 shadow-sm h-12 px-4 flex items-center justify-between relative">
  <button onclick="window.location.href='{{ route('dashboard-kasir') }}'"
    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10 transition">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
      <path d="M19 12H5"/><path d="M12 19L5 12L12 5"/>
    </svg>
  </button>
  <span class="font-bold text-white text-lg tracking-wider absolute left-1/2 -translate-x-1/2">Kashy</span>
  <div class="w-8"></div>
</nav>

<main class="max-w-2xl mx-auto w-full px-4 pt-6 pb-28">

  <!-- MEMBER SECTION -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-bold text-lg text-gray-900">Member</h2>
    </div>
    <div class="flex items-center gap-2 mb-4">
      <div class="flex-1 relative">
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" id="searchMemberInput" class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Cari nama / no HP member...">
      </div>
      <button id="searchButton" class="w-11 h-11 bg-terra hover:bg-terra-l rounded-xl flex items-center justify-center">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </button>
    </div>
    <div id="searchResultsContainer"></div>
  </div>

  <!-- KERANJANG SECTION -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-bold text-xl text-gray-900">Keranjang</h2>
      <span class="text-xs font-medium text-gray-500" id="orderNumber">Order #8821</span>
    </div>

    <div class="flex items-center justify-between mb-5 pb-4 border-b border-gray-100">
      <div class="flex items-center gap-2">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span class="text-sm text-gray-700" id="customerName">Pelanggan Umum</span>
      </div>
      <button onclick="editCustomer()" class="p-1 hover:bg-gray-50 rounded-lg transition">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
      </button>
    </div>

    <div class="space-y-4 mb-5" id="cartItems">
      <div class="text-center text-gray-400 py-4">Belum ada produk, tambah produk dulu yuk!</div>
    </div>

    <button onclick="openAddProductModal()" class="w-full py-3 mb-4 border-2 border-dashed border-terra/40 rounded-xl flex items-center justify-center gap-2 text-sm font-semibold text-terra bg-terra/5 hover:bg-terra/10 transition">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Tambah Produk
    </button>

    <button onclick="openDiscountModal()" id="discountBtn" class="w-full py-3 mb-5 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-between gap-2 text-sm font-medium text-gray-600 hover:border-gray-500 hover:bg-gray-100/50 transition px-4">
      <div class="flex items-center gap-2">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
        <span id="discountBtnLabel">Pilih Diskon</span>
      </div>
      <span id="discountBtnBadge" class="hidden text-xs font-bold px-2 py-0.5 rounded-full bg-terra/10 text-terra"></span>
    </button>

    <!-- SUMMARY -->
    <div class="space-y-3 mb-5">
      <div class="flex items-center justify-between text-sm">
        <span class="text-gray-600">Subtotal</span>
        <span class="font-bold text-gray-900" id="subtotal">Rp 0</span>
      </div>
      <div id="discountRow" class="flex items-center justify-between text-sm hidden">
        <span class="text-gray-600">Diskon <span id="discountPercentLabel" class="text-xs text-green-600"></span></span>
        <span class="font-medium text-green-500" id="discountAmount">Rp 0</span>
      </div>
      <div class="flex items-center justify-between text-lg pt-3 border-t border-gray-100">
        <span class="font-bold text-gray-900">Total</span>
        <span class="font-bold text-gray-900" id="total">Rp 0</span>
      </div>
    </div>

    <button onclick="goToPayment()" class="w-full bg-black hover:bg-gray-900 text-white font-bold py-4 rounded-xl flex items-center justify-center gap-2 transition shadow-lg">
      Lanjut ke Pembayaran
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </button>
  </div>
</main>

<!-- MODAL TAMBAH MEMBER -->
<div id="addMemberModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 modal-transition opacity-0 invisible transition-all">
  <div class="bg-white w-[90%] max-w-sm rounded-2xl p-6 shadow-2xl">
    <div class="flex justify-between items-center mb-4">
      <h3 class="font-bold text-xl text-gray-900">Tambah Member Baru</h3>
      <button onclick="closeAddMemberModal()" class="text-gray-400 hover:text-gray-600">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap</label>
        <input type="text" id="modalMemberName" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Contoh: Ani Wijaya">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor HP</label>
        <input type="tel" id="modalMemberPhone" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="08123456789">
      </div>
      <button onclick="saveNewMemberFromModal()" class="w-full bg-terra hover:bg-terra-l text-white font-bold py-3 rounded-xl transition mt-2">Simpan Member</button>
    </div>
  </div>
</div>

<!-- MODAL TAMBAH PRODUK -->
<div id="addProductModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 modal-transition opacity-0 invisible transition-all p-3">
  <div class="bg-white w-full max-w-[95%] rounded-xl shadow-xl overflow-hidden">
    <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100">
      <h3 class="font-semibold text-base text-gray-900">Tambah Produk</h3>
      <button onclick="closeAddProductModal()" class="text-gray-400 hover:text-gray-600 p-1">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="p-3">
      <div class="relative mb-3">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" id="searchProductInput" class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg text-sm" placeholder="Cari produk...">
      </div>
      <div id="productListContainer" class="space-y-2 max-h-[50vh] overflow-y-auto"></div>
      <div class="text-center text-[11px] text-gray-400 mt-3 pt-2 border-t border-gray-100">Produk tersedia untuk Kasir, Karyawan & Pelanggan</div>
    </div>
  </div>
</div>

<!-- MODAL PILIH DISKON -->
<div id="discountPickerModal" class="fixed inset-0 bg-black/50 flex items-end sm:items-center justify-center z-50 modal-transition opacity-0 invisible transition-all">
  <div class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl overflow-hidden max-h-[80vh] flex flex-col">
    <div class="flex justify-between items-center px-5 py-4 border-b border-gray-100 shrink-0">
      <div>
        <h3 class="font-bold text-lg text-gray-900">Pilih Diskon</h3>
        <p class="text-xs text-gray-400 mt-0.5" id="discountPickerSubtitle">Diskon aktif yang cocok dengan keranjang</p>
      </div>
      <button onclick="closeDiscountPickerModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 transition">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="overflow-y-auto flex-1 p-4 space-y-3" id="discountPickerList"></div>
    <div class="px-5 py-4 border-t border-gray-100 shrink-0 flex gap-3">
      <button onclick="clearSelectedDiscount()" class="flex-1 py-3 rounded-xl border-2 border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">Hapus Diskon</button>
      <button onclick="applySelectedDiscount()" class="flex-1 py-3 rounded-xl bg-black text-white text-sm font-semibold hover:bg-gray-900 transition">Terapkan</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div id="toast" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 bg-gray-900 text-white text-sm font-medium px-5 py-3 rounded-full shadow-xl flex items-center gap-2 pointer-events-none transition-all duration-300 opacity-0 translate-y-4">
  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
  <span id="toastMsg">—</span>
</div>

<script>
  @php
    $productsForJs = $products->map(function ($product) {
        return [
            'id'          => $product->id,
            'name'        => $product->nama_produk,
            'price'       => (int) $product->harga,
            'img'         => $product->gambar ? asset($product->gambar) : 'https://via.placeholder.com/40',
            'stok'        => $product->stok,
            'category_id' => (int) $product->category_id,
        ];
    })->values();

    $membersForJs = $members->map(function ($member) {
        return [
            'id'    => $member->id,
            'name'  => $member->nama,
            'phone' => $member->no_hp,
        ];
    })->values();

    $activeDiscountsForJs = $activeDiscounts->map(function($d) {
        $categoryIds   = array_map('intval', $d->categories->pluck('id')->toArray());
        $categoryNames = $d->categories->pluck('nama_kategori')->toArray();

        return [
            'id'             => $d->id,
            'nama_promosi'   => $d->nama_promosi ?? '',
            'tipe_diskon'    => $d->tipe_diskon,
            'nilai_diskon'   => (float) $d->nilai_diskon,
            'semua_produk'   => (bool) $d->semua_produk,
            'category_ids'   => $categoryIds,
            'category_names' => $categoryNames,
            'tanggal_selesai'=> $d->tanggal_selesai ? $d->tanggal_selesai->format('Y-m-d') : '',
        ];
    })->values();
  @endphp

  const productsDatabase = {!! json_encode($productsForJs) !!};
  let members            = {!! json_encode($membersForJs) !!};
  const activeDiscounts  = {!! json_encode($activeDiscountsForJs) !!};

  let currentSelectedMember  = null;
  let cart                   = [];
  let selectedDiscountId     = null;
  let tempSelectedDiscountId = null;

  // ── Utils ──
  function formatRupiah(angka) { return 'Rp ' + angka.toLocaleString('id-ID'); }
  function showToast(msg, isError = false) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    toast.style.backgroundColor = isError ? '#dc2626' : '#1f2937';
    toast.style.opacity = '1';
    toast.style.transform = 'translateX(-50%) translateY(0)';
    setTimeout(() => { toast.style.opacity='0'; toast.style.transform='translateX(-50%) translateY(16px)'; }, 2600);
  }
  function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, m => m === '&' ? '&amp;' : m === '<' ? '&lt;' : '&gt;');
  }

  function isDiscountRelevant(discount) {
    if (discount.semua_produk) return true;
    if (cart.length === 0) return false;
    const result = cart.some(item =>
      discount.category_ids.map(Number).includes(Number(item.category_id))
    );
    return result;
  }

  // ── Member ──
  function showDefaultMemberCard() {
    document.getElementById('searchResultsContainer').innerHTML = `
      <div class="text-center py-3">
        <p class="text-sm text-gray-500 mb-3">Tambah member baru untuk mendapatkan poin & promo</p>
        <button onclick="openAddMemberModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-terra hover:bg-terra-l text-white font-semibold rounded-xl transition shadow-sm text-sm">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Tambah Member Baru
        </button>
      </div>`;
  }

  function performSearch() {
    const query = document.getElementById('searchMemberInput').value.trim();
    const resultsContainer = document.getElementById('searchResultsContainer');
    if (query === '') { showDefaultMemberCard(); return; }
    const lowerQuery = query.toLowerCase();
    const filtered = members.filter(m => m.name.toLowerCase().includes(lowerQuery) || m.phone.includes(query));
    if (!filtered.length) {
      resultsContainer.innerHTML = `
        <div class="text-center py-3">
          <p class="text-sm text-gray-500 mb-3">Member "${escapeHtml(query)}" tidak ditemukan</p>
          <button onclick="openAddMemberModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-terra hover:bg-terra-l text-white font-semibold rounded-xl transition shadow-sm text-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Member Baru
          </button>
        </div>`;
      return;
    }
    let html = `<div class="space-y-3"><p class="text-xs font-medium text-gray-500 mb-1">📋 ${filtered.length} member ditemukan:</p>`;
    filtered.forEach(member => {
      html += `<div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
        <div class="flex-1">
          <p class="font-semibold text-gray-900 text-sm">${escapeHtml(member.name)}</p>
          <p class="text-xs text-gray-500">${escapeHtml(member.phone)}</p>
        </div>
        <button onclick="selectMember(${member.id})" class="px-4 py-2 bg-terra/10 hover:bg-terra/20 text-terra font-semibold text-sm rounded-lg">Pilih</button>
      </div>`;
    });
    html += `</div>`;
    resultsContainer.innerHTML = html;
  }

  function selectMember(memberId) {
    const member = members.find(m => m.id === memberId);
    if (member) {
      currentSelectedMember = member;
      document.getElementById('customerName').textContent = member.name;
      showToast(`✅ Member ${member.name} dipilih`);
      document.getElementById('searchMemberInput').value = '';
      showDefaultMemberCard();
    }
  }

  function openAddMemberModal() {
    const modal = document.getElementById('addMemberModal');
    document.getElementById('modalMemberName').value = '';
    document.getElementById('modalMemberPhone').value = '';
    modal.classList.remove('invisible', 'opacity-0');
    modal.classList.add('opacity-100', 'visible');
  }
  function closeAddMemberModal() {
    const modal = document.getElementById('addMemberModal');
    modal.classList.remove('opacity-100', 'visible');
    modal.classList.add('invisible', 'opacity-0');
  }

  function saveNewMemberFromModal() {
    const name  = document.getElementById('modalMemberName').value.trim();
    const phone = document.getElementById('modalMemberPhone').value.trim();
    if (!name || !phone) { showToast('⚠️ Nama dan nomor HP wajib diisi!', true); return; }
    if (members.find(m => m.phone === phone)) { showToast(`Nomor ${phone} sudah terdaftar`, true); return; }
    fetch("{{ route('kasir.member.store') }}", {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify({ nama: name, no_hp: phone })
    })
    .then(async res => {
      const data = await res.json().catch(() => null);
      if (!res.ok) {
        if (res.status === 422 && data?.errors) showToast(Object.values(data.errors)[0][0], true);
        else showToast('Gagal menambahkan member', true);
        return null;
      }
      return data;
    })
    .then(data => {
      if (!data || !data.success) return;
      const newMember = { id: data.member.id, name: data.member.nama, phone: data.member.no_hp };
      members.push(newMember);
      currentSelectedMember = newMember;
      document.getElementById('customerName').textContent = newMember.name;
      showToast(`🎉 Member ${newMember.name} berhasil ditambahkan & dipilih`);
      closeAddMemberModal();
      document.getElementById('searchMemberInput').value = '';
      showDefaultMemberCard();
    })
    .catch(() => showToast('Terjadi kesalahan server', true));
  }

  function editCustomer() {
    const newName = prompt('Edit nama pelanggan:', document.getElementById('customerName').textContent);
    if (newName?.trim()) {
      document.getElementById('customerName').textContent = newName.trim();
      currentSelectedMember = null;
      showToast('Nama pelanggan diperbarui');
    }
  }

  // ── Keranjang ──
  function renderCart() {
    const container = document.getElementById('cartItems');
    if (!cart.length) {
      container.innerHTML = `<div class="text-center text-gray-400 py-4">Belum ada produk, Tambah Produk Dulu Yuk!</div>`;
      calculateTotal();
      return;
    }
    let html = '';
    cart.forEach((item, index) => {
      const product = productsDatabase.find(p => p.id === item.id);
      const stokSisa = product ? product.stok : 0;
      const maxReached = item.qty >= stokSisa;
      const minReached = item.qty <= 1; // Tambahkan kondisi ini

      html += `
        <div class="flex items-start gap-3">
          <button onclick="removeCartItem(${index})" class="mt-2 text-gray-400 hover:text-red-500 transition" title="Hapus produk">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
          </button>
          <img src="${item.img}" alt="${item.name}" class="w-16 h-16 rounded-xl object-cover border border-gray-100">
          <div class="flex-1">
            <h4 class="font-medium text-sm text-gray-900 mb-1">${escapeHtml(item.name)}</h4>
            ${maxReached ? `<p class="text-[10px] text-red-500 font-medium mb-1">⚠️ Maks stok: ${stokSisa}</p>` : ''}
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <!-- TOMBOL MINUS - DIUBAH -->
                <button onclick="changeQty(${index}, -1)" 
                  class="w-7 h-7 flex items-center justify-center rounded-lg font-bold transition
                  ${minReached || stokSisa === 0 
                    ? 'bg-gray-50 text-gray-300 cursor-not-allowed' 
                    : 'bg-gray-100 hover:bg-gray-200 text-gray-700'}">
                  -
                </button>
                <span class="w-8 text-center font-semibold text-sm">${item.qty}</span>
                <!-- TOMBOL PLUS - TETAP SAMA -->
                <button onclick="changeQty(${index}, 1)" 
                  class="w-7 h-7 flex items-center justify-center rounded-lg font-bold transition
                  ${maxReached || stokSisa === 0 
                    ? 'bg-gray-50 text-gray-300 cursor-not-allowed' 
                    : 'bg-gray-100 hover:bg-gray-200 text-gray-700'}">
                  +
                </button>
              </div>
              <span class="font-bold text-sm text-gray-900">${formatRupiah(item.price * item.qty)}</span>
            </div>
          </div>
        </div>`;
    });
    container.innerHTML = html;

    if (selectedDiscountId !== null) {
      const disc = activeDiscounts.find(d => d.id === selectedDiscountId);
      if (disc && !isDiscountRelevant(disc)) {
        selectedDiscountId = null;
        updateDiscountButton();
        showToast('⚠️ Diskon dihapus karena kategori produk tidak sesuai', true);
      }
    }

    calculateTotal();
  }

  // ── REVISI: changeQty dengan validasi stok ──
  function changeQty(index, delta) {
    if (cart[index]) {
      let newQty = cart[index].qty + delta;
      if (newQty < 1) newQty = 1;

      if (delta > 0) {
        const product = productsDatabase.find(p => p.id === cart[index].id);
        if (product && newQty > product.stok) {
          showToast(`⚠️ Stok ${cart[index].name} tidak cukup! Sisa: ${product.stok}`, true);
          return;
        }
      }

      cart[index].qty = newQty;
      renderCart();
    }
  }

  function removeCartItem(index) {
    cart.splice(index, 1);
    renderCart();
    showToast('Item dihapus dari keranjang');
  }

  function calculateTotal() {
    const disc = selectedDiscountId !== null ? activeDiscounts.find(d => d.id === selectedDiscountId) : null;
    let subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    let discountAmount = 0;
    let discountLabel  = '';

    if (disc) {
      if (disc.semua_produk) {
        if (disc.tipe_diskon === 'persen') {
          discountAmount = subtotal * (disc.nilai_diskon / 100);
          discountLabel  = `(${disc.nilai_diskon}%)`;
        } else {
          discountAmount = disc.nilai_diskon * cart.reduce((sum, i) => sum + i.qty, 0);
          discountLabel  = `(Rp ${parseInt(disc.nilai_diskon).toLocaleString('id-ID')}/item)`;
        }
      } else {
        cart.forEach(item => {
          if (disc.category_ids.map(Number).includes(Number(item.category_id))) {
            if (disc.tipe_diskon === 'persen') {
              discountAmount += (item.price * item.qty) * (disc.nilai_diskon / 100);
            } else {
              discountAmount += disc.nilai_diskon * item.qty;
            }
          }
        });
        discountLabel = disc.tipe_diskon === 'persen'
          ? `(${disc.nilai_diskon}%)`
          : `(Rp ${parseInt(disc.nilai_diskon).toLocaleString('id-ID')}/item)`;
      }
    }

    const total = subtotal - discountAmount;
    document.getElementById('subtotal').textContent = formatRupiah(subtotal);
    document.getElementById('total').textContent    = formatRupiah(Math.round(total));

    if (discountAmount > 0) {
      document.getElementById('discountRow').classList.remove('hidden');
      document.getElementById('discountPercentLabel').textContent = discountLabel;
      document.getElementById('discountAmount').textContent = '-' + formatRupiah(Math.round(discountAmount));
    } else {
      document.getElementById('discountRow').classList.add('hidden');
    }
  }

  // ── REVISI: addProductToCart dengan validasi stok ──
  function addProductToCart(product) {
    // Cek stok habis
    if (product.stok === 0) {
      showToast(`⚠️ Stok ${product.name} habis!`, true);
      return;
    }

    const existing = cart.find(item => item.id === product.id);

    // Cek stok tidak cukup untuk tambah lagi
    if (existing && existing.qty >= product.stok) {
      showToast(`⚠️ Stok ${product.name} tidak cukup! Sisa: ${product.stok}`, true);
      return;
    }

    if (existing) existing.qty += 1;
    else cart.push({ ...product, qty: 1 });
    renderCart();
    showToast(`${product.name} ditambahkan ke keranjang`);
  }

  // ── Produk Modal ──
  function renderProductList(searchTerm = '') {
    const container = document.getElementById('productListContainer');
    const lowerTerm = searchTerm.toLowerCase().trim();
    let filtered    = productsDatabase;
    if (lowerTerm) filtered = productsDatabase.filter(p => p.name.toLowerCase().includes(lowerTerm));
    if (!filtered.length) { container.innerHTML = `<div class="text-center text-gray-400 py-4 text-sm">Produk Tidak Ditemukan</div>`; return; }
    let html = '';
    filtered.forEach(prod => {
      const stokHabis = prod.stok === 0;
      html += `<div class="product-item flex justify-between items-center p-3 border border-gray-100 rounded-xl ${stokHabis ? 'opacity-50' : 'hover:bg-terra/5'}">
        <div class="flex items-center gap-3">
          <img src="${prod.img}" class="w-10 h-10 rounded-lg object-cover">
          <div>
            <p class="font-semibold text-gray-900">${escapeHtml(prod.name)}</p>
            <p class="text-xs text-gray-500">${formatRupiah(prod.price)}</p>
            <p class="text-[10px] ${stokHabis ? 'text-red-500 font-semibold' : 'text-gray-400'}">Stok: ${prod.stok}${stokHabis ? ' (Habis)' : ''}</p>
          </div>
        </div>
        ${stokHabis
          ? `<span class="px-3 py-1 bg-gray-100 text-gray-400 text-sm rounded-lg font-medium">Habis</span>`
          : `<button onclick="addProductFromModal(${prod.id})" class="px-3 py-1 bg-terra text-white text-sm rounded-lg">Tambah</button>`
        }
      </div>`;
    });
    container.innerHTML = html;
  }

  function addProductFromModal(productId) {
    const product = productsDatabase.find(p => p.id === productId);
    if (product) { addProductToCart(product); closeAddProductModal(); }
  }

  let searchTimeout;
  function setupProductSearch() {
    const input = document.getElementById('searchProductInput');
    if (input) input.addEventListener('input', e => { clearTimeout(searchTimeout); searchTimeout = setTimeout(() => renderProductList(e.target.value), 300); });
  }

  function openAddProductModal() {
    const modal       = document.getElementById('addProductModal');
    const searchInput = document.getElementById('searchProductInput');
    if (searchInput) searchInput.value = '';
    renderProductList('');
    modal.classList.remove('invisible', 'opacity-0');
    modal.classList.add('opacity-100', 'visible');
  }
  function closeAddProductModal() {
    const modal = document.getElementById('addProductModal');
    modal.classList.remove('opacity-100', 'visible');
    modal.classList.add('invisible', 'opacity-0');
  }

  // ── DISKON PICKER MODAL ──
  function openDiscountModal() {
    const modal = document.getElementById('discountPickerModal');
    tempSelectedDiscountId = selectedDiscountId;
    renderDiscountPickerList();
    modal.classList.remove('invisible', 'opacity-0');
    modal.classList.add('opacity-100', 'visible');
  }
  function closeDiscountPickerModal() {
    const modal = document.getElementById('discountPickerModal');
    modal.classList.remove('opacity-100', 'visible');
    modal.classList.add('invisible', 'opacity-0');
  }

  function renderDiscountPickerList() {
    const container = document.getElementById('discountPickerList');

    if (!activeDiscounts.length) {
      container.innerHTML = `<div class="text-center text-gray-400 py-8 text-sm">Belum ada diskon aktif.<br><span class="text-xs">Buat diskon di menu Manajemen Diskon.</span></div>`;
      return;
    }

    let html = '';
    activeDiscounts.forEach(d => {
      const relevant  = isDiscountRelevant(d);
      const selected  = tempSelectedDiscountId === d.id;
      const tipeLabel = d.tipe_diskon === 'persen' ? `${d.nilai_diskon}%` : `Rp ${parseInt(d.nilai_diskon).toLocaleString('id-ID')}`;
      const selesai   = d.tanggal_selesai ? new Date(d.tanggal_selesai).toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'}) : '-';

      let catTags = '';
      if (d.semua_produk) {
        catTags = `<span class="prod-tag semua">Semua Produk</span>`;
      } else {
        catTags = (d.category_names || []).map((name, i) => {
          const catId  = d.category_ids[i];
          const inCart = cart.some(item => Number(item.category_id) === Number(catId));
          return `<span class="prod-tag ${inCart ? 'aktif-untuk' : 'tidak-relevan'}">${escapeHtml(name)}</span>`;
        }).join('');
      }

      html += `
        <div class="diskon-card rounded-xl p-4 bg-gray-50 border ${selected ? 'diskon-selected' : ''} ${!relevant ? 'diskon-disabled' : ''}"
          onclick="${relevant ? `selectTempDiscount(${d.id})` : ''}">
          <div class="flex items-start justify-between gap-3">
            <div class="flex-1">
              ${d.nama_promosi ? `<p class="text-xs font-semibold text-gray-500 mb-1 italic">${escapeHtml(d.nama_promosi)}</p>` : ''}
              <div class="flex items-center gap-2 mb-2">
                <span class="text-lg font-extrabold text-gray-900">${tipeLabel}</span>
                <span class="text-xs font-medium text-gray-500">${d.tipe_diskon === 'persen' ? 'Persentase' : 'Nominal'}</span>
              </div>
              <div class="flex flex-wrap gap-1 mb-2">${catTags}</div>
              <p class="text-[11px] text-gray-400">Berakhir ${selesai}</p>
            </div>
            <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center shrink-0 mt-0.5 ${selected ? 'border-terra bg-terra' : 'border-gray-300'}">
              ${selected ? '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>' : ''}
            </div>
          </div>
          ${!relevant && cart.length > 0 ? `<p class="text-[11px] text-red-400 mt-2 font-medium">⚠️ Tidak ada produk di keranjang yang masuk kategori ini</p>` : ''}
          ${!relevant && cart.length === 0 ? `<p class="text-[11px] text-gray-400 mt-2">Tambah produk ke keranjang dulu</p>` : ''}
        </div>`;
    });

    container.innerHTML = html;
    document.getElementById('discountPickerSubtitle').textContent = cart.length === 0
      ? 'Tambah produk ke keranjang untuk melihat diskon yang berlaku'
      : 'Tag hijau = kategori produk ada di keranjangmu';
  }

  function selectTempDiscount(id) {
    tempSelectedDiscountId = tempSelectedDiscountId === id ? null : id;
    renderDiscountPickerList();
  }

  function applySelectedDiscount() {
    selectedDiscountId = tempSelectedDiscountId;
    updateDiscountButton();
    calculateTotal();
    closeDiscountPickerModal();
    if (selectedDiscountId !== null) {
      const disc  = activeDiscounts.find(d => d.id === selectedDiscountId);
      const label = disc.tipe_diskon === 'persen' ? `${disc.nilai_diskon}%` : `Rp ${parseInt(disc.nilai_diskon).toLocaleString('id-ID')}`;
      showToast(`✅ Diskon ${label} diterapkan`);
    } else {
      showToast('Diskon dihapus');
    }
  }

  function clearSelectedDiscount() {
    tempSelectedDiscountId = null;
    selectedDiscountId     = null;
    updateDiscountButton();
    calculateTotal();
    closeDiscountPickerModal();
    showToast('Diskon dihapus');
  }

  function updateDiscountButton() {
    const btn   = document.getElementById('discountBtn');
    const label = document.getElementById('discountBtnLabel');
    const badge = document.getElementById('discountBtnBadge');
    if (selectedDiscountId !== null) {
      const disc = activeDiscounts.find(d => d.id === selectedDiscountId);
      if (disc) {
        const val = disc.tipe_diskon === 'persen' ? `${disc.nilai_diskon}%` : `Rp ${parseInt(disc.nilai_diskon).toLocaleString('id-ID')}`;
        label.textContent = disc.nama_promosi || 'Diskon Terpilih';
        badge.textContent = val;
        badge.classList.remove('hidden');
        btn.classList.add('border-terra/40', 'bg-terra/5');
        btn.classList.remove('border-gray-300');
        return;
      }
    }
    label.textContent = 'Pilih Diskon';
    badge.classList.add('hidden');
    btn.classList.remove('border-terra/40', 'bg-terra/5');
    btn.classList.add('border-gray-300');
  }

  // ── Go To Payment ──
  function goToPayment() {
    if (!cart.length) { showToast('⚠️ Keranjang masih kosong!', true); return; }

    const disc = selectedDiscountId !== null ? activeDiscounts.find(d => d.id === selectedDiscountId) : null;
    let subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    let discountAmount = 0;

    if (disc) {
      if (disc.semua_produk) {
        discountAmount = disc.tipe_diskon === 'persen'
          ? subtotal * (disc.nilai_diskon / 100)
          : disc.nilai_diskon * cart.reduce((s, i) => s + i.qty, 0);
      } else {
        cart.forEach(item => {
          if (disc.category_ids.map(Number).includes(Number(item.category_id))) {
            discountAmount += disc.tipe_diskon === 'persen'
              ? (item.price * item.qty) * (disc.nilai_diskon / 100)
              : disc.nilai_diskon * item.qty;
          }
        });
      }
    }

    const total = Math.round(subtotal - discountAmount);

    fetch("{{ route('kasir.transaksi.session') }}", {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify({
        cart,
        customer_name:   document.getElementById('customerName').textContent,
        member_id:       currentSelectedMember ? currentSelectedMember.id : null,
        discount_id:     selectedDiscountId,
        discount_amount: Math.round(discountAmount),
        subtotal,
        total
      })
    })
    .then(async res => {
      const data = await res.json().catch(() => null);
      if (!res.ok || !data?.success) { showToast('Gagal lanjut ke pembayaran', true); return; }
      window.location.href = data.redirect;
      setTimeout(() => { window.location.href = "{{ route('dashboard-kasir') }}"; }, 3000);
    })
    .catch(() => showToast('Gagal lanjut ke pembayaran', true));
  }

  function generateOrderNumber() {
    const num = Math.floor(Math.random() * 9000) + 1000;
    document.getElementById('orderNumber').textContent = 'Order #' + num;
  }

  function init() {
    generateOrderNumber();
    document.getElementById('customerName').textContent = 'Pelanggan Umum';
    cart = [];
    renderCart();
    showDefaultMemberCard();

    document.getElementById('searchButton').addEventListener('click', performSearch);
    document.getElementById('searchMemberInput').addEventListener('keypress', e => { if (e.key === 'Enter') performSearch(); });
    document.getElementById('searchMemberInput').addEventListener('input', e => { if (e.target.value.trim() === '') showDefaultMemberCard(); else performSearch(); });

    setupProductSearch();

    document.getElementById('addProductModal').addEventListener('click', e => { if (e.target === e.currentTarget) closeAddProductModal(); });
    document.getElementById('addMemberModal').addEventListener('click', e => { if (e.target === e.currentTarget) closeAddMemberModal(); });
    document.getElementById('discountPickerModal').addEventListener('click', e => { if (e.target === e.currentTarget) closeDiscountPickerModal(); });
  }
  init();
</script>
</body>
</html>