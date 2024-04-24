<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans');

        * {
            box-sizing: border-box;
        }

        body {
            background-color: #fafafa;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .c-email {
            width: 40vw;
            border-radius: 40px;
            overflow: hidden;
            box-shadow: 0px 7px 22px 0px rgba(0, 0, 0, .1);
        }

        .c-email__header {
            background-color: #0fd59f;
            width: 100%;
            height: 60px;
        }

        .c-email__header__title {
            font-size: 23px;
            font-family: 'Open Sans', sans-serif; /* Set font directly */
            height: 60px;
            line-height: 60px;
            margin: 0;
            text-align: center;
            color: white;
        }

        .c-email__content {
            width: 100%;
            height: 350px;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: center;

            background-color: #fff;
            padding: 15px;
        }

        .c-email__content__text {
            font-size: 20px;
            text-align: center;
            color: #343434;
            margin-top: 0;
        }

        .c-email__code {
            display: block;
            width: 60%;
            margin: 30px auto;
            background-color: #ddd;
            border-radius: 40px;
            padding: 20px;
            text-align: center;
            font-size: 36px;
            font-family: 'Open Sans', sans-serif; /* Set font directly */
            letter-spacing: 10px;
            box-shadow: 0px 7px 22px 0px rgba(0, 0, 0, .1);
        }
    </style>
</head>
<body>
<div class="c-email">
    <div class="c-email__header">
        <h2 class="c-email__header__title">Password Reset Request</h2>
    </div>
    <div class="c-email__content">
        <p class="c-email__content__text">Hello,</p>
        <p class="c-email__content__text">You are receiving this email because we received a password reset request for
            your account.</p>
        <div class="c-email__code">
            <span class="c-email__code__text">77878</span>
        </div>
        <p class="c-email__content__text">If you did not request a password reset, no further action is required.</p>
        <p class="c-email__content__text">Thank you!</p>
    </div>
</div>
</body>
</html>
