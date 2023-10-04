<!-- A list of fixtures, with links to edit and delete each fixture. A form to add a new fixture. -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fixtures</title>
</head>

<body>

    <?php
    include 'menu.html';
    include 'mySQL_connect.php';
    ?>

    <h1>Fixtures</h1>

    <!-- TABLE -->

    <table border="1" width="50%">

        <tr>
            <th>Fixture</th>
            <th>Date</th>
            <th>Time</th>
            <th>Home Team</th>
            <th>Away Team</th>
            <th>Comp</th>
            <th>Actions</th>
        </tr>

        <?php

        $query = "SELECT * FROM fixtures";

        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }

        while (list($fixture_id, $fixture_date, $fixture_time, $home_teamID, $away_teamID, $comp_id) = $result->fetch_row()) {
            echo "
                <tr>
                    <td>$fixture_id</td>
                    <td>$fixture_date</td>
                    <td>$fixture_time</td>
                    <td>
            ";
            getTeam($mysqli, $home_teamID);
            echo "
                    </td>
                    <td>
            ";
            getTeam($mysqli, $away_teamID);
            echo "
                    </td>
                    <td>
            ";
            getComp($mysqli, $comp_id);
            echo "
                    </td>
                    <td>
                    <a href='fixtures.php?edit=$fixture_id&fixture_date=$fixture_date&fixture_time=$fixture_time&home_teamID=$home_teamID&away_teamID=$away_teamID&comp_id=$comp_id'>Edit</a>
                    <a href='fixtures.php?delete=$fixture_id'>Delete</a>
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
        $fixture_id = $_GET['edit'];
        $fixture_date = $_GET['fixture_date'];
        $fixture_time = $_GET['fixture_time'];
        $home_teamID = $_GET['home_teamID'];
        $away_teamID = $_GET['away_teamID'];
        $comp_id = $_GET['comp_id'];
        echo "
            <form method='POST' action=''>
                Edit fixture: <br><br>
                <input type='text' name='new_fixture_date' value=$fixture_date> <br><br>
                <input type='text' name='new_fixture_time' value=$fixture_time> <br><br>
                Select home team: <select name='new_home_teamID'>
        ";
        getTeamsList($mysqli, $home_teamID);
        echo "
                </select> <br><br>
                Select away team: <select name='new_away_teamID'>
        ";
        getTeamsList($mysqli, $away_teamID);
        echo "
                </select> <br><br>
                Select competition: <select name='new_comp_id'>
        ";
        getCompList($mysqli, $comp_id);
        echo "
                </select> <br><br>
                <input name='edit' type='submit' value='Submit'>
            </form>
            <br>
        ";
        if (isset($_POST['edit'])) {
            $new_fixture_date = $_POST['new_fixture_date'];
            $new_fixture_time = $_POST['new_fixture_time'];
            $new_home_teamID = $_POST['new_home_teamID'];
            $new_away_teamID = $_POST['new_away_teamID'];
            $new_comp_id = $_POST['new_comp_id'];
            $query = "UPDATE fixtures SET fixture_date = '$new_fixture_date', fixture_time = '$new_fixture_time', home_teamID = '$new_home_teamID', away_teamID = '$new_away_teamID', comp_id = '$new_comp_id' WHERE fixture_id = '$fixture_id'";
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
        $fixture_id = $_GET['delete'];
        echo "
        <form method='POST' action=''>
            Are you sure you want to delete fixture $fixture_id? <br><br>
            <input name='delete' type='submit' value='YES'>
        </form>
        <br>
        ";
        if (isset($_POST['delete'])) {
            $query = "DELETE FROM fixtures WHERE fixture_id = '$fixture_id'";
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
        Add fixture: <br><br>
        Date: <input type="text" name="fixture_date"> <br><br>
        Time: <input type="text" name="fixture_time"> <br><br>
        Select home team: <select name="home_teamID"> <?php getTeamsList($mysqli, null); ?> </select> <br><br>
        Select away team: <select name="away_teamID"> <?php getTeamsList($mysqli, null); ?> </select> <br><br>
        Select competition: <select name="comp_id"> <?php getCompList($mysqli, null); ?> </select> <br><br>
        <input name='add' type="submit" value="Submit">
    </form>

    <?php
    if (isset($_POST['add'])) {
        $fixture_date = $_POST["fixture_date"];
        $fixture_time = $_POST["fixture_time"];
        $home_teamID = $_POST["home_teamID"];
        $away_teamID = $_POST["away_teamID"];
        $comp_id = $_POST["comp_id"];
        $query = "INSERT INTO fixtures (fixture_date, fixture_time, home_teamID, away_teamID, comp_id) VALUES ('$fixture_date', '$fixture_time', '$home_teamID', '$away_teamID', '$comp_id')";
        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }
        load();
    }
    ?>


    <?php


    // getTeam()

    function getTeam($mysqli, $team_id)
    {
        $query = "SELECT team_name FROM teams WHERE team_id = '$team_id'";

        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }

        while (list($team_name) = $result->fetch_row()) {
            echo $team_name;
        }
    }

    // getComp()

    function getComp($mysqli, $comp_id)
    {
        $query = "SELECT comp_name FROM competitions WHERE comp_id = '$comp_id'";

        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }

        while (list($comp_name) = $result->fetch_row()) {
            echo $comp_name;
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

    // getCompList()

    function getCompList($mysqli, $display_comp_id)
    {

        if (isset($display_comp_id)) {

            $query = "SELECT comp_id, comp_name FROM competitions WHERE comp_id = '$display_comp_id'";

            $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
            if (!$result) {
                echo ($mysqli->error);
                exit();
            }

            while (list($display_comp_id, $display_comp_name) = $result->fetch_row()) {
                echo "<option value=$display_comp_id>Comp $display_comp_name</option>";
            }
        }

        $query = "SELECT comp_id, comp_name FROM competitions";

        $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
        if (!$result) {
            echo ($mysqli->error);
            exit();
        }

        while (list($comp_id, $comp_name) = $result->fetch_row()) {
            if ($display_comp_id != $comp_id) {
                echo "<option value=$comp_id>Comp $comp_name</option>";
            }
        }
    }

    // load()   

    function load()
    {
        header("Location:fixtures.php");
    }


    // CLOSE DATABASE CONNECTION

    $mysqli->close();


    ?>


</body>

</html>