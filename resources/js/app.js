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

const updateRepeaterLabels = (list) => {
    Array.from(list.querySelectorAll('[data-repeater-item]')).forEach((item, index) => {
        const label = item.querySelector('[data-repeater-title]');

        if (label) {
            label.textContent = `${label.dataset.repeaterTitle} #${index + 1}`;
        }
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
            updateRepeaterLabels(list);
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
        updateRepeaterLabels(list);
    });

    document.querySelectorAll('[data-repeater-list]').forEach((list) => {
        updateRepeaterLabels(list);
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

const initializeGeneratorPresets = () => {
    const fillRepeaterFromTemplate = (listSelector, templateSelector, values, key) => {
        const list = document.querySelector(listSelector);
        const template = document.querySelector(templateSelector);

        if (!list || !template || !Array.isArray(values) || values.length === 0) {
            return;
        }

        list.innerHTML = '';

        values.forEach((value, index) => {
            const html = template.innerHTML.replaceAll('__INDEX__', String(index));
            list.insertAdjacentHTML('beforeend', html);
        });

        Array.from(list.querySelectorAll('[data-repeater-item]')).forEach((item, index) => {
            Object.entries(values[index] || {}).forEach(([field, value]) => {
                const input = item.querySelector(`[name$="[${field}]"]`);

                if (input) {
                    input.value = value;
                }
            });
        });

        updateRepeaterLabels(list);
    };

    const sopPresetData = document.querySelector('#sop-preset-data');
    const sopSelect = document.querySelector('[data-sop-preset-select]');
    const sopButton = document.querySelector('[data-sop-preset-fill]');

    if (sopPresetData && sopSelect && sopButton) {
        const presets = JSON.parse(sopPresetData.textContent || '{}');

        const applySopPreset = () => {
            const values = presets[sopSelect.value];

            fillRepeaterFromTemplate('[data-sop-step-list]', '[data-repeater-template="sop-steps"]', values, 'steps');
        };

        sopButton.addEventListener('click', applySopPreset);
        sopSelect.addEventListener('change', () => {
            const list = document.querySelector('[data-sop-step-list]');
            const isEffectivelyEmpty = list && list.querySelectorAll('[data-repeater-item]').length <= 1
                && !Array.from(list.querySelectorAll('textarea, input[type="text"]')).some((field) => field.value.trim() !== '');

            if (isEffectivelyEmpty) {
                applySopPreset();
            }
        });
    }

    const jobPresetData = document.querySelector('#job-preset-data');
    const jobSelect = document.querySelector('[data-job-preset-select]');
    const jobButton = document.querySelector('[data-job-preset-fill]');

    if (jobPresetData && jobSelect && jobButton) {
        const presets = JSON.parse(jobPresetData.textContent || '{}');

        const applyJobPreset = () => {
            const preset = presets[jobSelect.value];

            if (!preset) {
                return;
            }

            document.querySelectorAll('[data-job-preset-field]').forEach((field) => {
                const key = field.getAttribute('data-job-preset-field');

                if (preset[key]) {
                    field.value = preset[key];
                }
            });

            fillRepeaterFromTemplate(
                '[data-job-responsibility-list]',
                '[data-repeater-template="job-responsibilities"]',
                (preset.responsibilities || []).map((text) => ({ text })),
                'responsibilities',
            );

            fillRepeaterFromTemplate(
                '[data-job-kpi-list]',
                '[data-repeater-template="job-kpis"]',
                (preset.kpis || []).map((text) => ({ text })),
                'kpis',
            );
        };

        jobButton.addEventListener('click', applyJobPreset);
        jobSelect.addEventListener('change', applyJobPreset);
    }
};

const initializeHomeSearch = () => {
    const form = document.querySelector('[data-home-search]');
    const input = document.querySelector('[data-home-search-input]');
    const results = document.querySelector('[data-home-search-results]');
    const source = document.querySelector('#home-search-index');

    if (!form || !input || !results || !source) {
        return;
    }

    const items = JSON.parse(source.textContent || '[]');

    const renderResults = (keyword) => {
        const normalizedKeyword = keyword.trim().toLowerCase();

        if (normalizedKeyword.length < 2) {
            results.innerHTML = '';
            results.classList.add('hidden');
            return;
        }

        const matches = items
            .filter((item) => {
                const haystack = `${item.title} ${item.description} ${item.type}`.toLowerCase();

                return haystack.includes(normalizedKeyword);
            })
            .slice(0, 6);

        if (!matches.length) {
            results.innerHTML = '<p class="px-2 py-1 text-sm text-slate-500">Belum ada hasil yang cocok. Coba kata kunci lain atau tekan cari untuk membuka halaman tools.</p>';
            results.classList.remove('hidden');
            return;
        }

        results.innerHTML = matches.map((item) => `
            <a href="${item.url}" class="block rounded-2xl px-3 py-3 transition hover:bg-white">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">${item.title}</p>
                        <p class="mt-1 text-sm leading-6 text-slate-500">${item.description || ''}</p>
                    </div>
                    <span class="shrink-0 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-600">${item.type}</span>
                </div>
            </a>
        `).join('');
        results.classList.remove('hidden');
    };

    input.addEventListener('input', () => {
        renderResults(input.value);
    });

    input.addEventListener('focus', () => {
        renderResults(input.value);
    });

    document.addEventListener('click', (event) => {
        if (form.contains(event.target)) {
            return;
        }

        results.classList.add('hidden');
    });
};

const initializeCareerToolPresets = () => {
    const source = document.querySelector('#career-tool-presets');

    if (!source) {
        return;
    }

    const presets = JSON.parse(source.textContent || '{}');

    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-career-preset-fill]');

        if (!button) {
            return;
        }

        const key = button.getAttribute('data-career-preset-fill');
        const select = document.querySelector(`[data-career-preset-select="${key}"]`);
        const preset = presets[select?.value || ''];

        if (!preset) {
            return;
        }

        const resolveValue = (object, key) => key.split('.').reduce((carry, part) => carry?.[part], object);

        document.querySelectorAll(`[data-career-preset-group="${key}"] [data-career-preset-field]`).forEach((field) => {
            const sourceKey = field.getAttribute('data-career-preset-field');
            const value = resolveValue(preset, sourceKey);

            if (value === undefined || value === null) {
                return;
            }

            field.value = Array.isArray(value) ? value.join(', ') : value;
        });
    });
};

