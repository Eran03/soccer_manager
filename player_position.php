<!-- A list of player positions with links to edit and delete each player position. A form to add a new player position. -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLayer Positions</title>
</head>

<body>

    <?php
    include 'menu.html';
    include 'mySQL_connect.php';
    ?>

    <h1>Player Positions</h1>

    <!-- TABLE -->

    <table border="1" width="50%">

        <tr>
            <th>Player position</th>
            <th>Actions</th>
        </tr>

        <?php

        $query = "SELECT * FROM playerposition ORDER BY position_id";

        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }

        while (list($position_id, $position_descr) = $result->fetch_row()) {
            echo "
                <tr>
                    <td>$position_descr</td>
                    <td>
                    <a href='player_position.php?edit=$position_id&position_descr=$position_descr'>Edit</a>
                    <a href='player_position.php?delete=$position_id&position_descr=$position_descr'>Delete</a>
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
        $position_id = $_GET['edit'];
        $position_descr = $_GET['position_descr'];
        echo "
        <form method='POST' action=''>
            Edit competition: <input type='text' name='new_position_descr' value=$position_descr> <br><br>
            <input name='edit' type='submit' value='Submit'>
        </form>
        <br>
        ";
        if (isset($_POST['edit'])) {
            $new_position_descr = $_POST['new_position_descr'];
            $query = "UPDATE playerposition SET position_descr = '$new_position_descr' WHERE position_id = '$position_descr'";
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
        $position_id = $_GET['delete'];
        $position_descr = $_GET['position_descr'];
        echo "
        <form method='POST' action=''>
            Are you sure you want to delete player position '$position_descr'? <br><br>
            <input name='delete' type='submit' value='YES'>
        </form>
        <br>
        ";
        if (isset($_POST['delete'])) {
            $query = "DELETE FROM playerposition WHERE position_id = '$position_id'";
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
        Add player position: <input type="text" name="position_descr"> <br><br>
        <input name='add' type="submit" value="Submit">
    </form>

    <?php
    if (isset($_POST['add'])) {
        $position_descr = $_POST["position_descr"];
        $position_id = getPositionID($mysqli, $position_descr);
        $query = "INSERT INTO playerposition SET position_id = '$position_id', position_descr = '$position_descr'";
        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }
        load();
    }
    ?>

    <?php

    // getPositionID()

    function getPositionID($mysqli, $position_descr)
    {
        switch ($position_descr) {
            case 'GK':
                return 1;
                break;
            case 'CB':
                return 2;
                break;
            case 'LB':
                return 3;
                break;
            case 'FB':
                return 4;
                break;
            case 'LWB':
                return 5;
                break;
            case 'RWB':
                return 6;
                break;
            case 'SW':
                return 7;
                break;
            case 'DM':
                return 8;
                break;
            case 'CM':
                return 9;
                break;
            case 'AM':
                return 10;
                break;
            case 'LW':
                return 11;
                break;
            case 'RW':
                return 12;
                break;
            case 'CF':
                return 13;
                break;
            case 'WF':
                return 14;
                break;

            default:
                $query = "SELECT MAX(position_id) FROM playerposition";
                $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
                if (!$result) {
                    echo ($mysqli->error);
                    exit();
                }
                while (list($var) = $result->fetch_row()) {
                    $max = $var;
                }
                if($max < 15) {
                    return 15;
                } else {
                    return $max+1;
                }
                break;
        }
    }

    // load()

    function load()
    {
        header("Location:player_position.php");
    }


    // CLOSE DATABASE CONNECTION

    $mysqli->close();


    ?>

</body>

</html>