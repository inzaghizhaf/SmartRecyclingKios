@component('admin.partials.layout', ['title' => 'Lacak Mesin'])
    <div class="tracking-heading">
        <div>
            <h1>Lacak Mesin</h1>
            <p class="text-slate-500">Pantau lokasi dan status mesin secara real-time.</p>
        </div>
    </div>

    <div class="tracking-stats" id="machineStats"></div>

    <div class="tracking-content">
        <section class="machine-panel">
            <div class="machine-panel-head">
                <h2>Daftar Mesin</h2>
                <div class="machine-filters">
                    <label class="search-wrap" for="machineSearch">
                        <i class="ph ph-magnifying-glass"></i>
                        <input id="machineSearch" type="search" placeholder="Cari mesin...">
                    </label>
                    <label class="status-wrap" for="machineStatus">
                        <select id="machineStatus">
                            <option value="all">Semua Status</option>
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </label>
                </div>
            </div>
            <div id="machineList" class="machine-list"></div>
            <div class="machine-pagination"><span id="machineCount">0 mesin</span><span class="pagination-current">1</span></div>
        </section>

        <section class="map-panel">
            <div id="map"><iframe id="googleMap" title="Peta lokasi mesin" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
            <aside id="machineDetail" class="machine-detail hidden"></aside>
            <div class="map-legend">
                <span><b class="legend-dot online"></b>Online</span>
                <span><b class="legend-dot offline"></b>Offline</span>
                <span><b class="legend-dot maintenance"></b>Maintenance</span>
            </div>
        </section>
    </div>

    <style>
        .tracking-heading{display:flex;align-items:center;justify-content:space-between;margin:0 12px 22px}.tracking-heading h1{font-size:30px;font-weight:800;letter-spacing:-.7px}.tracking-heading p{margin-top:6px;color:#64748b;font-size:16px}.tracking-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px;margin:0 12px 18px}.tracking-card{height:114px;border:1px solid var(--card-border);background:var(--card-bg);border-radius:12px;display:flex;align-items:center;padding:20px 16px;gap:16px;box-shadow:0 2px 7px rgba(15,23,42,.025)}.tracking-card .stat-icon{width:54px;height:54px;border-radius:50%;background:var(--icon-bg);color:var(--icon-color);display:flex;align-items:center;justify-content:center;font-size:29px}.tracking-card p{font-size:12px;color:#334155;font-weight:700}.tracking-card strong{display:block;font-size:29px;line-height:1.1;margin:4px 0;color:#0f172a}.tracking-card small{font-size:11px;color:#64748b}.card-blue{--card-bg:#f5f9ff;--card-border:#cfe2ff;--icon-bg:#dcecff;--icon-color:#1267db}.card-green{--card-bg:#f3fcf4;--card-border:#c8ecd1;--icon-bg:#d9f7df;--icon-color:#16a13a}.card-orange{--card-bg:#fffaf0;--card-border:#fae3b0;--icon-bg:#fff0ca;--icon-color:#f28a00}.card-purple{--card-bg:#fbf6ff;--card-border:#e5cdf9;--icon-bg:#f0dcff;--icon-color:#7e22ce}
        .tracking-content{display:grid;grid-template-columns:372px minmax(0,1fr);gap:13px}.machine-panel,.map-panel{border:1px solid #dce4ed;border-radius:12px;background:#fff;overflow:hidden}.machine-panel{display:flex;min-height:616px;flex-direction:column}.machine-panel-head{padding:18px 16px 12px;border-bottom:1px solid #e6ebf0}.machine-panel h2{font-size:16px;font-weight:800}.machine-filters{display:flex;gap:12px;margin-top:13px}.search-wrap{position:relative;flex:1}.search-wrap i{position:absolute;top:10px;left:10px;font-size:17px;color:#64748b}.search-wrap input,.status-wrap select{height:35px;width:100%;border:1px solid #d6dee8;border-radius:6px;background:#fff;color:#334155;font-size:12px;outline:none}.search-wrap input{padding:0 9px 0 32px}.search-wrap input:focus,.status-wrap select:focus{border-color:#25a83a;box-shadow:0 0 0 2px #dcfce7}.status-wrap{width:132px}.status-wrap select{padding:0 8px}.machine-list{flex:1;min-height:0}.machine-row{width:100%;min-height:72px;border:0;border-bottom:1px solid #e7edf3;background:#fff;padding:12px 16px;display:flex;gap:13px;text-align:left;cursor:pointer;transition:background .15s}.machine-row:hover,.machine-row.active{background:#f7fdf8}.machine-row.active{box-shadow:inset 3px 0 #1fa637}.machine-device-icon{width:22px;height:22px;border-radius:3px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;flex:none;margin-top:2px}.machine-info{min-width:0;flex:1}.machine-title{display:flex;align-items:center;justify-content:space-between;gap:4px}.machine-title strong{font-size:13px}.machine-info p{font-size:11px;color:#64748b;margin-top:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.machine-info .last-seen{font-size:10px;text-align:right;margin-top:-11px}.status-pill{font-size:10px;border-radius:12px;padding:3px 8px;font-weight:700}.status-online{background:#d9f7df;color:#16802b}.status-offline{background:#fff0cc;color:#e87800}.status-maintenance{background:#f0dcff;color:#7e22ce}.machine-pagination{border-top:1px solid #e6ebf0;padding:13px 16px;color:#64748b;font-size:12px;display:flex;justify-content:space-between;align-items:center}.pagination-current{width:25px;height:25px;border-radius:5px;background:#21a53a;color:#fff;display:grid;place-items:center;font-weight:700}
        .map-panel{position:relative;height:616px}.map-panel #map{height:559px;width:100%;background:#e8eaed}.map-panel iframe{display:block;width:100%;height:100%;border:0}.map-legend{height:57px;background:#fff;border-top:1px solid #e6ebf0;display:flex;align-items:center;justify-content:center;gap:28px;font-size:11px;color:#334155;font-weight:600}.map-legend span{display:flex;align-items:center;gap:7px}.legend-dot{width:11px;height:11px;border:3px solid #fff;box-shadow:0 0 0 1px currentColor;display:inline-block}.legend-dot.online{color:#16a34a;background:#16a34a}.legend-dot.offline{color:#f59e0b;background:#f59e0b}.legend-dot.maintenance{color:#7c3aed;background:#7c3aed}.machine-detail{position:absolute;right:86px;top:96px;z-index:550;width:286px;background:#fff;border-radius:10px;padding:17px 18px;box-shadow:0 8px 22px rgba(15,23,42,.20)}.machine-detail.hidden{display:none}.detail-top{display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #edf0f3;padding-bottom:13px}.detail-top h2{font-size:17px;font-weight:800}.detail-top p{font-size:10px;color:#64748b;margin-top:2px}.detail-close{border:0;background:none;color:#64748b;font-size:18px;cursor:pointer}.detail-row{display:flex;gap:12px;margin-top:14px;color:#64748b}.detail-row>i{font-size:19px}.detail-row dt{font-size:11px}.detail-row dd{font-size:11px;color:#334155;font-weight:600;margin-top:2px}.signal-bar{width:100%;height:5px;border-radius:99px;background:#e2e8f0;margin-top:5px}.signal-bar b{height:100%;display:block;border-radius:inherit;background:#1f9c35}@media(max-width:1100px){.tracking-stats{grid-template-columns:repeat(2,1fr)}.tracking-content{grid-template-columns:1fr}.machine-panel{min-height:auto}.machine-list{max-height:420px;overflow-y:auto}}@media(max-width:640px){.tracking-stats{grid-template-columns:1fr}.tracking-content{gap:12px}.machine-detail{right:16px;left:16px;width:auto}.map-legend{gap:10px;overflow-x:auto;justify-content:flex-start;padding:0 12px;white-space:nowrap}}
    </style>
    <script>
        let machines = @json($machines), selectedId = null;
        const googleMap = document.getElementById('googleMap');
        const labels = { online: 'Online', offline: 'Offline', maintenance: 'Maintenance' };
        const meta = { online: ['#16a34a', 'status-online'], offline: ['#f59e0b', 'status-offline'], maintenance: ['#7c3aed', 'status-maintenance'] };
        const html = value => { const e = document.createElement('div'); e.textContent = value || '-'; return e.innerHTML; };
        const formatMachine = machine => machine.name || machine.device_id;

        function render() {
            const total = machines.reduce((sum, item) => ({ ...sum, [item.status]: (sum[item.status] || 0) + 1 }), {online:0, offline:0, maintenance:0});
            document.getElementById('machineStats').innerHTML = [
                ['card-blue','ph-desktop','Total Mesin',machines.length,'Semua mesin terdaftar'],['card-green','ph-map-pin','Online',total.online,'Mesin aktif'],['card-orange','ph-pause-circle','Offline',total.offline,'Mesin tidak aktif'],['card-purple','ph-wrench','Maintenance',total.maintenance,'Dalam perawatan']
            ].map(([style,icon,label,value,note]) => `<article class="tracking-card ${style}"><span class="stat-icon"><i class="ph-fill ${icon}"></i></span><div><p>${label}</p><strong>${value}</strong><small>${note}</small></div></article>`).join('');
            const query = document.getElementById('machineSearch').value.toLowerCase(), status = document.getElementById('machineStatus').value;
            const listed = machines.filter(m => (status === 'all' || m.status === status) && `${m.name} ${m.device_id} ${m.location_name || ''}`.toLowerCase().includes(query));
            document.getElementById('machineCount').textContent = `${listed.length} dari ${machines.length} mesin`;
            document.getElementById('machineList').innerHTML = listed.map(m => `<button class="machine-row ${m.id === selectedId ? 'active' : ''}" data-machine="${m.id}"><span class="machine-device-icon" style="background:${meta[m.status][0]}"><i class="ph-fill ph-desktop"></i></span><span class="machine-info"><span class="machine-title"><strong>${html(formatMachine(m))}</strong><em class="status-pill ${meta[m.status][1]}">${labels[m.status]}</em></span><p>${html(m.location_name || 'Lokasi belum diberi nama')}</p><p class="last-seen">Terakhir: ${html(m.last_seen_label || 'Belum ada data')}</p></span></button>`).join('') || '<p style="padding:20px;font-size:12px;color:#64748b">Mesin tidak ditemukan.</p>';
            document.querySelectorAll('[data-machine]').forEach(el => el.addEventListener('click', () => selectMachine(Number(el.dataset.machine), true)));
            if (!selectedId) showGoogleMap(machines.find(m => m.latitude !== null && m.longitude !== null));
        }
        function showGoogleMap(machine) {
            const latitude = machine?.latitude ?? -7.5755, longitude = machine?.longitude ?? 110.8243;
            googleMap.src = `https://www.google.com/maps?q=${encodeURIComponent(`${latitude},${longitude}`)}&z=15&output=embed`;
        }
        function selectMachine(id, focus) {
            selectedId=id; const m=machines.find(item=>item.id===id); if(!m)return; const detail=document.getElementById('machineDetail');
            detail.classList.remove('hidden'); detail.innerHTML=`<div class="detail-top"><div><h2>${html(formatMachine(m))}</h2><p>${html(m.device_id)}</p></div><span class="status-pill ${meta[m.status][1]}">${labels[m.status]}</span><button class="detail-close" aria-label="Tutup">×</button></div><dl><div class="detail-row"><i class="ph ph-map-pin"></i><div><dt>Lokasi</dt><dd>${html(m.location_name || 'Belum diberi nama')}</dd></div></div><div class="detail-row"><i class="ph ph-clock"></i><div><dt>Terakhir Update</dt><dd>${html(m.last_seen_label || 'Belum ada data')}</dd></div></div><div class="detail-row"><i class="ph ph-satellite"></i><div><dt>Satelit</dt><dd>${m.satellites ?? '-'} Satelit</dd></div></div><div class="detail-row"><i class="ph ph-signal-high"></i><div style="flex:1"><dt>Sinyal GPS</dt><dd>Bagus (${m.signal_strength ?? 0}%)</dd><div class="signal-bar"><b style="width:${Math.min(100, m.signal_strength || 0)}%"></b></div></div></div></dl>`;
            detail.querySelector('.detail-close').onclick=()=>{selectedId=null;detail.classList.add('hidden');render();}; if(focus)showGoogleMap(m);render();
        }
        document.getElementById('machineSearch').addEventListener('input',render); document.getElementById('machineStatus').addEventListener('change',render); render();
        setInterval(async()=>{try{const r=await fetch('{{ route('super-admin.machines.data') }}',{headers:{Accept:'application/json'}});if(r.ok){machines=(await r.json()).machines;render();}}catch(e){}},30000);
    </script>
@endcomponent
