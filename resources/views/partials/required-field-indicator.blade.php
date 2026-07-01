<style>
    label.required-field-label::after {
        content: " *";
        color: #dc3545;
        font-weight: 700;
    }
</style>
<script>
    (function() {
        function cssEscape(value) {
            if (window.CSS && typeof window.CSS.escape === 'function') {
                return window.CSS.escape(value);
            }

            return value.replace(/["\\]/g, '\\$&');
        }

        function findLabelForField(field) {
            if (field.id) {
                var label = document.querySelector('label[for="' + cssEscape(field.id) + '"]');

                if (label) {
                    return label;
                }
            }

            var group = field.closest('.form-group, .mb-3, .form-row, .col, [class*="col-"]');

            if (!group) {
                return null;
            }

            var labels = Array.prototype.slice.call(group.querySelectorAll('label'));

            return labels.find(function(label) {
                return label.compareDocumentPosition(field) & Node.DOCUMENT_POSITION_FOLLOWING;
            }) || labels[0] || null;
        }

        function markRequiredLabels() {
            var selector = 'input[required], select[required], textarea[required]';
            var ignoredTypes = ['hidden', 'submit', 'button', 'reset'];

            Array.prototype.forEach.call(document.querySelectorAll(selector), function(field) {
                if (ignoredTypes.indexOf((field.type || '').toLowerCase()) !== -1) {
                    return;
                }

                var label = findLabelForField(field);

                if (label && label.textContent.indexOf('*') === -1) {
                    label.classList.add('required-field-label');
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', markRequiredLabels);
        } else {
            markRequiredLabels();
        }
    })();
</script>
