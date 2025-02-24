<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation</title>
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.cdnfonts.com/css/sf-pro-display');
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap');

        a {
            text-decoration: none;
        }

        body {
            font-family: 'SF Pro Display', Arial, sans-serif;
        }

        /* Optionally, you can specify font families for specific elements */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'SF Pro Display', Arial, sans-serif;
        }

        p {
            font-family: 'SF Pro Display', Arial, sans-serif;
        }

        .view-btn {
            flex-wrap: wrap !important;
            flex-direction: column !important;
            width: 100% !important;
        }

        button:nth-child(1) {
            margin-right: 10px !important;
        }

        .view-btn {
            flex-wrap: wrap;
            flex-direction: column;
        }

        .view-btn-link {
            text-decoration: none !important;
        }

        #mobile-view-btn {
            visibility: hidden;
        }

        .ii a[href] {
            text-decoration: none !important;
        }

        @media only screen and (max-width:1100px) {
            button:nth-child(1) {
                margin-right: 0px !important;
                margin-bottom: 12px !important;
            }

            button {
                width: 100% !important;
                display: block !important;
            }
        }

        @media screen and (max-width: 600px) {
            #view-btn {
                flex-wrap: wrap !important;
                flex-direction: column !important;
            }

            button:nth-child(1) {
                margin-right: 0px !important;
                margin-bottom: 12px !important;
            }

            .view-btn-link {
                width: 250px !important;
                max-width: 100% !important;
                display: block !important;
                margin-bottom: 5px;
            }

            .invited-persons {
                max-width: 190px !important;
            }

            #desktop-view-btn {
                visibility: hidden;
            }

            #mobile-view-btn {
                display: block;
            }
        }
    </style>
</head>

