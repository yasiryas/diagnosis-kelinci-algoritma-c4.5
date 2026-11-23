(function () {
    'use strict';

    const csrfName = document.body.dataset.csrfName || 'csrf_test_name';

    function refreshCsrf(token) {
        if (!token) {
            return;
        }

        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) {
            meta.content = token;
        }

        document.querySelectorAll('input[name="' + csrfName + '"]').forEach(function (input) {
            input.value = token;
        });
    }

    function clearErrors(form) {
        form.querySelectorAll('.is-invalid').forEach(function (field) {
            field.classList.remove('is-invalid');
        });
        form.querySelectorAll('.invalid-feedback.dynamic-error').forEach(function (node) {
            node.remove();
        });

        const summary = form.querySelector('[data-error-summary]');
        if (summary) {
            summary.innerHTML = '';
            summary.classList.add('d-none');
        }
    }

    function showErrors(form, errors) {
        clearErrors(form);

        const summary = form.querySelector('[data-error-summary]');
        const list = document.createElement('ul');
        list.className = 'mb-0 pl-3';

        Object.keys(errors).forEach(function (fieldName) {
            const message = errors[fieldName];
            const field = form.querySelector('[name="' + fieldName + '"]');
            const item = document.createElement('li');
            item.textContent = message;
            list.appendChild(item);

            if (!field) {
                return;
            }

            field.classList.add('is-invalid');
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback dynamic-error d-block';
            feedback.textContent = message;
            field.insertAdjacentElement('afterend', feedback);
        });

        if (summary) {
            summary.appendChild(list);
            summary.classList.remove('d-none');
            summary.focus();
        }
    }

    function setLoading(button, loading) {
        if (!button) {
            return;
        }
        button.disabled = loading;
        if (loading) {
            button.dataset.originalText = button.textContent;
            button.textContent = 'Menunggu...';
        } else if (button.dataset.originalText) {
            button.textContent = button.dataset.originalText;
        }
    }

    function syncRow(form, row) {
        const rowId = form.dataset.rowId;
        if (!rowId || !row) {
            window.location.reload();
            return;
        }

        const target = document.querySelector('[data-row-id="' + rowId + '"]');
        if (!target || !target.parentElement) {
            window.location.reload();
            return;
        }

        if (form.dataset.ajaxAction === 'delete') {
            target.parentElement.remove();
            return;
        }

        Object.keys(row).forEach(function (key) {
            const cell = target.querySelector('[data-field="' + key + '"]');
            if (cell) {
                cell.textContent = row[key] === null ? '' : row[key];
            }
        });
    }

    function submitForm(form) {
        if (form.dataset.submitting === 'true') {
            return;
        }

        if (form.checkValidity() === false) {
            form.classList.add('was-validated');
            return;
        }

        const button = form.querySelector('button[type="submit"]');
        form.dataset.submitting = 'true';
        setLoading(button, true);
        clearErrors(form);

        fetch(form.dataset.ajaxUrl || form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (response) {
                return response.json().then(function (payload) {
                    return { ok: response.ok, payload: payload };
                });
            })
            .then(function (result) {
                refreshCsrf(result.payload.csrf);

                if (!result.ok || result.payload.status === false) {
                    showErrors(form, result.payload.errors || {});
                    return;
                }

                if (form.dataset.ajaxRedirect) {
                    window.location.assign(form.dataset.ajaxRedirect);
                    return;
                }

                syncRow(form, result.payload.row);
                $(form).closest('.modal').modal('hide');
            })
            .catch(function () {
                showErrors(form, { form: 'Permintaan gagal. Silakan coba lagi.' });
            })
            .finally(function () {
                form.dataset.submitting = 'false';
                setLoading(button, false);
            });
    }

    document.addEventListener('submit', function (event) {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-ajax-form')) {
            return;
        }

        event.preventDefault();
        submitForm(form);
    });

    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-ajax-url]');
        if (!trigger || trigger.tagName === 'FORM') {
            return;
        }

        const form = trigger.closest('form[data-ajax-form]');
        if (!form) {
            return;
        }

        event.preventDefault();
        form.dataset.ajaxUrl = trigger.dataset.ajaxUrl;
        form.dataset.ajaxMethod = trigger.dataset.ajaxMethod || 'post';
        form.dataset.rowId = trigger.dataset.rowId || '';
        form.requestSubmit();
    });
})();
