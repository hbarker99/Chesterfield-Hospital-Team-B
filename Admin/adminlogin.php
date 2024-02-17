<html>

<head>
</head>

<body>
<h1> Admin Login </h1>
    <div class="text-block" id="home">
        <div class="text-block">
            <?php
    include("../class/adminconnections.php");
    if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['login']))) {
        $username = $_POST['Username'];
        $password = $_POST['Password'];

        $result = admin_login($Username, $Password);
        if ($result) {
            header("Location: ../Admin/adminhome.php?uname=".$Username);
        } else {
            echo "invalid credintials";
        }

    }
    ?>

<form method="post">
                <div>
                    <label>Username</label>
                    <input required type="username" name="username" id="username" />
                </div>

                <div>
                    <label> Password </label>
                    <input required type="password" name="password" id="password" />
                    <br />
                    <a href="./forgot_password.php"> Forgot Password ? </a>
                </div>

                <div>
                    <input type="submit" value="Login" name="login" id="login" />
                </div>
            </form>
    


</body>

</html>