<!DOCTYPE html>
<html lang="en">

<head>
    <title>Otp email template</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ====== bootstrap css ======= -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- ======== font-awesome ====== -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<style>
    @font-face {
        font-family: 'Poppins-Bold';
        src: url('./fonts/Poppins-Bold.woff2') format('woff2'),
            url('Poppins-Bold.woff') format('woff');
        font-weight: bold;
    }

    @font-face {
        font-family: 'Poppins-Regular';
        src: url('./fonts/Poppins-Regular.woff2') format('woff2'),
            url('./fonts/Poppins-Regular.woff2') format('woff');
        font-weight: 400;
    }

    font-face {
        font-family: 'Poppins-SemiBold';
        src: url('./fonts/Poppins-SemiBold.woff2') format('woff2'),
            url('./fonts/Poppins-SemiBold.woff2') format('woff');
        font-weight: 600;
    }

    @media only screen and (max-width:768px) {
        .pattern {
            display: none;
        }

        .logo img {
            max-width: 170px;
        }

        table {
            width: 100% !important;
        }
    }
</style>



<body style="box-sizing: border-box;padding: 0; margin: 0px; position: relative;padding-top:40px;padding-bottom: 40px;">
    <table style="position: relative; width:100%;max-width: 600px; height: 100vh;display: block;margin: 0px auto; min-width: 280px; padding: 0 15px; ">
        <tr>
            <td style="display:block; padding-bottom: 40px;">
                <table style="padding: 30px 0">
                    <tbody>
                        <tr>
                            <td>
                                <a href="#" style="width: 70px; height: auto; display: block;" class="logo">
                                    <img src="{{ asset('public/storage/yesvitelogo.png')}}" alt="" style="width: 100%; height: 100%;">
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table style="border-radius: 10px;background: #FFF; width: 100% ;max-width: 600px; box-shadow: 0px 7px 21px 0px rgba(30, 36, 82, 0.05);padding-top: 39px; padding-left: 30px; padding-right: 30px; display: block;padding-bottom: 100px;">
                    <tbody>
                        <tr>
                            <td style="color: #524d4d; text-align: left; font-family: 'Poppins-Regular'; font-size: 15px; line-height: 28px; padding-bottom: 5px;">
                                ** This is an automated message **
                            </td>
                        </tr>
                        <tr>
                            <td style="color: #524d4d; text-align: left; font-family: 'Poppins-Regular'; font-size: 15px; line-height: 28px; padding-bottom: 5px;">
                                Hello {{$userData[0]['username']}},
                            </td>
                        </tr>
                        <tr>
                            <td style="color: #524d4d; text-align:left; font-family: 'Poppins-Regular'; font-size: 15px; line-height: 19px;">
                                Please enter the following TOKEN on the Yeswite App to verify that it is you who requested the password reset:
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex justify-content-center" type="button" style="border-radius: 10px;background: #fff; color:#ffffff; box-shadow: 0px 5px 20px 0px rgba(255, 78, 0, 0.20); display:flex; justify-content:center;
                                    padding: 18px 0px; border: 1px solid transparent; font-size: 18px; font-family: 'Poppins-Regular'; font-weight: 600;max-width: 402px; width: 100%; gap:20px; margin:30px auto 20px;">
                                    <span style="width:35px;height: 35px; border: 1px solid #E73080; display: block; text-align: center; line-height: 30px; background-color: transparent; border-radius: 5px; color:#E73080; margin-right:10px;">{{ $userData[0]['digit1']}}</span>
                                    <span style="width:35px;height: 35px; border: 1px solid #E73080; display: block; text-align: center; line-height: 30px; background-color: transparent; border-radius: 5px; color:#E73080; margin-right:10px;">{{$userData[0]['digit2']}}</span>
                                    <span style="width:35px;height: 35px; border: 1px solid #E73080; display: block; text-align: center; line-height: 30px; background-color: transparent; border-radius: 5px; color:#E73080; margin-right:10px;">{{$userData[0]['digit3']}}</span>
                                    <span style="width:35px;height: 35px; border: 1px solid #E73080; display: block; text-align: center; line-height: 30px; background-color: transparent; border-radius: 5px; color:#E73080; margin-right:10px;">{{$userData[0]['digit4']}}</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="color: #524d4d; text-align:left; font-family: 'Poppins-Regular'; font-size: 15px; line-height: 19px; padding-bottom: 15px;">
                                Please note that the token is only valid for 5 minutes. If it expires please request another by requesting another password reset.
                            </td>
                        </tr>
                        <tr>
                            <td style="color: #524d4d; font-family: 'Poppins-Regular' ; font-size: 14px; line-height: 20px;">Thank you,<br />
                                The Yesvite Team</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <div class="pattern position-absolute" style="bottom: 0; left: 0;">
        <img src="./images/Pattern.png" alt="">
    </div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>