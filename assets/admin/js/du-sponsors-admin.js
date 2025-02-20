document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".upload-sponsor-image");

    buttons.forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();

            const inputId = this.dataset.input;
            const inputPreview = inputId + "_preview";
            const inputElement = document.getElementById(inputId);

            // If the WordPress media uploader already exists, reuse it.
            let mediaUploader = wp.media({
                title: 'Select or Upload an Image',
                button: {
                    text: 'Use this Image'
                },
                multiple: false // Single image selection
            });

            // When an image is selected, run a callback.
            mediaUploader.on("select", function () {
                const attachment = mediaUploader.state().get("selection").first().toJSON();
                inputElement.value = attachment.id; // Set the image URL in the input field
                // load a preview of the image in the preview container
                const preview = document.getElementById(inputPreview);
                preview.innerHTML = '<img src="' + attachment.url + '" alt="' + attachment.alt + '" style="max-width:300px;height:auto;" class="du-sponsor-image-preview">';
            });

            // Open the uploader dialog.
            mediaUploader.open();
        });
    });

    //remove-sponsor-image
    const removeButtons = document.querySelectorAll(".remove-sponsor-image");

    removeButtons.forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();

            const inputId = this.dataset.input;
            const inputPreview = inputId + "_preview";
            const inputElement = document.getElementById(inputId);

            inputElement.value = 0; // Set the image URL in the input field
            // load a preview of the image in the preview container
            const preview = document.getElementById(inputPreview);
            preview.innerHTML = '';

        });
    });
});
