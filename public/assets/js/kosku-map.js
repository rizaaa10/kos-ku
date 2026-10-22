/**
 * kosKu - Map Explorer & Interactive Engine
 * PRD Version 1.0
 */

const KosKu = {
  map: null,
  markersLayer: null,
  geojsonLayer: null,
  kosData: [],
  activeMarkerId: null,
  userLocationMarker: null,
  favorites: [],
  compareList: [],

  init: function (config) {
    this.defaultCenter = config.defaultCenter || { lat: -7.243542, lng: 109.006528, zoom: 14 };
    this.initialKos = config.initialKos || [];
    this.wilayahList = config.wilayahList || [];
    this.selectedWilayahId = config.selectedWilayahId || '';

    this.loadFavorites();
    this.initMap();
    this.initEvents();
    this.updateCompareBar();
    this.updateFavoritesBadge();

    if (this.selectedWilayahId) {
      this.loadWilayahGeojson(this.selectedWilayahId);
    }

    if (this.initialKos && this.initialKos.length > 0) {
      this.renderKos(this.initialKos);
    } else {
      this.fetchKos();
    }
  },

  initMap: function () {
    const mapElement = document.getElementById('map');
    if (!mapElement) return;

    this.map = L.map('map', {
      center: [this.defaultCenter.lat, this.defaultCenter.lng],
      zoom: this.defaultCenter.zoom,
      zoomControl: false,
      attributionControl: true
    });

    // Custom Zoom Control top-right
    L.control.zoom({ position: 'topright' }).addTo(this.map);

    // OpenStreetMap Tile Layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | <strong>kosKu</strong>',
      maxZoom: 19
    }).addTo(this.map);

    this.markersLayer = L.layerGroup().addTo(this.map);
  },

  loadFavorites: function () {
    try {
      const stored = localStorage.getItem('kosku_favorites');
      this.favorites = stored ? JSON.parse(stored) : [];
    } catch (e) {
      this.favorites = [];
    }
  },

  saveFavorites: function () {
    try {
      localStorage.setItem('kosku_favorites', JSON.stringify(this.favorites));
      this.updateFavoritesBadge();
    } catch (e) {}
  },

  toggleFavorite: function (kosId, buttonEl) {
    kosId = parseInt(kosId);
    const index = this.favorites.indexOf(kosId);
    let isAdded = false;

    if (index > -1) {
      this.favorites.splice(index, 1);
      isAdded = false;
    } else {
      this.favorites.push(kosId);
      isAdded = true;
    }

    this.saveFavorites();

    if (buttonEl) {
      const icon = buttonEl.querySelector('i');
      if (icon) {
        if (isAdded) {
          icon.className = 'bi bi-heart-fill text-danger';
        } else {
          icon.className = 'bi bi-heart text-secondary';
        }
      }
    }

    this.showToast(isAdded ? 'Ditambahkan ke Favorit' : 'Dihapus dari Favorit');
  },

  updateFavoritesBadge: function () {
    const badge = document.getElementById('favCountBadge');
    if (badge) {
      badge.textContent = this.favorites.length;
      badge.style.display = this.favorites.length > 0 ? 'inline-flex' : 'none';
    }
  },

  toggleCompare: function (kosId) {
    kosId = parseInt(kosId);
    const index = this.compareList.indexOf(kosId);

    if (index > -1) {
      this.compareList.splice(index, 1);
    } else {
      if (this.compareList.length >= 3) {
        alert('Maksimal membandingkan 3 kos sekaligus.');
        return;
      }
      this.compareList.push(kosId);
    }

    this.updateCompareBar();
    this.updateCompareCheckboxes();
  },

  updateCompareCheckboxes: function () {
    document.querySelectorAll('.compare-check-input').forEach(chk => {
      const id = parseInt(chk.getAttribute('data-kos-id'));
      chk.checked = this.compareList.includes(id);
    });
  },

  updateCompareBar: function () {
    const bar = document.getElementById('compareFloatingBar');
    const countEl = document.getElementById('compareCount');
    if (!bar) return;

    if (this.compareList.length > 0) {
      bar.style.display = 'flex';
      if (countEl) countEl.textContent = this.compareList.length;
    } else {
      bar.style.display = 'none';
    }
  },

  clearCompare: function () {
    this.compareList = [];
    this.updateCompareBar();
    this.updateCompareCheckboxes();
  },

  showCompareModal: function () {
    if (this.compareList.length === 0) return;

    const modalBody = document.getElementById('compareModalBody');
    if (!modalBody) return;

    const selectedKos = this.kosData.filter(k => this.compareList.includes(parseInt(k.id)));
    if (selectedKos.length === 0) return;

    let html = `<div class="table-responsive"><table class="table table-bordered align-middle">`;
    html += `<thead class="table-light"><tr><th style="width: 20%;">Fitur / Spesifikasi</th>`;
    selectedKos.forEach(k => {
      html += `<th style="width: ${80 / selectedKos.length}%;" class="text-center">
        <h6 class="fw-bold mb-1">${this.escapeHtml(k.nama)}</h6>
        <span class="badge badge-kosku badge-kosku-dark text-capitalize mb-2">${k.tipe}</span>
      </th>`;
    });
    html += `</tr></thead><tbody>`;

    // Row Harga
    html += `<tr><td class="fw-bold">Harga per Bulan</td>`;
    selectedKos.forEach(k => {
      html += `<td class="text-center fw-bold text-dark fs-6">${this.formatRupiah(k.harga_min)}</td>`;
    });
    html += `</tr>`;

    // Row Wilayah & Alamat
    html += `<tr><td class="fw-bold">Wilayah & Alamat</td>`;
    selectedKos.forEach(k => {
      html += `<td><strong>${this.escapeHtml(k.nama_wilayah || '')}</strong><br><small class="text-muted">${this.escapeHtml(k.alamat)}</small></td>`;
    });
    html += `</tr>`;

    // Row Ketersediaan
    html += `<tr><td class="fw-bold">Ketersediaan Kamar</td>`;
    selectedKos.forEach(k => {
      const isAvailable = k.status_ketersediaan === 'tersedia' && parseInt(k.kamar_tersedia) > 0;
      html += `<td class="text-center">${isAvailable ? `<span class="badge badge-available">Tersedia (${k.kamar_tersedia} kamar)</span>` : '<span class="badge badge-full">Penuh</span>'}</td>`;
    });
    html += `</tr>`;

    // Row Fasilitas
    html += `<tr><td class="fw-bold">Fasilitas Utama</td>`;
    selectedKos.forEach(k => {
      let fasHtml = '<ul class="list-unstyled mb-0 small text-start ps-3">';
      if (k.fasilitas && k.fasilitas.length > 0) {
        k.fasilitas.forEach(f => {
          fasHtml += `<li><i class="bi bi-check2 text-dark me-1"></i> ${this.escapeHtml(f.nama)}</li>`;
        });
      } else {
        fasHtml += '<li class="text-muted">-</li>';
      }
      fasHtml += '</ul>';
      html += `<td>${fasHtml}</td>`;
    });
    html += `</tr>`;

    // Row Kontak WA
    html += `<tr><td class="fw-bold">Aksi Kontak</td>`;
    selectedKos.forEach(k => {
      const waMsg = encodeURIComponent(`Halo ${k.kontak_nama}, saya ingin bertanya tentang kamar kos ${k.nama} di kosKu.`);
      html += `<td class="text-center">
        <a href="https://wa.me/${k.kontak_wa}?text=${waMsg}" target="_blank" class="btn btn-sm btn-kosku-dark btn-pill mb-1 w-100" onclick="KosKu.track(${k.id}, 'klik_wa')">
          <i class="bi bi-whatsapp"></i> Chat WhatsApp
        </a>
        <a href="/kos/${k.slug}" class="btn btn-sm btn-kosku-light btn-pill w-100">
          Lihat Detail
        </a>
      </td>`;
    });
    html += `</tr>`;

    html += `</tbody></table></div>`;
    modalBody.innerHTML = html;

    const modal = new bootstrap.Modal(document.getElementById('compareModal'));
    modal.show();
  },

  renderKos: function (kosArray) {
    this.kosData = kosArray;
    this.markersLayer.clearLayers();

    const listContainer = document.getElementById('kosListCards');
    const countBadge = document.getElementById('kosCountBadge');
    if (countBadge) countBadge.textContent = kosArray.length;

    if (!listContainer) return;

    if (kosArray.length === 0) {
      listContainer.innerHTML = `
        <div class="card-kosku p-5 text-center my-4">
          <div class="mb-3 text-secondary"><i class="bi bi-geo-alt fs-1"></i></div>
          <h5 class="fw-bold text-dark">Belum ada kos di wilayah ini</h5>
          <p class="text-muted small mb-3">Coba ubah filter pencarian atau pilih wilayah terdekat lainnya.</p>
          <div>
            <button class="btn btn-kosku-dark btn-pill" onclick="KosKu.resetFilters()">
              <i class="bi bi-arrow-counterclockwise"></i> Reset Semua Filter
            </button>
          </div>
        </div>
      `;
      return;
    }

    let cardsHtml = '';
    const bounds = L.latLngBounds();

    kosArray.forEach(kos => {
      const isFav = this.favorites.includes(parseInt(kos.id));
      const isAvailable = kos.status_ketersediaan === 'tersedia' && parseInt(kos.kamar_tersedia) > 0;
      const thumb = kos.foto_utama ? `/${kos.foto_utama.path_file}` : '/uploads/kos/sample_kos_1.svg';
      const formattedPrice = this.formatRupiah(kos.harga_min);

      // Add Leaflet Marker
      const lat = parseFloat(kos.latitude);
      const lng = parseFloat(kos.longitude);

      if (!isNaN(lat) && !isNaN(lng)) {
        bounds.extend([lat, lng]);

        let typeLetter = 'C';
        if (kos.tipe === 'putri') typeLetter = 'W';
        if (kos.tipe === 'putra') typeLetter = 'P';

        const customIcon = L.divIcon({
          className: 'kosku-div-icon',
          html: `<div class="custom-kosku-marker" id="marker-${kos.id}" data-id="${kos.id}">
                   <span class="type-icon">${typeLetter}</span>
                   <span>${formattedPrice}</span>
                 </div>`,
          iconSize: [110, 36],
          iconAnchor: [55, 36],
          popupAnchor: [0, -36]
        });

        const popupHtml = `
          <div style="width: 250px;">
            <img src="${thumb}" style="width: 100%; height: 130px; object-fit: cover;" alt="${this.escapeHtml(kos.nama)}">
            <div style="padding: 12px;">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="badge badge-kosku badge-kosku-dark text-capitalize">${kos.tipe}</span>
                <span class="small fw-bold text-muted">${kos.nama_wilayah || ''}</span>
              </div>
              <h6 class="fw-bold mb-1 text-truncate" title="${this.escapeHtml(kos.nama)}">${this.escapeHtml(kos.nama)}</h6>
              <div class="fw-bold fs-6 text-dark mb-2">${formattedPrice} <span class="text-muted fw-normal" style="font-size: 11px;">/bln</span></div>
              <div class="d-flex gap-2">
                <a href="/kos/${kos.slug}" class="btn btn-sm btn-kosku-dark btn-pill w-100" style="font-size: 11px;">Lihat Detail</a>
              </div>
            </div>
          </div>
        `;

        const marker = L.marker([lat, lng], { icon: customIcon })
          .bindPopup(popupHtml)
          .addTo(this.markersLayer);

        marker.on('click', () => {
          this.highlightCard(kos.id);
          this.track(kos.id, 'view');
        });

        kos._marker = marker;
      }

      // Facility preview pills
      let fasPills = '';
      if (kos.fasilitas && kos.fasilitas.length > 0) {
        kos.fasilitas.slice(0, 3).forEach(f => {
          fasPills += `<span class="badge badge-kosku badge-kosku-light me-1"><i class="bi ${f.ikon || 'bi-check'}"></i> ${this.escapeHtml(f.nama)}</span>`;
        });
      }

      // Render Card
      cardsHtml += `
        <div class="card card-kosku mb-3 position-relative overflow-hidden" id="kos-card-${kos.id}" 
             onmouseenter="KosKu.highlightMarker(${kos.id})" 
             onclick="KosKu.flyToKos(${kos.id}, ${lat}, ${lng})">
          <div class="row g-0">
            <div class="col-4 col-sm-4 position-relative">
              <img src="${thumb}" class="img-fluid h-100 w-100 rounded-start-4" style="object-fit: cover; min-height: 140px;" alt="${this.escapeHtml(kos.nama)}">
              <span class="position-absolute top-0 start-0 m-2 badge badge-kosku badge-kosku-dark text-capitalize" style="font-size: 10px;">
                ${kos.tipe}
              </span>
            </div>
            <div class="col-8 col-sm-8">
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <span class="small text-muted fw-medium">${this.escapeHtml(kos.nama_wilayah || 'Wilayah')}</span>
                    <h6 class="card-title fw-bold text-dark mb-1 text-truncate" style="max-width: 190px;" title="${this.escapeHtml(kos.nama)}">
                      <a href="/kos/${kos.slug}" class="text-decoration-none text-dark hover-underline">${this.escapeHtml(kos.nama)}</a>
                    </h6>
                  </div>
                  <button type="button" class="btn btn-sm btn-icon-circle border-0 p-0" style="width: 32px; height: 32px;" onclick="event.stopPropagation(); KosKu.toggleFavorite(${kos.id}, this)">
                    <i class="bi ${isFav ? 'bi-heart-fill text-danger' : 'bi-heart text-secondary'} fs-6"></i>
                  </button>
                </div>

                <div class="my-1">
                  <span class="fs-6 fw-bold text-dark">${formattedPrice}</span>
                  <span class="text-muted small">/bln</span>
                  ${isAvailable ? `<span class="badge badge-available ms-2" style="font-size: 10px;">Tersedia</span>` : `<span class="badge badge-full ms-2" style="font-size: 10px;">Penuh</span>`}
                </div>

                <div class="text-truncate mb-2" style="font-size: 11px;">
                  ${fasPills}
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                  <div class="form-check form-check-inline m-0" onclick="event.stopPropagation();">
                    <input class="form-check-input compare-check-input" type="checkbox" id="chk-cmp-${kos.id}" data-kos-id="${kos.id}" onchange="KosKu.toggleCompare(${kos.id})" ${this.compareList.includes(parseInt(kos.id)) ? 'checked' : ''}>
                    <label class="form-check-label text-muted" for="chk-cmp-${kos.id}" style="font-size: 11px;">Bandingkan</label>
                  </div>
                  <a href="/kos/${kos.slug}" class="btn btn-sm btn-kosku-dark btn-pill px-3 py-1" style="font-size: 11px;" onclick="event.stopPropagation();">
                    Detail <i class="bi bi-chevron-right ms-1"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;
    });

    listContainer.innerHTML = cardsHtml;
  },

  highlightMarker: function (kosId) {
    document.querySelectorAll('.custom-kosku-marker').forEach(m => m.classList.remove('active'));
    const markerEl = document.getElementById(`marker-${kosId}`);
    if (markerEl) {
      markerEl.classList.add('active');
    }
  },

  highlightCard: function (kosId) {
    document.querySelectorAll('.card-kosku').forEach(c => c.classList.remove('border-dark', 'shadow-md'));
    const card = document.getElementById(`kos-card-${kosId}`);
    if (card) {
      card.classList.add('border-dark');
      card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    this.highlightMarker(kosId);
  },

  flyToKos: function (kosId, lat, lng) {
    if (this.map && !isNaN(lat) && !isNaN(lng)) {
      this.map.flyTo([lat, lng], 17, { duration: 1 });
      this.highlightMarker(kosId);

      const item = this.kosData.find(k => k.id == kosId);
      if (item && item._marker) {
        setTimeout(() => {
          item._marker.openPopup();
        }, 800);
      }
    }
  },

  loadWilayahGeojson: function (wilayahId) {
    if (!wilayahId) return;

    fetch(`/api/wilayah/${wilayahId}/geojson`)
      .then(r => r.json())
      .then(res => {
        if (res.status === 'success') {
          if (this.geojsonLayer) {
            this.map.removeLayer(this.geojsonLayer);
            this.geojsonLayer = null;
          }

          if (res.geojson) {
            this.geojsonLayer = L.geoJSON(res.geojson, {
              style: {
                color: '#18181b',
                weight: 2,
                opacity: 0.8,
                dashArray: '5, 5',
                fillColor: '#18181b',
                fillOpacity: 0.05
              }
            }).addTo(this.map);

            this.map.fitBounds(this.geojsonLayer.getBounds(), { padding: [40, 40] });
          } else if (res.center_lat && res.center_lng) {
            this.map.flyTo([res.center_lat, res.center_lng], res.zoom || 14);
          }
        }
      })
      .catch(err => console.error('Error loading GeoJSON:', err));
  },

  fetchKos: function () {
    const listContainer = document.getElementById('kosListCards');
    if (listContainer) {
      listContainer.innerHTML = `
        <div class="text-center py-5">
          <div class="spinner-border text-dark" role="status"></div>
          <p class="text-muted small mt-2">Memuat daftar kos...</p>
        </div>
      `;
    }

    const params = new URLSearchParams();
    const wilayahSelect = document.getElementById('wilayahSelect');
    if (wilayahSelect && wilayahSelect.value) params.append('wilayah_id', wilayahSelect.value);

    const activeTipe = document.querySelector('.chip-tipe.active');
    if (activeTipe && activeTipe.getAttribute('data-tipe')) {
      params.append('tipe', activeTipe.getAttribute('data-tipe'));
    }

    const searchInput = document.getElementById('searchQuery');
    if (searchInput && searchInput.value) params.append('q', searchInput.value);

    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect && sortSelect.value) params.append('sort', sortSelect.value);

    const hargaMin = document.getElementById('filterHargaMin');
    if (hargaMin && hargaMin.value) params.append('harga_min', hargaMin.value);

    const hargaMax = document.getElementById('filterHargaMax');
    if (hargaMax && hargaMax.value) params.append('harga_max', hargaMax.value);

    const ketersediaan = document.getElementById('filterKetersediaan');
    if (ketersediaan && ketersediaan.value) params.append('ketersediaan', ketersediaan.value);

    // Facilities
    const selectedFas = [];
    document.querySelectorAll('.filter-fasilitas-check:checked').forEach(c => {
      selectedFas.push(c.value);
    });
    if (selectedFas.length > 0) {
      params.append('fasilitas', selectedFas.join(','));
    }

    fetch(`/api/kos?${params.toString()}`)
      .then(r => r.json())
      .then(res => {
        if (res.status === 'success') {
          this.renderKos(res.data);
        }
      })
      .catch(err => {
        console.error('Fetch error:', err);
      });
  },

  initEvents: function () {
    // Wilayah dropdown change
    const wilayahSelect = document.getElementById('wilayahSelect');
    if (wilayahSelect) {
      wilayahSelect.addEventListener('change', (e) => {
        const wId = e.target.value;
        this.loadWilayahGeojson(wId);
        this.fetchKos();
      });
    }

    // Tipe chips
    document.querySelectorAll('.chip-tipe').forEach(chip => {
      chip.addEventListener('click', (e) => {
        document.querySelectorAll('.chip-tipe').forEach(c => c.classList.remove('active'));
        chip.classList.add('active');
        this.fetchKos();
      });
    });

    // Search query with debounce
    const searchInput = document.getElementById('searchQuery');
    if (searchInput) {
      let timeout = null;
      searchInput.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
          this.fetchKos();
        }, 350);
      });
    }

    // Sort select
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
      sortSelect.addEventListener('change', () => {
        this.fetchKos();
      });
    }

    // Geolocation button
    const geoBtn = document.getElementById('btnNearMe');
    if (geoBtn) {
      geoBtn.addEventListener('click', () => {
        this.findNearMe();
      });
    }
  },

  findNearMe: function () {
    if (!navigator.geolocation) {
      alert('Peramban Anda tidak mendukung geolokasi.');
      return;
    }

    const geoBtn = document.getElementById('btnNearMe');
    if (geoBtn) {
      geoBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mencari...';
      geoBtn.disabled = true;
    }

    navigator.geolocation.getCurrentPosition(
      (pos) => {
        if (geoBtn) {
          geoBtn.innerHTML = '<i class="bi bi-geo"></i> Sekitar Saya';
          geoBtn.disabled = false;
        }

        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;

        if (this.userLocationMarker) {
          this.map.removeLayer(this.userLocationMarker);
        }

        const userIcon = L.divIcon({
          className: 'user-geo-icon',
          html: `<div style="width: 20px; height: 20px; border-radius: 50%; background: #18181b; border: 3px solid #ffffff; box-shadow: 0 0 0 4px rgba(24, 24, 27, 0.25);"></div>`,
          iconSize: [20, 20],
          iconAnchor: [10, 10]
        });

        this.userLocationMarker = L.marker([lat, lng], { icon: userIcon })
          .bindPopup('<strong>Lokasi Anda Saat Ini</strong>')
          .addTo(this.map);

        this.map.flyTo([lat, lng], 15);

        // Sort kosData by distance
        if (this.kosData.length > 0) {
          this.kosData.sort((a, b) => {
            const distA = Math.hypot(parseFloat(a.latitude) - lat, parseFloat(a.longitude) - lng);
            const distB = Math.hypot(parseFloat(b.latitude) - lat, parseFloat(b.longitude) - lng);
            return distA - distB;
          });
          this.renderKos(this.kosData);
        }

        this.showToast('Menampilkan kos terdekat dari lokasi Anda.');
      },
      (err) => {
        if (geoBtn) {
          geoBtn.innerHTML = '<i class="bi bi-geo"></i> Sekitar Saya';
          geoBtn.disabled = false;
        }
        alert('Tidak dapat mengakses lokasi. Pastikan izin GPS peramban telah diaktifkan.');
      },
      { timeout: 10000, enableHighAccuracy: true }
    );
  },

  resetFilters: function () {
    const wilayahSelect = document.getElementById('wilayahSelect');
    if (wilayahSelect) wilayahSelect.value = '';

    const searchInput = document.getElementById('searchQuery');
    if (searchInput) searchInput.value = '';

    document.querySelectorAll('.chip-tipe').forEach(c => c.classList.remove('active'));
    const defaultChip = document.querySelector('.chip-tipe[data-tipe=""]');
    if (defaultChip) defaultChip.classList.add('active');

    const hargaMin = document.getElementById('filterHargaMin');
    if (hargaMin) hargaMin.value = '';

    const hargaMax = document.getElementById('filterHargaMax');
    if (hargaMax) hargaMax.value = '';

    const ketersediaan = document.getElementById('filterKetersediaan');
    if (ketersediaan) ketersediaan.value = '';

    document.querySelectorAll('.filter-fasilitas-check').forEach(c => c.checked = false);

    if (this.geojsonLayer) {
      this.map.removeLayer(this.geojsonLayer);
      this.geojsonLayer = null;
    }

    this.map.flyTo([this.defaultCenter.lat, this.defaultCenter.lng], this.defaultCenter.zoom);
    this.fetchKos();
  },

  track: function (kosId, jenis) {
    const form = new FormData();
    form.append('jenis', jenis);
    fetch(`/api/kos/${kosId}/track`, {
      method: 'POST',
      body: form
    }).catch(() => {});
  },

  showToast: function (message) {
    const toastEl = document.getElementById('koskuToast');
    const toastBody = document.getElementById('koskuToastBody');
    if (toastEl && toastBody) {
      toastBody.textContent = message;
      const toast = new bootstrap.Toast(toastEl, { delay: 2500 });
      toast.show();
    }
  },

  formatRupiah: function (amount) {
    return 'Rp ' + Number(amount).toLocaleString('id-ID');
  },

  escapeHtml: function (string) {
    if (!string) return '';
    return String(string).replace(/[&<>"']/g, function (m) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
    });
  }
};
