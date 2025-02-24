<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Email</title>
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.cdnfonts.com/css/sf-pro-display');
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap');

        a {
            text-decoration: none;
        }
    </style>
</head>

<body style="font-family: 'SF Pro Display', sans-serif !important;margin: 0px;background: #F8F8F8;display: flex;justify-content: center;">
    <!-- partial:index.partial.html -->
    <div style="width: 100%;max-width: 650px; margin:0 auto; height:100%;padding: 1 0px 10px;" class="ui-sortable">
        <table style="border-radius: 5px;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3);width: 100%;height:100%;background: #fff;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3);padding: 30px;border-radius: 20px;" class="full selected-table" border="0" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td>
                        <a href="#" style="width: 80px; height: auto; display: block;" class="logo">
                            <img src="{{ asset('public/storage/yesvitelogo.png') }}" alt="" style="width: 100%; height: 100%;">
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
                        <h4 style="font-size: 26px;line-height: 44px;font-weight: 400;color: #000000 ;margin: 0px 0px;font-family: 'SF Pro Display'">
                            Hello,Support Team
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
                        <p style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">
                            I am writing to report an inappropriate {{$userdata['report_from']}} that I encountered on your platform. The content violates the platform's community guidelines, and I would appreciate your prompt attention to this matter.
                        </p>
                    </td>
                </tr>
                <!-- -------------- -->
                <tr>
                    <td height="20" style="font-size:0px">&nbsp;</td>
                </tr>
                <!-- -------------- -->
                <tr>
                    @if($userdata['report_from']=='chat')
                    <td style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">
                        <p>Reporter Username : {{isset($userdata['reporter_username'])?$userdata['reporter_username']:""}}</p>
                        <p>Reported Username : {{isset($userdata['reported_username'])?$userdata['reported_username']:""}}</p>
                        <p>Report Type : {{isset($userdata['report_type'])?$userdata['report_type']:""}}</p>
                        <p>Report Description : {{isset($userdata['report_description'])?$userdata['report_description'] :""}}</p>
                        <p>Report Time : {{isset($userdata['report_time'])?$userdata['report_time'] :""}}</p>
                    </td>
                    @else
                    <td style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">
                        <p>Reporter Username : {{isset($userdata['reporter_username'])?$userdata['reporter_username'] :""}}</p>
                        <p>Event Name : {{isset($userdata['event_name'])?$userdata['event_name'] :""}}</p>
                        <p>Report Type : {{isset($userdata['report_type'])? $userdata['report_type'] :""}}</p>
                        <p>Report Description : {{isset($userdata['report_description'])?$userdata['report_description'] :""}}</p>
                        <p>Report Time : {{isset($userdata['report_time'])? $userdata['report_time'] :""}}</p>
                    </td>
                    @endif
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
                        <p style="font-family: 'SF Pro Display';font-size: 16px;line-height: 20px;font-weight: 700;color: #0F172A;margin-bottom: 5px;">
                            Best regards,
                        </p>
                        <p style="font-family: 'SF Pro Display';font-size: 16px;line-height: 20px;font-weight: 700;color: #0F172A;margin: 0px;">
                            {{$userdata['reporter_username']}}
                        </p>
                    </td>
                </tr>
                <!-- -------------- -->
            </tbody>
        </table>

    </div>
    <!-- partial -->

</body>

</html>