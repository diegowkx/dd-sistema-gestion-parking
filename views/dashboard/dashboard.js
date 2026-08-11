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
                    render: function(row) {
                        return `
                            <button type="button" class="btn btn-ver-ip rounded-circle text-white shadow-sm" data-id="${row.id}" style="width: 32px; height: 32px; padding:0; background-color: #d9383a; border: none;">
                                <i class="fas fa-eye text-white"></i>
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

});