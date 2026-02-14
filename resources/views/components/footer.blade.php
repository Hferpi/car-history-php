<footer class="w-full lg:max-w-4xl max-w-83 mt-12 mb-6 border-t border-gray-200 dark:border-gray-700 flex flex-col gap-4 pt-6">


    <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
        <div>
            <p>&copy; {{ date('Y') }} <span class="font-bold text-cyan-900 dark:text-cyan-400">Car History</span>. Todos los derechos reservados.</p>
        </div>

        <div class="flex gap-6">
            <button onclick="toggleFooterSection('condiciones')" class="hover:text-blue-500 transition cursor-pointer">Condiciones de uso</button>
            <button onclick="toggleFooterSection('contacto')" class="hover:text-blue-500 transition cursor-pointer">Contactanos</button>
            <button onclick="toggleFooterSection('privacidad')" class="hover:text-blue-500 transition cursor-pointer">Privacidad</button>
        </div>
    </div>

  <div id="footer-content" class="hidden w-full mt-4 transition-all animate-fade-in">
    <div class="w-full md:w-2/3 mx-auto p-4 rounded-xl bg-cyan-100/50 dark:bg-cyan-900/20 border border-cyan-200 dark:border-cyan-800 shadow-sm">
        <p id="footer-text" class="text-sm text-cyan-900 dark:text-cyan-300 text-center leading-relaxed">
            </p>
    </div>
</div>
</footer>
