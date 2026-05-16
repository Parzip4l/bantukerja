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
