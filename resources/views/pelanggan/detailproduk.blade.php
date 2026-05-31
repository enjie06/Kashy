<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Produk | SND STORE</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          terra: '#C8966C', 'terra-l': '#E5B18A', 'terra-ll': '#F0D7C7',
          'terra-xs': '#FAF2EC', muted: '#9C8B7E', border: '#EAE0D6', bg: '#F5F0EB',
        },
        fontFamily: { display: ['Poppins','serif'], body: ['Poppins','sans-serif'] },
      }
    }
  }
</script>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Poppins', sans-serif; }
  .fade-up { animation: fadeUp 0.4s ease; }
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .bn-item.active .bn-icon { background: #F0D7C7; }
  .bn-item.active .bn-icon svg { stroke: #C8966C; }
  .bn-item.active .bn-label { color: #C8966C; font-weight: 600; }

  #hamburger-menu {
  display: none;
  position: absolute;
  top: calc(100% + 8px);
  right: 0;
  background: #1f2937;
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 14px;
  overflow: hidden;
  min-width: 170px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.3);
  z-index: 200;
}

#hamburger-menu.open {
  display: block;
  animation: fadeUp .2s ease;
}

.hb-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 16px;
  color: #d1d5db;
  font-size: 13px;
  font-weight: 500;
  text-decoration: none;
  border-bottom: 1px solid rgba(255,255,255,0.06);
}

.hb-item:last-child {
  border-bottom: none;
}

.hb-item:hover {
  background: rgba(200,150,108,0.15);
  color: #C8966C;
}
</style>
</head>
<body class="bg-bg min-h-screen flex flex-col">

<!-- ════ TOPBAR ════ -->
<nav class="sticky top-0 z-50 bg-gray-900 px-4 py-3 flex items-center justify-between shadow-md relative">
<a href="javascript:history.back()"
   class="w-11 h-11 flex items-center justify-center rounded-xl text-white bg-white/10 hover:bg-white/20 active:scale-95 transition-all">
  <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2.7" viewBox="0 0 24 24">
    <path d="M19 12H5m7-7-7 7 7 7"/>
  </svg>
</a>

  <div class="absolute left-1/2 -translate-x-1/2 text-center">
    <span class="text-lg sm:text-xl font-bold text-white">SND STORE</span>
    <p class="text-[10px] text-gray-400 leading-none mt-0.5" id="pageSubtitle">Detail Produk</p>
  </div>

  <div class="relative">
    <button id="hamburger-btn" onclick="toggleMenu()" class="flex flex-col gap-1.5 p-2 rounded-lg hover:bg-white/10">
      <span class="block w-6 h-0.5 bg-white rounded"></span>
      <span class="block w-6 h-0.5 bg-white rounded"></span>
      <span class="block w-6 h-0.5 bg-white rounded"></span>
    </button>

    <div id="hamburger-menu">
      <a href="{{ route('katalog') }}" class="hb-item">
        Beranda
      </a>

      <a href="{{ route('daftar-produk') }}" class="hb-item">
        Daftar Produk
      </a>
    </div>
  </div>
</nav>

<!-- KONTEN UTAMA -->
<div class="flex-1 pb-6">
  <div class="max-w-4xl mx-auto px-4 md:px-8 py-5">
    <div id="detailContainer" class="flex flex-col lg:flex-row gap-8 items-start">
      <!-- akan diisi JavaScript -->
    </div>
  </div>
</div>

<script>
  function toggleMenu() {
  document.getElementById('hamburger-menu').classList.toggle('open');
}

document.addEventListener('click', function(e) {
  const btn  = document.getElementById('hamburger-btn');
  const menu = document.getElementById('hamburger-menu');

  if (!btn.contains(e.target) && !menu.contains(e.target)) {
    menu.classList.remove('open');
  }
});

