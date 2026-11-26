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

    function currentListUrl(list) {
        return new URL(list.dataset.currentUrl || window.location.href, window.location.href);
    }

    function setListError(list, message) {
        let error = list.querySelector('[data-list-error]');
        if (!error) {
            error = document.createElement('div');
            error.className = 'alert alert-danger';
            error.setAttribute('role', 'alert');
            error.dataset.listError = '';
            list.insertBefore(error, list.firstChild);
        }

        error.textContent = message;
    }

    function requestList(list, url, errorMessage) {
        const requestId = Number(list.dataset.requestId || 0) + 1;
        const targetUrl = new URL(url, window.location.href);
        list.dataset.requestId = String(requestId);

        return fetch(targetUrl.toString(), {
            method: 'GET',
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
                if (requestId !== Number(list.dataset.requestId)) {
                    return;
                }

                if (!result.ok || !result.payload.status || typeof result.payload.html !== 'string') {
                    throw new Error('Respons daftar tidak valid.');
                }

                refreshCsrf(result.payload.csrf);
                list.innerHTML = result.payload.html;
                list.dataset.currentUrl = targetUrl.toString();
            })
            .catch(function () {
                if (requestId !== Number(list.dataset.requestId)) {
                    return;
                }

                setListError(list, errorMessage || 'Pencarian gagal. Silakan coba lagi.');
            });
    }

    function liveSearchUrl(form) {
        const url = new URL(form.action || window.location.href, window.location.href);
        const keyword = form.querySelector('[name="keyword"]');
        url.searchParams.delete(form.dataset.pageParam);

        if (keyword && keyword.value !== '') {
            url.searchParams.set('keyword', keyword.value);
        } else {
            url.searchParams.delete('keyword');
        }

        return url;
    }

    function syncRow(form) {
        const list = document.querySelector('[data-admin-list]');
        if (!list) {
            window.location.reload();
            return;
        }

        requestList(list, currentListUrl(list), 'Perubahan berhasil, tetapi daftar gagal diperbarui. Silakan coba lagi.');
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

                syncRow(form);
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

    const searchTimers = new WeakMap();

    document.addEventListener('input', function (event) {
        const input = event.target;
        if (!(input instanceof HTMLInputElement) || input.name !== 'keyword') {
            return;
        }

        const form = input.closest('form[data-live-search]');
        if (!form) {
            return;
        }

        if (searchTimers.has(form)) {
            window.clearTimeout(searchTimers.get(form));
        }

        searchTimers.set(form, window.setTimeout(function () {
            const list = document.querySelector('[data-admin-list]');
            if (list) {
                requestList(list, liveSearchUrl(form));
            }
        }, 300));
    });

    document.addEventListener('submit', function (event) {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        if (form.hasAttribute('data-live-search')) {
            event.preventDefault();
            if (searchTimers.has(form)) {
                window.clearTimeout(searchTimers.get(form));
                searchTimers.delete(form);
            }
            const list = document.querySelector('[data-admin-list]');
            if (list) {
                requestList(list, liveSearchUrl(form));
            }
            return;
        }

        if (!form.hasAttribute('data-ajax-form')) {
            return;
        }

        event.preventDefault();
        submitForm(form);
    });

    document.addEventListener('click', function (event) {
        const pageLink = event.target.closest('a.page-link');
        if (pageLink) {
            const list = pageLink.closest('[data-admin-list]');
            if (list) {
                event.preventDefault();
                const url = new URL(pageLink.href, window.location.href);
                const form = document.querySelector('form[data-live-search]');
                const keyword = form ? form.querySelector('[name="keyword"]') : null;
                if (keyword && keyword.value !== '') {
                    url.searchParams.set('keyword', keyword.value);
                } else {
                    url.searchParams.delete('keyword');
                }
                requestList(list, url, 'Paginasi gagal dimuat. Silakan coba lagi.');
                return;
            }
        }

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
