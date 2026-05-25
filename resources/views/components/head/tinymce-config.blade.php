<script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
 
 tinymce.init({
  selector: 'textarea#myeditorinstance',
  plugins: 'link image code',
   toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  toolbar2: 'print preview media | forecolor backcolor emoticons',
 
  image_title: true,
  automatic_uploads: true,
  relative_urls : false,
  remove_script_host : false,

  file_picker_types: 'image',
  height: 300,

  convert_urls: false,
   /* and here's our custom image picker*/
   images_upload_handler: function (blobInfo, success, failure) {
    var self = this;
    var xhr = new XMLHttpRequest();

    xhr.withCredentials = false;
    xhr.open('POST', '{{route('uploadimage').'?_token='.csrf_token()}}');

    xhr.onload = function () {

        var json = JSON.parse(xhr.responseText);

        var filename = prompt('If you plan on reusing this image in other templates, rename the file so that it\'s easily recognizable in your images list', json.filename);

        if (filename && filename !== json.filename) {
            json.filename = filename;

            axios.post(self.imageRenameUrl, json)
                .then(function (r) {
                    var renamedImage = r.data;
                    success(renamedImage.location);
                })
        }
        else {
            success(filename);
        }
    };

    var formData = new FormData();
    formData.append('file', blobInfo.blob(), blobInfo.filename());

    xhr.send(formData);
},
  images_reuse_filename: true,
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
});

</script>