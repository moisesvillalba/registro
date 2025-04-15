/**
 * Sistema de Registro - Scripts principales
 * Maneja la navegación multipaso, validaciones y UI interactiva
 */
document.addEventListener('DOMContentLoaded', function() {
  // Referencias de elementos del DOM
  const form = document.getElementById('registration-form');
  const formSteps = document.querySelectorAll('.form-step');
  const progressBar = document.getElementById('progress-bar');
  const progressSteps = document.querySelectorAll('.progress-step');
  const nextButtons = document.querySelectorAll('.next-step');
  const prevButtons = document.querySelectorAll('.prev-step');
  const submitButton = document.getElementById('submit-button');
  const fileInputs = document.querySelectorAll('input[type="file"]');
  
  // Inicializar la barra de progreso
  if (progressBar) {
      updateProgressBar();
  }
  
  // Manejar navegación entre pasos
  if (nextButtons.length) {
      nextButtons.forEach(button => {
          button.addEventListener('click', function() {
              const currentStep = parseInt(this.getAttribute('data-next')) - 1;
              const nextStep = parseInt(this.getAttribute('data-next'));
              
              // Validar campos del paso actual antes de avanzar
              if (validateStepFields(currentStep)) {
                  navigateToStep(nextStep);
                  updateProgressBar();
              } else {
                  showNotification('Por favor, complete todos los campos requeridos.', 'error');
              }
          });
      });
  }
  
  if (prevButtons.length) {
      prevButtons.forEach(button => {
          button.addEventListener('click', function() {
              const prevStep = parseInt(this.getAttribute('data-prev'));
              navigateToStep(prevStep);
              updateProgressBar();
          });
      });
  }
  
  // Navegación directa a través de los iconos de paso
  if (progressSteps.length) {
      progressSteps.forEach(step => {
          step.addEventListener('click', function() {
              const clickedStep = parseInt(this.getAttribute('data-step'));
              const currentStep = getCurrentStep();
              
              // Solo permitir ir hacia atrás o a pasos ya completados
              if (clickedStep < currentStep) {
                  navigateToStep(clickedStep);
                  updateProgressBar();
              }
          });
      });
  }
  
  // Manejo de archivos con vista previa
  if (fileInputs.length) {
      fileInputs.forEach(input => {
          input.addEventListener('change', handleFileSelect);
      });
  }
  
  // Manejar envío del formulario
  if (form) {
      form.addEventListener('submit', function(event) {
          // Validar todos los campos requeridos antes de enviar
          if (!validateAllFields()) {
              event.preventDefault();
              showNotification('Por favor, complete todos los campos requeridos.', 'error');
          }
      });
  }
  
  // Toggle para menú móvil
  const navToggle = document.querySelector('.nav-toggle');
  const navMenu = document.getElementById('main-menu');
  
  if (navToggle && navMenu) {
      navToggle.addEventListener('click', function() {
          navMenu.classList.toggle('active');
          this.setAttribute('aria-expanded', 
              this.getAttribute('aria-expanded') === 'true' ? 'false' : 'true');
      });
  }
  
  // Funciones auxiliares
  
  // Obtener el paso actual
  function getCurrentStep() {
      let currentStep = 1;
      formSteps.forEach((step, index) => {
          if (step.classList.contains('active')) {
              currentStep = index + 1;
          }
      });
      return currentStep;
  }
  
  // Navegar a un paso específico
  function navigateToStep(stepNumber) {
      formSteps.forEach((step, index) => {
          step.classList.remove('active');
          if (index + 1 === stepNumber) {
              step.classList.add('active');
          }
      });
      
      // Actualizar estados de los indicadores de paso
      progressSteps.forEach((step, index) => {
          step.classList.remove('active', 'completed');
          
          if (index + 1 < stepNumber) {
              step.classList.add('completed');
          } else if (index + 1 === stepNumber) {
              step.classList.add('active');
          }
      });
      
      // Hacer scroll hacia arriba
      window.scrollTo({
          top: 0,
          behavior: 'smooth'
      });
  }
  
  // Actualizar barra de progreso
  function updateProgressBar() {
      if (!progressBar) return;
      
      const currentStep = getCurrentStep();
      const totalSteps = formSteps.length;
      const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
      
      progressBar.style.width = `${progressPercentage}%`;
  }
  
  // Validar campos de un paso específico
  function validateStepFields(stepIndex) {
      const currentStep = formSteps[stepIndex];
      const requiredFields = currentStep.querySelectorAll('[required]');
      let isValid = true;
      
      requiredFields.forEach(field => {
          if (!field.value.trim()) {
              field.classList.add('form-control-error');
              
              // Mostrar mensaje de error si no existe
              let errorMessage = field.parentElement.querySelector('.error-message');
              if (!errorMessage) {
                  errorMessage = document.createElement('div');
                  errorMessage.className = 'error-message';
                  errorMessage.textContent = 'Este campo es requerido';
                  field.parentElement.appendChild(errorMessage);
              }
              
              isValid = false;
          } else {
              field.classList.remove('form-control-error');
              
              // Eliminar mensaje de error si existe
              const errorMessage = field.parentElement.querySelector('.error-message');
              if (errorMessage) {
                  field.parentElement.removeChild(errorMessage);
              }
          }
      });
      
      return isValid;
  }
  
  // Validar todos los campos requeridos
  function validateAllFields() {
      let isValid = true;
      
      // Validar cada paso
      formSteps.forEach((step, index) => {
          if (!validateStepFields(index)) {
              isValid = false;
          }
      });
      
      return isValid;
  }
  
  // Manejar selección de archivos
  function handleFileSelect(event) {
      const input = event.target;
      const files = input.files;
      
      // Limpiar vista previa anterior
      const previewContainer = input.closest('.form-group').querySelector('.file-preview');
      if (previewContainer) {
          previewContainer.innerHTML = '';
          
          if (files.length > 0) {
              for (let i = 0; i < files.length; i++) {
                  const file = files[i];
                  
                  // Crear contenedor de archivo
                  const fileElement = document.createElement('div');
                  fileElement.className = 'file-info';
                  
                  // Nombre y tamaño
                  const fileDetails = document.createElement('div');
                  fileDetails.innerHTML = `
                      <span class="file-name">${file.name}</span>
                      <span class="file-size">${formatFileSize(file.size)}</span>
                  `;
                  
                  fileElement.appendChild(fileDetails);
                  previewContainer.appendChild(fileElement);
                  
                  // Vista previa para imágenes
                  if (file.type.startsWith('image/')) {
                      const reader = new FileReader();
                      reader.onload = function(e) {
                          const img = document.createElement('img');
                          img.className = 'file-thumbnail';
                          img.src = e.target.result;
                          previewContainer.appendChild(img);
                      };
                      reader.readAsDataURL(file);
                  } else {
                      // Icono para archivos no imagen
                      const fileIcon = document.createElement('div');
                      fileIcon.className = 'file-icon';
                      fileIcon.innerHTML = `<i class="fas fa-file"></i>`;
                      previewContainer.appendChild(fileIcon);
                  }
              }
          }
      }
  }
  
  // Formatear tamaño de archivo
  function formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes';
      
      const k = 1024;
      const sizes = ['Bytes', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }
  
  // Mostrar notificación
  function showNotification(message, type = 'info') {
      const container = document.getElementById('notification-container');
      if (!container) return;
      
      const notification = document.createElement('div');
      notification.className = `notification notification-${type}`;
      
      notification.innerHTML = `
          <div class="notification-content">
              <div class="notification-message">${message}</div>
              <button class="notification-close">&times;</button>
          </div>
      `;
      
      container.appendChild(notification);
      
      // Mostrar con animación
      setTimeout(() => {
          notification.classList.add('show');
      }, 10);
      
      // Configurar cierre
      const closeButton = notification.querySelector('.notification-close');
      closeButton.addEventListener('click', () => {
          notification.classList.remove('show');
          setTimeout(() => {
              container.removeChild(notification);
          }, 300);
      });
      
      // Cerrar automáticamente después de 5 segundos
      setTimeout(() => {
          if (notification.parentNode) {
              notification.classList.remove('show');
              setTimeout(() => {
                  if (notification.parentNode) {
                      container.removeChild(notification);
                  }
              }, 300);
          }
      }, 5000);
  }
  
  // Efecto ripple para botones
  const buttons = document.querySelectorAll('.btn');
  buttons.forEach(button => {
      button.addEventListener('click', function(e) {
          const x = e.clientX - e.target.getBoundingClientRect().left;
          const y = e.clientY - e.target.getBoundingClientRect().top;
          
          const ripple = document.createElement('span');
          ripple.className = 'ripple';
          ripple.style.left = `${x}px`;
          ripple.style.top = `${y}px`;
          
          this.appendChild(ripple);
          
          setTimeout(() => {
              ripple.remove();
          }, 600);
      });
  });
});