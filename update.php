<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$BookTitle = $AuthorName = $ReleaseDate = $BookLinks = $Tags= "";
$BookTitle_err = $AuthorName_err = $ReleaseDate_err = $BookLinks_err = $Tags_err ="";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

    // Validate BookTitle
    $input_BookTitle = trim($_POST["BookTitle"]);
    if(empty($input_BookTitle)){
        $BookTitle_err = "Please enter a BookTitle.";
    } else{
        $BookTitle = $input_BookTitle;
    }

    // Validate AuthorName
    $input_AuthorName = trim($_POST["AuthorName"]);
    if(empty($input_AuthorName)){
        $AuthorName_err = "Please enter an AuthorName.";
    } else{
        $AuthorName = $input_AuthorName;
    }

    // Validate ReleaseDate
    $input_ReleaseDate = trim($_POST["ReleaseDate"]);
    if(empty($input_ReleaseDate)){
        $ReleaseDate_err = "Please enter the ReleaseDate amount.";
    } else{
        $ReleaseDate = $input_ReleaseDate;
    }

    $input_BookLinks = trim($_POST["BookLinks"]);
    if(empty($input_BookLinks)){
        $BookLinks_err = "Please enter the appropriate Link.";
    } else{
        $BookLinks = $input_BookLinks;
    }

    $input_Tags = trim($_POST["Tags"]);
        $Tags = $input_Tags;




    // Check input errors before inserting in database
    if(empty($BookTitle_err) && empty($AuthorName_err) && empty($ReleaseDate_err) && empty($BookLinks_err) && empty($Tags_err)){
        // Prepare an update statement
        $sql = "UPDATE books SET BookTitle=?, AuthorName=?, ReleaseDate=?, links=?, Tags=? WHERE id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_BookTitle, $param_AuthorName, $param_ReleaseDate , $param_BookLinks, $param_Tags ,$param_id);

            // Set parameters
            $param_BookTitle = $BookTitle;
            $param_AuthorName = $AuthorName;
            $param_ReleaseDate = $ReleaseDate;
            $param_BookLinks   = $BookLinks;
            $param_Tags   = $Tags;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM books WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $BookTitle = $row["BookTitle"];
                    $AuthorName = $row["AuthorName"];
                    $ReleaseDate  = $row["ReleaseDate"];
                    $Tags  = $row["Tags"];
                    $BookLinks = $row['links'];

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
                            <label>ReleaseDate </label>
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


                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
