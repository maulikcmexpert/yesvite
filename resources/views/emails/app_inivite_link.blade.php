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

      .main-center,
      .m_-2106421672916089406main-center {
         justify-content: center !important;
      }
   </style>
</head>

<body style="font-family: 'SF Pro Display', sans-serif !important ;margin: 0px auto;background: #ffffff; display: flex; justify-content: center;  max-width:600px;" class="main-center">
   <!-- partial:index.partial.html -->
   <div style="width: 100%;max-width: 650px;height:100%;padding: 0px 10px;" class="ui-sortable">
      <table style="border-radius: 5px;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3); width: 100%; margin: 0px auto; max-width:600px; height:100%;background: #fff;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3);padding: 30px;border-radius: 20px;" class="full selected-table" border="0" cellpadding="0" cellspacing="0">
         <tbody>
            <tr>
               <td>
                  {{-- <img src="./logo.svg" alt="logo"> --}}
                  <img src="{{ asset('public/storage/yesvitelogo.png')}}" style="width: 100%;max-width: 150px;height:40px" alt="logo">

               </td>
            </tr>
            <!-- -------------- -->
            <tr>
               <td height="50" style="font-size:0px">&nbsp;</td>
            </tr>
            <!-- -------------- -->
            <tr>
               <td>
                  <h4 style="font-size: 20px;line-height: 28px;font-weight: 700;color: #0F172A;margin: 0px 0px;">{{$userdata['send_by']}} <span style="font-size: 20px;line-height: 28px;font-weight: 500;color: #0F172A;margin: 0px 0px;">sent you a private message via Yesvite</span></h4>
               </td>
            </tr>
            <!-- -------------- -->
            <tr>
               <td height="20" style="font-size:0px">&nbsp;</td>
            </tr>
            <!-- -------------- -->
            <tr>
               <td>

                  <!-- <div class="invited-persons" style="display: flex;align-items: center;justify-content: space-between; width: 100%;max-width: 250px;background-color: #F0FFE8;border: 1px solid #23AA26;padding: 5px 10px 5px 10px;border-radius: 100px;margin-left: 50px;">
                  <div class="persons-left">
                     <h5 style="font-size: 10px;line-height: normal;font-weight: 600;color: #0F172A;margin: 0px;">RSVP’d <span style="color: #23AA26;">YES</span></h5>
                  </div>
                  <div class="persons-right" style="display: flex;align-items: center;gap: 15px;">
                     <span class="person-span-1" style="font-size: 10px;line-height: normal;font-weight: 600;color: #0F172A;position: relative;"><i class="fa-solid fa-user-group" style="color: #DDE3E0;font-size: 8px;"></i> 3 Adults</span>
                     <span style="font-size: 10px;line-height: normal;font-weight: 600;color: #0F172A;">2 Kids</span>
                  </div>
               </div>
               <p style="font-size: 18px;line-height: 25px;font-weight: 400;color: #0F172A;margin: 0px 0px;margin-top: 15px !important;">“Thanks guys for the invite!  Can’t wait to see you guys”</p> -->
               </td>
            </tr>
            <tr>
               <td height="40" style="font-size:0px">&nbsp;</td>
            </tr>
            <!-- -------------- -->
            <tr>
               <td>
                  <div class="view-btn" style="display: flex;align-items: center;gap: 15px;">
                     <button style="font-family: 'SF Pro Display', sans-serif;font-size: 14px;line-height: 20px;font-weight: 500;color: #fff;background: #F73C71;border: 1px solid #F73C71;border-radius: 10px; padding: 10px 24px 10px 24px;">View Message</button>
                     <button style="font-family: 'SF Pro Display', sans-serif;font-size: 14px;line-height: 20px;font-weight: 500;color: #0F172A;background: transparent;border: 1px solid #E2E8F0;border-radius: 10px; padding: 10px 24px 10px 24px;">View Maria's Profile</button>
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
                  <p style="font-family:'Manrope';font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">Please add <a href="" style="color: #0F172A;font-size: 12px;line-height: 20px;font-weight: 700;">Notifications@yesvite.com</a> to your contacts so the email does not go to your SPAM folder.</p>
                  <p style="font-family:'Manrope';font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">If you don’t want to receive these notifications please update your <a href="" style="color: #F73C71;font-weight: 700;text-transform: capitalize;">Account Settings > Notifications.</a></p>
                  <p style="font-family:'Manrope';font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">You have received this email from <a href="" style="color: #F73C71;font-weight: 700;">notifications@yesvite.com</a> on behalf of <a href="" style="color: #F73C71;font-weight: 700;">ekuanox@gmail.com</a>.</p>
                  <p style="font-family:'Manrope';font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">© Yesvite 2024</p>
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
                  <p style="font-family:'Manrope';font-size: 12px;line-height: 20px;font-weight: 700;color: #0F172A;margin: 0px;">Invitee Email: <a href="mailto:crisilis@hotmail.com" style="font-family:'Manrope';font-size: 12px;line-height: 20px;font-weight: 500;color: #0F172A;">crisilis@hotmail.com</a></p>
               </td>
            </tr>
            <!-- -------------- -->
         </tbody>
      </table>

   </div>
   <!-- partial -->

</body>

</html>