const BASE = [
  { name:'Kulot Modis',         cat:'celana',   price:'Rp 76.000',  old:'',           badge:'new',     stok:12, bg:'linear-gradient(135deg,#e8ddd4,#d4c5b4)' },
  { name:'Kemeja Cokelat',      cat:'kemeja',   price:'Rp 52.000',  old:'',           badge:'',        stok:0,  bg:'linear-gradient(135deg,#d4c9bc,#c4b8a8)' },
  { name:'Rok Putih Layer',     cat:'rok',      price:'Rp 70.000',  old:'',           badge:'new',     stok:8,  bg:'linear-gradient(135deg,#ede0d4,#ddd0c4)' },
  { name:'Cardigan Rajut',      cat:'cardigan', price:'Rp 47.000',  old:'',           badge:'',        stok:5,  bg:'linear-gradient(135deg,#e0d4c9,#d0c4b9)' },
  { name:'Kemeja Flannel',      cat:'kemeja',   price:'Rp 85.000',  old:'',           badge:'branded', stok:3,  bg:'linear-gradient(135deg,#d9cfc4,#c9bfb4)' },
  { name:'Celana Cargo',        cat:'celana',   price:'Rp 120.000', old:'',           badge:'branded', stok:7,  bg:'linear-gradient(135deg,#cfc4b9,#bfb4a9)' },
  { name:'Jaket Denim',         cat:'jaket',    price:'Rp 150.000', old:'',           badge:'',        stok:4,  bg:'linear-gradient(135deg,#c9cfe0,#b9c2d4)' },
  { name:'Rok Mini Plaid',      cat:'rok',      price:'Rp 95.000',  old:'Rp 120.000', badge:'sale',    stok:6,  bg:'linear-gradient(135deg,#e0c9c9,#d0b9b9)' },
  { name:'Kemeja Putih',        cat:'kemeja',   price:'Rp 65.000',  old:'',           badge:'new',     stok:10, bg:'linear-gradient(135deg,#eee8e0,#ddd8d0)' },
  { name:'Celana Jeans Slim',   cat:'celana',   price:'Rp 135.000', old:'Rp 160.000', badge:'sale',    stok:2,  bg:'linear-gradient(135deg,#c5cdd8,#b5bdc8)' },
  { name:'Jaket Bomber',        cat:'jaket',    price:'Rp 185.000', old:'',           badge:'branded', stok:0,  bg:'linear-gradient(135deg,#c8c8c8,#b8b8b8)' },
  { name:'Rok Lipit Krem',      cat:'rok',      price:'Rp 78.000',  old:'',           badge:'',        stok:9,  bg:'linear-gradient(135deg,#ede5d8,#ddd5c8)' },
  { name:'Cardigan Krem',       cat:'cardigan', price:'Rp 95.000',  old:'Rp 120.000', badge:'sale',    stok:5,  bg:'linear-gradient(135deg,#e5ddd0,#d5cdc0)' },
  { name:'Kemeja Stripe',       cat:'kemeja',   price:'Rp 75.000',  old:'',           badge:'',        stok:0,  bg:'linear-gradient(135deg,#d8e0e8,#c8d0d8)' },
  { name:'Celana Kulot',        cat:'celana',   price:'Rp 90.000',  old:'',           badge:'new',     stok:14, bg:'linear-gradient(135deg,#e0d8d0,#d0c8c0)' },
  { name:'Jaket Kulit Sintetis',cat:'jaket',    price:'Rp 220.000', old:'',           badge:'branded', stok:3,  bg:'linear-gradient(135deg,#c0c0c0,#b0b0b0)' },
  { name:'Rok A-Line',          cat:'rok',      price:'Rp 82.000',  old:'',           badge:'',        stok:0,  bg:'linear-gradient(135deg,#e8d8d0,#d8c8c0)' },
  { name:'Blouse Renda',        cat:'kemeja',   price:'Rp 68.000',  old:'',           badge:'new',     stok:6,  bg:'linear-gradient(135deg,#f0e8e0,#e0d8d0)' },
  { name:'Cardigan Knit',       cat:'cardigan', price:'Rp 110.000', old:'',           badge:'',        stok:0,  bg:'linear-gradient(135deg,#d8d0c8,#c8c0b8)' },
  { name:'Celana Pipa',         cat:'celana',   price:'Rp 105.000', old:'Rp 130.000', badge:'sale',    stok:4,  bg:'linear-gradient(135deg,#d8c8b8,#c8b8a8)' },
  { name:'Jaket Corduroy',      cat:'jaket',    price:'Rp 165.000', old:'',           badge:'',        stok:7,  bg:'linear-gradient(135deg,#c8b8a8,#b8a898)' },
  { name:'Rok Wrap',            cat:'rok',      price:'Rp 88.000',  old:'',           badge:'',        stok:11, bg:'linear-gradient(135deg,#e0ccc0,#d0bcb0)' },
  { name:'Kemeja Linen',        cat:'kemeja',   price:'Rp 92.000',  old:'',           badge:'',        stok:0,  bg:'linear-gradient(135deg,#e8e0d0,#d8d0c0)' },
  { name:'Celana Palazzo',      cat:'celana',   price:'Rp 115.000', old:'',           badge:'new',     stok:8,  bg:'linear-gradient(135deg,#d0c8c0,#c0b8b0)' },
  { name:'Blouse Pita',         cat:'kemeja',   price:'Rp 58.000',  old:'',           badge:'',        stok:5,  bg:'linear-gradient(135deg,#eedde8,#decdd8)' },
  { name:'Rok Tutu Mini',       cat:'rok',      price:'Rp 72.000',  old:'Rp 90.000',  badge:'sale',    stok:3,  bg:'linear-gradient(135deg,#e8d8e8,#d8c8d8)' },
  { name:'Cardigan Oversize',   cat:'cardigan', price:'Rp 130.000', old:'',           badge:'branded', stok:6,  bg:'linear-gradient(135deg,#d8d0c8,#c8c0b8)' },
  { name:'Celana Chino',        cat:'celana',   price:'Rp 98.000',  old:'',           badge:'',        stok:9,  bg:'linear-gradient(135deg,#d4ccc0,#c4bcb0)' },
  { name:'Jaket Hoodie',        cat:'jaket',    price:'Rp 175.000', old:'',           badge:'new',     stok:0,  bg:'linear-gradient(135deg,#c8d0d8,#b8c0c8)' },
  { name:'Rok Floral',          cat:'rok',      price:'Rp 85.000',  old:'',           badge:'',        stok:12, bg:'linear-gradient(135deg,#e8d0d0,#d8c0c0)' },
  { name:'Kemeja Motif',        cat:'kemeja',   price:'Rp 79.000',  old:'',           badge:'',        stok:4,  bg:'linear-gradient(135deg,#d8c8d0,#c8b8c0)' },
  { name:'Celana Linen',        cat:'celana',   price:'Rp 108.000', old:'',           badge:'',        stok:7,  bg:'linear-gradient(135deg,#dcd4c8,#ccc4b8)' },
  { name:'Cardigan V-Neck',     cat:'cardigan', price:'Rp 88.000',  old:'Rp 110.000', badge:'sale',    stok:2,  bg:'linear-gradient(135deg,#e4dcd0,#d4ccc0)' },
  { name:'Jaket Fleece',        cat:'jaket',    price:'Rp 140.000', old:'',           badge:'',        stok:0,  bg:'linear-gradient(135deg,#c8d4dc,#b8c4cc)' },
  { name:'Rok Midi',            cat:'rok',      price:'Rp 92.000',  old:'',           badge:'new',     stok:5,  bg:'linear-gradient(135deg,#e4d4cc,#d4c4bc)' },
  { name:'Kemeja Oversized',    cat:'kemeja',   price:'Rp 88.000',  old:'',           badge:'',        stok:8,  bg:'linear-gradient(135deg,#dcd8d0,#ccc8c0)' },
  { name:'Celana Rok Slit',     cat:'celana',   price:'Rp 125.000', old:'',           badge:'branded', stok:0,  bg:'linear-gradient(135deg,#c8c4bc,#b8b4ac)' },
  { name:'Jaket Rain Coat',     cat:'jaket',    price:'Rp 195.000', old:'Rp 240.000', badge:'sale',    stok:3,  bg:'linear-gradient(135deg,#c0c8d0,#b0b8c0)' },
  { name:'Blouse Puff Sleeve',  cat:'kemeja',   price:'Rp 72.000',  old:'',           badge:'new',     stok:6,  bg:'linear-gradient(135deg,#eae0d8,#dad0c8)' },
  { name:'Rok Asimetris',       cat:'rok',      price:'Rp 98.000',  old:'',           badge:'',        stok:4,  bg:'linear-gradient(135deg,#e0d4cc,#d0c4bc)' },
  { name:'Cardigan Panjang',    cat:'cardigan', price:'Rp 145.000', old:'',           badge:'branded', stok:0,  bg:'linear-gradient(135deg,#dcd0c4,#ccc0b4)' },
  { name:'Celana Mom Jeans',    cat:'celana',   price:'Rp 130.000', old:'',           badge:'',        stok:9,  bg:'linear-gradient(135deg,#c4ccd8,#b4bcc8)' },
  { name:'Jaket Trucker',       cat:'jaket',    price:'Rp 160.000', old:'',           badge:'',        stok:0,  bg:'linear-gradient(135deg,#c8c0b8,#b8b0a8)' },
  { name:'Rok Ruffle',          cat:'rok',      price:'Rp 76.000',  old:'',           badge:'',        stok:7,  bg:'linear-gradient(135deg,#e4d8d0,#d4c8c0)' },
  { name:'Kemeja Batik Modern', cat:'kemeja',   price:'Rp 115.000', old:'',           badge:'',        stok:5,  bg:'linear-gradient(135deg,#d8c8b8,#c8b8a8)' },
  { name:'Celana Skirt',        cat:'celana',   price:'Rp 88.000',  old:'Rp 105.000', badge:'sale',    stok:2,  bg:'linear-gradient(135deg,#e0d0c8,#d0c0b8)' },
  { name:'Cardigan Stripes',    cat:'cardigan', price:'Rp 98.000',  old:'',           badge:'new',     stok:10, bg:'linear-gradient(135deg,#d8d4cc,#c8c4bc)' },
  { name:'Jaket Varsity',       cat:'jaket',    price:'Rp 210.000', old:'',           badge:'branded', stok:0,  bg:'linear-gradient(135deg,#b8c4d0,#a8b4c0)' },
];

