<div class="print-container bg-white mx-auto text-black"
     style="
        width: 210mm;
        min-height: 297mm;
        padding: 14mm 16mm 12mm 16mm;
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 13px;
        line-height: 1.4;
     ">

    <!-- Header with logo and Annex -->
    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px; font-family: Arial, Helvetica, sans-serif;">
        <div>
            <img src="{{ asset('dist/logo/philhealth_logo.png') }}"
                 alt="PhilHealth Logo"
                 style="width:150px; height:auto;">
        </div>
     
    </div>

    <!-- Title -->
    <div style="text-align:center; margin-bottom:12px; font-family: Arial, Helvetica, sans-serif;">
        <div style="font-weight:bold; font-size:18px;">
            PHILHEALTH NCR CONSENT FORM
        </div>
    </div>

    <!-- Intro -->
    <div style="margin-bottom:12px; text-align:justify; font-family: Arial, Helvetica, sans-serif;">
        <div style="font-weight:bold; font-size:16px; margin-bottom:8px;">
            PAHINTULOT PARA SA PAGPAPADALA NG BENEFIT PAYMENT NOTICE (BPN) SA<br>
            PAMAMAGITAN NG SMS AT/O EMAIL
        </div>

        <div style="margin-bottom:6px;font-size:16px;">
            Sa pamamagitan nito, malaya at boluntaryo kong ibinibigay ang aking pahintulot sa PhilHealth
            Regional Office NCR na padalhan ako ng impormasyon tungkol sa aking Benefit Payment
            Notice (BPN) o ang detalye ng bayad sa aking confinement/availment gamit ang sumusunod na
            pamamaraan:
        </div>

        <div style="font-style:italic; font-size:15px;">
            (I hereby freely and voluntarily authorize PhilHealth Regional Office NCR to send information
            regarding my Benefit Payment Notice (BPN) or the details of my benefit payment for my
            confinement/availment through the following methods:)
        </div>
    </div>

    <!-- Section I -->
    <div style="margin-bottom:14px; font-family: Arial, Helvetica, sans-serif;">
        <div style="font-weight:bold; font-size:15px; margin-bottom:8px;">
            I. PAGPILI NG PAMAMARAAN (PLEASE MARK YOUR CHOICE):
        </div>

        <div style="padding-left:20px;">
            <div style="margin-bottom:6px;font-size:12px">
                [ ] <b>SMS / TEXT MESSAGE</b>
                <span style="margin-left:6px;">Cellphone Number:</span>
                <span style="display:inline-block; min-width:230px; border-bottom:1px solid #000;font-size:12px">
                    {{ $MOBILE_NO ?? '' }}
                </span>
            </div>

            <div style="margin-bottom:6px;font-size:12px">
                [ ] <b>EMAIL</b>
                <span style="margin-left:6px;font-size:12px">Email Address:</span>
                <span style="display:inline-block; min-width:250px; border-bottom:1px solid #000;font-size:12px">
                   {{ $EMAIL }}
                </span>
            </div>

            <div style="margin-bottom:6px;font-size:12px">
                [ ] <b>Others:</b>
                <span style="display:inline-block; min-width:300px; border-bottom:1px solid #000;font-size:12px">&nbsp;</span>
            </div>
        </div>
    </div>

    <!-- Section II -->
    <div style="margin-bottom:16px; text-align:justify; font-family: Arial, Helvetica, sans-serif;">
        <div style="font-weight:bold; font-size:15px; margin-bottom:8px;">
            II. PAGSANG-AYON AT PAGKILALA (AGREEMENT AND ACKNOWLEDGMENT):
        </div>

        <ol style="margin:0; padding-left:24px;; font-family: Arial, Helvetica, sans-serif; font-size:15px">
            <li style="margin-bottom:5px;">Nauunawaan ko na ang aking BPN ay naglalaman ng impormasyon tungkol sa aking health insurance benefits.</li>
            <li style="margin-bottom:5px;">Kinikilala ko na ang pagbibigay ng maling impormasyon (cellphone number o email) ay maaaring maging sanhi ng hindi ko pagtanggap ng abiso.</li>
            <li style="margin-bottom:5px;">Sumasang-ayon ako na ang PhilHealth ay walang pananagutan kung ang impormasyon ay mabasa ng ibang tao dahil sa maling numerong naibigay o kung ang aking mobile device/email account ay hindi ligtas.</li>
            <li>Ang pahintulot na ito ay mananatiling may bisa hanggang hindi ko binabawi sa pamamagitan ng isang nakasulat na abiso sa PhilHealth NCR.</li>
        </ol>
<div style="margin-top:24px; font-family: Arial, Helvetica, sans-serif;">
 <div  style="margin-bottom:16px; text-align:justify; font-family: Arial, Helvetica, sans-serif;font-style:italic;">
            (I understand that my BPN contains sensitive information. I acknowledge that providing
            incorrect contact details may result in non-receipt of the notice. I agree that PhilHealth
            shall not be held liable for unauthorized access to the information if the provided contact
            details are incorrect or if my device/account is compromised. This consent remains valid
            until revoked in writing.)
        </div>

</div>
       
    </div>

    <!-- Signature -->
   <div style="margin-top:24px; font-family: Arial, Helvetica, sans-serif;">

    <!-- Signature line (TOP) -->
    <div style="text-align:left; margin-bottom:14px; font-size:13px;">
    
    <b>LAGDA NG MIYEMBRO / KINATAWAN</b>

    <!-- Wrapper for line + italic text -->
    <span style="display:inline-block; margin-left:6px;">
        
        <!-- Line directly above -->
        <div style="width:260px; border-top:1px solid #000; margin-bottom:2px;"></div>

        <!-- Italic text -->
        <span style="font-style:italic;">
            (Signature of Member / Authorized Representative)
        </span>

    </span>

</div>

    <!-- Name -->
    <div style="margin-bottom:10px;">
        <b>PANGALAN (PRINTED NAME):</b>
        <span style="display:inline-block; width:65%; border-bottom:1px solid #000;"><b>{{ $MEMBER_LAST_NAME }}, {{ $MEMBER_FIRST_NAME }} {{ $MEMBER_MIDDLE_NAME }}</b></span>
    </div>

    <!-- PIN and Date -->
    <div style="margin-top:8px; font-size:14px;">

    <div style="display:flex; align-items:center; gap:10px;">

        <!-- PIN -->
        <span><b>PhilHealth ID Number (PIN):</b></span>
        <span style="flex:1; border-bottom:1px solid #000; height:14px;"><b>{{ $PIN }}</b></span>

        <!-- Date -->
        <span style="margin-left:20px;"><b>Petsa (Date):</b></span>
        <span style="flex:1; border-bottom:1px solid #000; height:14px;"></span>

    </div>

</div>

</div>
    </div>
</div>