<?php use Sail\Constants; ?>
<html>
<script id='form-base'>
    const BASE64_PREFIX = "data:*/*;base64,";
    const loadingSpinner = document.createElement('img');
    let buttons = [];
    loadingSpinner.setAttribute('src', <?php echo '"' . Constants::IMAGES_ROUTE . 'loading-spinner.gif"' ?>);
    loadingSpinner.setAttribute('alt', 'Loading');

    function makeFormRestSubmit(formId, apiRoute) {
        const form = document.getElementById(formId);
        form.onsubmit = (function(event) {
            const button = buttons.filter(b => b.getAttribute('form') == formId)[0];
            buttonDisable(button);
            return formRestSubmit(form, button, apiRoute, event)
        });
    }

    function buttonDisable(button) {
        button.disabled = true;
        //button.append(loadingSpinner);
    }

    function buttonEnable(button) {
        button.disabled = false;
        if (button.lastElementChild.tagName == 'img') {
            button.lastElementChild.remove();
        }
    }

    function formRestSubmit(form, button, apiRoute, event) {
        const fileInput = document.querySelector('input[type=file]');
        const formData = new FormData(form);
        let reader = null;

        for(var kvPair of formData.entries()) {
            if (Array.isArray(formData.getAll(kvPair[0])) && formData.getAll(kvPair[0]).length > 1) {
                formData.set(kvPair[0], formData.getAll(kvPair[0]).join('|'));
            } else if(fileInput && kvPair[0] === fileInput.id) {
                reader = new FileReader();
            }
        }

        if (reader) {
            reader.addEventListener("load", function () {
                let fileContents = reader.result;
                if (fileContents.startsWith(BASE64_PREFIX)) {
                    fileContents = fileContents.slice(BASE64_PREFIX.length);
                }
                formData.set(fileInput.id, fileContents);
                postData(apiRoute, formData, button);
            }, false);
            if (fileInput.files.length !== 0)
                reader.readAsDataURL(fileInput.files[0]);
            else
                postData(apiRoute, formData, button);
        } else {
            postData(apiRoute, formData, button);
        };

        return false;
    }

    function postData(apiRoute, formData, button) {
        const formHeaders = new Headers({
            'Content-Type': 'application/json',
            'x-wp-nonce': SAIL.nonce
        });

        fetch(apiRoute, {
                method: 'POST',
                headers: formHeaders,
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => {
                if (response.headers.get('Location')) {
                    location.href = response.headers.get('Location');
                }
                if (response.redirected) {
                    location.href = response.url;
                }
                buttonEnable(button);
                return response.json();
            })
            .catch(error => {
                buttonEnable(button);
                console.error(error)
                return false;
            });
        return false;
    }

    window.addEventListener('load', (event) => {
        SAIL.formActions.forEach(action => makeFormRestSubmit(action['id'],  "<?php echo Constants::FORM_REST_PREFIX ?>" + action['action']));
        buttons = [...document.getElementsByTagName('button')];
    });
</script>
</html>