<body style="font-family: 'SF Pro Display', sans-serif !important;margin: 0px;background: #F8F8F8;display: flex;justify-content: center;">
    <!-- partial:index.partial.html -->
    <div style="width: 100%;max-width: 650px; height:100%; padding: 0px 10px; margin: 50px auto 0px" class="ui-sortable">
        <table style="border-radius: 5px;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3);width: 100%;height:100%;background: #fff;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3);padding: 30px;border-radius: 20px;" class="full selected-table" border="0" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td>
                        <div style="width: 100%;max-width: 150px;height:65px">
                            <img src="{{asset('public/storage/yesvitelogo.png')}}" style="width: 100%;max-width: 150px;height:65px" alt="logo">
                        </div>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td height="30" style="font-size:0px">&nbsp;</td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td>
                        <h4 style="font-size: 32px;line-height: 28px;font-weight: 700;color: #0F172A;margin: 0px 0px;">You’re Invited! 🎉</h4>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td height="30" style="font-size:0px">&nbsp;</td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td>
                        <div class="user-name" style="display: flex;align-items: center;gap: 15px;margin-bottom: 15px;">
                            <!-- <span style="display: block; width:50px; height: 50px; max-width:100%; margin-right:10px; margin-top:9px">
                                <img src="{{ asset('public/storage/profile/'.$eventData['profileUser'])}}" style="width: 100%;height: 100%; border-radius:50%; margin-right:10px; object-fit:cover; display:block" alt="user-img">
                            </span> -->
                            <h3 style="text-transform: capitalize;font-size: 32px;line-height: 44px;font-weight: 700;color: #0A090B;margin: 0px 0px;text-transform: capitalize;margin-left: 0px;width:85%; word-wrap:break-word;">{{ @$eventData['hosted_by']}}</h3>
                        </div>
                        <h5 style="font-size: 32px;line-height: 44px;font-weight: 400;color: #0A090B;margin: 0px 0px;">has invited you to:</h5>
                        <h2 style="font-size: 32px;line-height: 44px;font-weight: 700;color: #0A090B;margin: 0px 0px;">{{ @$eventData['event_name'] }}</h2>
                        <p style="font-size: 18px;line-height: 27px;font-weight: 400;color: #0F172A;margin: 10px 0px;"> {{ @$eventData['date'] .' @'. @$eventData['time'] }}</p>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td>
                        <div class="invited-img" style="width: 100%;max-width: 300px;height: 430px;border-radius: 10px;border: 1px solid lightgray;">
                            <img src="{{ asset('public/storage/event_images/'.$eventData['event_image'])}}" alt="" style="width: 100%;height: 100%;border-radius: 10px;">
                        </div>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td height="30" style="font-size:0px">&nbsp;</td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td>
                        <div class="view-btn mt-0" id="view-btn" style="display:inline;width: 100%;">
                            @php
                                $eventLink = route('rsvp', ['event_invited_user_id' => encrypt($eventData['event_invited_user_id']), 'eventId' => encrypt($eventData['event_id'])]);
                                $shortLink = createShortUrl($eventLink);
                            @endphp
                            <a href="{{$shortLink}}" style="font-family: 'SF Pro Display', sans-serif; margin-right:10px; font-size: 14px;line-height: 20px;font-weight: 500;color: #fff;background: #F73C71;border: 1px solid #F73C71;border-radius: 10px; padding: 10px 24px 10px 24px; text-align:center;text-decoration:none" class="view-btn-link">View Invitation</a>
                        </div>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td height="30" style="font-size:0px">&nbsp;</td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td>
                        <p style="font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">Please add <a href="" style="font-size: 12px;line-height: 20px;font-weight: 700;color: #0F172A;">notifications@yesvite.com</a> to your contacts so the email does not go to your SPAM folder.</p>
                        <p style="font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">If you don’t want to receive these notifications please update your <a href="" style="color: #F73C71;font-weight: 700;text-transform: capitalize;">account settings >Notifications.</a></p>
                        <p style="font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">You have received this email from <a href="" style="color: #F73C71;font-weight: 700;">notifications@yesvite.com</a> on behalf of <a href="" style="color: #F73C71;font-weight: 700;">{{ @$eventData['host_email']}}</a>.</p>
                        <p style="font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">© Yesvite {{date('Y')}}</p>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td height="10" style="font-size:0px">&nbsp;</td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td>
                        <div class="download-form-img" style="display: flex;align-items: center;gap: 10px;">
                            <a href="https://play.google.com/store/apps/details?id=com.yesvite.android" style="width: 100%;max-width: 120px;height: 40px;border-radius: 5px;display: block;margin-right: 15px"><img src="{{ asset('public/storage/google-play.png')}}" alt="" style="width: 100%;height: 100%;"></a>
                            <a href="https://apps.apple.com/us/app/yesvite/id6736650042" style="width: 100%;max-width: 120px;height: 40px;border-radius: 5px;display: block;"><img src="{{ asset('public/storage/app-store.png')}}" alt="" style="width: 100%;height: 100%;"></a>
                        </div>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td height="30" style="font-size:0px">&nbsp;</td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td>
                        <div class="social-icons" style="display: flex;align-items: center;justify-content: space-between;">
                            <div style="width: 100%;max-width: 95px;height: 50px;">
                                <img src="{{ asset('public/storage/yesvitelogo.png')}}" alt="" style="width: 100%;height: 100%;">
                            </div>
                            <ul style="display: flex;align-items: center;gap: 10px;margin: 0px 0px;">
                                <li style="list-style-type: none;"><a href="" style="color: #475569;width: 100%;max-width: 16px;height: 16px;"><i class="fa-brands fa-facebook"></i></a></li>
                                <li style="list-style-type: none;"><a href="" style="color: #475569;width: 100%;max-width: 16px;height: 16px;"><i class="fa-brands fa-twitter"></i></a></li>
                                <li style="list-style-type: none;"><a href="" style="color: #475569;width: 100%;max-width: 16px;height: 16px;"><i class="fa-brands fa-apple" style="font-size: 18px;"></i></a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td height="20" style="font-size:0px">&nbsp;</td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td>
                        <p style="font-size: 12px;line-height: 20px;font-weight: 700;color: #0F172A;margin: 0px;">Invite Email: <span style="font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">{{@$eventData['host_email']}}</span></p>
                    </td>
                </tr>
                <!-- -------------- -->
            </tbody>
        </table>

    </div>
    <!-- partial -->

</body>

</html>