<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodePen - Email Newsletter Template</title>
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.cdnfonts.com/css/sf-pro-display');
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap');

        .persons-right .person-span-1::after {
            content: "";
            position: absolute;
            width: 2px;
            height: 10px;
            background: #0000001A;
            top: 50%;
            right: -8px;
            transform: translate(-0%, -50%);
        }

        a {
            text-decoration: none;
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
                width: 100% !important;
                max-width: 100% !important;
                display: block !important;
                margin-bottom: 5px;
            }

            .invited-persons {
                max-width: 120px !important;
            }
        }
    </style>
</head>

<body style="font-family: 'SF Pro Display', sans-serif !important;margin: 0px;background: #F8F8F8;display: flex;justify-content: center;">
    <!-- partial:index.partial.html -->
    <div style="width: 100%;max-width: 650px;margin:0 auto;height:100%; padding: 15px 15px;" class="ui-sortable">
        <table style="border-radius: 5px;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3);width: 100%;height:100%;background: #fff;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3);padding: 30px;border-radius: 20px;" class="full selected-table" border="0" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td>
                        <div style="width: 100%;max-width: 150px;height:40px">
                            <img src="{{ asset('public/storage/yesvitelogo.png')}}" style="width: 100%;max-width: 150px;height:40px; object-fit:cover" alt="logo">
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
                        <h4 style="font-size: 20px;line-height: 28px;font-weight: 700;color: #0F172A;margin: 0px 0px;">You have a new RSVP</h4>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td height="20" style="font-size:0px">&nbsp;</td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td>
                        <div class="user-name" style="display: flex;align-items: center;gap: 15px;margin-bottom: 15px;">
                            <span style="display: block;width: 100%;max-width: 50px;height: 50px;border-radius: 50%0; margin-right:10px;">
                                <img src="{{ asset('public/storage/profile/'.$eventData['profileUser'])}}" style="width: 100%;height: 100%; border-radius:50%; margin-right:10px" alt="user-img">
                            </span>
                            <div>
                                <h3 style="font-size: 20px;line-height: 40px;font-weight: 700;color: #0A090B;margin: 0px 0px;">{{ $eventData['guest_name'] }} <span style="font-weight: 400;">RSVP'd</span> {{ ($eventData['rsvp_status'] == '1')?'YES':'NO' }} <span style="font-weight: 400;">for</span></h3>
                                <h4 style="color: #F73C71;font-size: 24px;line-height: 30px;font-weight: 700;margin: 0px 0px;">{{ $eventData['event_name'] }} </h4>
                            </div>
                        </div>
                        <?php
                        if ($eventData['rsvp_status'] == '1') {
                        ?>

                            <div class="invited-persons" style="display: flex;align-items: center;justify-content: space-between; width: 100%;max-width: 250px;background-color: #F0FFE8;border: 1px solid #23AA26;padding: 5px 10px 5px 10px;border-radius: 100px;margin-left: 50px;">
                                <div class="persons-left">
                                    <h5 style="font-size: 10px;line-height: normal;font-weight: 600;color: #0F172A;margin: 0px;">RSVP’d <span style="color: #23AA26;">YES</span></h5>
                                </div>
                                <div class="persons-right" style="display: flex;align-items: center;gap: 15px;">
                                    <span class="person-span-1" style="font-size: 10px;line-height: normal;font-weight: 600;color: #0F172A;position: relative;"><i class="fa-solid fa-user-group" style="color: #DDE3E0;font-size: 8px;"></i> {{ $eventData['adults'] }} Adults</span>
                                    <span style="font-size: 10px;line-height: normal;font-weight: 600;color: #0F172A;">{{ $eventData['kids'] }} Kids</span>
                                </div>
                            </div>
                        <?php } else { ?>

                            <div class="invited-persons" style="display: flex;align-items: center;justify-content: space-between; width: 100%;max-width: 250px;background-color: #d9a8a8;border: 1px solid #e52121;padding: 5px 10px 5px 10px;border-radius: 100px;margin-left: 50px;">
                                <div class="persons-left">
                                    <h5 style="font-size: 10px;line-height: normal;font-weight: 600;color: #0F172A;margin: 0px;">RSVP’d <span style="color: #e52121;">NO</span></h5>
                                </div>
                                <div class="persons-right" style="display: flex;align-items: center;gap: 15px;">
                                    <span class="person-span-1" style="font-size: 10px;line-height: normal;font-weight: 600;color: #0F172A;position: relative;"><i class="fa-solid fa-user-group" style="color: #DDE3E0;font-size: 8px;"></i> {{ $eventData['adults'] }} Adults</span>
                                    <span style="font-size: 10px;line-height: normal;font-weight: 600;color: #0F172A;">{{ $eventData['kids'] }} Kids</span>
                                </div>
                            </div>
                        <?php }
                        ?>



                        <p style="font-size: 18px;line-height: 25px;font-weight: 400;color: #0F172A;margin: 0px 0px;margin-top: 15px !important;">{{ $eventData['rsvp_message'] }}</p>
                    </td>
                </tr>
                <tr>
                    <td height="40" style="font-size:0px">&nbsp;</td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td class="view-more fdfd" width="10%">
                        <div class="view-btn mt-0" id="view-btn" style="display:inline-block; width:10%">
                            <a href="#" style="width: fit-content;max-width:fit-content; font-family: 'SF Pro Display', sans-serif; margin-right:10px; font-size: 14px;line-height: 20px;font-weight: 500;color: #fff;background: #F73C71;border: 1px solid #F73C71;border-radius: 10px; padding: 10px 24px 10px 24px; display:inline-block; text-align:center;" class="view-btn-link">View Invitation</a>
                            <a href="#" style="width: fit-content;max-width:fit-content; font-family: 'SF Pro Display', sans-serif;font-size: 14px;line-height: 20px;font-weight: 500;color: #0F172A;background: transparent;border: 1px solid #E2E8F0;border-radius: 10px; padding: 10px 24px 10px 24px; text-align:center; display:inline-block;" class="view-btn-link">Message James</a>
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
                        <p style="font-family:'Manrope';font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">Please add <a href="" style="color: #0F172A;font-size: 12px;line-height: 20px;font-weight: 700;">contact@yesvite.com</a> to your contacts so the email does not go to your SPAM folder.</p>
                        <p style="font-family:'Manrope';font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">If you don’t want to receive these notifications please update your <a href="" style="color: #F73C71;font-weight: 700;text-transform: capitalize;">account settings >Notifications.</a></p>
                        <p style="font-family:'Manrope';font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">You have received this email from <a href="" style="color: #F73C71;font-weight: 700;">notifications@yesvite.com</a> on behalf of <a href="" style="color: #F73C71;font-weight: 700;">ekuanox@gmail.com</a>.</p>
                        <p style="font-family:'Manrope';font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">© Yesvite {{date('Y')}}</p>
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
                            <a href="#" style="width: 100%;max-width: 120px;height: 40px;border-radius: 5px;display: block;"><img src="{{ asset('public/storage/google-play.png')}}" alt="" style="width: 100%;height: 100%;"></a>
                            <a href="#" style="width: 100%;max-width: 120px;height: 40px;border-radius: 5px;display: block;"><img src="{{ asset('public/storage/app-store.png')}}" alt="" style="width: 100%;height: 100%;"></a>
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
                            <div style="width: 100%;max-width: 95px;height: 24px;"><img src="{{ asset('public/storage/yesvitelogo.png')}}" alt="" style="width: 100%;height: 100%;"></div>
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
                        <p style="font-family:'Manrope';font-size: 12px;line-height: 20px;font-weight: 700;color: #0F172A;margin: 0px;">Invitee Email: <span style="font-family:'Manrope';font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">crisilis@hotmail.com</span></p>
                    </td>
                </tr>
                <!-- -------------- -->
            </tbody>
        </table>

    </div>
    <!-- partial -->

</body>
<script>

</script>

</html>