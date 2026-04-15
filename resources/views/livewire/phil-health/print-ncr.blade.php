<div class="print-container bg-white mx-auto text-black"
     style="
        width: 210mm;
        height: 297mm;
        padding: 12mm 14mm 10mm 14mm;
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 14px;
        line-height: 1.3;
        overflow: hidden;
     ">

    <!-- Header with logo and Annex -->
    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
        <div>
            <img src="{{ asset('dist/logo/philhealth_logo.png') }}"
                 alt="PhilHealth Logo"
                 style="width:140px;">
        </div>
        <div style="font-weight:bold; font-size:14px;">
            ANNEX A
        </div>
    </div>

    <!-- Title -->
    <div style="text-align:center; margin-bottom:10px;">
        <div style="font-weight:bold; font-size:20px;">
            PHILHEALTH NCR CONSENT FORM
        </div>
    </div>

    <!-- Intro -->
    <div style="margin-bottom:10px; text-align:justify;">
        <div style="font-weight:bold; font-size:15px; margin-bottom:6px;">
            PAHINTULOT PARA SA PAGPAPADALA NG BENEFIT PAYMENT NOTICE (BPN) SA<br>
            PAMAMAGITAN NG SMS AT/O EMAIL
        </div>

        <div style="margin-bottom:5px;">
            Sa pamamagitan nito, malaya at boluntaryo kong ibinibigay ang aking pahintulot sa PhilHealth
            Regional Office NCR na padalhan ako ng impormasyon tungkol sa aking Benefit Payment
            Notice (BPN) o ang detalye ng bayad sa aking confinement/availment gamit ang sumusunod na
            pamamaraan:
        </div>

        <div style="font-style:italic; font-size:13px;">
            (I hereby freely and voluntarily authorize PhilHealth Regional Office NCR to send information
            regarding my Benefit Payment Notice (BPN) or the details of my benefit payment for my
            confinement/availment through the following methods:)
        </div>
    </div>

    <!-- Section I -->
    <div style="margin-bottom:12px;">
        <div style="font-weight:bold; font-size:14px; margin-bottom:6px;">
            I. PAGPILI NG PAMAMARAAN (PLEASE MARK YOUR CHOICE):
        </div>

        <div style="padding-left:18px;">
            <div style="margin-bottom:5px;">
                [ ] <b>SMS / TEXT MESSAGE</b>
                <span style="margin-left:6px;">Cellphone Number:</span>
                <span style="display:inline-block; min-width:200px; border-bottom:1px solid #000;">
                    {{ $MOBILE_NO ?? '' }}
                </span>
            </div>

            <div style="margin-bottom:5px;">
                [ ] <b>EMAIL</b>
                <span style="margin-left:6px;">Email Address:</span>
                <span style="display:inline-block; min-width:220px; border-bottom:1px solid #000;">
                    {{ $EMAIL ?? '' }}
                </span>
            </div>

            <div>
                [ ] <b>Others:</b>
                <span style="display:inline-block; min-width:260px; border-bottom:1px solid #000;">&nbsp;</span>
            </div>
        </div>
    </div>

    <!-- Section II -->
    <div style="margin-bottom:12px; text-align:justify;">
        <div style="font-weight:bold; font-size:14px; margin-bottom:6px;">
            II. PAGSANG-AYON AT PAGKILALA (AGREEMENT AND ACKNOWLEDGMENT):
        </div>

        <ol style="margin:0; padding-left:22px;">
            <li style="margin-bottom:4px;">Nauunawaan ko na ang aking BPN ay naglalaman ng impormasyon tungkol sa aking health insurance benefits.</li>
            <li style="margin-bottom:4px;">Kinikilala ko na ang pagbibigay ng maling impormasyon (cellphone number o email) ay maaaring maging sanhi ng hindi ko pagtanggap ng abiso.</li>
            <li style="margin-bottom:4px;">Sumasang-ayon ako na ang PhilHealth ay walang pananagutan kung ang impormasyon ay mabasa ng ibang tao dahil sa maling numerong naibigay o kung ang aking mobile device/email account ay hindi ligtas.</li>
            <li>Ang pahintulot na ito ay mananatiling may bisa hanggang hindi ko binabawi sa pamamagitan ng isang nakasulat na abiso sa PhilHealth NCR.</li>
        </ol>

        <div style="font-style:italic; font-size:13px; margin-top:6px;">
            (I understand that my BPN contains sensitive information. I acknowledge that providing
            incorrect contact details may result in non-receipt of the notice. I agree that PhilHealth
            shall not be held liable for unauthorized access to the information if the provided contact
            details are incorrect or if my device/account is compromised. This consent remains valid
            until revoked in writing.)
        </div>
    </div>

    <!-- Signature -->
    <div style="margin-top:16px;">
        <div style="margin-bottom:12px;">
            <div style="width:240px; border-bottom:1px solid #000; margin-left:auto; margin-bottom:4px;"></div>
            <div style="text-align:right; font-size:13px;">
                LAGDA NG MIYEMBRO / KINATAWAN
            </div>
        </div>

        <div style="margin-bottom:8px;">
            PANGALAN (PRINTED NAME):
            <span style="display:inline-block; min-width:320px; border-bottom:1px solid #000;"></span>
        </div>

        <div>
            PhilHealth ID Number (PIN):
            <span style="display:inline-block; min-width:150px; border-bottom:1px solid #000;"></span>

            <span style="margin-left:16px;">Petsa (Date):</span>
            <span style="display:inline-block; min-width:120px; border-bottom:1px solid #000;"></span>
        </div>
    </div>

</div>