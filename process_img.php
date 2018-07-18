<?php
$response = "";
if(isset($_FILES["image-to-upload"]))
{
    $response = "";
    $upload_ok = 1;

    $target_dir = "../media/profile_picts/";
    $logo = "../media/logo_white.png";
    $target_file = $target_dir . uniqid() . "." . pathinfo($_FILES["image-to-upload"]["name"],PATHINFO_EXTENSION);
    $image_file_type = pathinfo($_FILES["image-to-upload"]["name"],PATHINFO_EXTENSION);

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"]))
    {
        if(!getimagesize($_FILES["image-to-upload"]["tmp_name"]))
        {
            $response .= "-File is not an image. ";
            $upload_ok = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file))
    {
        $response .= "-Sorry, file already exists. ";
        $upload_ok = 0;
    }

    // Check file size
    if ($_FILES["image-to-upload"]["size"] > 500000)
    {
        $response .= "-Sorry, your file is too large. ";
        $upload_ok = 0;
    }

    // Allow certain file formats
    if($image_file_type != "jpg"
    && $image_file_type != "png"
    && $image_file_type != "jpeg"
    && $image_file_type != "JPG" )
    {
        $response .= "-Sorry, only JPG, JPEG, PNG files are allowed. ";
        $upload_ok = 0;
    }

    // Check if $upload_ok is set to 0 by an error
    if ($upload_ok == 0)
    {
        $response .= "-Sorry, your file was not uploaded. ";
    }
    else
    {
        if (!move_uploaded_file($_FILES["image-to-upload"]["tmp_name"], $target_file)) {
            $response .= "-Sorry, there was an error uploading your file. ";
            $upload_ok = 0;
        }
    }
}
else
{
    $response .= "-Hey, you! You didn't provide a file! ";
    $upload_ok = 0;
}

// FILE TRANSFORMATION
if(isset($upload_ok) AND $upload_ok == 1)
{
    // get profile picture dimension
    list($width, $height) = getimagesize($target_file);

    if($image_file_type == "jpg" || $image_file_type == "jpeg" || $image_file_type == "JPG")
        $target_file = imagecreatefromjpeg($target_file);
    elseif($image_file_type == "png")
        $target_file = imagecreatefrompng($target_file);

    imagefilter($target_file, IMG_FILTER_GRAYSCALE);
    $logo = imagecreatefrompng($logo);

    if($width > $height)
    {
        $profile_pict_dim = $height;
        $position_x = 0.5*($width - $height);
        $position_y = 0;
    }
    else
    {
        $profile_pict_dim = $width;
        $position_x = 0;
        $position_y = 0.5*($height - $width);
    }

    // make profile picture
    $profile_pict = imagecreatetruecolor(500, 500);


    imagecopyresampled( $profile_pict, $target_file, 0, 0, $position_x, $position_y, 500, 500, $profile_pict_dim , $profile_pict_dim);
    imagecopyresampled( $profile_pict, $logo, 0, 0, 0, 0, 500, 500, 3333 , 3333);

    // Output the image
    $profile_pict_name = uniqid();
    if($image_file_type == "jpg" || $image_file_type == "jpeg" || $image_file_type == "JPG")
    {
        imagejpeg($profile_pict, "../media/profile_picts/". $profile_pict_name .".jpg");
        $response .= "<img src='../media/profile_picts/". $profile_pict_name .".jpg' width='200px'>";
    }
    elseif($image_file_type == "png")
    {
        imagepng($profile_pict, "../media/profile_picts/". $profile_pict_name .".png");
        $response .= "<img src='../media/profile_picts/". $profile_pict_name .".png' width='200px'>";
    }

    // Free up memory
    imagedestroy($profile_pict);
    imagedestroy($target_file);
}

if($response[0] == '-')
{
    echo substr($response, 1);
}
else
{
    $excitement = ["Nice!", "Looking good!", "Crikey!", "Oh La La!", "Look at you!"];

    echo "<p id='excitement'>". $excitement[rand(0, count($excitement)-1)] ."</p>". $response;
}


?>
