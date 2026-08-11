$(document).ready(function () {

    const modalIngresoElement = document.getElementById('modalIngreso');
    const ingresoModal = modalIngresoElement ? new bootstrap.Modal(modalIngresoElement) : null;

    const modalSalidaElement = document.getElementById('modalSalida');
    const salidaModal = modalSalidaElement ? new bootstrap.Modal(modalSalidaElement) : null;

    const formIngreso = document.getElementById('formIngreso');
    const selectTipoPlaza = document.getElementById('ing_tipo_plaza');
    const hintTipoPlaza = document.getElementById('ing_tipo_plaza_hint');

    const salidaBuscador = document.getElementById('salida_buscador');
    const salidaDetalle = document.getElementById('salida_detalle');
    const salidaVacio = document.getElementById('salida_vacio');
    const btnConfirmarSalida = document.getElementById('btnConfirmarSalida');

    // ==========================================
    // RESTRICCIONES DE ENTRADA
    // ==========================================
    $('#ing_placa, #sal_buscar_placa').on('input', function () {
        this.value = this.value.replace(/[^a-zA-Z0-9-]/g, '').toUpperCase();
    });

    // ==========================================
    // FORMATEO DE TIEMPO TRANSCURRIDO
    // ==========================================
    function formatearTranscurrido(fechaIngresoStr) {
        const ingreso = new Date(fechaIngresoStr.replace(' ', 'T'));
        const ahora = new Date();
        let minutos = Math.max(0, Math.floor((ahora - ingreso) / 60000));
        const horas = Math.floor(minutos / 60);
        const mins = minutos % 60;
        return horas > 0 ? `${horas}h ${mins}min` : `${mins}min`;
    }

    // ==========================================
    // DATATABLE CONFIGURACIÓN
    // ==========================================
    const tabla = $('#datatable_parqueo').DataTable({
        autoWidth: false,
        serverSide: false,
        responsive: true,
        bDestroy: true,
        ajax: {
            url: '?c=parqueo&a=listar',
            dataSrc: function (json) {
                if (!json || !json.success || !Array.isArray(json.data)) return [];
                return json.data;
            }
        },
        columns: [
            {
                data: 'codigo',
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
            {
                data: 'fecha_hora_ingreso',
                render: function (data) {
                    return data.replace('T', ' ').substring(0, 16);
                }
            },
            {
                data: 'fecha_hora_ingreso',
                className: 'celda-tiempo-transcurrido',
                render: function (data) {
                    return `<span data-ingreso="${data}">${formatearTranscurrido(data)}</span>`;
                }
            },
            {
                data: 'estado',
                render: function () {
                    return '<span class="badge bg-success px-3 py-2">ACTIVO</span>';
                }
            },
            {
                data: null,
                orderable: false,
                className: 'text-center',
                render: function (row) {
                    const svgSalida = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>`;
                    return `
                        <button type="button" class="btn btn-gradient-primary text-white btn-registrar-salida rounded-pill shadow-sm px-3 py-2 d-inline-flex align-items-center gap-2" data-codigo="${row.codigo}" title="Registrar Salida">
                            ${svgSalida}<span>Salida</span>
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
            search: "Buscar por código o placa:",
            lengthMenu: "Mostrar _MENU_ registros",
            zeroRecords: "No hay tickets activos",
            emptyTable: "No hay vehículos en el parqueo actualmente",
            loadingRecords: "Cargando...",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros totales)"
        }
    });

    // Refresca la tabla completa cada 30s (para ver nuevos ingresos/salidas de otros operadores)
    setInterval(() => tabla.ajax.reload(null, false), 30000);

    // Actualiza el texto de "tiempo transcurrido" cada segundo sin recargar la tabla
    setInterval(() => {
        document.querySelectorAll('.celda-tiempo-transcurrido span[data-ingreso]').forEach(span => {
            span.textContent = formatearTranscurrido(span.getAttribute('data-ingreso'));
        });
    }, 1000);

    // ==========================================
    // MODAL INGRESO
    // ==========================================
    function cargarTiposDisponibles() {
        if (!selectTipoPlaza) return;
        selectTipoPlaza.innerHTML = '<option value="">Cargando...</option>';

        fetch('?c=parqueo&a=tiposDisponibles')
            .then(res => res.json())
            .then(result => {
                selectTipoPlaza.innerHTML = '<option value="">-- Seleccionar --</option>';
                if (result.success && Array.isArray(result.data) && result.data.length > 0) {
                    result.data.forEach(tipo => {
                        const opt = document.createElement('option');
                        opt.value = tipo.nombre;
                        opt.textContent = `${tipo.nombre} — S/ ${parseFloat(tipo.precio_hora).toFixed(2)}/h (${tipo.espacios_libres} libres)`;
                        selectTipoPlaza.appendChild(opt);
                    });
                    if (hintTipoPlaza) hintTipoPlaza.innerText = '';
                } else {
                    if (hintTipoPlaza) hintTipoPlaza.innerText = 'No hay tipos de plaza con espacio disponible en este momento.';
                }
            })
            .catch(() => {
                selectTipoPlaza.innerHTML = '<option value="">-- Seleccionar --</option>';
                if (hintTipoPlaza) hintTipoPlaza.innerText = 'No se pudo cargar la lista de tipos de plaza.';
            });
    }

    const btnNuevoIngreso = document.querySelector('.btn-nuevo-ingreso');
    if (btnNuevoIngreso && ingresoModal) {
        btnNuevoIngreso.addEventListener('click', function () {
            if (formIngreso) formIngreso.reset();
            cargarTiposDisponibles();
            ingresoModal.show();
        });
    }

    if (formIngreso) {
        formIngreso.addEventListener('submit', async function (e) {
            e.preventDefault();

            const placa = document.getElementById('ing_placa').value.trim();
            const tipo = document.getElementById('ing_tipo_plaza').value;

            if (!placa || placa.length < 5) {
                return Swal.fire('Atención', 'Ingresa una placa válida.', 'warning');
            }
            if (!tipo) {
                return Swal.fire('Atención', 'Selecciona un tipo de plaza.', 'warning');
            }

            const formData = new FormData(this);

            try {
                const res = await fetch('?c=parqueo&a=ingresar', { method: 'POST', body: formData });
                const data = await res.json();

                if (!data.success) {
                    throw new Error(data.message || 'Error al registrar el ingreso');
                }

                if (ingresoModal) ingresoModal.hide();
                tabla.ajax.reload(null, false);
                Swal.fire('¡Ingreso Registrado!', `Código de ticket: <b>${data.data.codigo}</b>`, 'success');
            } catch (err) {
                Swal.fire('Error', err.message || 'Error en el servidor', 'error');
            }
        });
    }

    // ==========================================
    // MODAL SALIDA / COBRO
    // ==========================================
    function mostrarDetalleTarifa(detalle) {
        document.getElementById('sal_codigo').innerText = detalle.codigo;
        document.getElementById('sal_placa').innerText = detalle.placa;
        document.getElementById('sal_tipo').innerText = detalle.tipo_plaza_nombre;
        document.getElementById('sal_ingreso').innerText = detalle.fecha_hora_ingreso.replace('T', ' ').substring(0, 16);
        document.getElementById('sal_tiempo').innerText = formatearTranscurrido(detalle.fecha_hora_ingreso);
        document.getElementById('sal_horas').innerText = `${detalle.horas_cobradas} h`;
        document.getElementById('sal_subtotal').innerText = `S/ ${parseFloat(detalle.subtotal).toFixed(2)}`;
        document.getElementById('sal_total').innerText = `S/ ${parseFloat(detalle.monto_total).toFixed(2)}`;
        document.getElementById('sal_codigo_hidden').value = detalle.codigo;

        if (salidaBuscador) salidaBuscador.classList.add('d-none');
        if (salidaVacio) salidaVacio.classList.add('d-none');
        if (salidaDetalle) salidaDetalle.classList.remove('d-none');
        if (btnConfirmarSalida) btnConfirmarSalida.classList.remove('d-none');
    }

    function resetModalSalida(mostrarBuscador) {
        if (salidaDetalle) salidaDetalle.classList.add('d-none');
        if (btnConfirmarSalida) btnConfirmarSalida.classList.add('d-none');
        if (salidaBuscador) salidaBuscador.classList.toggle('d-none', !mostrarBuscador);
        if (salidaVacio) salidaVacio.classList.toggle('d-none', mostrarBuscador === false);
        const inputBuscar = document.getElementById('sal_buscar_placa');
        if (inputBuscar) inputBuscar.value = '';
    }

    function cargarTarifaPorCodigo(codigo) {
        fetch(`?c=parqueo&a=calcularTarifa&codigo=${encodeURIComponent(codigo)}`)
            .then(res => res.json())
            .then(result => {
                if (!result.success) {
                    Swal.fire('Error', result.message || 'No se pudo calcular la tarifa', 'error');
                    return;
                }
                mostrarDetalleTarifa(result.data);
            })
            .catch(() => Swal.fire('Error', 'Error de conexión al calcular la tarifa', 'error'));
    }

    // Botón "Salida" en la tabla: abre el modal directo con el detalle ya cargado
    document.addEventListener('click', function (e) {
        const btnSalida = e.target.closest('.btn-registrar-salida');
        if (btnSalida && salidaModal) {
            const codigo = btnSalida.getAttribute('data-codigo');
            resetModalSalida(false);
            salidaModal.show();
            cargarTarifaPorCodigo(codigo);
        }
    });

    // Botón "Ticket Perdido": abre el modal en modo buscador (por placa)
    const btnTicketPerdido = document.querySelector('.btn-ticket-perdido');
    if (btnTicketPerdido && salidaModal) {
        btnTicketPerdido.addEventListener('click', function () {
            resetModalSalida(true);
            salidaModal.show();
        });
    }

    // Buscar ticket por placa dentro del modal de salida
    const btnBuscarTicket = document.getElementById('btnBuscarTicket');
    if (btnBuscarTicket) {
        btnBuscarTicket.addEventListener('click', function () {
            const placa = document.getElementById('sal_buscar_placa').value.trim();
            if (!placa) {
                return Swal.fire('Atención', 'Ingresa una placa para buscar.', 'warning');
            }

            fetch(`?c=parqueo&a=buscar&placa=${encodeURIComponent(placa)}`)
                .then(res => res.json())
                .then(result => {
                    if (!result.success) {
                        Swal.fire('No encontrado', result.message || 'No se encontró el ticket', 'warning');
                        return;
                    }
                    cargarTarifaPorCodigo(result.data.codigo);
                })
                .catch(() => Swal.fire('Error', 'Error de conexión al buscar el ticket', 'error'));
        });
    }

    // Confirmar cobro y salida
    if (btnConfirmarSalida) {
        btnConfirmarSalida.addEventListener('click', function () {
            const codigo = document.getElementById('sal_codigo_hidden').value;
            const total = document.getElementById('sal_total').innerText;

            Swal.fire({
                title: '¿Confirmar Cobro y Salida?',
                html: `Ticket <b>${codigo}</b><br>Total a cobrar: <b>${total}</b>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.isConfirmed) return;

                const formData = new FormData();
                formData.append('codigo', codigo);

                fetch('?c=parqueo&a=finalizar', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) {
                            Swal.fire('Error', data.message || 'No se pudo registrar la salida', 'error');
                            return;
                        }
                        if (salidaModal) salidaModal.hide();
                        tabla.ajax.reload(null, false);
                        Swal.fire('¡Salida Registrada!', data.message, 'success');
                    })
                    .catch(() => Swal.fire('Error', 'Error en la petición al servidor', 'error'));
            });
        });
    }

    // Al cerrar el modal de salida, lo deja listo para la próxima apertura
    if (modalSalidaElement) {
        modalSalidaElement.addEventListener('hidden.bs.modal', () => resetModalSalida(true));
    }
});