const PRODUCTS = BASE.map((p, i) => ({ id: i+1, ...p }));

const productExtra = {
  default: { ukuran:'All Size', bahan:'Katun premium', warna:'Netral', kondisi:'Like New', lingkar_dada:'-', panjang_baju:'-' },
  1: { ukuran:'M, L, XL', bahan:'Linen', warna:'Cokelat Tua', kondisi:'Baru', lingkar_dada:'96 cm', panjang_baju:'98 cm' },
  2: { ukuran:'S, M', bahan:'Katun', warna:'Cokelat Susu', kondisi:'Like New', lingkar_dada:'88 cm', panjang_baju:'70 cm' },
  3: { ukuran:'All Size', bahan:'Polyester', warna:'Putih', kondisi:'Baru', lingkar_dada:'-', panjang_baju:'85 cm' },
  4: { ukuran:'Free Size', bahan:'Rajut', warna:'Krem', kondisi:'Like New', lingkar_dada:'100 cm', panjang_baju:'60 cm' },
  5: { ukuran:'M, L', bahan:'Flanel', warna:'Merah Kotak', kondisi:'Branded', lingkar_dada:'104 cm', panjang_baju:'72 cm' },
  8: { ukuran:'S, M, L', bahan:'Katun', warna:'Plaid', kondisi:'Like New', lingkar_dada:'90 cm', panjang_baju:'55 cm' },
};

