<!DOCTYPE html>
<html>
    <head>
        <title>STORY on social media</title>
    </head>
    <body>

        <div id="progress-bar" style="position: absolute; top: 0; left:0; width: 0; height: 5px; background-color: black; "></div>

    <h1 style="margin: 50px;">STORY ON SOCIAL MEDIA</h1>

    <h2>PROFILE PICTURE</h2>
    <form id="profile-pict-upload" action="process_img.php" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" id="image-to-upload">
        <input type="submit" value="Upload" name="submit">
    </form>
    <p id="profile-pict-upload-response"></p>

    </body>
    <script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
        $(function() {
            var img = $("#image-to-upload"),
                form_data = new FormData();

            $("input[type='submit']").click(function(event){
                event.preventDefault();

                form_data.append("image-to-upload", $("#image-to-upload")[0].files[0]);

                $.ajax({
                    type: 'POST',
                    url: $('form').attr('action'),
                    data: form_data,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#progress-bar').css({'width': '0%', 'box-shadow': '0 0 10px black'}).show();
                    },
                    xhr: function() {
                        myXhr = $.ajaxSettings.xhr();
                        if(myXhr.upload)
                        {
                            myXhr.upload.addEventListener('progress', function(event) {
                                if(event.lengthComputable)
                                {
                                    var percentComplete = 0.9 * (event.loaded / event.total) * 100;
                                    // $('#progress-bar').css('width', percentComplete +'%');
                                    $('#progress-bar').animate({'width': percentComplete +'%'}, 200);
                                }
                            }, false);
                        }
                        else
                        {
                            console.log("Uploadress is not supported.");
                        }

                        return myXhr;
                    },
                    success: function(response) {
                        $('#progress-bar').animate({'width': '100%'}).delay(200).fadeOut();
                        $('#profile-pict-upload-response').html(response);
                        return false;
                    },
                    error: function(error) {
                        $('#profile-pict-upload-response').html(response);
                        $('#progress-bar').animate({'width': '100%', 'background-color': '#e74c3c'}, 200).css('box-shadow', '0 0 10px #e74c3c');
                    }
                });

            });
        });

    </script>
</html>
