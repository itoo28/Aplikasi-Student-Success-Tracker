const confirmDialog = document.getElementById('appConfirmDialog');

if (confirmDialog) {
    const overlay = confirmDialog.querySelector('[data-confirm-overlay]');
    const titleElement = document.getElementById('appConfirmDialogTitle');
    const messageElement = document.getElementById('appConfirmDialogMessage');
    const iconWrapElement = document.getElementById('appConfirmDialogIconWrap');
    const accentElement = document.getElementById('appConfirmDialogAccent');
    const cancelButton = confirmDialog.querySelector('[data-confirm-cancel]');
    const approveButton = confirmDialog.querySelector('[data-confirm-approve]');

    const variantClasses = {
        danger: {
            iconWrap: ['bg-rose-100', 'text-rose-700'],
            accent: ['from-rose-500', 'via-amber-400', 'to-orange-500'],
            button: ['from-rose-600', 'to-orange-500', 'hover:from-rose-700', 'hover:to-orange-600'],
            shadow: 'shadow-[0_10px_22px_rgba(244,63,94,0.35)]',
        },
        warning: {
            iconWrap: ['bg-amber-100', 'text-amber-700'],
            accent: ['from-amber-500', 'via-orange-400', 'to-rose-500'],
            button: ['from-amber-500', 'to-orange-500', 'hover:from-amber-600', 'hover:to-orange-600'],
            shadow: 'shadow-[0_10px_22px_rgba(245,158,11,0.35)]',
        },
        primary: {
            iconWrap: ['bg-indigo-100', 'text-indigo-700'],
            accent: ['from-indigo-500', 'via-sky-400', 'to-cyan-500'],
            button: ['from-indigo-600', 'to-sky-500', 'hover:from-indigo-700', 'hover:to-sky-600'],
            shadow: 'shadow-[0_10px_22px_rgba(79,70,229,0.35)]',
        },
    };

    let activeForm = null;
    let lastFocusedElement = null;

    const resetApproveButtonState = () => {
        approveButton.disabled = false;
        approveButton.classList.remove('opacity-70', 'cursor-not-allowed');
    };

    const applyVariant = (variant = 'danger') => {
        const selectedVariant = variantClasses[variant] ?? variantClasses.danger;
        const allVariants = Object.values(variantClasses);

        allVariants.forEach((style) => {
            iconWrapElement.classList.remove(...style.iconWrap);
            accentElement.classList.remove(...style.accent);
            approveButton.classList.remove(...style.button);
            approveButton.classList.remove(style.shadow);
        });

        iconWrapElement.classList.add(...selectedVariant.iconWrap);
        accentElement.classList.add(...selectedVariant.accent);
        approveButton.classList.add(...selectedVariant.button);
        approveButton.classList.add(selectedVariant.shadow);
    };

    const closeDialog = ({ restoreFocus = true } = {}) => {
        confirmDialog.classList.add('hidden');
        confirmDialog.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
        activeForm = null;
        resetApproveButtonState();

        if (restoreFocus && lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
            lastFocusedElement.focus();
        }
    };

    const openDialog = (form) => {
        activeForm = form;
        lastFocusedElement = document.activeElement;

        titleElement.textContent = form.dataset.confirmTitle || 'Konfirmasi Tindakan';
        messageElement.textContent =
            form.dataset.confirmMessage || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
        approveButton.textContent = form.dataset.confirmApprove || 'Ya, Lanjutkan';
        cancelButton.textContent = form.dataset.confirmCancel || 'Batal';

        applyVariant(form.dataset.confirmVariant || 'danger');

        confirmDialog.classList.remove('hidden');
        confirmDialog.classList.add('flex');
        document.body.classList.add('overflow-hidden');

        if (window.lucide) {
            window.lucide.createIcons();
        }

        setTimeout(() => {
            cancelButton.focus();
        }, 10);
    };

    document.addEventListener(
        'submit',
        (event) => {
            const form = event.target;

            if (!(form instanceof HTMLFormElement) || !form.matches('form[data-confirm]')) {
                return;
            }

            event.preventDefault();
            openDialog(form);
        },
        true
    );

    overlay.addEventListener('click', () => closeDialog());
    cancelButton.addEventListener('click', () => closeDialog());

    approveButton.addEventListener('click', () => {
        if (!activeForm) {
            return;
        }

        approveButton.disabled = true;
        approveButton.classList.add('opacity-70', 'cursor-not-allowed');

        const targetForm = activeForm;

        // Open WhatsApp links if present (used by bimbingan cancel flow)
        const waLinksJson = targetForm.dataset.confirmWaLinks;
        if (waLinksJson) {
            try {
                const links = JSON.parse(waLinksJson);
                links.forEach(link => {
                    if (link) {
                        window.open(link, '_blank');
                    }
                });
            } catch (e) {
                console.error('Error opening WhatsApp links:', e);
            }
        }

        closeDialog({ restoreFocus: false });
        targetForm.submit();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !confirmDialog.classList.contains('hidden')) {
            closeDialog();
        }
    });
}

const toastRegion = document.getElementById('appToastRegion');

if (toastRegion) {
    const removeToast = (toastElement) => {
        if (!(toastElement instanceof HTMLElement) || toastElement.classList.contains('is-closing')) {
            return;
        }

        toastElement.classList.add('is-closing');
        window.setTimeout(() => {
            toastElement.remove();
            if (!toastRegion.querySelector('[data-toast]')) {
                toastRegion.remove();
            }
        }, 220);
    };

    toastRegion.querySelectorAll('[data-toast]').forEach((toastElement) => {
        const duration = Number(toastElement.dataset.toastDuration || 4800);
        let timer = window.setTimeout(() => removeToast(toastElement), duration);

        const closeButton = toastElement.querySelector('[data-toast-close]');
        closeButton?.addEventListener('click', () => removeToast(toastElement));

        toastElement.addEventListener('mouseenter', () => {
            window.clearTimeout(timer);
        });

        toastElement.addEventListener('mouseleave', () => {
            timer = window.setTimeout(() => removeToast(toastElement), 1700);
        });
    });
}
