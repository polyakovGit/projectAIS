<?php
if (isset($_POST["submit"])) {
    if(!empty($_FILES["fileToSelect"]["name"])){
        $target_dir = "pythonDirectory/Images/"; 
        $target_file = $target_dir . basename($_FILES["fileToSelect"]["name"]);
        $uploadOk = 1; 
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
        $check = getimagesize($_FILES["fileToSelect"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    
        if (file_exists($target_file)) {
            $uploadOk = 0;
        }
    
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
    
        if ($uploadOk == 0) {
            //echo "Sorry, your file was not uploaded."; 
        } else {
            if (move_uploaded_file($_FILES["fileToSelect"]["tmp_name"], $target_file)) {
               $uploadResult = "The file " . htmlspecialchars(basename($_FILES["fileToSelect"]["name"])) . " has been uploaded.";
            } else {
                //echo "Sorry, there was an error uploading your file.";
            }
        }
        
        $file = $_FILES["fileToSelect"];
        $file_name = $file["name"];
        exec("python pythonDirectory/predict.py $file_name 2>&1", $output, $return_var);
    
        if ($return_var > 0) {
            //if there are errors on python code. 
            $result = "There is an error!";
            print_r($output);
        } else {
            //if everything ok on python code
            $result = $output[0];
        }
    }
}
?>
<html>

<head>
    <link rel="stylesheet" href="mystyle.css">
    <title>Age Predictor</title>
</head>

<body>
    <div class="centerdiv">
        <form action="" method="post" enctype="multipart/form-data">
            <div style="text-align: center;margin-bottom:3vh;">SELECT IMAGE TO PREDICT AGE</div>
            <?php 
                if(isset($uploadResult)){
                    echo "<div class='uploadInfo'>*image has been uploaded recently<div>";
                }
            ?>
            <input type="file" name="fileToSelect" id="fileToSelect">
            <input type="submit" value="Predict Age" name="submit">
        </form>
    </div>
    <?php if (isset($result)) { ?>
        <div class="centerdiv" style="margin-top:4vh;">
            <img src="pythonDirectory/Images/<?php echo $file_name ?>" alt="img" style="max-width: 20vw;">
        </div>
        <div class="centerdiv" style="margin-top:2vh;font-size:24px;"><?php echo "$result" ?></div>

    <?php } ?>
</body>

</html>