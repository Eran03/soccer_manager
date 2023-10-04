<!-- A list of competitions, with links to edit and delete each competition. A form to add a new competition. -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competitions</title>
</head>

<body>

    <?php
    include 'menu.html';
    include 'mySQL_connect.php';
    ?>

    <h1>Competitions</h1>

    <!-- TABLE -->

    <table border="1" width="50%">

        <tr>
            <th>Competition</th>
            <th>Actions</th>
        </tr>

        <?php

        $query = "SELECT * FROM competitions";

        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }

        while (list($comp_id, $comp_name) = $result->fetch_row()) {
            echo "
                <tr>
                    <td>$comp_name</td>
                    <td>
                    <a href='competitions.php?edit=$comp_id&comp_name=$comp_name'>Edit</a>
                    <a href='competitions.php?delete=$comp_id&comp_name=$comp_name'>Delete</a>
                    </td>
                </tr>
            ";
        }

        ?>

    </table>

    <br>

    <?php

    // EDIT FORM

    if (isset($_GET['edit'])) {
        $comp_id = $_GET['edit'];
        $comp_name = $_GET['comp_name'];
        echo "
        <form method='POST' action=''>
            Edit competition: <input type='text' name='new_comp_name' value=$comp_name> <br><br>
            <input name='edit' type='submit' value='Submit'>
        </form>
        <br>
        ";
        if (isset($_POST['edit'])) {
            $new_comp_name = $_POST['new_comp_name'];
            $query = "UPDATE competitions SET comp_name = '$new_comp_name' WHERE comp_id = '$comp_id'";
            $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
            if (!$result) {
                echo ($mysqli->error);
                exit();
            }
            load();
        }
    }

    // DELETE FORM

    if (isset($_GET['delete'])) {
        $comp_id = $_GET['delete'];
        $comp_name = $_GET['comp_name'];
        echo "
        <form method='POST' action=''>
            Are you sure you want to delete competition '$comp_name'? <br><br>
            <input name='delete' type='submit' value='YES'>
        </form>
        <br>
        ";
        if (isset($_POST['delete'])) {
            $query = "DELETE FROM competitions WHERE comp_id = '$comp_id'";
            $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
            if (!$result) {
                echo ($mysqli->error);
                exit();
            }
            load();
        }
    }

    ?>

    <!-- ADD FORM -->

    <form method="POST" action="">
        Add competition: <input type="text" name="comp_name"> <br><br>
        <input name='add' type="submit" value="Submit">
    </form>

    <?php
    if (isset($_POST['add'])) {
        $comp_name = $_POST["comp_name"];
        $query = "INSERT INTO competitions SET comp_name = '$comp_name'";
        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }
        load();
    }
    ?>


    <?php

    // load()
    
    function load() {
        header("Location:competitions.php");
    }

    
    // CLOSE DATABASE CONNECTION

    $mysqli->close();
    
    
    ?>
    

</body>

</html>