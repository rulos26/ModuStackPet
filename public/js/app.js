
// Detecta el tema preferido del sistema
function applySystemTheme() {
    const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

    // Verifica si el sistema tiene configurado el tema oscuro
    if (prefersDark) {
        document.documentElement.classList.add('dark-theme');
        // O puedes guardar esta preferencia en localStorage para usarla luego
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark-theme');
        localStorage.setItem('theme', 'light');
    }
}

// Aplica el tema al cargar la página
window.addEventListener('load', () => {
    // Verifica si hay un tema guardado en localStorage
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark-theme');
        } else {
            document.documentElement.classList.remove('dark-theme');
        }
    } else {
        // Si no hay un tema guardado, aplica el tema del sistema
        applySystemTheme();
    }

    // Escucha los cambios en el tema del sistema (si el usuario cambia la configuración)
    window.matchMedia("(prefers-color-scheme: dark)").addEventListener('change', applySystemTheme);
});
