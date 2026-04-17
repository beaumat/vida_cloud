<div class="print-container bg-white mx-auto text-black"
    style="
    width: 100%;
    max-width: 210mm;
    min-height: 100%;
    padding: 20mm 1mm 1mm 1mm;
    font-family: Arial, Helvetica, sans-serif;
    text-align: justify;
    line-height: 1.35;
    box-sizing: border-box;
    margin: none;
    background: #fff;
">

    <!-- Annex  padding: 18mm 20mm 18mm 20mm;-->
    <div style="text-align:right; margin-bottom:24px;">
        <div style="font-size:20px;">ANNEX A</div>
    </div>

    <!-- Title -->
   <div style="text-align:center; margin-bottom:24px; font-family: Arial, Helvetica, sans-serif;">
    <div style="font-weight:bold; font-size:30px;">
        PHILHEALTH NCR CONSENT FORM
    </div>
</div>

    <!-- Intro -->
    <div style="margin-bottom:18px; text-align:justify;">
      <div style="font-weight:bold; font-size:20px; font-family: Arial; margin-bottom:10px;">
    PAHINTULOT PARA SA PAGPAPADALA NG BENEFIT PAYMENT NOTICE (BPN) SA <br>
    PAMAMAGITAN NG SMS AT/O EMAIL
</div>

        {{-- <div style="margin-bottom:10px; font-size:18px; font-family: Arial, Helvetica, sans-serif">
            Sa pamamagitan nito, malaya at boluntaryo kong ibinibigay ang aking pahintulot sa <b>PhilHealth Regional Office NCR</b> na padalhan ako ng impormasyon tungkol sa aking Benefit Payment Notice (BPN) o ang detalye ng bayad sa aking confinement/availment gamit ang sumusunod na pamamaraan:
        </div>

        <div style="font-style:italic; font-size:12px;">
            (I hereby freely and voluntarily authorize PhilHealth Regional Office NCR to send information regarding my Benefit Payment Notice (BPN) or the details of my benefit payment for my confinement/availment through the following methods:)
        </div> --}}
    </div>

      <div style="margin-bottom:10px; font-size:19px; font-family: Arial; text-align:justify;">
            Sa pamamagitan nito, malaya at boluntaryo kong ibinibigay ang aking pahintulot sa <b>PhilHealth Regional Office NCR</b> na padalhan ako ng impormasyon tungkol sa aking <b>Benefit Payment Notice (BPN)</b> o ang detalye ng bayad sa aking confinement/availment gamit ang sumusunod na pamamaraan:
        </div>

       <div style="font-style: italic; font-size: 19px; text-align: justify; text-justify: inter-word;
        line-height: 1.4; width: 100%; display: block;margin-bottom:8px">
    (I hereby freely and voluntarily authorize PhilHealth Regional Office NCR to send information regarding my Benefit Payment Notice (BPN) or the details of my benefit payment for my confinement/availment through the following methods:)
</div>


    <!-- Section I -->
    <div style="margin-bottom:24px;">
        <div style="font-weight:bold; font-size:20px; margin-bottom:10px;margin-left: 20px;">
            I. PAGPILI NG PAMAMARAAN (PLEASE MARK YOUR CHOICE):
        </div>

        <div style="padding-left:22px">
            <div style="margin-bottom:8px;font-size:19px;margin-left: 25px">
                [ ] <b>SMS / TEXT MESSAGE</b>
                <span>Cellphone Number:</span>
                <span style="display:inline-block; width:320px; border-bottom:1px solid #000; line-height:1;">
                    {{ $MOBILE_NO ?? '' }}
                </span>
            </div>

            <div style="margin-bottom:8px;font-size:19px;margin-left: 25px">
                [ ] <b>EMAIL</b>
                <span>Email Address:</span>
                <span style="display:inline-block; width:500px; border-bottom:1px solid #000; line-height:1;">
                    {{ $EMAIL ?? '' }}
                </span>
            </div>

            <div style="margin-bottom:8px;font-size:19px;margin-left: 25px">
                [ ] <b>Others:</b>
                <span style="display:inline-block; width:620px; border-bottom:1px solid #000; line-height:1;">
                    &nbsp;
                </span>
            </div>
        </div>
    </div>

    <!-- Section II -->
    <div style="margin-bottom:28px;">
        <div style="font-weight:bold;font-size:20px; margin-bottom:10px;margin-left: 20px">
            II. PAGSANG-AYON AT PAGKILALA (AGREEMENT AND ACKNOWLEDGMENT):
        </div>

        <ol style="margin:0; padding-left:28px; font-size:19px;margin-left: 25px">
            <li style="margin-bottom:6px;">
                Nauunawaan ko na ang aking BPN ay naglalaman ng impormasyon tungkol sa aking health insurance benefits.
            </li>
            <li style="margin-bottom:6px;">
                Kinikilala ko na ang pagbibigay ng maling impormasyon (cellphone number o email) ay maaaring maging sanhi ng hindi ko pagtanggap ng abiso.
            </li>
            <li style="margin-bottom:6px;">
                Sumasang-ayon ako na ang PhilHealth ay walang pananagutan kung ang impormasyon ay mabasa ng ibang tao dahil sa maling numerong naibigay o kung ang aking mobile device/email account ay hindi ligtas.
            </li>
            <li>
                Ang pahintulot na ito ay mananatiling may bisa hanggang hindi ko binabawi sa pamamagitan ng isang nakasulat na abiso sa PhilHealth NCR.
            </li>
        </ol>

        <div style="font-style:italic; font-size:19px; margin-top:18px;margin-left: 25px">
            (I understand that my BPN contains sensitive information. I acknowledge that providing incorrect contact details may result in non-receipt of the notice. I agree that PhilHealth shall not be held liable for unauthorized access to the information if the provided contact details are incorrect or if my device/account is compromised. This consent remains valid until revoked in writing.)
        </div>
    </div>
    <!-- Signature -->
    <div style="margin-top:30px;">
          <!-- Signature -->
   <div style="margin-top:24px; font-family: Arial, Helvetica, sans-serif;">

    <!-- Signature line (TOP) -->
            <div style="text-align:left; margin-bottom:14px; font-size:19px;">
            
            <b>LAGDA NG MIYEMBRO / KINATAWAN</b>

            <!-- Wrapper for line + italic text -->
            <span style="display:inline-block; margin-left:6px;">
                
                <!-- Line directly above -->
                <div style="width:425px; border-top:1px solid #000; margin-bottom:2px;"></div>

                <!-- Italic text -->
                <span style="font-style:italic;">
                    (Signature of Member / Authorized Representative)
                </span>

            </span>

 </div>

        <div style="margin-bottom:12px; font-size:19px;">
            <b>PANGALAN (PRINTED NAME):</b>
            <span style="display:inline-block; width:63%; border-bottom:1px solid #000; line-height:1;">
                {{ $MEMBER_LAST_NAME ?? '' }}, {{ $MEMBER_FIRST_NAME ?? '' }} {{ $MEMBER_MIDDLE_NAME ?? '' }}
            </span>
        </div>

        <div style="display:flex; align-items:center; gap:14px; font-size:19px;">
            <span><b>PhilHealth ID Number (PIN):</b></span>
            <span style="flex:1; border-bottom:1px solid #000; line-height:1;">
                {{ $PIN ?? '' }}
            </span>

            <span><b>Petsa (Date):</b></span>
            <span style="flex:1; border-bottom:1px solid #000; line-height:1">
              {{ $DATE_DISCHARGED ? \Carbon\Carbon::parse($DATE_DISCHARGED)->format('m/d/Y') : '' }}
            </span>
        </div>
    </div>

</div>