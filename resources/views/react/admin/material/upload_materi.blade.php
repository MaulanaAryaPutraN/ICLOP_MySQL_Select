`@extends('php/teacher/home')
@section('content')
<div style="padding: 20px; max-width: 68%; margin-left:5px;  ">
    <div style="border: 1px solid #ccc; padding: 10px 10px 10px 10px; border-radius: 5px;margin-bottom:40px">
        <div class="form-group">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group" style="margin-bottom: 20px;">
                    <input type="text" name="title" class='form-control' placeholder="Tittle" />
                </div>
                <div class="form-group">
                    <style>
                        .ck-content > p{
                            height: 300px !important;
                        }
                    </style>

                    <textarea name="editor" id="editor" class="form-control"></textarea>
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <input type="text" name="title" class='form-control' placeholder="Tittle" />
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js">
    <script ></script>
    <script type="text/javascript">
        ClassicEditor
            .create(document.querySelector('#editor'), {
                ckfinder: {
                    uploadUrl: '{{route('uploadimage').'?_token='.csrf_token()}}',
                }
            });
    </script>
@endsection

