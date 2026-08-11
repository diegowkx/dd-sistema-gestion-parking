<div class="modal fade modal-creative" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-5 overflow-hidden bg-white">

            <!-- Hero Header con Degradado Moderno -->
            <div class="modal-header border-0 p-4 bg-gradient-primary text-white position-relative overflow-hidden">
                <div class="position-relative z-1 d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="modal-icon-badge rounded-circle bg-white-10 p-3 d-flex align-items-center justify-content-center backdrop-blur">
                            <i class="fas fa-user-shield fs-4 text-white"></i>
                        </div>
                        <div>
                            <span class="badge bg-white-20 text-white rounded-pill px-3 py-1 mb-1 extra-small text-uppercase tracking-wider">Gestión de Personal</span>
                            <h4 class="modal-title fw-bold fs-5 mb-0 text-white" id="modalUsuarioLabel">
                                <span id="lblTituloUsuario">NUEVO USUARIO</span>
                            </h4>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white opacity-75 opacity-100-hover" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="header-bg-circle-1"></div>
                <div class="header-bg-circle-2"></div>
            </div>

            <form id="formUsuario" autocomplete="off">
                <div class="modal-body p-4 p-md-5 bg-slate-50">
                    <input type="hidden" id="es_edicion" name="es_edicion" value="0">

                    <div class="row g-4">
                        <!-- Inputs Flotantes Estilizados -->
                        <div class="col-md-6">
                            <div class="form-floating input-group-custom">
                                <input type="text" maxlength="15" class="form-control form-control-creative rounded-4 border-0 shadow-sm ps-4" id="documento_id" name="documento_id" placeholder="Documento" required>
                                <label for="documento_id" class="ps-4 text-muted fw-medium">Documento (DNI/CE) <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating input-group-custom">
                                <select class="form-select form-control-creative rounded-4 border-0 shadow-sm ps-4" id="rol_name" name="rol_name" required>
                                    <option value="">-- Seleccionar --</option>
                                    <?php if (!empty($rolesDisponibles)): foreach ($rolesDisponibles as $rol): ?>
                                        <option value="<?php echo htmlspecialchars($rol['nombre']); ?>"><?php echo htmlspecialchars($rol['nombre']); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                                <label for="rol_name" class="ps-4 text-muted fw-medium">Rol <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating input-group-custom">
                                <input type="text" class="form-control form-control-creative rounded-4 border-0 shadow-sm ps-4" id="username" name="username" placeholder="Usuario" autocomplete="username">
                                <label for="username" class="ps-4 text-muted fw-medium">Usuario <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating input-group-custom">
                                <input type="password" maxlength="70" class="form-control form-control-creative rounded-4 border-0 shadow-sm ps-4" id="password" name="password" placeholder="Contraseña" autocomplete="new-password">
                                <label for="password" class="ps-4 text-muted fw-medium" id="reqPasswordLabel">Contraseña <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating input-group-custom">
                                <input type="text" maxlength="100" class="form-control form-control-creative rounded-4 border-0 shadow-sm ps-4" id="nombres" name="nombres" placeholder="Nombres" required>
                                <label for="nombres" class="ps-4 text-muted fw-medium">Nombres <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating input-group-custom">
                                <input type="text" maxlength="100" class="form-control form-control-creative rounded-4 border-0 shadow-sm ps-4" id="apellidos" name="apellidos" placeholder="Apellidos" required>
                                <label for="apellidos" class="ps-4 text-muted fw-medium">Apellidos <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-floating input-group-custom">
                                <input type="email" maxlength="100" class="form-control form-control-creative rounded-4 border-0 shadow-sm ps-4" id="correo" name="correo" placeholder="Correo" required>
                                <label for="correo" class="ps-4 text-muted fw-medium">Correo Electrónico <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Flotante -->
                <div class="modal-footer border-0 p-4 bg-white d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light-creative rounded-pill px-4 py-2-5 text-slate-600 fw-semibold" data-bs-dismiss="modal">
                        <i class="fas fa-xmark me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2-5 text-white fw-bold shadow-glow" id="btnGuardarUsuario" form="formUsuario">
                        <i class="fas fa-save me-2"></i>Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>