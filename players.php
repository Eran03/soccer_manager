<!-- A list of player information (ordered by surname), the position they play in and their shirt number, with links to edit and delete each player. A form to add a new player. -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Information</title>
</head>

<body>

    <?php
    include 'menu.html';
    include 'mySQL_connect.php';
    ?>

    <h1>Player Information</h1>

    <!-- TABLE -->

    <table border="1" width="50%">

        <tr>
            <th><a href="players.php">Player</a></th>
            <th><a href="players.php?sort=team_id">Team</a></th>
            <th>Shirt number</th>
            <th>Position</th>
            <th>Actions</th>
        </tr>

        <?php

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        } else {
            $sort = null;
        }
        if ($sort == "team_id") {
            $query = "SELECT * FROM players ORDER BY team_id";
        } else {
            $query = "SELECT * FROM players ORDER BY player_name";
        }

        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }

        while (list($player_id, $team_id, $player_name, $player_sqd_num, $position_id) = $result->fetch_row()) {


            echo "
                <tr>
                    <td>$player_name</td>
                    <td>
            ";
            echo getTeamName($mysqli, $team_id);
            echo "
                    </td>
                    <td>$player_sqd_num</td>
                    <td>
            ";
            echo getPositionDescr($mysqli, $position_id);
            echo "
                    </td>
                    <td>
                    <a href='players.php?sort=$sort&edit=$player_id&team_id=$team_id&player_name=$player_name&player_sqd_num=$player_sqd_num&position_id=$position_id'>Edit</a>
                    <a href='players.php?sort=$sort&delete=$player_id&player_name=$player_name'>Delete</a>
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
        $player_id = $_GET['edit'];
        $team_id = $_GET['team_id'];
        $player_name = $_GET['player_name'];
        $player_sqd_num = $_GET['player_sqd_num'];
        $position_id = $_GET['position_id'];
        echo "
            <form method='POST' action=''>
                Edit player: <br><br>
                Surname Name: <input type='text' name='new_player_name' value=\"$player_name\"'> <br><br>
                Select team: <select name='new_team_id'>
        ";
        getTeamsList($mysqli, $team_id);
        echo "
                </select> <br><br>
                Number: <input type='text' name='new_player_sqd_num' value=$player_sqd_num> <br><br>
                Position: <br><br>
        ";
        getPositions($mysqli, $position_id);
        echo "
                <input name='edit' type='submit' value='Submit'>
            </form>
            <br>
        ";

        if (isset($_POST['edit'])) {
            $new_team_id = $_POST['new_team_id'];
            $new_player_name = $_POST['new_player_name'];

            if($player_sqd_num == $_POST['new_player_sqd_num']) {
                $new_player_sqd_num = $_POST["new_player_sqd_num"];
            } else if(check_player_sqd_num($mysqli, $_POST['new_player_sqd_num'])) {
                $new_player_sqd_num = $_POST["new_player_sqd_num"];
            }
            
            $new_position_id = $_POST['position_id'];
            $query = "UPDATE players SET player_name = '$new_player_name', team_id = '$new_team_id', player_sqd_num = '$player_sqd_num', position_id = '$new_position_id' WHERE player_id = '$player_id'";
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
        $player_id = $_GET['delete'];
        $player_name = $_GET['player_name'];
        echo "
        <form method='POST' action=''>
            Are you sure you want to delete player '$player_name'? <br><br>
            <input name='delete' type='submit' value='YES'>
        </form>
        <br>
        ";
        if (isset($_POST['delete'])) {
            $query = "DELETE FROM players WHERE player_id = '$player_id'";
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
        Add player: <br><br>
        Surname Name: <input type="text" name="player_name"> <br><br>
        Select team: <select name="team_id"> <?php getTeamsList($mysqli, null); ?> </select> <br><br>
        Number: <input type="text" name="player_sqd_num"> <br><br>
        Position: <br><br>
        <?php getPositions($mysqli, null); ?> <br>
        <input name='add' type="submit" value="Submit">
    </form>

    <?php
    if (isset($_POST['add'])) {
        $player_name = $_POST["player_name"];
        $team_id = $_POST["team_id"];
        if (check_player_sqd_num($mysqli, $_POST["player_sqd_num"])) {
            $player_sqd_num = $_POST["player_sqd_num"];
        }
        $position_id = $_POST["position_id"];
        $query = "INSERT INTO players SET player_name = '$player_name', team_id = '$team_id', player_sqd_num = '$player_sqd_num', position_id = '$position_id'";
        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }
        load();
    }
    ?>

    <?php

    // getTeamName()

    function getTeamName($mysqli, $team_id)
    {
        $query = "SELECT team_name FROM teams WHERE team_id = '$team_id'";

        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }

        while (list($team_name) = $result->fetch_row()) {
            return $team_name;
        }
    }

    // getPositionDescr()

    function getPositionDescr($mysqli, $position_id)
    {
        $query = "SELECT position_descr FROM playerposition WHERE position_id = '$position_id'";

        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }

        while (list($poition_descr) = $result->fetch_row()) {
            return $poition_descr;
        }
    }

    // getTeamsList()

    function getTeamsList($mysqli, $display_team_id)
    {
        if (isset($display_team_id)) {

            $query = "SELECT team_id, team_name FROM teams WHERE team_id = '$display_team_id'";

            $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
            if (!$result) {
                echo ($mysqli->error);
                exit();
            }

            while (list($display_team_id, $display_team_name) = $result->fetch_row()) {
                echo "<option value=$display_team_id>Team $display_team_name</option>";
            }
        }

        $query = "SELECT team_id, team_name FROM teams";

        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }

        while (list($team_id, $team_name) = $result->fetch_row()) {

            if ($display_team_id != $team_id) {
                echo "<option value=$team_id>Team $team_name</option>";
            }
        }
    }

    // getPositions()

    function getPositions($mysqli, $checked_position_id)
    {
        $query = "SELECT position_id, position_descr FROM playerposition";

        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }

        echo "<table border=1>";

        $count = 0;

        while (list($position_id, $position_descr) = $result->fetch_row()) {

            if ($count % 2 == 0) {
                echo "<tr>";;
            }

            if ($position_id == $checked_position_id) {
                echo "
                    <td>
                        <label>
                        <input type='radio' checked name='position_id' value=$position_id> $position_descr
                        </label>
                    </td>
                ";
            } else {
                echo "
                    <td>
                        <label>
                        <input type='radio' name='position_id' value=$position_id> $position_descr
                        </label>
                    </td>
                ";
            }

            if ($count % 2 == 1) {
                echo "</tr>";;
            }

            ++$count;
        }

        echo "</table> <br>";
    }

    //check_player_sqd_num()

    function check_player_sqd_num($mysqli, $player_sqd_num)
    {
        $query = "SELECT player_sqd_num FROM players WHERE player_sqd_num = '$player_sqd_num'";

        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }

        $check_player_sqd_num = null;
        while (list($var) = $result->fetch_row()) {
            $check_player_sqd_num = $var;
        }

        if ($check_player_sqd_num == null) {
            return true;
        } else {
            echo "Unable to add player: players on the same team cannot have the same shirt number";
            exit();
        }
    }

    // load()

    function load()
    {
        header("Location:players.php");
    }


    // CLOSE DATABASE CONNECTION

    $mysqli->close();


    ?>

</body>

</html>