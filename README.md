# Sistema de Registro Integral

![Banner del Sistema de Registro](https://via.placeholder.com/800x200/1e3a8a/FFFFFF?text=Sistema+de+Registro)

## 📋 Descripción

Sistema de Registro es una aplicación web completa que permite registrar y administrar información de miembros, incluyendo datos personales, laborales, logiales y médicos. Diseñado con una interfaz amigable y fácil de usar tanto para los usuarios que registran información como para administradores.

✨ **Características principales:**
- Formulario multipaso intuitivo
- Panel administrativo para gestión de registros
- Subida y visualización de documentos
- Compatible con dispositivos móviles
- Fácil de instalar y personalizar

## 🚀 Instalación rápida

### Requisitos previos
- Servidor web con PHP 7.4 o superior
- MySQL 5.7 o MariaDB 10.3 o superior
- Acceso a un panel de control de hosting o FTP

### Pasos para instalar

1. **Descarga del código**
   ```
   git clone https://github.com/moisesvillalba/registro.git
   ```
   O descarga el [archivo ZIP](https://github.com/moisesvillalba/registro/archive/refs/heads/main.zip) y descomprímelo.

2. **Sube los archivos a tu servidor**
   - Transfiere todos los archivos y carpetas a la carpeta pública de tu servidor web (generalmente `public_html`, `www` o `htdocs`).

3. **Crea una base de datos**
   - Accede a tu panel de control y crea una nueva base de datos MySQL.
   - Crea un usuario con todos los permisos para esta base de datos.
   - Anota el nombre de la base de datos, el usuario y la contraseña.

4. **Ejecuta el instalador**
   - Abre tu navegador y accede a: `http://tu-dominio.com/install.php`
   - Completa el formulario con los datos de la base de datos.
   - Haz clic en "Instalar Sistema".

5. **¡Listo para usar!**
   - Accede al formulario de registro: `http://tu-dominio.com/index.php`
   - Accede al panel administrativo: `http://tu-dominio.com/login.php`
     - Usuario: `admin`
     - Contraseña: `Admin123`

> **⚠️ Importante:** Por seguridad, cambia la contraseña del administrador inmediatamente después de la instalación.

## 📖 Guía de uso

### Registrar un nuevo miembro

El formulario está dividido en 4 secciones para facilitar el registro:

1. **Datos Personales**: Información básica del miembro (nombre, CI, contacto, etc.)
2. **Datos Laborales**: Información sobre su trabajo
3. **Datos Logiales**: Información institucional
4. **Datos Médicos**: Información médica relevante

Para completar el registro:
- Rellena los campos en cada sección
- Navega entre secciones con los botones "Siguiente" y "Anterior"
- Completa todos los campos obligatorios (marcados con *)
- Haz clic en "Enviar Formulario" al finalizar

### Administrar registros

1. **Acceder al panel de administración**
   - Ingresa a: `http://tu-dominio.com/login.php`
   - Inicia sesión con tus credenciales

2. **Ver listado de miembros**
   - Verás una tabla con todos los registros
   - Usa la búsqueda para filtrar resultados
   - Navega entre páginas con los botones de paginación

3. **Acciones disponibles**
   - **Ver**: Muestra todos los detalles del registro
   - **Editar**: Permite modificar la información
   - **Eliminar**: Borra el registro (acción irreversible)

## 🛠️ Personalización

### Cambiar colores y estilos

El sistema usa variables CSS para facilitar la personalización:

1. Abre el archivo `assets/css/styles.css`
2. Busca la sección `:root` al inicio del archivo
3. Modifica los valores de las variables para cambiar los colores principales:
   ```css
   :root {
     --primary-color: #1e3a8a;  /* Color principal */
     --primary-light: #3b82f6;  /* Color principal claro */
     /* más variables... */
   }
   ```

### Ajustar permisos de carpetas

Si encuentras problemas para subir archivos:

1. Asegúrate de que las siguientes carpetas tengan permisos de escritura (755 o 775):
   - `/uploads`
   - `/uploads/documentos`

## 📱 Compatibilidad

El Sistema de Registro es completamente responsive y funciona en:
- Computadoras de escritorio
- Laptops
- Tablets
- Teléfonos móviles

Navegadores soportados:
- Chrome (recomendado)
- Firefox
- Edge
- Safari

## ❓ Solución de problemas comunes

### Error de conexión a la base de datos
- Verifica que los datos de conexión sean correctos
- Confirma que el usuario tenga permisos suficientes
- Asegúrate de que el servicio MySQL/MariaDB esté activo

### No se pueden subir archivos
- Verifica los permisos de la carpeta `uploads`
- Comprueba que los archivos no excedan el límite de tamaño (5MB)
- Asegúrate de usar formatos permitidos: JPG, PNG o PDF

### Problemas con el formulario
- Asegúrate de completar todos los campos obligatorios
- Verifica que no haya errores de formato (ej. fechas futuras)
- Comprueba tu conexión a internet

## 🔄 Actualizaciones y mantenimiento

Para mantener el sistema seguro y funcional:

1. **Copia de seguridad regular**
   - Descarga una copia de todos los archivos
   - Exporta la base de datos desde tu panel de control

2. **Verificación de integridad**
   - Comprueba periódicamente que el sistema funcione correctamente
   - Revisa los registros para asegurarte de que se guarden adecuadamente

## 📞 Soporte

¿Necesitas ayuda con la instalación o uso del sistema?

- GitHub: (https://github.com/moisesvillalba/registro/issues)
- Email: [moisesvillalba@gmail.com]

## 🔒 Seguridad

El sistema incluye:
- Protección contra inyección SQL
- Validación de entradas
- Sanitización de datos
- Protección básica contra CSRF
