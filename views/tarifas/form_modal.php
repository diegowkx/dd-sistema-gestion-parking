<div class="modal fade modal-creative" id="modalDescuento" tabindex="-1" aria-labelledby="modalDescuentoLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-5 overflow-hidden bg-white">

            <div class="modal-header border-0 p-4 bg-gradient-primary text-white position-relative overflow-hidden">
                <div class="position-relative z-1 d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="modal-icon-badge rounded-circle bg-white-10 p-3 d-flex align-items-center justify-content-center backdrop-blur">
                            <i class="fas fa-tags fs-4 text-white"></i>
                        </div>
                        <div>
                            <span class="badge bg-white-20 text-white rounded-pill px-3 py-1 mb-1 extra-small text-uppercase tracking-wider">Ajuste de Cobro</span>
                            <h4 class="modal-title fw-bold fs-5 mb-0 text-white" id="modalDescuentoLabel">
                                Descuento / Tolerancia — <span id="desc_codigo_titulo">—</span>
                            </h4>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white opacity-75 opacity-100-hover" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="header-bg-circle-1"></div>
                <div class="header-bg-circle-2"></div>
            </div>

            <form id="formDescuento" autocomplete="off">
                <div class="modal-body p-4 p-md-5 bg-slate-50">
                    <input type="hidden" id="desc_parking_codigo" name="parking_codigo" value="">

                    <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Placa</span>
                            <span class="fw-semibold" id="desc_placa">—</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-semibold" id="desc_subtotal">S/ 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Descuento actual</span>
                            <span class="fw-semibold" id="desc_actual">S/ 0.00</span>
                        </div>
                    </div>

                    <div class="form-floating input-group-custom">
                        <input type="number" step="0.01" min="0" class="form-control form-control-creative rounded-4 border-0 shadow-sm ps-4" id="desc_monto" name="descuento" placeholder="Descuento" required>
                        <label for="desc_monto" class="ps-4 text-muted fw-medium">Nuevo Monto de Descuento (S/) <span class="text-danger">*</span></label>
                    </div>
                    <p class="extra-small text-muted mt-2 mb-0">
                        No puede ser negativo ni superar el subtotal. El nuevo total se calculará como subtotal − descuento.
                    </p>
                </div>

                <div class="modal-footer border-0 p-4 bg-white d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light-creative rounded-pill px-4 py-2-5 text-slate-600 fw-semibold" data-bs-dismiss="modal">
                        <i class="fas fa-xmark me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2-5 text-white fw-bold shadow-glow" id="btnGuardarDescuento" form="formDescuento">
                        <i class="fas fa-save me-2"></i>Aplicar Descuento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
