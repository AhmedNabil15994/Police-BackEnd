<input type="file" class="form-control" name="{{ $name ?? 'image' }}"
       onchange="readURL(this, '{{ $imgUploadPreviewID ?? 'imgUploadPreview' }}', 'single');">
<img id='{{ $imgUploadPreviewID ?? 'imgUploadPreview' }}'
     style="{{ !is_null($image) ? 'height: 100px;':'display: none; height: 100px;' }}"
     src="{{ !is_null($image) ? url($image) : '' }}"
     class="img-preview img-thumbnail"
     alt="image preview"/>
