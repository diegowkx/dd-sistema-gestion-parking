$(document).ready(function () {

    const modalDescuentoElement = document.getElementById('modalDescuento');
    const descuentoModal = modalDescuentoElement ? new bootstrap.Modal(modalDescuentoElement) : null;

    const formDescuento = document.getElementById('formDescuento');
    let subtotalActual = 0;

    // ==========================================
    // DATATABLE CONFIGURACIÓN
    // ==========================================
    const tabla = $('#datatable_tarifas').DataTable({
        autoWidth: false,
        serverSide: false,
        responsive: true,
        bDestroy: true,
        order: [[7, 'desc']],
        ajax: {
            url: '?c=tarifas&a=listar',
            dataSrc: function (json) {
                if (!json || !json.success || !Array.isArray(json.data)) return [];
                return json.data;
            }
        },
        columns: [
            {
                data: 'parking_codigo',
                render: function (data) {
                    return `<span class="fw-bold text-dark">${data}</span>`;
                }
            },
            { data: 'placa' },
            {
                data: 'tipo_plaza_nombre',
                render: function (data) {
                    return `<span class="badge bg-secondary fs-7">${data}</span>`;
                }
            },
            { data: 'minutos_totales' },
            {
                data: 'subtotal',
                render: function (data) {
                    return `S/ ${parseFloat(data).toFixed(2)}`;
                }
            },
            {
                data: 'descuento',
                render: function (data) {
                    const val = parseFloat(data);
                    return val > 0
                        ? `<span class="text-danger fw-semibold">- S/ ${val.toFixed(2)}</span>`
                        : `<span class="text-muted">S/ 0.00</span>`;
                }
            },
            {
                data: 'monto_total',
                render: function (data) {
                    return `<span class="fw-bold text-primary-custom">S/ ${parseFloat(data).toFixed(2)}</span>`;
                }
            },
            {
                data: 'fecha_hora_pago',
                render: function (data) {
                    return data.replace('T', ' ').substring(0, 16);
                }
            },
            {
                data: null,
                orderable: false,
                className: 'text-center',
                render: function (row) {
                    const jsonCobro = encodeURIComponent(JSON.stringify(row));
                    const svgDescuento = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 11 3.83A2 2 0 0 0 9.59 3.17H4a1 1 0 0 0-1 1v5.59a2 2 0 0 0 .59 1.41l9.58 9.58a2 2 0 0 0 2.82 0l4.6-4.6a2 2 0 0 0 0-2.82Z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>`;

                    return `
                        <button type="button" class="btn btn-warning text-white btn-aplicar-descuento rounded-circle shadow-sm" data-cobro="${jsonCobro}" style="width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Aplicar descuento / tolerancia">
                            ${svgDescuento}
                        </button>
                    `;
                }
            }
        ],
        language: {
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
            },
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            zeroRecords: "No se encontraron cobros",
            emptyTable: "Aún no hay cobros registrados",
            loadingRecords: "Cargando...",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros totales)"
        }
    });

    // --- ABRIR MODAL DE DESCUENTO ---
    document.addEventListener('click', function (e) {
        const btnDescuento = e.target.closest('.btn-aplicar-descuento');
        if (btnDescuento && descuentoModal) {
            const rawData = btnDescuento.getAttribute('data-cobro');
            const row = JSON.parse(decodeURIComponent(rawData));

            if (formDescuento) formDescuento.reset();

            subtotalActual = parseFloat(row.subtotal);

            document.getElementById('desc_parking_codigo').value = row.parking_codigo;
            document.getElementById('desc_codigo_titulo').innerText = row.parking_codigo;
            document.getElementById('desc_placa').innerText = row.placa;
            document.getElementById('desc_subtotal').innerText = `S/ ${subtotalActual.toFixed(2)}`;
            document.getElementById('desc_actual').innerText = `S/ ${parseFloat(row.descuento).toFixed(2)}`;
            document.getElementById('desc_monto').value = row.descuento;
            document.getElementById('desc_monto').max = String(subtotalActual);

            descuentoModal.show();
        }
    });

    // --- SUBMIT DEL FORMULARIO DE DESCUENTO ---
    if (formDescuento) {
        formDescuento.addEventListener('submit', async function (e) {
            e.preventDefault();

            const descuento = parseFloat(document.getElementById('desc_monto').value);

            if (isNaN(descuento) || descuento < 0) {
                return Swal.fire('Atención', 'El descuento no puede ser negativo.', 'warning');
            }
            if (descuento > subtotalActual) {
                return Swal.fire('Atención', `El descuento no puede superar el subtotal (S/ ${subtotalActual.toFixed(2)}).`, 'warning');
            }

            const formData = new FormData(this);

            try {
                const res = await fetch('?c=tarifas&a=aplicarDescuento', { method: 'POST', body: formData });
                const data = await res.json();

                if (!data.success) {
                    throw new Error(data.message || 'Error al aplicar el descuento');
                }

                if (descuentoModal) descuentoModal.hide();
                tabla.ajax.reload(null, false);
                Swal.fire('¡Éxito!', data.message, 'success');
            } catch (err) {
                Swal.fire('Error', err.message || 'Error en el servidor', 'error');
            }
        });
    }
});
