<style>
  body {
    margin : 0;
    padding : 0;
  }
  table {
    height: 100% !important;
    width: 100% !important;
    color: black !important;
    margin: 0;
    padding: 0;
    border-collapse: collapse;
    border: none;
  }

  tr {
    margin: 0;
    padding: 0;
    background: #F9F8FC;
    border-collapse: collapse;
  }

  td {
    margin: 0;
    padding: 0;
    border-collapse: collapse;
    text-align: center;
  }

  /* .header {
    
  } */

  /* .body {
    width:50% !important;
    margin-left: 23% !important; 
    padding: 2% !important;
  } */
  .icon {
    width: 32px !important;
    height: 32px !important;
  }

  @media only screen and (max-width: 600px) {
    .body {
      font-family: 'Poppins', sans-serif  !important;
      width:96% !important;
      margin-left: 2% !important; 
      padding: 4% !important;
    }

    .pinCode {
      font-size: 36px !important; 
    }

    .enterYourOTP {
      color: black !important;
      font-size: 24px !important;
    }

    .description {
      color: black !important;
      font-size: 14px !important;
    }

    .footer {
      background: #F9F8FC;
      text-align: center !important; 
      /* left: 50% !important; */
    }

    .logo {
      width: 121px !important;
      height: 40px !important;
    }

    .mailLogo {
      width: 80px !important;
      height: 68.98px !important;
    }

    .followUs {
      margin-left: 8% !important;
    }
  }

  @media only screen and (min-width: 601px) {
    .body {
      font-family: 'Poppins', sans-serif  !important;
      width:50% !important;
      margin-left: 25% !important; 
      padding: 4% !important;
    }

    .pinCode {
      font-size: 56px !important;  
    }

    .enterYourOTP { 
      font-size: 36px !important;  
    }

    .description {
      font-size: 16px !important;
    }
    
    .footer {
      background: #F9F8FC;
      text-align: center !important; 
      margin:0 auto !important;
      /* padding-left: 8% !important;  */
    }

    .logo {
      width: 187px !important;
      height: 62px !important;
    }

    .mailLogo {
      width: 120px !important;
      height: 103.46px !important;
    }

    .followUs {
      margin-left: 21% !important;
    }
  }
</style>

@php
  $pincodeStr = (string)$pin_code;

  $logo = \App\Models\Ad::where('title', 'logo')->first();
  $mailLogo = \App\Models\Ad::where('title', 'mailLogo')->first();
  $facebook = \App\Models\Ad::where('title', 'facebook')->first();
  $linkedin = \App\Models\Ad::where('title', 'linkedin')->first();
  $twitter = \App\Models\Ad::where('title', 'twitter')->first();
@endphp

<body>
  <table class="containter">
    <tr>
      <td>
        <div class="header">
        <img class="logo" src="{{$logo ? url($logo->file) : ''}}" alt="">
        </div>
      </td>
    </tr>
    <tr style="border-color: #F9F8FC;">
      <td >
        <div style="width: 100%; background-color:#F9F8FC;">
          <div class="body" style=" background-color: white;">
            <br>
            <div>
              <img class="mailLogo" src="{{$mailLogo ? url($mailLogo->file) : ''}}" alt="">
            </div>
            <br>
            <br>
            <div class="enterYourOTP" style="text-align:left; font-weight: 600; font-family: 'Poppins', sans-serif  !important;" >
              Enter your OTP
            </div>
            <br>
            <div class="description" style="text-align:left; font-weight: 400; font-family: 'Poppins', sans-serif  !important;">
              To verify your email address, enter this code in your browser
            </div>
            <br>
            <div class="pinCode" style="background-color: #F9F8FC; border-radius: 4px; border: 1px solid #6149AF; padding-top: 4%; font-weight: 600; padding-bottom: 4%">
              {{$pincodeStr[0]}}&nbsp;&nbsp;{{$pincodeStr[1]}}&nbsp;&nbsp;{{$pincodeStr[2]}}&nbsp;&nbsp;{{$pincodeStr[3]}}
            </div>
            <br>
            <div class="description" style="text-align:left; font-weight: 400; font-family: 'Poppins', sans-serif  !important;">
              If you didn’t request a code, you can safely ignore this email
            </div>
            <br>
            <div style="background-color: #F9F8FC; border-radius: 4px; padding-top: 2%; padding-bottom: 2%; font-family: 'Poppins', sans-serif  !important;">
              Have a question? <a href="mailto: support@leaguepls.com">We’re here to help</a>
            </div>
            <br>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td style="">
        <br>
        <div class="footer" style="text-align: center;">
          <ul class="followUs" style="list-style-type: none; align-items: center;"">
            <li>
              <span style="font-size: 14px;font-weight: 500;">Follow us</span>
              <a href="{{\App\Models\Setting::where('type', 'contact.facebook')->orderBy('id', 'desc')->first()->content}}"  target="_blank" style="text-decoration: none;">
                <img class="icon" style="vertical-align: middle;" src="{{$facebook ? url($facebook->file) : ''}}" alt="">
              </a>
              <a href="{{\App\Models\Setting::where('type', 'contact.facebook')->orderBy('id', 'desc')->first()->content}}"  target="_blank" style="text-decoration: none;">
                <img class="icon" style="vertical-align: middle;" src="{{$twitter ? url($twitter->file) : ''}}" alt="">
              </a>
              <a href="{{\App\Models\Setting::where('type', 'contact.facebook')->orderBy('id', 'desc')->first()->content}}"  target="_blank" style="text-decoration: none;">
                <img class="icon" style="vertical-align: middle;" src="{{$linkedin ? url($linkedin->file) : ''}}" alt="">
              </a>
            </li>
          </ul>
        </div>
        <br><br>
      </td>
    </tr>
  </table>
</body>