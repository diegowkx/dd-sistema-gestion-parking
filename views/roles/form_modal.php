<div class="modal fade modal-creative" id="modalRol" tabindex="-1" aria-labelledby="modalRolLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-5 overflow-hidden bg-white">

            <!-- Hero Header con Degradado Moderno -->
            <div class="modal-header border-0 p-4 bg-gradient-primary text-white position-relative overflow-hidden">
                <div class="position-relative z-1 d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="modal-icon-badge rounded-circle bg-white-10 p-3 d-flex align-items-center justify-content-center backdrop-blur">
                            <i class="fas fa-shield-halved fs-4 text-white"></i>
                        </div>
                        <div>
                            <span class="badge bg-white-20 text-white rounded-pill px-3 py-1 mb-1 extra-small text-uppercase tracking-wider">Gestión de Acceso</span>
                            <h4 class="modal-title fw-bold fs-5 mb-0 text-white" id="modalRolLabel">
                                <span id="lblTituloRol">CONFIGURAR ROL</span>
                            </h4>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white opacity-75 opacity-100-hover" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Círculos decorativos de fondo -->
                <div class="header-bg-circle-1"></div>
                <div class="header-bg-circle-2"></div>
            </div>

            <form id="formRol" autocomplete="off">
                <div class="modal-body p-4 p-md-5 bg-slate-50">
                    <input type="hidden" id="rol_id_hidden" name="id" value="">
                    <input type="hidden" id="es_edicion" name="es_edicion" value="0">

                    <!-- Input flotante -->
                    <div class="mb-4">
                        <div class="form-floating input-group-custom">
                            <input type="text" 
                                   class="form-control form-control-creative fw-bold text-uppercase rounded-4 border-0 shadow-sm ps-4" 
                                   id="nombre_rol" 
                                   name="nombre" 
                                   placeholder="Nombre del Rol" 
                                   maxlength="50" 
                                   required>
                            <label for="nombre_rol" class="ps-4 text-muted fw-medium">
                                Nombre del Rol <span class="text-danger">*</span>
                            </label>
                        </div>
                    </div>

                    <!-- Panel de selección de Permisos -->
                    <div class="permisos-wrapper rounded-4 p-4 bg-white shadow-sm border border-slate-100">
                        <div class="d-flex flex-wrap justify-content-between align-items-center pb-3 mb-3 border-bottom border-slate-100 gap-2">
                            <div>
                                <h6 class="fw-bold mb-1 text-slate-800 text-uppercase fs-7 tracking-wide">
                                    <i class="fas fa-key me-2 text-primary-custom"></i>Módulos & Permisos
                                </h6>
                                <p class="text-muted extra-small mb-0">Selecciona los módulos a los que este rol tendrá acceso total.</p>
                            </div>
                            <span class="badge badge-pill-glow px-3 py-2 fw-bold" id="lblCantPermisos">
                                0 seleccionados
                            </span>
                        </div>

                        <div class="row g-3 overflow-auto custom-scrollbar pe-1" id="rowPermisos" style="max-height: 310px;">
                            <?php if (!empty($permisosDisponibles)): ?>
                                <?php foreach ($permisosDisponibles as $permiso): ?>
                                    <div class="col-md-6">
                                        <label class="permiso-card-creative w-100 d-flex align-items-center justify-content-between p-3 rounded-4 cursor-pointer transition-all" for="permiso_<?php echo htmlspecialchars($permiso['nombre']); ?>">
                                            <div class="d-flex align-items-center gap-3 pe-2">
                                                <div class="permiso-icon-wrapper rounded-3 d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-layer-group fs-6"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold d-block text-uppercase text-slate-800 fs-7">
                                                        <?php echo htmlspecialchars($permiso['nombre']); ?>
                                                    </span>
                                                    <?php if (!empty($permiso['descripcion'])): ?>
                                                        <small class="text-muted extra-small d-block text-truncate-2 mt-1">
                                                            <?php echo htmlspecialchars($permiso['descripcion']); ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="form-check m-0 p-0 me-2">
                                                <!-- SE REINTEGRA LA CLASE chk-permiso REQUERIDA POR EL SCRIPT DE EDICIÓN -->
                                                <input class="form-check-input chk-permiso chk-permiso-creative rounded-circle" 
                                                       type="checkbox" 
                                                       name="permisos[]" 
                                                       value="<?php echo htmlspecialchars($permiso['nombre']); ?>" 
                                                       id="permiso_<?php echo htmlspecialchars($permiso['nombre']); ?>">
                                            </div>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12 text-center text-muted py-5">
                                    <i class="fas fa-folder-open fs-2 mb-2 opacity-50"></i>
                                    <p class="small mb-0">No se encontraron módulos disponibles en la base de datos.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <!-- Footer Flotante -->
                <div class="modal-footer border-0 p-4 bg-white d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light-creative rounded-pill px-4 py-2-5 text-slate-600 fw-semibold" data-bs-dismiss="modal">
                        <i class="fas fa-xmark me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2-5 text-white fw-bold shadow-glow" id="btnGuardarRol" form="formRol">
                        <i class="fas fa-check-circle me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>