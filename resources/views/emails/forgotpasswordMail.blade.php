<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CodePen - Email Newsletter Template</title>
  <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <style>
      @import url('https://fonts.cdnfonts.com/css/sf-pro-display');
      @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap');
      a{
         text-decoration: none;
      }
   </style>
</head>
<body style="font-family: 'SF Pro Display', sans-serif !important;margin: 0px;background: #F8F8F8;display: flex;justify-content: center;">
<!-- partial:index.partial.html -->
<div style="width: 100%;max-width: 650px; margin:0 auto; height:100%;padding: 0px 10px;" class="ui-sortable">
   <table style="border-radius: 5px;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3);width: 100%;height:100%;background: #fff;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3);padding: 30px;border-radius: 20px;" class="full selected-table" border="0" cellpadding="0" cellspacing="0">
      <tbody>
         <tr>
            <td>
                <a href="#" style="width: 70px; height: auto; display: block;" class="logo">
                    <img src="{{ asset('public/storage/yesvitelogo.png')}}" alt="" style="width: 100%; height: 100%;">
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
                <p style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">** This is an automated message **</p>
            </td>
         </tr>
         <!-- -------------- -->
         <tr>
            <td height="15" style="font-size:0px">&nbsp;</td>
         </tr>
         <!-- -------------- -->
         <tr>
            <td>
               <p style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">Hello {{$userData[0]['username']}},</p>
            </td>
         </tr>
         <!-- -------------- -->
         <tr>
            <td height="15" style="font-size:0px">&nbsp;</td>
         </tr>
         <!-- -------------- -->
         <tr>
            <td>
               <p style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">Please enter the following TOKEN on the Yeswite App to verify that it is you who requested the password reset:</p>
            </td>
         </tr>
         <!-- -------------- -->
        <tr>
        <td height="25" style="font-size:0px">&nbsp;</td>
        </tr>
         <!-- -------------- -->
         <tr>
            <td>
               <div class="view-btn" style="display: flex;align-items: center;gap: 15px; justify-content:center">
                   <input type="text" value="{{ $userData[0]['digit1']}}" style="width: 35px; height: 35px; border: 1px solid #F73C71; border-radius: 5px;color: #F73C71; font-weight: 600; font-family: 'SF Pro Display'; font-size: 18px; text-align: center; ">
                   <input type="text" value="{{$userData[0]['digit2']}}" style="width: 35px; height: 35px; border: 1px solid #F73C71; border-radius: 5px; color: #F73C71; font-weight: 600; font-family: 'SF Pro Display'; font-size: 18px; text-align: center;">
                   <input type="text" value="{{$userData[0]['digit3']}}" style="width: 35px; height: 35px; border: 1px solid #F73C71; border-radius: 5px; color: #F73C71; font-weight: 600; font-family: 'SF Pro Display'; font-size: 18px; text-align: center;">
                   <input type="text" value="{{$userData[0]['digit4']}}" style="width: 35px; height: 35px; border: 1px solid #F73C71; border-radius: 5px; color: #F73C71; font-weight: 600; font-family: 'SF Pro Display'; font-size: 18px; text-align: center;">
               </div>
            </td>
         </tr>
         <tr>
            <td height="25" style="font-size:0px">&nbsp;</td>
        </tr>
        <tr>
            <td>
               <p style="font-size: 16px;line-height: 25px;font-weight: 400;color: #0F172A ;margin: 0px 0px;font-family: 'SF Pro Display'">Please note that the token is only valid for 5 minutes. If it expires please request another by requesting another password reset.</p>
            </td>
         </tr>
         <!-- -------------- -->
         <tr>
            <td height="20" style="font-size:0px">&nbsp;</td>
         </tr>
         <!-- -------------- -->
         <tr>
            <td>
               <p style="font-family: 'SF Pro Display';font-size: 16px;line-height: 20px;font-weight: 700;color: #0F172A;margin-bottom: 5px;">Thank you,</p>
               <p style="font-family: 'SF Pro Display';font-size: 16px;line-height: 20px;font-weight: 700;color: #0F172A;margin: 0px;">The Yesvite Team</p>
            </td>
         </tr>
         <!-- -------------- -->
      </tbody>
   </table>

</div>
<!-- partial -->
  
</body>
</html>
