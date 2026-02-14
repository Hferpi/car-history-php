import './bootstrap';
import { createIcons, Car, Plus, History, Wrench, BadgeCheck, Trash, Trash2, Bot } from 'lucide';

// =====================================================
// 1. Configuración de Iconos Lucide
// =====================================================
const initIcons = () => {
    createIcons({
        icons: { Car, Plus, History, Wrench, BadgeCheck, Trash2, Bot }
    });
};

// =====================================================
// 2. Lógica de Modo Oscuro
// =====================================================
const initTheme = () => {
    const toggle = document.getElementById('theme-toggle');
    const root = document.documentElement;

    const isDark =
        localStorage.theme === 'dark' ||
        (!localStorage.theme &&
            window.matchMedia('(prefers-color-scheme: dark)').matches);

    root.classList.toggle('dark', isDark);

    toggle?.addEventListener('click', () => {
        const isNowDark = root.classList.toggle('dark');
        localStorage.theme = isNowDark ? 'dark' : 'light';
    });
};

// =====================================================
// 3. Modelos dinámicos según Marca
// =====================================================
const initMarcaSelect = () => {
    const marcaSelect = document.getElementById('marca');
    const modeloSelect = document.getElementById('modelo');
    if (!marcaSelect || !modeloSelect) return;

    const marcas = JSON.parse(marcaSelect.dataset.marcas || '[]');

    marcaSelect.addEventListener('change', () => {
        const marcaId = marcaSelect.value;
        modeloSelect.innerHTML = '<option value="">Selecciona modelo</option>';
        modeloSelect.disabled = true;

        if (!marcaId) return;

        const marca = marcas.find(m => m.id == marcaId);
        if (marca?.modelos?.length) {
            marca.modelos.forEach(modelo => {
                const option = new Option(modelo.nombre, modelo.id);
                modeloSelect.add(option);
            });
            modeloSelect.disabled = false;
        }
    });
};

// =====================================================
// 4. Selector de Avatar
// =====================================================
const initAvatarSelector = () => {
    const buttons = document.querySelectorAll('.avatar-btn');
    const hiddenInput = document.getElementById('avatar-hidden');
    const previsualizacion = document.getElementById('img-avatar');
    if (!buttons.length || !hiddenInput) return;

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('ring-2', 'ring-blue-500'));
            btn.classList.add('ring-2', 'ring-blue-500');

            const val = btn.dataset.value;
            hiddenInput.value = val;
            if (previsualizacion) previsualizacion.src = `/${val}`;
        });
    });
};

// =====================================================
// INICIALIZACIÓN
// =====================================================
document.addEventListener('DOMContentLoaded', () => {
    initIcons();
    initTheme();
    initMarcaSelect();
    initAvatarSelector();
});

// =====================================================
// FUNCIONES GLOBALES (Accesibles desde HTML onclick)
// =====================================================

// -------- Modal de Imagen --------
window.openImageModal = function (src) {
    const modal = document.getElementById('imageModal');
    const img = document.getElementById('modalImage');
    if (!modal || !img) return;

    img.src = src;
    modal.classList.replace('hidden', 'flex');
    document.body.style.overflow = 'hidden';
};

window.closeImageModal = function () {
    const modal = document.getElementById('imageModal');
    if (!modal) return;

    modal.classList.replace('flex', 'hidden');
    document.body.style.overflow = 'auto';
};

// -------- Confirmar Borrado --------
window.confirmarBorrado = function (event, id) {
    event.stopPropagation();

    if (confirm('¿Estás seguro de que deseas eliminar este vehículo?')) {
        document.getElementById('delete-form-' + id)?.submit();
    }
};

// -------- Seleccionar Vehículo --------
window.seleccionarVehiculo = function (id, elemento) {

    document.querySelectorAll('.vehiculo-card').forEach(card => {
        card.className =
            "vehiculo-card relative cursor-pointer group rounded-2xl border border-gray-200 dark:border-gray-700 flex flex-col items-center bg-white dark:bg-gray-800 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden";

        const ib = card.querySelector('.info-box');
        if (ib) {
            ib.className =
                "info-box bg-gray-600 dark:bg-gray-950 w-full p-4 flex flex-col gap-3 text-white transition-colors duration-300";
        }

        const mt = card.querySelector('.marca-text');
        if (mt) {
            mt.className =
                "marca-text text-[10px] text-gray-300 dark:text-blue-400 uppercase font-bold tracking-widest";
        }
    });

    elemento.className =
        "vehiculo-card relative cursor-pointer group rounded-2xl border border-transparent ring-4 ring-blue-500 flex flex-col items-center bg-white shadow-lg transition-all duration-300 overflow-hidden";

    const infoBox = elemento.querySelector('.info-box');
    if (infoBox) {
        infoBox.className =
            "info-box bg-white w-full p-4 flex flex-col gap-3 text-gray-900 transition-colors duration-300";
    }

    const marcaText = elemento.querySelector('.marca-text');
    if (marcaText) {
        marcaText.className =
            "marca-text text-[10px] text-blue-600 uppercase font-bold tracking-widest";
    }

    fetch("/vehicles/select", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ id })
    });
};

// -------- Lógica del Footer --------
const footerTexts = {
    condiciones: "Al usar Car History, aceptas que el registro de mantenimientos es responsabilidad del usuario. No nos hacemos responsables de errores en la carga de datos externos.",
    contacto: "Soporte técnico: soporte@carhistory.com. Estamos disponibles de Lunes a Viernes para ayudarte con tus vehículos.",
    privacidad: "Tus datos están seguros. Las imágenes de tus facturas se procesan mediante IA únicamente para extraer información relevante y no se comparten con terceros."
};

window.toggleFooterSection = function (section) {
    const container = document.getElementById('footer-content');
    const textElement = document.getElementById('footer-text');
    const newText = footerTexts[section];

    if (!container || !textElement) return;

    if (!container.classList.contains('hidden') && textElement.innerText === newText) {
        container.classList.add('hidden');
    } else {
        textElement.innerText = newText;
        container.classList.remove('hidden');
        container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
};
