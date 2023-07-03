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

        //Test if table exists
        $res = mysqli_query($conn, "SHOW TABLES LIKE 'Contact'");
        if (mysqli_num_rows($res) <= 0) {
            echo "<h2>Contact Page is empty</h2>";
        } else {
            //Update data
            $ProductName = $_POST['ProductName'];
            $Price = $_POST['Price'];

            if ($stmt = mysqli_prepare($conn, "UPDATE Products SET Price = ? WHERE ProductName = ?")) {
                mysqli_stmt_bind_param($stmt, 'ds', $Price, $ProductName);
                mysqli_stmt_execute($stmt);
                if (mysqli_stmt_affected_rows($stmt) == 0) {
                    echo "<h2>Message \"$ProductName\" was not found in the contact page.</h2>";
                }
                else {
                    echo "<h2>Email of the Dog \"$ProductName\" has been successfully updated to $Price.</h2>";
                }
                mysqli_stmt_close($stmt);
                
            } 
        }
        //Close the connection
        mysqli_close($conn);

    } else {

    ?>

    <h2>Update a Product</h2>
    <br>

    <form method="post" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <table>
            <tr>
                <td class="no-border"> <label for="ProductName">Message</label> </td>
                <td class="no-border"> <input type="text" name="ProductName" id="ProductName"> </td>
            </tr>
            <tr>
                <td class="no-border"> <label for="Price">Updated email</label> </td>
                <td class="no-border"> <input type="text" name="Price" id="Price"> </td>
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
            <td> <a href="update.php">Update another message</a> </td>
            <td> <a href="read.php">View Messages</a> </td>
            <td> <a href="index.php">Back to Contact Us</a> </td>
        </tr>
    </table>

</div>

<?php require "templates/footer.php"; ?>

