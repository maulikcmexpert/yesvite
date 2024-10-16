<!DOCTYPE html>
<html lang="en">

<head>
    <title>Email Varification</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ====== bootstrap css ======= -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- ======== font-awesome ====== -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins';
        }

        .verify-wrapper {
            position: relative;
            width: 100%;
            max-width: 600px;
            height: 100vh;
            display: block;
            margin: 0px auto;
            min-width: 280px;
            padding: 0 15px;
        }

        .verify-wrapper .logo {
            width: 70px;
            margin-bottom: 40px;
            /* margin: 0 auto 30px; */
        }

        .verify-wrapper .logo img {
            width: 100%;
        }

        .success-img {
            margin: 0 auto 30px;
            text-align: center;
        }

        .success-img i {
            font-size: 60px;
            color: #E73080;
        }

        .success-img img {
            width: 100%;
        }

        .verify-content {
            border-radius: 10px;
            background: #FFF;
            width: 100%;
            max-width: 600px;
            box-shadow: 0px 7px 21px 0px rgba(30, 36, 82, 0.05);
            padding-top: 39px;
            padding-left: 30px;
            padding-right: 30px;
            display: block;
            padding-bottom: 50px;
            margin-top: 50px;
        }

        .verify-content h3 {
            font-size: 25px;
        }

        .verify-content p {
            font-size: 16px;
            /* color: lightgrey; */
            line-height: 28px;
            text-align: center;
            font-weight: 300;
            max-width: 250px;
            margin: 15px auto 0;
            color: #5c5555;
        }

        .email-login-btn {
            background-color: #F73C71 !important;
            border: 1px solid #F73C71 !important;
            color: #fff;
            border-radius: 16px;
            -webkit-border-radius: 16px;
            -moz-border-radius: 16px;
            -ms-border-radius: 16px;
            -o-border-radius: 16px;
            padding: 12px 30px;
            font-size: 14px;
            font-weight: 600;
            transition: 0.5s;
            -webkit-transition: 0.5s;
            -moz-transition: 0.5s;
            -ms-transition: 0.5s;
            -o-transition: 0.5s;
            text-transform: capitalize;

        }

        .email-login-btn-wrp {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 20px !important;
        }
    </style>

</head>

<body>
    <div class="verify-wrapper">
        <div class="container">
            <div class="verify-content">
                <div class="logo">
                    <img src="{{ asset('public/storage/yesvitelogo.png')}}" alt="logo">
                </div>
                <div class="success-img">

                    @if($faild != 'faild')
                    <i class="fa-regular fa-circle-check"></i>
                    @else
                    <i class="fa-regular fa-circle-xmark"></i>
                    @endif
                </div>
                @if($faild != 'faild')
                <h3 class="text-center">Email Verification</h3>
                <p>{{$message}}</p>
                <a href="{{route('auth.login')}}" class="email-login-btn-wrp"><button type="button" class="email-login-btn">Login</button></a>
                @endif
                @if($faild == 'reSendfaild')
                <h3 class="text-center">Invalid Token</h3>
                <p>{{$message}}</p>
                <a href="{{route('ResendVerificationMail',$user_id)}}" class="email-login-btn-wrp" id="requestEmail"><button type="button" class="email-login-btn">Request Another Email</button></a>
                {{-- <input type="hidden" name="user_id" value="{{$user_id}}"> --}}
                @endif
                <p>{{$message}}</p>


            </div>
        </div>
    </div>
</body>

</html>