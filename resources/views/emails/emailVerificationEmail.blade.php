<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodePen - Email Newsletter Template</title>
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.cdnfonts.com/css/sf-pro-display');
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap');

        a {
            text-decoration: none;
        }
    </style>
</head>

<body
    style="font-family: 'SF Pro Display', sans-serif !important;margin: 0px;background: #F8F8F8;display: flex;justify-content: center;">
    <!-- partial:index.partial.html -->
    <div style="width: 100%;max-width: 650px; margin:0 auto; height:100%;padding: 1 0px 10px;" class="ui-sortable">
        <table
            style="box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3);width: 100%;height:100%;background: #fff;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3);padding: 30px;"
            class="full selected-table" border="0" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td>
                        <a href="#" style="width: 80px; height: auto; display: block;" class="logo">
                            <img src="{{ asset('public/storage/yesvitelogo.png') }}" alt=""
                                style="width: 100%; height: 100%;">
                        </a>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td height="20" style="font-size:0px">&nbsp;</td>
                </tr>
                <!-- -------------- -->

                <!-- -------------- -->
                <tr>
                    <td height="20" style="font-size:0px">&nbsp;</td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td>
                        <h4
                            style="font-size: 16px;line-height: 25px;font-weight: 400;color: #000000 ;margin: 0px 0px;font-family: 'SF Pro Display'">
                            Hello {{$userData['username']}},
                        </h4>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td height="25" style="font-size:0px">&nbsp;</td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td>
                        <p
                            style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">
                            Welcome to Yesvite!</p>
                        <!-- <p style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">
                            "Please verify your email address : {{$userData['email']}} first before you can login to your
                            account".</p> -->
                    </td>
                </tr>
                <tr>
                    <td height="7" style="font-size:0px">&nbsp;</td>
                </tr>
                {{-- <tr>
                    <td>
                        <p
                            style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">
                            Your email address: {{$userData['email']}} was added to your Yesvite account.</p>
                        </p>
                    </td>
                </tr> --}}
                <tr>
                    <td height="7" style="font-size:0px">&nbsp;</td>
                </tr>
                @if(isset($userData['password'])&& $userData['password']!="")
                <tr>
                    <td>
                        <p
                            style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">
                            Your Email have been verified, click the button below to login.</p>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td height="10" style="font-size:0px">&nbsp;</td>
                </tr>

                <tr>
                    <td>
                        <p
                            style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">
                            Your Temporary password for login is : {{$userData['password']}}. </br>After login Please make sure to Change this temporary password for your security</p>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td height="10" style="font-size:0px">&nbsp;</td>
                </tr>


                <!-- -------------- -->
                <tr>
                    <td>
                        <div class="view-btn" style="display: flex;align-items: center;gap: 15px;">
                            <a href="{{ route('auth.login') }}"><button
                                    style="font-family: 'SF Pro Display', sans-serif;font-size: 14px;line-height: 20px;font-weight: 500;color: #fff;background: #F73C71;border: 1px solid #F73C71;border-radius: 10px; padding: 10px 30px 10px 30px;width: 100%;max-width: 325px;text-transform: capitalize;">Login here</button></a>
                         
                        </div>
                    </td>
                </tr>
                <tr>
                    <td height="15" style="font-size:0px">&nbsp;</td>
                </tr>
                {{-- <tr>
                    <td>
                        <p
                            style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">
                            This email expires after 3 hours. But no worries, if your email expires you can request
                            another one from support.</p>
                    </td>
                </tr> --}}
                @else
                <tr>
                    <td>
                        <p
                            style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">
                            To verify your email address and secure your account, click the verify account button below.</p>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td height="10" style="font-size:0px">&nbsp;</td>
                </tr>


                <!-- -------------- -->
                <tr>
                    <td>
                        <div class="view-btn" style="display: flex;align-items: center;gap: 15px;">
                            <a href="{{ route('user.verify',$userData['token']) }}"><button
                                    style="font-family: 'SF Pro Display', sans-serif;font-size: 14px;line-height: 20px;font-weight: 500;color: #fff;background: #F73C71;border: 1px solid #F73C71;border-radius: 10px; padding: 10px 30px 10px 30px;width: 100%;max-width: 325px;text-transform: capitalize;">Verify
                                    Account</button></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td height="15" style="font-size:0px">&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <p
                            style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">
                            This email expires after 3 hours. But no worries, if your email expires you can request
                            another one from our support team <a href="https://support.yesvite.com/hc/en-us">here</a>.</p>
                    </td>
                </tr>
                @endif
                <tr>
                    <td height="7" style="font-size:0px">&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <p
                            style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">
                            If you didn't make this request, please feel free to ignore this email. If you still need additional help please visit our <a href="https://support.yesvite.com/hc/en-us">Help Center</a>.</p>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td height="30" style="font-size:0px">&nbsp;</td>
                </tr>
                <!-- -------------- -->

                <tr>
                    <td height="10" style="font-size:0px">&nbsp;</td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td>
                        <p
                            style="font-family: 'SF Pro Display';font-size: 16px;line-height: 20px;font-weight: 700;color: #0F172A;margin-bottom: 5px;">
                            Thank you,</p>
                        <p
                            style="font-family: 'SF Pro Display';font-size: 16px;line-height: 20px;font-weight: 700;color: #0F172A;margin: 0px;">
                            The Yesvite Team</p>
                    </td>
                </tr>
                <!-- -------------- -->
            </tbody>
        </table>

    </div>
    <!-- partial -->

</body>

</html>