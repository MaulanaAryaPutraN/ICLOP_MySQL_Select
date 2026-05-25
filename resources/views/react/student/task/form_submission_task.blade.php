
<div style="padding-top: 15px; padding-bottom: 15px">
    <p class='text-list' style='font-size: 24px; font-weight: 600;width: 400px !important;'> Upload File Practicum </p>

































































    <div class="texts" style=" position: relative;">
        <style>
            text:hover{
                text-decoration: none !important;
            }
        </style>
        <form action="{{ Route("task_submission") }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group" >
                <label for="">Evidence</label>
                <input type="file" name="file" class="form-control">
                <small>Enter the work results <code>.php | .html </code></small>
            </div>
            <br />
            <div class="form-group">
                <label for="">Comment</label>
                <textarea class="form-control" name="comment" placeholder="..."></textarea>
            </div>
            <br />
            <div class="form-group">
                <input type="submit" value="Upload" class="btn btn-primary">
            </div>


        </form>

        <a type="submit" style="margin-top:10px" class="btn btn-primary" href="{{ Route("unittesting") }}">Hasil Testing PHP Unit</a>

    </div>
</div>
