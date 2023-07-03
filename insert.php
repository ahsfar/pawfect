<?php require "templates/header.php"; ?>

<div class="center-align">

    <?php

    if (isset($_POST['submit'])) {
        require "database/config.php";

        //Establish the connection
        $conn = mysqli_init();
        mysqli_ssl_set($conn,NULL,NULL,$sslcert,NULL,NULL);
        if(!mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306, MYSQLI_CLIENT_SSL)){
            die('Failed to connect to MySQL: '.mysqli_connect_error());
        }

        $res = mysqli_query($conn, "SHOW TABLES LIKE 'Contact'");
    
        if (mysqli_num_rows($res) <= 0) {
            //Create table if it does not exist
            $sql = file_get_contents("database/schema.sql");
            if(!mysqli_query($conn, $sql)){
                die('Table Creation Failed');
            }
        }

        // Insert data from form
        $DogName = $_POST['DogName'];
        $Email = $_POST['Email'];

        if ($stmt = mysqli_prepare($conn, "INSERT INTO Contact (DogName, Email) VALUES (?, ?)")) {
            mysqli_stmt_bind_param($stmt, 'sd', $DogName, $Email);
            mysqli_stmt_execute($stmt);
            if (mysqli_stmt_affected_rows($stmt) == 0) {
                echo "<h2>Unalbe to send message.</h2>";
            }
            else {
                echo "<h2> Message has been successfully sent.</h2>";
            }
            mysqli_stmt_close($stmt);
            
        }

        //Close the connection
        mysqli_close($conn);

    } else {

    ?>

    <h2>Send message</h2>
    <br>

    <form method="post" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <table>
            <tr>
                <td class="no-border"> <label for="DogName">Dog Name</label> </td>
                <td class="no-border"> <input type="text" name="DogName" id="DogName"> </td>
            </tr>
            <tr>
                <td class="no-border"> <label for="Email">Email</label> </td>
                <td class="no-border"> <input type="text" name="Email" id="Email"> </td>
            </tr>
        </table>      
        <br><br>
        <input type="submit" name="submit" value="Submit">
    </form>

    <?php
        }
    ?>

    <br> <br> <br>
    <table>
        <tr>
            <td> <a href="insert.php">Add Another Contact</a> </td>
            <td> <a href="read.php">View messages</a> </td>
            <td> <a href="index.php">Back to Contact Us</a> </td>
        </tr>
    </table>

</div>

<?php require "templates/footer.php"; ?>