PRODUCTS.forEach(p => {
  const extra = productExtra[p.id] || productExtra.default;
  Object.assign(p, extra);
});

const ICONS = {
  kemeja:   `<path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z"/>`,
  celana:   `<path d="M6 2h12v4l-3 16H9L6 6V2z"/><path d="M6 6h12M12 6v4"/>`,
  rok:      `<rect x="7" y="2" width="10" height="4" rx="1"/><path d="M7 6 4 20h16L17 6"/>`,
  cardigan: `<path d="M3 6.5 7.5 3h3L12 5l1.5-2h3L21 6.5l-3 2.5v11a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V9L3 6.5z"/><path d="M12 5v15"/>`,
  jaket:    `<path d="M3 7 7 3h2.5L12 6l2.5-3H17l4 4-2.5 2L17 7v13a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V7L4.5 9 3 7z"/><path d="M12 6v14"/>`,
  default:  `<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>`,
};

const BADGE_LABELS  = { new:'New', branded:'Branded', sale:'Sale' };
const BADGE_CLASSES = { new:'bg-gray-900 text-white', branded:'bg-terra text-white', sale:'bg-red-500 text-white' };
const DESCRIPTIONS  = {
  kemeja:   'Kemeja premium dengan bahan adem dan nyaman dipakai seharian. Cocok untuk gaya kasual maupun semi-formal.',
  celana:   'Celana berkualitas tinggi dengan potongan modern. Memberikan kenyamanan maksimal dan tampilan stylish.',
  rok:      'Rok elegan dengan desain feminin yang jatuh sempurna. Bahan berkualitas untuk tampilan anggun setiap saat.',
  cardigan: 'Cardigan rajut lembut yang memberi kehangatan tanpa mengorbankan gaya. Material premium tahan lama.',
  jaket:    'Jaket trendy yang menjadi statement piece outfit kamu. Bahan kuat dengan jahitan rapi.',
  default:  'Produk fashion pilihan dengan kualitas terbaik. Desain mengikuti tren terkini.'
};

function getProductId() {
  const match = window.location.pathname.match(/\/detail-produk\/(\d+)/);
  if (match) return parseInt(match[1]);
  const params = new URLSearchParams(window.location.search);
  if (params.has('id')) return parseInt(params.get('id'));
  return null;
}

