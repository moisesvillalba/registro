<?php
// Iniciar sesión
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de registro integral para miembros. Complete sus datos personales, laborales, logiales y médicos.">
    <meta name="theme-color" content="#1e3a8a">
    <title>Formulario de Registro | Sistema Integral</title>
    
    <!-- Precargar CSS crítico -->
    <link rel="preload" href="assets/css/styles.css" as="style">
    <link rel="stylesheet" href="assets/css/styles.css">
    
    <!-- Precargar fuente de iconos -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="nav-container">
        <div class="logo">
            <a href="index.php">
                <i class="fas fa-clipboard-list"></i>
                Sistema de Registro
            </a>
        </div>
        <button class="nav-toggle" aria-label="Abrir menú" aria-expanded="false">
            <i class="fas fa-bars"></i>
        </button>
        <ul class="nav-menu" id="main-menu">
            <li><a href="index.php" class="active" aria-current="page">Inicio</a></li>
            <li><a href="login.php">Admin</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
    </nav>

    <main class="container">
        <div class="header">
            <h1>Formulario de Registro</h1>
            <p>Complete todos los campos requeridos para registrarse en nuestro sistema</p>
        </div>
        
        <div class="form-container">
            <!-- Pasos del formulario - Versión mejorada con iconos -->
            <div class="form-steps-container">
                <div class="progress-container">
                    <div class="progress-line"></div>
                    <div class="progress-bar" id="progress-bar"></div>
                </div>
                <div class="steps-nav">
                    <button class="progress-step active" data-step="1" aria-label="Ir a Datos Personales" type="button">
                        <div class="step-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="step-label">Datos Personales</div>
                    </button>
                    <button class="progress-step" data-step="2" aria-label="Ir a Datos Laborales" type="button">
                        <div class="step-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="step-label">Datos Laborales</div>
                    </button>
                    <button class="progress-step" data-step="3" aria-label="Ir a Datos Logiales" type="button">
                        <div class="step-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="step-label">Datos Logiales</div>
                    </button>
                    <button class="progress-step" data-step="4" aria-label="Ir a Datos Médicos" type="button">
                        <div class="step-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div class="step-label">Datos Médicos</div>
                    </button>
                </div>
            </div>
            
            <form action="process.php" method="POST" enctype="multipart/form-data" id="registration-form" novalidate>
                <!-- PASO 1: DATOS PERSONALES -->
                <section class="form-step active" data-step="1" aria-labelledby="paso1-titulo">
                    <div class="section">
                        <h2 class="section-title" id="paso1-titulo">
                            <i class="fas fa-user"></i> DATOS PERSONALES
                        </h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="ci">CI:<span class="required">*</span></label>
                                <input type="text" inputmode="numeric" id="ci" name="ci" class="form-control" required
                                       placeholder="Ingrese solo números" autocomplete="off" pattern="[0-9]*">
                                <small class="form-help">Número de Cédula de Identidad sin puntos</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="nombre">Nombre:<span class="required">*</span></label>
                                <input type="text" id="nombre" name="nombre" class="form-control" required
                                       placeholder="Ingrese su nombre" autocomplete="given-name">
                            </div>
                            
                            <div class="form-group">
                                <label for="apellido">Apellido:<span class="required">*</span></label>
                                <input type="text" id="apellido" name="apellido" class="form-control" required
                                       placeholder="Ingrese su apellido" autocomplete="family-name">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fecha_nacimiento">Fecha de Nacimiento:<span class="required">*</span></label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" required
                                       autocomplete="bday" max="<?php echo date('Y-m-d'); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="lugar">Lugar:<span class="required">*</span></label>
                                <input type="text" id="lugar" name="lugar" class="form-control" required
                                       placeholder="Lugar de nacimiento">
                            </div>
                            
                            <div class="form-group">
                                <label for="profesion">Profesión:<span class="required">*</span></label>
                                <input type="text" id="profesion" name="profesion" class="form-control" required
                                       placeholder="Ingrese su profesión">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="direccion">Dirección:<span class="required">*</span></label>
                                <input type="text" id="direccion" name="direccion" class="form-control" required
                                       placeholder="Dirección de residencia" autocomplete="street-address">
                            </div>
                            
                            <div class="form-group">
                                <label for="telefono">Teléfono:<span class="required">*</span></label>
                                <div class="input-with-icon">
                                    <i class="fas fa-phone"></i>
                                    <input type="tel" id="telefono" name="telefono" class="form-control" required
                                           placeholder="+595 (9XX) XXX-XXX" autocomplete="tel">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="ciudad">Ciudad:<span class="required">*</span></label>
                                <input type="text" id="ciudad" name="ciudad" class="form-control" required
                                       placeholder="Ciudad actual" list="ciudades-comunes" autocomplete="address-level2">
                                <datalist id="ciudades-comunes">
                                    <option value="Asunción">
                                    <option value="Ciudad del Este">
                                    <option value="Encarnación">
                                    <option value="San Lorenzo">
                                    <option value="Luque">
                                    <option value="Capiatá">
                                    <option value="Lambaré">
                                    <option value="Fernando de la Mora">
                                </datalist>
                            </div>
                            
                            <div class="form-group">
                                <label for="barrio">Barrio:<span class="required">*</span></label>
                                <input type="text" id="barrio" name="barrio" class="form-control" required
                                       placeholder="Barrio o sector">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="esposa">Esposa:</label>
                                <input type="text" id="esposa" name="esposa" class="form-control"
                                       placeholder="Nombre de esposa">
                                <small class="form-help">Opcional</small>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="hijos">Hijos:</label>
                                <textarea id="hijos" name="hijos" class="form-control" rows="2"
                                          placeholder="Nombres de hijos, separados por comas"></textarea>
                                <small class="form-help">Opcional</small>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="madre">Madre:</label>
                                <input type="text" id="madre" name="madre" class="form-control"
                                       placeholder="Nombre de madre">
                                <small class="form-help">Opcional</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="padre">Padre:</label>
                                <input type="text" id="padre" name="padre" class="form-control"
                                       placeholder="Nombre de padre">
                                <small class="form-help">Opcional</small>
                            </div>
                        </div>
                        
                        <div class="step-navigation">
                            <div></div> <!-- Espacio vacío para alineación -->
                            <button type="button" class="btn btn-primary next-step" data-next="2">
                                Siguiente <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </section>
                
                <!-- PASO 2: DATOS LABORALES -->
                <section class="form-step" data-step="2" aria-labelledby="paso2-titulo">
                    <div class="section">
                        <h2 class="section-title" id="paso2-titulo">
                            <i class="fas fa-briefcase"></i> DATOS LABORALES
                        </h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="direccion_laboral">Dirección Laboral:<span class="required">*</span></label>
                                <input type="text" id="direccion_laboral" name="direccion_laboral" class="form-control" required
                                       placeholder="Dirección de su trabajo" autocomplete="work street-address">
                            </div>
                            
                            <div class="form-group">
                                <label for="empresa">Empresa:</label>
                                <input type="text" id="empresa" name="empresa" class="form-control"
                                       placeholder="Nombre de la empresa" autocomplete="organization">
                                <small class="form-help">Opcional</small>
                            </div>
                        </div>
                        
                        <div class="step-navigation">
                            <button type="button" class="btn btn-outline prev-step" data-prev="1">
                                <i class="fas fa-arrow-left"></i> Anterior
                            </button>
                            <button type="button" class="btn btn-primary next-step" data-next="3">
                                Siguiente <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </section>
                
                <!-- PASO 3: DATOS LOGIALES -->
                <section class="form-step" data-step="3" aria-labelledby="paso3-titulo">
                    <div class="section">
                        <h2 class="section-title" id="paso3-titulo">
                            <i class="fas fa-monument"></i> DATOS LOGIALES
                        </h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="logia_actual">Logia Actual:<span class="required">*</span></label>
                                <input type="text" id="logia_actual" name="logia_actual" class="form-control" required
                                       placeholder="Nombre de la Logia">
                            </div>
                            
                            <div class="form-group">
                                <label for="grado_masonico">Grado Masónico:<span class="required">*</span></label>
                                <select id="grado_masonico" name="grado_masonico" class="form-control" required>
                                    <option value="">Seleccione Grado</option>
                                    <option value="aprendiz">Aprendiz</option>
                                    <option value="companero">Compañero</option>
                                    <option value="maestro">Maestro</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="grado_capitular">Grado Capitular:</label>
                                <select id="grado_capitular" name="grado_capitular" class="form-control">
                                    <option value="">Seleccione Grado</option>
                                    <option value="primer_grado">Primer Grado</option>
                                    <option value="segundo_grado">Segundo Grado</option>
                                    <option value="tercer_grado">Tercer Grado</option>
                                    <option value="no_aplica">No Aplica</option>
                                </select>
                                <small class="form-help">Opcional</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="fecha_iniciacion">Fecha de Iniciación:<span class="required">*</span></label>
                                <input type="date" id="fecha_iniciacion" name="fecha_iniciacion" class="form-control" required
                                       max="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="logia_iniciacion">Logia de Iniciación:<span class="required">*</span></label>
                                <input type="text" id="logia_iniciacion" name="logia_iniciacion" class="form-control" required
                                       placeholder="Nombre de la Logia de Iniciación">
                            </div>
                            
                            <div class="form-group">
                                <label for="certificados">Foto de Certificados:</label>
                                <div class="file-upload-container">
                                    <input type="file" id="certificados" name="certificados[]" multiple 
                                           class="form-control file-input" 
                                           accept="image/jpeg,image/png,application/pdf">
                                    <label for="certificados" class="file-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Seleccionar archivos</span>
                                    </label>
                                    <div class="file-preview"></div>
                                </div>
                                <small class="form-help">Formatos permitidos: PDF, JPG, PNG. Máximo 5MB. (Opcional)</small>
                            </div>
                        </div>
                        
                        <div class="step-navigation">
                            <button type="button" class="btn btn-outline prev-step" data-prev="2">
                                <i class="fas fa-arrow-left"></i> Anterior
                            </button>
                            <button type="button" class="btn btn-primary next-step" data-next="4">
                                Siguiente <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </section>
                
                <!-- PASO 4: DATOS MÉDICOS -->
                <section class="form-step" data-step="4" aria-labelledby="paso4-titulo">
                    <div class="section">
                        <h2 class="section-title" id="paso4-titulo">
                            <i class="fas fa-heartbeat"></i> DATOS MÉDICOS
                        </h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="grupo_sanguineo">Grupo Sanguíneo:<span class="required">*</span></label>
                                <select id="grupo_sanguineo" name="grupo_sanguineo" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="enfermedades_base">Enfermedades Base:</label>
                                <textarea id="enfermedades_base" name="enfermedades_base" class="form-control" rows="2"
                                          placeholder="Liste enfermedades crónicas o de base"></textarea>
                                <small class="form-help">Opcional</small>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <fieldset class="radio-fieldset">
                                    <legend>¿Cuenta con Seguro Privado?</legend>
                                    <div class="radio-options">
                                        <div class="radio-group">
                                            <input type="radio" id="seguro_si" name="seguro_privado" value="Si">
                                            <label for="seguro_si">Sí</label>
                                        </div>
                                        <div class="radio-group">
                                            <input type="radio" id="seguro_no" name="seguro_privado" value="No" checked>
                                            <label for="seguro_no">No</label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            
                            <div class="form-group">
                                <fieldset class="radio-fieldset">
                                    <legend>¿IPS?</legend>
                                    <div class="radio-options">
                                        <div class="radio-group">
                                            <input type="radio" id="ips_si" name="ips" value="Si">
                                            <label for="ips_si">Sí</label>
                                        </div>
                                        <div class="radio-group">
                                            <input type="radio" id="ips_no" name="ips" value="No" checked>
                                            <label for="ips_no">No</label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="alergias">Alergias:</label>
                                <textarea id="alergias" name="alergias" class="form-control" rows="2"
                                          placeholder="Liste medicamentos o sustancias a las que es alérgico"></textarea>
                                <small class="form-help">Opcional</small>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="numero_emergencia">Número en caso de Emergencias:<span class="required">*</span></label>
                                <div class="input-with-icon">
                                    <i class="fas fa-phone"></i>
                                    <input type="tel" id="numero_emergencia" name="numero_emergencia" class="form-control" required
                                           placeholder="+595 (9XX) XXX-XXX">
                                </div>
                                <small class="form-help">Teléfono de contacto en caso de emergencia</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="contacto_emergencia">Contacto en Caso de Emergencias:<span class="required">*</span></label>
                                <input type="text" id="contacto_emergencia" name="contacto_emergencia" class="form-control" required
                                       placeholder="Nombre del contacto de emergencia">
                            </div>
                        </div>
                        
                        <div class="step-navigation">
                            <button type="button" class="btn btn-outline prev-step" data-prev="3">
                                <i class="fas fa-arrow-left"></i> Anterior
                            </button>
                            <button type="submit" class="btn btn-success" id="submit-button">
                                <i class="fas fa-save"></i> Enviar Formulario
                            </button>
                        </div>
                        
                        <!-- Indicador de campos requeridos -->
                        <div class="required-fields-note">
                            <span class="required">*</span> Campos requeridos
                        </div>
                    </div>
                </section>
            </form>
        </div>
        
        <!-- Notificaciones -->
        <div id="notification-container" aria-live="polite"></div>
    </main>
    
    <footer class="footer">
        <div class="footer-content flex justify-center items-center space-x-4">
            <p>&copy; <?php echo date('Y'); ?> Sistema de Registro. Todos los derechos reservados.</p>
            <nav class="footer-links flex space-x-4">
                <a href="#">Términos y Condiciones</a>
                <a href="#">Política de Privacidad</a>
                <a href="#">Contacto</a>
            </nav>
        </div>
    </footer>
    
    <!-- Scripts - Carga diferida para mejor rendimiento -->
    <script src="assets/js/script.js" defer></script>
</body>
</html>