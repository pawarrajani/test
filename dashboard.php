<?php
//include auth_session.php file on all user panel pages
include("auth_session.php");
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <title>File Upload </title>
    </head>
    <body>

        <div class="container">
           <div class="mt-3 mb-3"> 
                <p>Hey, <?php echo $_SESSION['username']; ?>! You are in user dashboard page.</p>
                <p align="right"><a href="logout.php">Logout</a></p>
            <div>
            <h1 class="mt-3 mb-3 text-center">File Upload</h1>

            <table class="table">
                <tr>
                    <td width="50%" align="right"><b>Select File</b></td>
                    <td width="50%">
                        <input type="file" id="select_file" multiple />
                    </td>
                </tr>
            </table>
            <div class="progress" id="progress_bar" style="display:none; ">
                <div class="progress-bar" id="progress_bar_process" role="progressbar" style="width:0%">0%</div>
            </div>
            <div id="uploaded_image" class="row mt-5"></div>
        </div>
    </body>
</html>

<script>

function _(element)
{
    return document.getElementById(element);
}

_('select_file').onchange = function(event){

    var form_data = new FormData();

    var image_number = 1;

    var error = '';

    for(var count = 0; count < _('select_file').files.length; count++)  
    {
        if(!['image/jpeg', 'image/png'].includes(_('select_file').files[count].type))
        {
            error += '<div class="alert alert-danger"><b>'+image_number+'</b> Selected File must be .jpg or .png Only.</div>';
        }
        else
        {
            form_data.append("images[]", _('select_file').files[count]);
        }

        image_number++;
    }

    if(error != '')
    {
        _('uploaded_image').innerHTML = error;

        _('select_file').value = '';
    }
    else
    {
        _('progress_bar').style.display = 'block';

        var ajax_request = new XMLHttpRequest();

        ajax_request.open("POST", "upload.php");

        ajax_request.upload.addEventListener('progress', function(event){

            var percent_completed = Math.round((event.loaded / event.total) * 100);

            _('progress_bar_process').style.width = percent_completed + '%';

            _('progress_bar_process').innerHTML = percent_completed + '% completed';

        });

        ajax_request.addEventListener('load', function(event){

            _('uploaded_image').innerHTML = '<div class="alert alert-success">Files Uploaded Successfully</div>';

            _('select_file').value = '';

        });

        ajax_request.send(form_data);
    }

};

</script>

