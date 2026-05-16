const copyToClipboard = () => {
    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-copy-target]');

        if (!button) {
            return;
        }

        const selector = button.getAttribute('data-copy-target');
        const target = document.querySelector(selector);

        if (!target) {
            return;
        }

        const text = target.textContent?.trim() || '';

        if (!text) {
            return;
        }

        navigator.clipboard.writeText(text).then(() => {
            const original = button.textContent;
            button.textContent = 'Berhasil disalin';

            window.setTimeout(() => {
                button.textContent = original;
            }, 1800);
        });
    });
};

const initializeRepeaters = () => {
    const nextIndex = (list) => {
        const indexes = Array.from(list.querySelectorAll('[name]'))
            .map((field) => {
                const match = field.name.match(/\[(\d+)\]/);
                return match ? Number.parseInt(match[1], 10) : -1;
            })
            .filter((index) => index >= 0);

        return indexes.length ? Math.max(...indexes) + 1 : 0;
    };

    const updateLabels = (list) => {
        Array.from(list.querySelectorAll('[data-repeater-item]')).forEach((item, index) => {
            const label = item.querySelector('[data-repeater-title]');

            if (label) {
                label.textContent = `${label.dataset.repeaterTitle} #${index + 1}`;
            }
        });
    };

    document.addEventListener('click', (event) => {
        const addButton = event.target.closest('[data-repeater-add]');

        if (addButton) {
            const key = addButton.dataset.repeaterAdd;
            const list = document.querySelector(`[data-repeater-list="${key}"]`);
            const template = document.querySelector(`[data-repeater-template="${key}"]`);

            if (!list || !template) {
                return;
            }

            const index = nextIndex(list);
            const html = template.innerHTML.replaceAll('__INDEX__', String(index));
            list.insertAdjacentHTML('beforeend', html);
            updateLabels(list);
            return;
        }

        const removeButton = event.target.closest('[data-repeater-remove]');

        if (!removeButton) {
            return;
        }

        const item = removeButton.closest('[data-repeater-item]');
        const list = item?.parentElement;

        if (!item || !list) {
            return;
        }

        if (list.querySelectorAll('[data-repeater-item]').length <= 1) {
            return;
        }

        item.remove();
        updateLabels(list);
    });

    document.querySelectorAll('[data-repeater-list]').forEach((list) => {
        updateLabels(list);
    });
};

const initializeMobileMenu = () => {
    const toggle = document.querySelector('[data-mobile-menu-toggle]');
    const panel = document.querySelector('[data-mobile-menu-panel]');

    if (!toggle || !panel) {
        return;
    }

    const closeMenu = () => {
        panel.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
    };

    const openMenu = () => {
        panel.classList.remove('hidden');
        toggle.setAttribute('aria-expanded', 'true');
    };

    toggle.addEventListener('click', () => {
        if (panel.classList.contains('hidden')) {
            openMenu();
            return;
        }

        closeMenu();
    });

    document.addEventListener('click', (event) => {
        if (panel.classList.contains('hidden')) {
            return;
        }

        if (panel.contains(event.target) || toggle.contains(event.target)) {
            return;
        }

        closeMenu();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeMenu();
        }
    });
};

const initializeRupiahInputs = () => {
    const formatRupiah = (value) => {
        const digits = String(value ?? '').replace(/[^\d]/g, '');

        if (!digits) {
            return '';
        }

        return new Intl.NumberFormat('id-ID').format(Number.parseInt(digits, 10));
    };

    const applyFormatting = (input) => {
        input.value = formatRupiah(input.value);
    };

    document.addEventListener('input', (event) => {
        const input = event.target.closest('[data-rupiah-input]');

        if (!input) {
            return;
        }

        applyFormatting(input);
    });

    document.addEventListener('blur', (event) => {
        const input = event.target.closest('[data-rupiah-input]');

        if (!input) {
            return;
        }

        applyFormatting(input);
    }, true);

    document.querySelectorAll('[data-rupiah-input]').forEach((input) => {
        applyFormatting(input);
    });
};

copyToClipboard();
initializeRepeaters();
initializeMobileMenu();
initializeRupiahInputs();
