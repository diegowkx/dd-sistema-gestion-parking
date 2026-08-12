$(document).ready(function() {

    // Silenciar alertas de error nativas de DataTables
    $.fn.dataTable.ext.errMode = 'none';

    // Inicializar instancia de Modal solo si existe el elemento en el DOM y Bootstrap está disponible
    const modalElement = document.getElementById('modalIpDetalle');
    let modalIp = null;
    
    if (modalElement && typeof bootstrap !== 'undefined') {
        modalIp = new bootstrap.Modal(modalElement);
    }

    // Configuración de Idioma en Español para DataTables
    const lenguajeEspanol = {
        processing:     "Procesando...",
        search:         "Buscar:",
        lengthMenu:     "Mostrar _MENU_ registros",
        info:           "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty:      "Mostrando 0 a 0 de 0 registros",
        infoFiltered:   "(filtrado de _MAX_ registros totales)",
        infoPostFix:    "",
        loadingRecords: "Cargando...",
        zeroRecords:    "No se encontraron resultados",
        emptyTable:     "Ningún dato disponible en esta tabla",
        paginate: {
            first:      "Primero",
            previous:   "Anterior",
            next:       "Siguiente",
            last:       "Último"
        },
        aria: {
            sortAscending:  ": Activar para ordenar la columna de manera ascendente",
            sortDescending: ": Activar para ordenar la columna de manera descendente"
        }
    };

    // Función para reestructurar la paginación de DataTables
    function corregirPaginacionJS(settings) {
        const $wrapper = $(settings.nTableWrapper);
        const $paginate = $wrapper.find('.dataTables_paginate');

        $paginate.attr('style', 'display: flex !important; align-items: center !important; justify-content: flex-end !important; padding-top: 8px !important; background: transparent !important; border: none !important;');

        $wrapper.find('.dataTables_scrollBody, .dataTables_wrapper').css({
            'border': 'none',
            'box-shadow': 'none',
            'outline': 'none'
        });

        $paginate.find('a, span, .paginate_button').each(function() {
            const $btn = $(this);
            
            $btn.removeClass('paginate_button').css({
                'background': 'none',
                'border': 'none',
                'box-shadow': 'none',
                'outline': 'none'
            });

            const esActivo = $btn.hasClass('current');
            const esDeshabilitado = $btn.hasClass('disabled');
            const esNumero = !$btn.hasClass('previous') && !$btn.hasClass('next') && !$btn.hasClass('first') && !$btn.hasClass('last') && $btn.children().length === 0;

            if (esNumero) {
                $btn.attr('style', `
                    display: inline-flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    width: 32px !important;
                    height: 32px !important;
                    border-radius: 50% !important;
                    margin: 0 10px !important;
                    font-weight: bold !important;
                    font-size: 0.875rem !important;
                    text-decoration: none !important;
                    cursor: ${esDeshabilitado ? 'default' : 'pointer'} !important;
                    background-color: ${esActivo ? '#d9383a' : 'transparent'} !important;
                    color: ${esActivo ? '#ffffff' : '#6c757d'} !important;
                    border: none !important;
                `);
            } else if ($btn.hasClass('previous') || $btn.hasClass('next')) {
                $btn.attr('style', `
                    display: inline-block !important;
                    padding: 4px 8px !important;
                    margin: 0 5px !important;
                    font-size: 0.85rem !important;
                    text-decoration: none !important;
                    cursor: ${esDeshabilitado ? 'default' : 'pointer'} !important;
                    background: transparent !important;
                    color: ${esDeshabilitado ? '#cccccc' : '#6c757d'} !important;
                    opacity: ${esDeshabilitado ? '0.5' : '1'} !important;
                    border: none !important;
                `);
            }

            $btn.off('mouseenter mouseleave').on('mouseenter', function() {
                if (esDeshabilitado) return;
                if (esActivo) {
                    $(this).css('background-color', '#bd3133');
                } else if (esNumero) {
                    $(this).css({ 'background-color': '#e9ecef', 'color': '#212529' });
                } else {
                    $(this).css({ 'background-color': '#f8f9fa', 'color': '#212529' });
                }
            }).on('mouseleave', function() {
                if (esDeshabilitado) return;
                if (esActivo) {
                    $(this).css({ 'background-color': '#d9383a', 'color': '#ffffff' });
                } else {
                    $(this).css({ 'background-color': 'transparent', 'color': '#6c757d' });
                }
            });
        });
    }

    // ==========================================
    // DATATABLE: AUDITORÍA / HISTORIAL DE IPS
    // ==========================================
    if ($('#datatable_ips').length) {
        $('#datatable_ips').DataTable({
            autoWidth: false,
            responsive: true,
            bDestroy: true,
            pagingType: 'simple_numbers',
            ajax: {
                url: '?c=dashboard&a=listarIps',
                dataSrc: 'data'
            },
            columns: [
                { data: 'usuario_username', render: function(d) { return `<strong>${d}</strong>`; } },
                { data: 'usuario_nombre_completo' },
                { data: 'rol_name', render: function(d) { return `<span class="badge text-white" style="background-color: #6f42c1;">${d}</span>`; } },
                { data: 'ip_address', render: function(d) { return `<code class="text-danger fw-bold">${d}</code>`; } },
                { data: 'fecha_acceso' },
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function (data, type, row) {
                        // SVG directo del ícono de "ver" (ojo), idéntico al patrón limpio de tus SVGs de editar/desactivar
                        const svgVer = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>`;

                        return `
                            <button type="button" class="btn btn-ver-ip rounded-circle text-white shadow-sm" data-id="${row.id}" style="width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; background-color: #d9383a; border: none;" title="Ver Detalle">
                                ${svgVer}
                            </button>`;
                    }
                }
            ],
            language: lenguajeEspanol,
            drawCallback: function(settings) {
                corregirPaginacionJS(settings);
            }
        });
    }

    // ==========================================
    // LÓGICA DEL MODAL DE AUDITORÍA DE IP
    // ==========================================
    document.addEventListener('click', function(e) {
        const btnVerIp = e.target.closest('.btn-ver-ip');
        if (btnVerIp) {
            const id = btnVerIp.getAttribute('data-id');
            fetch(`?c=dashboard&a=getIpById&id=${id}`)
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        const d = res.data;
                        document.getElementById('det_ip_id').innerText = `#${d.id}`;
                        document.getElementById('det_ip_usuario').innerText = d.usuario_username;
                        document.getElementById('det_ip_nombre').innerText = d.usuario_nombre_completo;
                        document.getElementById('det_ip_correo').innerText = d.correo || '-';
                        document.getElementById('det_ip_rol').innerText = d.rol_name;
                        document.getElementById('det_ip_address').innerText = d.ip_address;
                        document.getElementById('det_ip_fecha').innerText = d.fecha_acceso;
                        
                        if (!modalIp && modalElement && typeof bootstrap !== 'undefined') {
                            modalIp = new bootstrap.Modal(modalElement);
                        }
                        if (modalIp) {
                            modalIp.show();
                        }
                    }
                });
        }
    });

    // ==========================================
    // KPIs + OCUPACIÓN POR TIPO + GRÁFICO DE INGRESOS
    // ==========================================
    function formatSoles(valor) {
        return `S/ ${parseFloat(valor).toFixed(2)}`;
    }

    function pintarKpis(resumen) {
        document.getElementById('kpi_vehiculos_activos').innerText = resumen.vehiculos_activos;
        document.getElementById('kpi_espacios_disponibles').innerText =
            `${resumen.espacios_disponibles} / ${resumen.capacidad_total}`;
        document.getElementById('kpi_ingresos_hoy').innerText = formatSoles(resumen.ingresos_hoy);
        document.getElementById('kpi_ingresos_mes').innerText = formatSoles(resumen.ingresos_mes);
    }

    function pintarOcupacionPorTipo(tipos) {
        const contenedor = document.getElementById('ocupacion-por-tipo');
        if (!contenedor) return;

        if (!Array.isArray(tipos) || tipos.length === 0) {
            contenedor.innerHTML = '<p class="text-muted extra-small mb-0">No hay tipos de plaza activos.</p>';
            return;
        }

        contenedor.innerHTML = tipos.map(tipo => {
            const pct = parseFloat(tipo.porcentaje);
            const colorBarra = pct >= 90 ? 'bg-danger' : (pct >= 60 ? 'bg-warning' : 'bg-success');
            return `
                <div class="mb-3">
                    <div class="d-flex justify-content-between extra-small mb-1">
                        <span class="fw-semibold">${tipo.nombre}</span>
                        <span class="text-muted">${tipo.espacios_ocupados} / ${tipo.capacidad_total} (${pct}%)</span>
                    </div>
                    <div class="progress" style="height: 10px; border-radius: 999px;">
                        <div class="progress-bar ${colorBarra}" role="progressbar" style="width: ${pct}%; border-radius: 999px;"></div>
                    </div>
                </div>
            `;
        }).join('');
    }

    let chartIngresos = null;
    function pintarChartIngresos(ingresos7Dias) {
        const canvas = document.getElementById('chart_ingresos_7_dias');
        if (!canvas || typeof Chart === 'undefined') return;

        const etiquetas = ingresos7Dias.map(d => {
            const fecha = new Date(d.fecha + 'T00:00:00');
            return fecha.toLocaleDateString('es-PE', { day: '2-digit', month: 'short' });
        });
        const totales = ingresos7Dias.map(d => parseFloat(d.total));

        if (chartIngresos) {
            chartIngresos.data.labels = etiquetas;
            chartIngresos.data.datasets[0].data = totales;
            chartIngresos.update();
            return;
        }

        chartIngresos = new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: etiquetas,
                datasets: [{
                    label: 'Ingresos (S/)',
                    data: totales,
                    backgroundColor: '#d9383a',
                    borderRadius: 6,
                    maxBarThickness: 36
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: (v) => `S/ ${v}` } }
                }
            }
        });
    }

    function cargarResumenDashboard() {
        fetch('?c=dashboard&a=resumen')
            .then(res => res.json())
            .then(result => {
                if (!result.success) return;
                pintarKpis(result.data.resumen);
                pintarOcupacionPorTipo(result.data.ocupacion);
                pintarChartIngresos(result.data.ingresos_7_dias);
            })
            .catch(() => {
                // Silencioso: si falla, las tarjetas quedan con "—"
            });
    }

    cargarResumenDashboard();
    // Refresca los KPIs cada 60s para reflejar ingresos/salidas recientes
    setInterval(cargarResumenDashboard, 60000);

});