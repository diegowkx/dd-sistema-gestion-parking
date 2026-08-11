$(document).ready(function () {
    let estadoFiltro = 'ACTIVO';

    const modalTipoPlazaElement = document.getElementById('modalTipoPlaza');
    const tipoPlazaModal = modalTipoPlazaElement ? new bootstrap.Modal(modalTipoPlazaElement) : null;

    const formTipoPlaza = document.getElementById('formTipoPlaza');
    const lblTituloTipoPlaza = document.getElementById('lblTituloTipoPlaza');
    const inputNombre = document.getElementById('tp_nombre');
    const inputCapacidad = document.getElementById('tp_capacidad_total');
    const hintOcupados = document.getElementById('tp_espacios_ocupados_hint');

    let espaciosOcupadosActual = 0;

    // ==========================================
    // RESTRICCIONES DE ENTRADA EN TIEMPO REAL
    // ==========================================
    $('#tp_nombre').on('input', function () {
        this.value = this.value.replace(/[^a-zA-Z0-9\s]/g, '').toUpperCase();
    });

    // ==========================================
    // DATATABLE CONFIGURACIÓN
    // ==========================================
    const tabla = $('#datatable_tipos_plaza').DataTable({
        autoWidth: false,
        serverSide: false,
        responsive: true,
        bDestroy: true,
        ajax: {
            url: '?c=tipos_plaza&a=listar',
            data: function (d) { d.estado = estadoFiltro; },
            dataSrc: function (json) {
                if (!json || !json.success || !Array.isArray(json.data)) return [];
                return json.data;
            }
        },
        columns: [
            {
                data: 'nombre',
                render: function (data) {
                    return `<span class="fw-bold text-dark">${data}</span>`;
                }
            },
            {
                data: 'precio_hora',
                render: function (data) {
                    return `S/ ${parseFloat(data).toFixed(2)}`;
                }
            },
            { data: 'capacidad_total' },
            { data: 'espacios_ocupados' },
            {
                data: null,
                render: function (row) {
                    const libres = row.capacidad_total - row.espacios_ocupados;
                    const clase = libres <= 0 ? 'text-danger fw-bold' : 'text-success fw-bold';
                    return `<span class="${clase}">${libres}</span>`;
                }
            },
            {
                data: 'activo',
                render: function (data) {
                    return data == 1
                        ? '<span class="badge bg-success px-3 py-2">ACTIVO</span>'
                        : '<span class="badge bg-danger px-3 py-2">INACTIVO</span>';
                }
            },
            {
                data: null,
                orderable: false,
                className: 'text-center',
                render: function (row) {
                    const jsonTipo = encodeURIComponent(JSON.stringify(row));

                    const svgEditar = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>`;
                    const svgDesactivar = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>`;
                    const svgRestaurar = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>`;

                    let botones = `
                        <button type="button" class="btn btn-warning text-white btn-editar-tipo-plaza rounded-circle shadow-sm" data-tipo="${jsonTipo}" style="width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Editar">
                            ${svgEditar}
                        </button>
                    `;

                    if (row.activo == 1) {
                        botones += `
                            <button type="button" class="btn btn-danger btn-desactivar-tipo-plaza rounded-circle shadow-sm" data-id="${row.id}" data-nombre="${row.nombre}" style="width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Desactivar">
                                ${svgDesactivar}
                            </button>
                        `;
                    } else {
                        botones += `
                            <button type="button" class="btn btn-success btn-restaurar-tipo-plaza rounded-circle shadow-sm" data-id="${row.id}" data-nombre="${row.nombre}" style="width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Restaurar">
                                ${svgRestaurar}
                            </button>
                        `;
                    }

                    return `<div class="d-flex align-items-center justify-content-center gap-2">${botones}</div>`;
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
            zeroRecords: "No se encontraron tipos de plaza",
            emptyTable: "No hay tipos de plaza en esta sección",
            loadingRecords: "Cargando...",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros totales)"
        }
    });

    // Control de pestañas
    document.querySelectorAll('#pills-tab-tipos-plaza .tab-estado').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('#pills-tab-tipos-plaza .tab-estado').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            estadoFiltro = this.getAttribute('data-estado');
            tabla.ajax.reload();
        });
    });

    // Botón Nuevo Tipo de Plaza
    const btnNuevo = document.querySelector('.btn-nuevo-tipo-plaza');
    if (btnNuevo && tipoPlazaModal) {
        btnNuevo.addEventListener('click', function () {
            if (formTipoPlaza) formTipoPlaza.reset();

            document.getElementById('tp_id').value = '';
            document.getElementById('tp_es_edicion').value = '0';
            espaciosOcupadosActual = 0;
            if (inputNombre) inputNombre.readOnly = false;
            if (hintOcupados) hintOcupados.innerText = '';
            if (inputCapacidad) inputCapacidad.min = '1';

            if (lblTituloTipoPlaza) lblTituloTipoPlaza.innerText = 'NUEVO TIPO DE PLAZA';

            tipoPlazaModal.show();
        });
    }

    // Eventos delegados de tabla (Editar, Desactivar, Restaurar)
    document.addEventListener('click', function (e) {

        // --- EDITAR ---
        const btnEditar = e.target.closest('.btn-editar-tipo-plaza');
        if (btnEditar) {
            const rawData = btnEditar.getAttribute('data-tipo');
            const row = JSON.parse(decodeURIComponent(rawData));

            fetch(`?c=tipos_plaza&a=obtener&id=${encodeURIComponent(row.id)}`)
                .then(res => res.json())
                .then(result => {
                    if (!result.success) {
                        Swal.fire('Error', result.message || 'No se pudo cargar el tipo de plaza', 'error');
                        return;
                    }
                    const data = result.data;

                    if (formTipoPlaza) formTipoPlaza.reset();

                    document.getElementById('tp_id').value = data.id;
                    document.getElementById('tp_es_edicion').value = '1';
                    document.getElementById('tp_nombre').value = data.nombre;
                    document.getElementById('tp_precio_hora').value = data.precio_hora;
                    document.getElementById('tp_capacidad_total').value = data.capacidad_total;

                    espaciosOcupadosActual = parseInt(data.espacios_ocupados, 10) || 0;
                    if (inputCapacidad) inputCapacidad.min = String(espaciosOcupadosActual > 0 ? espaciosOcupadosActual : 1);
                    if (hintOcupados) {
                        hintOcupados.innerText = espaciosOcupadosActual > 0
                            ? `Actualmente hay ${espaciosOcupadosActual} espacio(s) ocupado(s). La capacidad no puede ser menor a ese número.`
                            : '';
                    }

                    if (lblTituloTipoPlaza) lblTituloTipoPlaza.innerText = 'EDITAR TIPO DE PLAZA: ' + data.nombre;

                    tipoPlazaModal.show();
                })
                .catch(() => Swal.fire('Error', 'Error de conexión al cargar el tipo de plaza', 'error'));
        }

        // --- DESACTIVAR ---
        const btnDesactivar = e.target.closest('.btn-desactivar-tipo-plaza');
        if (btnDesactivar) {
            const id = btnDesactivar.getAttribute('data-id');
            const nombre = btnDesactivar.getAttribute('data-nombre');

            Swal.fire({
                title: '¿Desactivar Tipo de Plaza?',
                text: `¿Deseas desactivar "${nombre}"? No podrá usarse para nuevos ingresos.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, Desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('id', id);

                    fetch('?c=tipos_plaza&a=eliminar', { method: 'POST', body: formData })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                tabla.ajax.reload(null, false);
                                Swal.fire('Desactivado', data.message, 'success');
                            } else {
                                Swal.fire('Error', data.message || 'Error al desactivar', 'error');
                            }
                        })
                        .catch(() => Swal.fire('Error', 'Error en la petición al servidor', 'error'));
                }
            });
        }

        // --- RESTAURAR ---
        const btnRestaurar = e.target.closest('.btn-restaurar-tipo-plaza');
        if (btnRestaurar) {
            const id = btnRestaurar.getAttribute('data-id');
            const nombre = btnRestaurar.getAttribute('data-nombre');

            Swal.fire({
                title: '¿Restaurar Tipo de Plaza?',
                text: `¿Deseas volver a activar "${nombre}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, Restaurar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('id', id);

                    fetch('?c=tipos_plaza&a=restaurar', { method: 'POST', body: formData })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                tabla.ajax.reload(null, false);
                                Swal.fire('Restaurado', data.message, 'success');
                            } else {
                                Swal.fire('Error', data.message || 'Error al restaurar', 'error');
                            }
                        })
                        .catch(() => Swal.fire('Error', 'Error en la petición al servidor', 'error'));
                }
            });
        }
    });

    // ==========================================
    // SUBMIT DEL FORMULARIO CON VALIDACIONES
    // ==========================================
    if (formTipoPlaza) {
        formTipoPlaza.addEventListener('submit', async function (e) {
            e.preventDefault();

            const nombre = document.getElementById('tp_nombre').value.trim();
            const precio = parseFloat(document.getElementById('tp_precio_hora').value);
            const capacidad = parseInt(document.getElementById('tp_capacidad_total').value, 10);

            if (!nombre) {
                return Swal.fire('Atención', 'El nombre es obligatorio.', 'warning');
            }

            if (isNaN(precio) || precio <= 0) {
                return Swal.fire('Atención', 'El precio por hora debe ser mayor que 0.', 'warning');
            }

            if (isNaN(capacidad) || capacidad <= 0 || !Number.isInteger(capacidad)) {
                return Swal.fire('Atención', 'La capacidad total debe ser un número entero mayor que 0.', 'warning');
            }

            if (capacidad < espaciosOcupadosActual) {
                return Swal.fire('Atención', `No puedes reducir la capacidad por debajo de los ${espaciosOcupadosActual} espacio(s) ocupado(s) actualmente.`, 'warning');
            }

            const formData = new FormData(this);

            try {
                const res = await fetch('?c=tipos_plaza&a=guardar', { method: 'POST', body: formData });
                const data = await res.json();

                if (!data.success) {
                    throw new Error(data.message || 'Error al procesar el tipo de plaza');
                }

                if (tipoPlazaModal) tipoPlazaModal.hide();
                tabla.ajax.reload(null, false);
                Swal.fire('¡Éxito!', data.message, 'success');
            } catch (err) {
                Swal.fire('Error', err.message || 'Error en el servidor', 'error');
            }
        });
    }
});
