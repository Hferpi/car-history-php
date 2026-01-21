import './bootstrap';

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
