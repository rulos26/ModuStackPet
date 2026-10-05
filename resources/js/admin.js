/**
 * Admin shell assets previously loaded from CDN in layouts/app.blade.php.
 * Versions match package.json pins (same as the old CDN tags).
 *
 * Page scripts from @stack('scripts') / @yield('js') are stored in a
 * <template> so they do not run before this ES module exposes jQuery/Swal.
 */
import jQuery from 'jquery';
import 'bootstrap';
import Swal from 'sweetalert2';
import 'admin-lte/dist/js/adminlte.min.js';

window.$ = window.jQuery = jQuery;
window.Swal = Swal;

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
});

const flashSuccess = document.body?.dataset?.flashSuccess;
const flashError = document.body?.dataset?.flashError;

if (flashSuccess) {
    Toast.fire({ icon: 'success', title: flashSuccess });
}

if (flashError) {
    Toast.fire({ icon: 'error', title: flashError });
}

jQuery.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content'),
    },
});

function runDeferredPageScripts() {
    const template = document.getElementById('deferred-page-scripts');
    if (!template) {
        return;
    }

    template.content.querySelectorAll('script').forEach((oldScript) => {
        const script = document.createElement('script');
        for (const attr of oldScript.attributes) {
            script.setAttribute(attr.name, attr.value);
        }
        script.textContent = oldScript.textContent;
        document.body.appendChild(script);
    });
}

runDeferredPageScripts();
