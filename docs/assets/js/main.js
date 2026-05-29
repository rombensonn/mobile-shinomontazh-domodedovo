(function () {
    const header = document.querySelector('.site-header');
    const menuToggle = document.querySelector('.menu-toggle');
    const modal = document.querySelector('[data-modal]');
    const modalSourceInput = modal ? modal.querySelector('input[name="source_form"]') : null;

    if (menuToggle && header) {
        menuToggle.addEventListener('click', () => {
            const isOpen = header.classList.toggle('nav-open');
            menuToggle.setAttribute('aria-expanded', String(isOpen));
        });
    }

    document.querySelectorAll('a[href^="#"]').forEach((link) => {
        link.addEventListener('click', (event) => {
            const target = document.querySelector(link.getAttribute('href'));
            if (!target) return;
            event.preventDefault();
            header && header.classList.remove('nav-open');
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    function openModal(source) {
        if (!modal) return;
        if (modalSourceInput && source) modalSourceInput.value = source;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-lock');
        const phone = modal.querySelector('input[name="phone"]');
        setTimeout(() => phone && phone.focus(), 80);
    }

    function closeModal() {
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-lock');
    }

    document.querySelectorAll('[data-modal-open]').forEach((button) => {
        button.addEventListener('click', () => openModal(button.dataset.source || 'Кнопка сайта'));
    });
    document.querySelectorAll('[data-modal-close]').forEach((button) => button.addEventListener('click', closeModal));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeModal();
    });

    function formatRuPhone(value) {
        let digits = value.replace(/\D/g, '');
        if (digits.startsWith('8')) digits = '7' + digits.slice(1);
        if (!digits.startsWith('7')) digits = '7' + digits;
        digits = digits.slice(0, 11);
        const p = digits.slice(1);
        let result = '+7';
        if (p.length > 0) result += ' (' + p.slice(0, 3);
        if (p.length >= 3) result += ')';
        if (p.length > 3) result += ' ' + p.slice(3, 6);
        if (p.length > 6) result += '-' + p.slice(6, 8);
        if (p.length > 8) result += '-' + p.slice(8, 10);
        return result;
    }

    document.querySelectorAll('input[type="tel"]').forEach((input) => {
        input.addEventListener('input', () => {
            input.value = formatRuPhone(input.value);
        });
        input.addEventListener('focus', () => {
            if (!input.value) input.value = '+7 ';
        });
    });

    document.querySelectorAll('[data-form]').forEach((form) => {
        const pageInput = form.querySelector('input[name="page_url"]');
        if (pageInput) pageInput.value = window.location.href;

        form.addEventListener('submit', (event) => {
            form.querySelectorAll('.field-error').forEach((field) => field.classList.remove('field-error'));
            form.querySelectorAll('.form-error').forEach((error) => error.remove());

            const phone = form.querySelector('input[name="phone"]');
            const privacy = form.querySelector('input[name="privacy"]');
            const phoneDigits = phone ? phone.value.replace(/\D/g, '') : '';
            let errorText = '';

            if (!phone || phoneDigits.length < 10) {
                errorText = 'Укажите телефон, чтобы мастер мог связаться с вами.';
                phone && phone.classList.add('field-error');
            } else if (!privacy || !privacy.checked) {
                errorText = 'Подтвердите согласие с политикой конфиденциальности.';
                privacy && privacy.classList.add('field-error');
            }

            if (errorText) {
                event.preventDefault();
                const error = document.createElement('p');
                error.className = 'form-error';
                error.textContent = errorText;
                form.appendChild(error);
                return;
            }

            const button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.dataset.originalText = button.textContent;
                button.textContent = 'Отправляем...';
            }
        });
    });

    document.querySelectorAll('.faq-item').forEach((item) => {
        item.addEventListener('toggle', () => {
            if (!item.open) return;
            document.querySelectorAll('.faq-item[open]').forEach((other) => {
                if (other !== item) other.open = false;
            });
        });
    });

    const revealItems = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        revealItems.forEach((item) => observer.observe(item));
    } else {
        revealItems.forEach((item) => item.classList.add('is-visible'));
    }
})();