const initializeAnalyticsEvents = () => {
    const normalizeScoreRange = (value) => {
        const numeric = Number.parseInt(value, 10);

        if (Number.isNaN(numeric)) {
            return value || undefined;
        }

        if (numeric >= 80) {
            return '80-100';
        }

        if (numeric >= 60) {
            return '60-79';
        }

        if (numeric >= 40) {
            return '40-59';
        }

        return '0-39';
    };

    const emit = (eventName, params = {}) => {
        const payload = {
            page_location: window.location.pathname,
            ...params,
        };

        if (typeof window.gtag === 'function') {
            window.gtag('event', eventName, payload);
            return;
        }

        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ event: eventName, ...payload });
    };

    document.addEventListener('submit', (event) => {
        const form = event.target.closest('[data-analytics-generate-form]');

        if (!form) {
            return;
        }

        emit(form.getAttribute('data-analytics-event') || 'career_tool_generate', {
            tool_name: form.getAttribute('data-tool-name') || 'unknown',
            action: 'generate',
            score_range: normalizeScoreRange(form.getAttribute('data-score-range')),
        });
    });

    document.addEventListener('click', (event) => {
        const copyTarget = event.target.closest('[data-analytics-copy]');

        if (copyTarget) {
            emit(copyTarget.getAttribute('data-analytics-event') || 'career_tool_copy', {
                tool_name: copyTarget.getAttribute('data-tool-name') || 'unknown',
                action: 'copy',
                score_range: normalizeScoreRange(copyTarget.getAttribute('data-score-range')),
            });
        }

        const exportTarget = event.target.closest('[data-analytics-export]');

        if (exportTarget) {
            emit(exportTarget.getAttribute('data-analytics-event') || 'career_tool_export', {
                tool_name: exportTarget.getAttribute('data-tool-name') || 'unknown',
                action: exportTarget.getAttribute('data-export-type') || 'export',
                score_range: normalizeScoreRange(exportTarget.getAttribute('data-score-range')),
            });
        }
    });
};

copyToClipboard();
initializeRepeaters();
initializeMobileMenu();
initializeRupiahInputs();
initializeGeneratorPresets();
initializeHomeSearch();
initializeCareerToolPresets();
initializeAnalyticsEvents();
