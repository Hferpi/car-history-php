import './bootstrap';

// iconos lucide

import { createIcons, Car, Plus, History, Wrench, BadgeCheck } from 'lucide';

document.addEventListener('DOMContentLoaded', () => {
    createIcons({
        icons: {
            Car,
            Plus,
            History,
            Wrench,
            BadgeCheck,
        }
    });
});


// cambiar modo oscuro o claro

document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('theme-toggle');
    const root = document.documentElement;


    if (
        localStorage.theme === 'dark' ||
        (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        root.classList.add('dark');
    } else {
        root.classList.remove('dark');
    }

    toggle?.addEventListener('click', () => {
        root.classList.toggle('dark');

        localStorage.theme = root.classList.contains('dark')
            ? 'dark'
            : 'light';
    });
});



//Modelos en base a la marca

document.addEventListener('DOMContentLoaded', () => {
    const marcaSelect = document.getElementById('marca');
    const modeloSelect = document.getElementById('modelo');

    // Tomamos los datos desde el atributo
    const marcas = JSON.parse(marcaSelect.dataset.marcas);

    marcaSelect.addEventListener('change', () => {
        const marcaId = marcaSelect.value;

        modeloSelect.innerHTML = '<option value="">Cargando modelos...</option>';
        modeloSelect.disabled = true;

        if (!marcaId) {
            modeloSelect.innerHTML = '<option value="">Selecciona modelo</option>';
            return;
        }

        const marca = marcas.find(m => m.id == marcaId);

        modeloSelect.innerHTML = '<option value="">Selecciona modelo</option>';

        if (marca && marca.modelos.length) {
            marca.modelos.forEach(modelo => {
                const option = document.createElement('option');
                option.value = modelo.id;
                option.textContent = modelo.nombre;
                modeloSelect.appendChild(option);
            });
            modeloSelect.disabled = false;
        }
    });
});


//Avatar

document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.avatar-btn');
    const hiddenInput = document.getElementById('avatar-hidden');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Marcar seleccionado visualmente
            buttons.forEach(b => b.classList.remove('ring-2', 'ring-blue-500'));
            btn.classList.add('ring-2', 'ring-blue-500');

            // Guardar valor en el input oculto
            hiddenInput.value = btn.dataset.value;
        });
    });
});

