<!-- A list of teams, with links to edit and delete each team. A form to add a new team. -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams</title>
</head>

<body>

    <?php
    include 'menu.html';
    include 'mySQL_connect.php';
    ?>

    <h1>Teams</h1>

    <!-- TABLE -->

    <table border="1" width="50%">

        <tr>
            <th>Team</th>
            <th>Team email</th>
            <th>Actions</th>
        </tr>

        <?php

        $query = "SELECT * FROM teams";

        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }

        while(list($team_id, $team_name, $team_email) = $result->fetch_row()) {
            echo "
                <tr>
                    <td>$team_name</td>
                    <td><a href='mailto:$team_email'>$team_email</a></td>
                    <td>
                    <a href='teams.php?edit=$team_id&team_name=$team_name&team_email=$team_email'>Edit</a>
                    <a href='teams.php?delete=$team_id&team_name=$team_name'>Delete</a>
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
        $team_id = $_GET['edit'];
        $team_name = $_GET['team_name'];
        $team_email = $_GET['team_email'];
        echo "
        <form method='POST' action=''>
            Edit team: <br><br>
            Team: <input type='text' name='new_team_name' value=$team_name> <br><br>
            Team email: <input type='text' name='new_team_email' value=$team_email> <br><br>
            <input name='edit' type='submit' value='Submit'>
        </form>
        <br>
        ";
        if (isset($_POST['edit'])) {
            $new_team_name = $_POST['new_team_name'];
            $query = "UPDATE teams SET team_name = '$new_team_name' WHERE team_id = '$team_id'";
            $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
            if(!$result) {
                echo($mysqli->error);
                exit();
            }
            load();
        }
    }

    // DELETE FORM

    if (isset($_GET['delete'])) {
        $team_id = $_GET['delete'];
        $team_name = $_GET['team_name'];
        echo "
        <form method='POST' action=''>
            Are you sure you want to delete team '$team_name', <br>
            all players associated with that team, <br>
            the fixures that the team has played in, <br>
            as well as the player fixtures? <br><br>
            <input name='delete' type='submit' value='YES'>
        </form>
        <br>
        ";
        if (isset($_POST['delete'])) {
            $query =
            /* DELETE FROM players WHERE team_id = '$team_id';
            DELETE FROM fixtures WHERE team_id = '$team_id';
            DELETE FROM player_fixtures WHERE team_id = '$team_id'; */ 
            "DELETE FROM teams WHERE team_id = '$team_id';
            ";

            $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
            if(!$result) {
                echo($mysqli->error);
                exit();
            }
            load();
        }
    }

    ?>

    <!-- ADD FORM -->

    <form method="POST" action="">
        Add team: <br><br>
        Team: <input type="text" name="team_name"> <br><br>
        Team email: <input type="text" name="team_email"> <br><br>
        <input name='add' type="submit" value="Submit">
    </form>

    <?php
    if (isset($_POST['add'])) {
        $team_name = $_POST["team_name"];
        $team_email = $_POST["team_email"];
        $query = "INSERT INTO teams (team_name, team_email) VALUES ('$team_name', '$team_email')";
        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if(!$result) {
            echo($mysqli->error);
            exit();
        }
        load();
    }
    ?>

    <!-- LOAD -->

    <?php
        function load() {
            header("Location:teams.php");
        }
    ?>


    <!-- CLOSE DATABASE CONNECTION -->

    <?php $mysqli->close(); ?>

</body>

</html>