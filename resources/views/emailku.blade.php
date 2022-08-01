<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        /* desktop view */
        .container {
            font-family: Helvetica, Arial, sans-serif;
            min-width: 1000px;
            overflow: auto;
            line-height: 2;
        }

        .container2 {
            margin: 50px auto;
            width: 70%;
            padding: 20px 0;
        }

        .head {
            border-bottom: 1px solid #eee;
        }

        .head .title {
            font-size: 22.4px;
            color: #00466a;
            text-decoration: none;
            font-weight: 600;
        }

        .user {
            font-size: 17.6px;
        }

        .otp {
            background: #00466a;
            margin: 0 auto;
            width: max-content;
            padding: 0 10px;
            color: #fff;
            border-radius: 4px;
        }

        .regards {
            font-size: 14.4px;
        }

        hr {
            border: none;
            border-top: 1px solid #eee;
        }

        .company {
            float: right;
            padding: 8px 0;
            color: #aaa;
            font-size: 12px;
            line-height: 1;
            font-weight: 300;
        }

        /* tab view */
        @media only screen and (max-width: 768px) {
            .container {
                max-width: 768px;
            }

            .container2 {
                margin: 50px auto;
                width: 70%;
                padding: 20px 0;
            }
        }

        /* mobile view */
        @media only screen and (max-width: 425px) {
            .container {
                max-width: 768px;
            }

            .container2 {
                margin: 50px auto;
                width: 70%;
                padding: 20px 0;
            }

            .container2 p {
                font-size: 30px;
            }

            .head .title {
                font-size: 50px;
            }

            .user {
                font-size: 30px;
            }

            .regards {
                font-size: 30px;
            }

            .company {
                font-size: 30px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="container2">
            <div class="head">
                <a href="" class="title">Musko App</a>
            </div>
            <p class="user">Hi, {{$user}}</p>
            <p>
                Thank you for choosing Musko App. Use the following OTP to complete
                your Sign Up procedures. OTP is valid for 30 minutes
            </p>
            <h2 class="otp">{{$otp}}</h2>
            <p class="regards">Regards,<br />Musko App</p>
            <hr />
            <div class="company">
                <p>Musko App</p>
                <p>Sukabirus</p>
                <p>Bandung</p>
            </div>
        </div>
    </div>
     
</body>

</html>