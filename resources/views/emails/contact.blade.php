<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Yesvite</title>
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
            .main-center,
            .m_-2106421672916089406main-center {
                justify-content: center !important;
            }
        </style>
</head>
<body style="font-family: 'SF Pro Display', sans-serif !important ;margin: 0px auto;background: #ffffff; display: flex; justify-content: center;  max-width:600px;" class="main-center">
   <div style="width: 100%;max-width: 650px;height:100%;padding: 0px 10px;" class="ui-sortable">
      <table style="border-radius: 5px;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3); width: 100%; margin: 0px auto; max-width:600px; height:100%;background: #fff;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3);padding: 30px;border-radius: 20px;" class="full selected-table" border="0" cellpadding="0" cellspacing="0">
         <tbody>
            <tr>
               <td>
                  <img src="{{ asset('public/storage/yesvitelogo.png')}}" style="width: 100%;max-width: 150px;height:65px" alt="logo">
               </td>
            </tr>
            <tr>
               <td height="50" style="font-size:0px">&nbsp;</td>
            </tr>
            <tr>
               <td>
                  <h4 style="font-size: 20px;line-height: 28px;font-weight: 700;color: #0F172A;margin: 0px 0px;">Contact Us Submission</h4>
               </td>
            </tr>
            <tr>
               <td height="30" style="font-size:0px">&nbsp;</td>
            </tr>
            <tr>
               <td>
                    <p style="font-family: 'Manrope', sans-serif; font-size: 12px; line-height: 20px; font-weight: 500; color: #0F172A;">
                        You have received a new message from the Contact Us form. Please find the details below:
                    </p>
                    <p style="font-family: 'Manrope', sans-serif; font-size: 12px; line-height: 20px; font-weight: 500; color: #0F172A;">
                        <strong>Name:</strong> {{ $data['name'] }}
                    </p>
                    <p style="font-family: 'Manrope', sans-serif; font-size: 12px; line-height: 20px; font-weight: 500; color: #0F172A;">
                        <strong>Email:</strong> {{ $data['email'] }}
                    </p>         
                    <p style="font-family: 'Manrope', sans-serif; font-size: 12px; line-height: 20px; font-weight: 500; color: #0F172A;">
                        <strong>Message:</strong> 
                        <br>
                        {{ $data['message'] }}
                    </p>
                    <p style="font-family:'Manrope';font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">
                        © Yesvite 2024
                    </p>
               </td>
            </tr>
            <tr>
               <td height="10" style="font-size:0px">&nbsp;</td>
            </tr>
            <tr>
               <td>
                  <div class="download-form-img" style="display: flex;align-items: center;gap: 10px;">
                     <a href="#" style="width: 100%;max-width: 120px;height: 40px;border-radius: 5px;display: block;"><img src="{{ asset('public/storage/google-play.png')}}" alt="" style="width: 100%;height: 100%;"></a>
                     <a href="#" style="width: 100%;max-width: 120px;height: 40px;border-radius: 5px;display: block;"><img src="{{ asset('public/storage/app-store.png')}}" alt="" style="width: 100%;height: 100%;"></a>
                  </div>
               </td>
            </tr>
            <tr>
               <td height="30" style="font-size:0px">&nbsp;</td>
            </tr>
            <tr>
               <td>
                  <div class="social-icons" style="display: flex;align-items: center;justify-content: space-between;">
                     <div style="width: 100%;max-width: 95px;height: 50px;"><img src="{{ asset('public/storage/yesvitelogo.png')}}" alt="" style="width: 100%;height: 100%;"></div>
                        <ul style="display: flex;align-items: center;gap: 10px;margin: 0px 0px;">
                            <li style="list-style-type: none;"><a href="" style="color: #475569;width: 100%;max-width: 16px;height: 16px;"><i class="fa-brands fa-facebook"></i></a></li>
                            <li style="list-style-type: none;"><a href="" style="color: #475569;width: 100%;max-width: 16px;height: 16px;"><i class="fa-brands fa-twitter"></i></a></li>
                            <li style="list-style-type: none;"><a href="" style="color: #475569;width: 100%;max-width: 16px;height: 16px;"><i class="fa-brands fa-apple" style="font-size: 18px;"></i></a></li>
                        </ul>
                  </div>
               </td>
            </tr>
            <tr>
               <td height="20" style="font-size:0px">&nbsp;</td>
            </tr>
            {{-- <tr>
               <td>
                  <p style="font-family:'Manrope';font-size: 12px;line-height: 20px;font-weight: 700;color: #0F172A;margin: 0px;">Invitee Email: <a href="mailto:crisilis@hotmail.com" style="font-family:'Manrope';font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">crisilis@hotmail.com</a></p>
               </td>
            </tr> --}}
         </tbody>
      </table>
   </div>
</body>

</html>
