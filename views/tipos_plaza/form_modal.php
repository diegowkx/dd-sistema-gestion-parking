<div class="modal fade modal-creative" id="modalTipoPlaza" tabindex="-1" aria-labelledby="modalTipoPlazaLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-5 overflow-hidden bg-white">

            <!-- Hero Header -->
            <div class="modal-header border-0 p-4 bg-gradient-primary text-white position-relative overflow-hidden">
                <div class="position-relative z-1 d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="modal-icon-badge rounded-circle bg-white-10 p-3 d-flex align-items-center justify-content-center backdrop-blur">
                            <i class="fas fa-square-parking fs-4 text-white"></i>
                        </div>
                        <div>
                            <span class="badge bg-white-20 text-white rounded-pill px-3 py-1 mb-1 extra-small text-uppercase tracking-wider">Módulo de Parqueo</span>
                            <h4 class="modal-title fw-bold fs-5 mb-0 text-white" id="modalTipoPlazaLabel">
                                <span id="lblTituloTipoPlaza">NUEVO TIPO DE PLAZA</span>
                            </h4>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white opacity-75 opacity-100-hover" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="header-bg-circle-1"></div>
                <div class="header-bg-circle-2"></div>
            </div>

            <form id="formTipoPlaza" autocomplete="off">
                <div class="modal-body p-4 p-md-5 bg-slate-50">
                    <input type="hidden" id="tp_id" name="id" value="">
                    <input type="hidden" id="tp_es_edicion" name="es_edicion" value="0">

                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="form-floating input-group-custom">
                                <input type="text" maxlength="50" class="form-control form-control-creative rounded-4 border-0 shadow-sm ps-4" id="tp_nombre" name="nombre" placeholder="Nombre" required>
                                <label for="tp_nombre" class="ps-4 text-muted fw-medium">Nombre (ej. AUTO, MOTO) <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating input-group-custom">
                                <input type="number" step="0.01" min="0.01" class="form-control form-control-creative rounded-4 border-0 shadow-sm ps-4" id="tp_precio_hora" name="precio_hora" placeholder="Precio" required>
                                <label for="tp_precio_hora" class="ps-4 text-muted fw-medium">Precio por Hora (S/) <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating input-group-custom">
                                <input type="number" step="1" min="1" class="form-control form-control-creative rounded-4 border-0 shadow-sm ps-4" id="tp_capacidad_total" name="capacidad_total" placeholder="Capacidad" required>
                                <label for="tp_capacidad_total" class="ps-4 text-muted fw-medium">Capacidad Total <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-text ps-2 extra-small" id="tp_espacios_ocupados_hint"></div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 p-4 bg-white d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light-creative rounded-pill px-4 py-2-5 text-slate-600 fw-semibold" data-bs-dismiss="modal">
                        <i class="fas fa-xmark me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2-5 text-white fw-bold shadow-glow" id="btnGuardarTipoPlaza" form="formTipoPlaza">
                        <i class="fas fa-save me-2"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
