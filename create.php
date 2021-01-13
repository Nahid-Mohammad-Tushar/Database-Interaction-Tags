<?php
// Include config file
require_once "config.php";



// Define variables and initialize with empty values
$BookTitle = $AuthorName = $ReleaseDate = $BookLinks = $Tags= "";
$BookTitle_err = $AuthorName_err = $ReleaseDate_err = $BookLinks_err = $Tags_err ="";










// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate BookTitle
    $input_BookTitle = ($_POST["BookTitle"]);
    if(empty($input_BookTitle)){
        $BookTitle_err = "Please enter a BookTitle.";
    } else{
        $BookTitle = $input_BookTitle;
    }

    // Validate AuthorName
    $input_AuthorName = ($_POST["AuthorName"]);
    if(empty($input_AuthorName)){
        $AuthorName_err = "Please enter an AuthorName.";
    } else{
        $AuthorName = $input_AuthorName;
    }

    // Validate ReleaseDate
    $input_ReleaseDate = ($_POST["ReleaseDate"]);
    if(empty($input_ReleaseDate)){
        $ReleaseDate_err = "Please enter the ReleaseDate amount.";
    } else{
        $ReleaseDate = $input_ReleaseDate;
    }

    $input_BookLinks = ($_POST["BookLinks"]);
    if(empty($input_BookLinks)){
        $BookLinks_err = "Please enter the appropriate Link.";
    } else{
        $BookLinks = $input_BookLinks;
    }

    $input_Tags = ($_POST["Tags"]);
      $Tags = $input_Tags;




    // Check input errors before inserting in database
    if(empty($BookTitle_err) && empty($AuthorName_err) && empty($ReleaseDate_err) && empty($BookLinks_err) && empty($Tags_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO books (BookTitle, AuthorName, ReleaseDate, links, images, Tags) VALUES (?, ?, ?, ?, ?, ?)";

        ////////////////////////////////////////////////////////////////////////////

        $image_name = $_FILES['file']['name'];
        $target_dir = "../Login/user/simpleuser/cover/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);

        // Select file type
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Valid file extensions
        $extensions_arr = array("jpg","jpeg","png","gif");
         move_uploaded_file($_FILES['file']['tmp_name'],$target_dir.$image_name);


        ////////////////////////////////////////////////////////////////////////////

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_BookTitle, $param_AuthorName, $param_ReleaseDate, $param_BookLinks, $param_image, $param_Tags);

            // Set parameters
            $param_BookTitle = $BookTitle;
            $param_AuthorName = $AuthorName;
            $param_ReleaseDate = $ReleaseDate;
            $param_BookLinks   = $BookLinks;
            $param_image = 'cover/'.$image_name;
            $param_Tags   = $Tags;



            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group <?php echo (!empty($BookTitle_err)) ? 'has-error' : ''; ?>">
                            <label>BookTitle</label>
                            <input type="text" name="BookTitle" class="form-control" value="<?php echo $BookTitle; ?>">
                            <span class="help-block"><?php echo $BookTitle_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($AuthorName_err)) ? 'has-error' : ''; ?>">
                            <label>AuthorName</label>
                            <textarea name="AuthorName" class="form-control"><?php echo $AuthorName; ?></textarea>
                            <span class="help-block"><?php echo $AuthorName_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($ReleaseDate_err)) ? 'has-error' : ''; ?>">
                            <label>ReleaseDate</label>
                            <input type="text" name="ReleaseDate" class="form-control" value="<?php echo $ReleaseDate; ?>">
                            <span class="help-block"><?php echo $ReleaseDate_err;?></span>
                        </div>


                        <div class="form-group <?php echo (!empty($BookLinks_err)) ? 'has-error' : ''; ?>">
                            <label>BookLinks</label>
                            <input type="text" name="BookLinks" class="form-control" value="<?php echo $BookLinks; ?>">
                            <span class="help-block"><?php echo $BookLinks_err;?></span>
                        </div>


                        <div class="form-group <?php echo (!empty($Tags_err)) ? 'has-error' : ''; ?>">
                            <label>Tags</label>
                            <input type="text" name="Tags" class="form-control" value="<?php echo $Tags; ?>">
                            <span class="help-block"><?php echo $Tags_err;?></span>
                        </div>


                        <label>photo</label>
                        <input type='file' name='file' />
                        <br />


                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>


</body>
</html>
