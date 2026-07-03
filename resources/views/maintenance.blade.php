<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Gadgets - Maintenance</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, Helvetica, sans-serif;
        }

        body{
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
            padding:20px;
        }

        .maintenance-box{
            width:100%;
            max-width:550px;
            background:#fff;
            padding:45px 35px;
            text-align:center;
        }

        h2{
            color:#2563eb;
            font-size:34px;
            margin-bottom:10px;
        }

        h1{
            font-size:28px;
            color:#222;
            margin-bottom:15px;
        }

        p{
            color:#666;
            font-size:17px;
            line-height:1.7;
            margin-bottom:30px;
        }

        .status{
            display:inline-block;
            background:#fff3cd;
            color:#856404;
            padding:10px 22px;
            border-radius:30px;
            font-size:15px;
            font-weight:bold;
        }

        .footer{
            margin-top:35px;
            color:#888;
            font-size:14px;
        }

        @media(max-width:600px){

            .maintenance-box{
                padding:35px 20px;
            }

            h2{
                font-size:28px;
            }

            h1{
                font-size:22px;
            }

            p{
                font-size:15px;
            }
        }

    </style>
</head>
<body>

    <div class="maintenance-box">

        <h2>Total Gadgets</h2>

        <h1>Website Under Maintenance</h1>

        <p>
            We are currently performing scheduled maintenance to improve your
            shopping experience. Our website will be back online shortly.
        </p>

        <span class="status">🚧 Maintenance in Progress</span>

        <div class="footer">
            © 2026 Total Gadgets. All Rights Reserved.
        </div>

    </div>

</body>
</html>