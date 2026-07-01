<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector(@json($formSelector));
        if (!form || !window.sessionStorage) return;

        const draftKey = @json($draftKey);
        const pendingKey = draftKey + ':submitted';
        const hasValidationErrors = @json($errors->any());
        const fileDbName = 'donkey_form_file_drafts';
        const fileStoreName = 'files';

        function fields() {
            return form.querySelectorAll('input, select, textarea');
        }

        function fileFields() {
            return form.querySelectorAll('input[type="file"][name]');
        }

        function openFileDb() {
            return new Promise(function (resolve, reject) {
                if (!window.indexedDB) {
                    reject(new Error('IndexedDB is not available'));
                    return;
                }

                const request = indexedDB.open(fileDbName, 1);

                request.onupgradeneeded = function () {
                    request.result.createObjectStore(fileStoreName);
                };

                request.onsuccess = function () {
                    resolve(request.result);
                };

                request.onerror = function () {
                    reject(request.error);
                };
            });
        }

        function fileKey(field) {
            return draftKey + ':' + field.name;
        }

        async function putDraftFile(field) {
            if (!field.files || field.files.length === 0) {
                await deleteDraftFile(field);
                return;
            }

            try {
                const db = await openFileDb();
                const transaction = db.transaction(fileStoreName, 'readwrite');
                const store = transaction.objectStore(fileStoreName);
                const files = Array.from(field.files);
                store.put({
                    multiple: field.multiple,
                    files: files,
                }, fileKey(field));
            } catch (error) {
                // File draft restore is best-effort. Normal form fields still remain saved.
            }
        }

        async function getDraftFile(field) {
            try {
                const db = await openFileDb();

                return await new Promise(function (resolve, reject) {
                    const transaction = db.transaction(fileStoreName, 'readonly');
                    const store = transaction.objectStore(fileStoreName);
                    const request = store.get(fileKey(field));

                    request.onsuccess = function () {
                        resolve(request.result);
                    };

                    request.onerror = function () {
                        reject(request.error);
                    };
                });
            } catch (error) {
                return null;
            }
        }

        async function deleteDraftFile(field) {
            try {
                const db = await openFileDb();
                const transaction = db.transaction(fileStoreName, 'readwrite');
                transaction.objectStore(fileStoreName).delete(fileKey(field));
            } catch (error) {
                // Ignore storage cleanup errors.
            }
        }

        async function clearDraftFiles() {
            await Promise.all(Array.from(fileFields()).map(deleteDraftFile));
        }

        async function restoreDraftFiles() {
            if (typeof DataTransfer === 'undefined') return;

            for (const field of fileFields()) {
                const draftFile = await getDraftFile(field);
                if (!draftFile || !Array.isArray(draftFile.files) || draftFile.files.length === 0) continue;

                const dataTransfer = new DataTransfer();
                draftFile.files.forEach(function (file) {
                    dataTransfer.items.add(file);
                });

                field.files = dataTransfer.files;
                field.dispatchEvent(new Event('change', { bubbles: true }));
            }
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

        async function restoreDraft() {
            const submitWasPending = sessionStorage.getItem(pendingKey) === '1';
            if (submitWasPending && !hasValidationErrors) {
                sessionStorage.removeItem(draftKey);
                sessionStorage.removeItem(pendingKey);
                await clearDraftFiles();
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

            await restoreDraftFiles();
        }

        restoreDraft();
        form.addEventListener('input', saveDraft);
        form.addEventListener('change', function (event) {
            saveDraft();

            if (event.target && event.target.type === 'file') {
                putDraftFile(event.target);
            }
        });
        form.addEventListener('submit', function () {
            if (form.checkValidity()) sessionStorage.setItem(pendingKey, '1');
        });
    });
</script>
