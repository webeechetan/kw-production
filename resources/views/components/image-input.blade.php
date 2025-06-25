@props(['model'])
<div>
    <div class="form-file_upload form-file_upload-logo">
        <input class="image_upload_input" type="file" id="formFile" 
            wire:model="{{ $model }}"
            accept="image/jpeg, image/jpg, image/png, image/gif">
        <div class="form-file_upload-box">
            <div class="form-file_upload-box-icon"><i class='bx bx-image'></i></div>
            <div class="form-file_upload-box-text">Upload Image</div>
        </div>
        <div class="form-file_upload-valText">Allowed *.jpeg, *.jpg, *.png, *.gif max size of 3 Mb</div>
    </div>
    <div class="mt-4 d-none upload-progress">
        <div class="progress w-100" role="progressbar" aria-label="Project Progress" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar progress-success" ><span class="progress-bar-text">0%</span></div>
        </div>
    </div>

    <div class="image-preview-section mt-3 d-none">

    </div>

    <div class="remove-image-sesction d-none">
        <button type="button" class="remove-image-button btn btn-danger btn-sm" wire:click="removeImage"><i class="bx bx-trash"></i></button>
    </div>

</div>