const productId = getProductId();
const product   = productId ? PRODUCTS.find(p => p.id === productId) : null;
const container = document.getElementById('detailContainer');

if (!product) {
  container.innerHTML = `<div class="w-full text-center py-20 text-muted">
    <svg class="w-16 h-16 mx-auto mb-4 stroke-border" viewBox="0 0 24 24" fill="none" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    <p class="text-lg">Produk tidak ditemukan</p>
    <a href="/daftar-produk" class="inline-block mt-4 bg-terra text-white px-4 py-2 rounded-full text-sm">Lihat produk lain</a>
  </div>`;
} else {
  // Update subtitle topbar
  document.getElementById('pageSubtitle').textContent = product.name;

  const inStock = product.stok > 0;
  const icon    = ICONS[product.cat] || ICONS.default;

  const badgeHtml = product.badge
    ? `<span class="absolute top-4 right-4 z-10 text-xs font-bold px-2.5 py-1 rounded-full ${BADGE_CLASSES[product.badge]}">${BADGE_LABELS[product.badge]}</span>`
    : '';

  const oldPriceHtml = product.old
    ? `<span class="text-base text-muted line-through">${product.old}</span>` : '';

  // Stok badge
  const stokHtml = inStock
    ? `<div class="flex items-center gap-2">
         <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
           <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
         </div>
         <span class="text-sm font-medium text-green-700">Stok Tersedia</span>
         <span class="text-xs font-bold bg-green-100 text-green-700 px-2 py-0.5 rounded-full">${product.stok} pcs</span>
       </div>`
    : `<div class="flex items-center gap-2">
         <div class="w-5 h-5 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
           <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
         </div>
         <span class="text-sm font-medium text-red-700">Stok Habis</span>
         <span class="text-xs font-bold bg-red-100 text-red-700 px-2 py-0.5 rounded-full">0 pcs</span>
       </div>`;

  const specRows = [
    { label:'Bahan',        value: product.bahan },
    { label:'Warna',        value: product.warna },
    { label:'Kondisi',      value: product.kondisi },
    { label:'Lingkar Dada', value: product.lingkar_dada },
    { label:'Panjang Baju', value: product.panjang_baju },
  ].filter(r => r.value && r.value !== '-').map(r => `
    <tr class="border-b border-border">
      <td class="bg-terra-xs px-4 py-3 font-semibold text-gray-700 w-[42%] text-sm">${r.label}</td>
      <td class="px-4 py-3 text-gray-600 text-sm">${r.value}</td>
    </tr>`).join('');

  container.innerHTML = `
    <!-- GAMBAR -->
    <div class="w-full lg:w-[45%] fade-up">
      <div class="relative w-full max-w-[380px] mx-auto rounded-3xl overflow-hidden shadow-md" style="aspect-ratio:1/1; background:${product.bg}">
        <div class="w-full h-full flex items-center justify-center">
          <svg width="140" height="140" viewBox="0 0 24 24" fill="rgba(255,255,255,0.12)" stroke="rgba(255,255,255,0.7)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">${icon}</svg>
        </div>
        ${badgeHtml}
      </div>
    </div>

    <!-- INFO -->
    <div class="w-full lg:flex-1 fade-up">
      <!-- Kategori + stok -->
      <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
        <span class="text-xs bg-white border border-border text-muted px-3 py-1 rounded-full capitalize">${product.cat}</span>
        ${stokHtml}
      </div>

      <!-- Nama -->
      <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">${product.name}</h1>

      <!-- Harga -->
      <div class="flex items-baseline gap-3 mb-4">
        <span class="text-2xl font-bold text-terra">${product.price}</span>
        ${oldPriceHtml}
      </div>

      <!-- Ukuran -->
      <div class="mb-4 p-3 bg-white rounded-xl border border-border shadow-sm">
        <div class="flex items-center gap-2">
          <svg class="w-4 h-4 text-terra flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
          <span class="font-semibold text-gray-800 text-sm">Ukuran:</span>
          <span class="text-gray-900 font-medium text-sm">${product.ukuran}</span>
        </div>
      </div>

      <!-- Deskripsi -->
      <p class="text-sm leading-relaxed text-gray-600 mb-5">${DESCRIPTIONS[product.cat] || DESCRIPTIONS.default}</p>

      <!-- Tabel spesifikasi -->
      <div class="mb-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-2">Detail Produk</h3>
        <div class="overflow-hidden rounded-2xl border border-border bg-white shadow-sm">
          <table class="w-full"><tbody>${specRows}</tbody></table>
        </div>
      </div>

    </div>
  `;
}
</script>
</body>
</html>