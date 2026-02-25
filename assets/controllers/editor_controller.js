import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        // Cargamos el script principal de forma dinámica si no está en el base.html.twig
        console.log('[editor] connect', this.element);
        // Crear y mostrar un spinner mientras carga el editor
        try {
            this._editorSpinner = document.createElement('div');
            this._editorSpinner.className = 'editor-spinner d-inline-flex align-items-center gap-2';
            this._editorSpinner.style.marginBottom = '8px';
            this._editorSpinner.innerHTML = `<div class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></div><small> Cargando editor...</small>`;
            if (this.element && this.element.parentNode) {
                this.element.parentNode.insertBefore(this._editorSpinner, this.element);
            }
        } catch (e) {
            console.warn('[editor] no se pudo crear spinner', e);
        }

        const script = document.createElement('script');
        // Preferir la copia local de TinyMCE en public/assets, usar CDN solo como fallback
        script.src = '/assets/js/tinymce/tinymce.js';
        script.onload = () => {
            console.log('[editor] tinymce script loaded');
            try {
                tinymce.init({
                    target: this.element,
                    license_key: 'gpl', // Requerido en versiones recientes
                    language: 'es',     // Si bajaste el pack de idioma
                    menubar: false,
                        plugins: 'lists link image table code',
                        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | code | image',
                        images_upload_url: '/tinymce/upload',
                        images_upload_credentials: true,
                    // Usando CDN, no hace falta establecer base_url local
                    setup: (editor) => {
                        console.log('[editor] tinymce setup for element', editor.targetElm || this.element);
                        // Eliminar spinner cuando el editor esté listo
                        if (this._editorSpinner && this._editorSpinner.parentNode) {
                            this._editorSpinner.parentNode.removeChild(this._editorSpinner);
                            this._editorSpinner = null;
                        }
                    }
                });
                console.log('[editor] tinymce.init called');
            } catch (e) {
                console.error('[editor] tinymce.init error', e);
                if (this._editorSpinner && this._editorSpinner.parentNode) {
                    this._editorSpinner.parentNode.removeChild(this._editorSpinner);
                    this._editorSpinner = null;
                }
            }
        };

        // Si la copia local falla, intentar CDN
        script.onerror = (err) => {
            console.warn('[editor] local tinymce failed, attempting CDN fallback', err);
            const fallback = document.createElement('script');
            fallback.src = 'https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js';
            // Reuse the same onload handler
            fallback.onload = script.onload;
            fallback.onerror = (e) => {
                console.error('[editor] failed to load tinymce from local and CDN', e);
                if (this._editorSpinner && this._editorSpinner.parentNode) {
                    this._editorSpinner.innerHTML = '<small class="text-danger">Error cargando el editor</small>';
                }
            };
            document.head.appendChild(fallback);
        };
        script.onerror = (err) => {
            console.error('[editor] failed to load tinymce script', err);
        };
        document.head.appendChild(script);
    }

    disconnect() {
        console.log('[editor] disconnect', this.element);
        if (typeof tinymce !== 'undefined') {
            console.log('[editor] removing tinymce instance(s)');
            tinymce.remove();
        }
    }
}