<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        .button {
            background-color: black;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }

        .container {
            position: relative;
            border-radius: 5px;
            background-color: lightgrey;
            padding: 80px 0 100px 0;
        }

        input,
        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 4px;
            margin: 5px 0;
            opacity: 0.85;
            display: inline-block;
            font-size: 17px;
            line-height: 20px;
            text-decoration: none;
        }

        input:hover,
        .btn:hover {
            opacity: 1;
        }

        .fb {
            background-color: #3B5998;
            color: white;
        }

        .twitter {
            background-color: #55ACEE;
            color: white;
        }

        .google {
            background-color: #dd4b39;
            color: white;
        }

        input[type=submit] {
            background-color: #04AA6D;
            color: white;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        .col {
            float: left;
            width: 50%;
            margin: auto;
            padding: 0 50px;
            margin-top: 6px;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .vl {
            position: absolute;
            left: 50%;
            transform: translate(-50%);
            border: 2px solid #ddd;
            height: 175px;
        }

        .vl-innertext {
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 50%;
            padding: 8px 10px;
        }

        /* hide some text on medium and large screens */
        .hide-md-lg {
            display: none;
        }

        .bottom-container {
            text-align: center;
            background-color: #666;
            border-radius: 0px 0px 4px 4px;
        }

        @media screen and (max-width: 650px) {
            .col {
                width: 100%;
                margin-top: 0;
            }

            .vl {
                display: none;
            }

            .hide-md-lg {
                display: block;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <form action="teacher_login_process.php" method="POST">
        <div class="row">
            <center><img src="C:\Users\91979\Desktop\uhayam\WhatsApp Image 2023-09-21 at 9.36.48 PM.jpeg" width=10%></center>
            <h2 style="text-align:center">UDHAYAM INSTITUTE</h2>
            <div class="vl">
                <span class="vl-innertext">or</span>
            </div>

            <div class="col">
                <a href="https://www.facebook.com/login.php" class="btn">
                    <i class="fa fa-facebook fa-fw"></i> Login with Facebook
                </a>
                <a href="https://twitter.com/i/flow/login" class="btn">
                    <i class="fa fa-twitter fa-fw"></i> Login with Twitter
                </a>
                <a href="#" class="btn">
                    <i class="fa fa-phone fa-fw"></i> Login with phone number
                </a>
                <a href="1.html" class="btn">
                    <i class="fa fa-home fa-fw"></i> back to home
                </a>
            </div>

            <div class="col">
                <div class="hide-md-lg">
                </div>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button class="button" type="submit">LOGIN</button>
            </div>

        </div>
    </form>
</div>

<div class="bottom-container">
    <center><a href="forgotpassword.php" style="color:white" class="btn">Forgot password</a></center>
</div>

</body>
</html>
