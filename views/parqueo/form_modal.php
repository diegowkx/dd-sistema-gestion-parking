<!-- =======================================================
     MODAL: NUEVO INGRESO
======================================================= -->
<div class="modal fade modal-creative" id="modalIngreso" tabindex="-1" aria-labelledby="modalIngresoLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-5 overflow-hidden bg-white">

            <div class="modal-header border-0 p-4 bg-gradient-primary text-white position-relative overflow-hidden">
                <div class="position-relative z-1 d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="modal-icon-badge rounded-circle bg-white-10 p-3 d-flex align-items-center justify-content-center backdrop-blur">
                            <i class="fas fa-car fs-4 text-white"></i>
                        </div>
                        <div>
                            <span class="badge bg-white-20 text-white rounded-pill px-3 py-1 mb-1 extra-small text-uppercase tracking-wider">Módulo de Parqueo</span>
                            <h4 class="modal-title fw-bold fs-5 mb-0 text-white" id="modalIngresoLabel">NUEVO INGRESO</h4>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white opacity-75 opacity-100-hover" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="header-bg-circle-1"></div>
                <div class="header-bg-circle-2"></div>
            </div>

            <form id="formIngreso" autocomplete="off">
                <div class="modal-body p-4 p-md-5 bg-slate-50">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="form-floating input-group-custom">
                                <input type="text" maxlength="15" class="form-control form-control-creative rounded-4 border-0 shadow-sm ps-4" id="ing_placa" name="placa" placeholder="Placa" required>
                                <label for="ing_placa" class="ps-4 text-muted fw-medium">Placa del Vehículo <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-floating input-group-custom">
                                <select class="form-select form-control-creative rounded-4 border-0 shadow-sm ps-4" id="ing_tipo_plaza" name="tipo_plaza_nombre" required>
                                    <option value="">-- Seleccionar --</option>
                                </select>
                                <label for="ing_tipo_plaza" class="ps-4 text-muted fw-medium">Tipo de Plaza <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-text ps-2 extra-small" id="ing_tipo_plaza_hint"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 bg-white d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light-creative rounded-pill px-4 py-2-5 text-slate-600 fw-semibold" data-bs-dismiss="modal">
                        <i class="fas fa-xmark me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2-5 text-white fw-bold shadow-glow" id="btnRegistrarIngreso" form="formIngreso">
                        <i class="fas fa-ticket me-2"></i>Registrar Ingreso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =======================================================
     MODAL: SALIDA / COBRO (también atiende Ticket Perdido)
======================================================= -->
<div class="modal fade modal-creative" id="modalSalida" tabindex="-1" aria-labelledby="modalSalidaLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-5 overflow-hidden bg-white">

            <div class="modal-header border-0 p-4 bg-gradient-primary text-white position-relative overflow-hidden">
                <div class="position-relative z-1 d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="modal-icon-badge rounded-circle bg-white-10 p-3 d-flex align-items-center justify-content-center backdrop-blur">
                            <i class="fas fa-right-from-bracket fs-4 text-white"></i>
                        </div>
                        <div>
                            <span class="badge bg-white-20 text-white rounded-pill px-3 py-1 mb-1 extra-small text-uppercase tracking-wider">Módulo de Parqueo</span>
                            <h4 class="modal-title fw-bold fs-5 mb-0 text-white" id="modalSalidaLabel">SALIDA Y COBRO</h4>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white opacity-75 opacity-100-hover" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="header-bg-circle-1"></div>
                <div class="header-bg-circle-2"></div>
            </div>

            <div class="modal-body p-4 p-md-5 bg-slate-50">

                <!-- Buscador (para Ticket Perdido: buscar por placa) -->
                <div id="salida_buscador" class="row g-3 mb-4">
                    <div class="col-8">
                        <div class="form-floating input-group-custom">
                            <input type="text" class="form-control form-control-creative rounded-4 border-0 shadow-sm ps-4" id="sal_buscar_placa" placeholder="Placa">
                            <label for="sal_buscar_placa" class="ps-4 text-muted fw-medium">Buscar por Placa</label>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <button type="button" class="btn btn-gradient-primary rounded-pill w-100 py-2-5 text-white fw-bold" id="btnBuscarTicket">
                            <i class="fas fa-magnifying-glass me-2"></i>Buscar
                        </button>
                    </div>
                </div>

                <!-- Detalle del ticket encontrado -->
                <div id="salida_detalle" class="d-none">
                    <div class="bg-white rounded-4 shadow-sm p-4 mb-3">
                        <div class="row g-3">
                            <div class="col-6"><span class="text-muted extra-small">Código</span><div class="fw-bold fs-6" id="sal_codigo">—</div></div>
                            <div class="col-6"><span class="text-muted extra-small">Placa</span><div class="fw-bold fs-6" id="sal_placa">—</div></div>
                            <div class="col-6"><span class="text-muted extra-small">Tipo de Plaza</span><div class="fw-bold fs-6" id="sal_tipo">—</div></div>
                            <div class="col-6"><span class="text-muted extra-small">Ingreso</span><div class="fw-bold fs-6" id="sal_ingreso">—</div></div>
                            <div class="col-12"><span class="text-muted extra-small">Tiempo Transcurrido</span><div class="fw-bold fs-6 text-primary-custom" id="sal_tiempo">—</div></div>
                        </div>
                    </div>
                    <div class="bg-white rounded-4 shadow-sm p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Horas cobradas</span>
                            <span class="fw-semibold" id="sal_horas">—</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-semibold" id="sal_subtotal">S/ 0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold fs-6">Total a Cobrar</span>
                            <span class="fw-bold fs-5 text-primary-custom" id="sal_total">S/ 0.00</span>
                        </div>
                        <p class="extra-small text-muted mb-0 mt-2">
                            Si corresponde aplicar un descuento o tolerancia, hazlo después desde el módulo de Tarifas.
                        </p>
                    </div>
                    <input type="hidden" id="sal_codigo_hidden" value="">
                </div>

                <div id="salida_vacio" class="text-center text-muted py-4">
                    <i class="fas fa-ticket fs-1 opacity-25 mb-2 d-block"></i>
                    Busca un ticket por código (desde la tabla) o por placa para ver el detalle del cobro.
                </div>
            </div>

            <div class="modal-footer border-0 p-4 bg-white d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-light-creative rounded-pill px-4 py-2-5 text-slate-600 fw-semibold" data-bs-dismiss="modal">
                    <i class="fas fa-xmark me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-gradient-primary rounded-pill px-5 py-2-5 text-white fw-bold shadow-glow d-none" id="btnConfirmarSalida">
                    <i class="fas fa-check me-2"></i>Confirmar Cobro y Salida
                </button>
            </div>
        </div>
    </div>
</div>
