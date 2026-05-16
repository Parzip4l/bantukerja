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

copyToClipboard();
initializeRepeaters();
