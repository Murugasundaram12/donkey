<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector(@json($formSelector));
        if (!form || !window.sessionStorage) return;

        const draftKey = @json($draftKey);
        const pendingKey = draftKey + ':submitted';
        const hasValidationErrors = @json($errors->any());

        function fields() {
            return form.querySelectorAll('input, select, textarea');
        }

        function saveDraft() {
            const draft = {};

            fields().forEach(function (field) {
                if (!field.name || field.type === 'file' || field.name === '_token') return;

                if (field.type === 'checkbox' || field.type === 'radio') {
                    if (!draft[field.name]) draft[field.name] = [];
                    if (field.checked) draft[field.name].push(field.value);
                    return;
                }

                if (field.tagName === 'SELECT' && field.multiple) {
                    draft[field.name] = Array.from(field.selectedOptions).map(function (option) {
                        return option.value;
                    });
                    return;
                }

                draft[field.name] = field.value;
            });

            sessionStorage.setItem(draftKey, JSON.stringify(draft));
        }

        function restoreDraft() {
            const submitWasPending = sessionStorage.getItem(pendingKey) === '1';
            if (submitWasPending && !hasValidationErrors) {
                sessionStorage.removeItem(draftKey);
                sessionStorage.removeItem(pendingKey);
                return;
            }

            if (hasValidationErrors) sessionStorage.removeItem(pendingKey);

            const raw = sessionStorage.getItem(draftKey);
            if (!raw) return;

            let draft;
            try {
                draft = JSON.parse(raw);
            } catch (error) {
                sessionStorage.removeItem(draftKey);
                return;
            }

            fields().forEach(function (field) {
                if (!field.name || field.type === 'file' || field.name === '_token') return;
                if (!Object.prototype.hasOwnProperty.call(draft, field.name)) return;

                if (field.type === 'checkbox' || field.type === 'radio') {
                    field.checked = Array.isArray(draft[field.name])
                        && draft[field.name].includes(field.value);
                    return;
                }

                if (field.tagName === 'SELECT' && field.multiple) {
                    Array.from(field.options).forEach(function (option) {
                        option.selected = draft[field.name].includes(option.value);
                    });
                } else {
                    field.value = draft[field.name];
                }

                field.dispatchEvent(new Event('change', { bubbles: true }));
                if (window.jQuery && window.jQuery(field).hasClass('select2')) {
                    window.jQuery(field).trigger('change.select2');
                }
            });
        }

        restoreDraft();
        form.addEventListener('input', saveDraft);
        form.addEventListener('change', saveDraft);
        form.addEventListener('submit', function () {
            if (form.checkValidity()) sessionStorage.setItem(pendingKey, '1');
        });
    });
</script>
