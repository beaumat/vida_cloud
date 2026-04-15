<div class="print-container bg-white mx-auto text-black"
     style="width: 210mm; height: 297mm; padding: 14mm 14mm 12mm 14mm; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 1.2; overflow: hidden;">

    <!-- HEADER -->
    <div class="flex justify-between items-start mb-2">
        <div class="w-1/3">
            <img style="width:165px;" src="{{ asset('dist/logo/philhealth_logo.png') }}" alt="PhilHealth Logo" />
        </div>
    </div>

    <!-- TITLE -->
    <div class="text-center mb-3">
        <h1 style="font-size:15px; font-weight:bold; margin-bottom:5px;">
            PHILHEALTH NCR CONSENT FORM
        </h1>
        <div style="border-top:1px solid black; border-bottom:1px solid black; padding:5px 0;"></div>
    </div>

    <!-- INTRO -->
    <div style="margin-bottom:8px; text-align:justify;">
        <p style="margin-bottom:3px; font-weight:bold;">
            PAHINTULOT PARA SA PAGPAPADALA NG BENEFIT PAYMENT NOTICE (BPN) SA<br>
            PAMAMAGITAN NG SMS AT/O EMAIL
        </p>

        <p style="margin-bottom:3px;">
            Sa pamamagitan nito, malaya at boluntaryo kong ibinibigay ang aking pahintulot sa <b>PhilHealth
            Regional Office NCR</b> na padalhan ako ng impormasyon tungkol sa aking <b>Benefit Payment
            Notice (BPN)</b> o ang detalye ng bayad sa aking confinement/availment gamit ang sumusunod na
            pamamaraan:
        </p>

        <p style="font-style:italic; font-size:11px;">
            (I hereby freely and voluntarily authorize PhilHealth Regional Office NCR to send information
            regarding my Benefit Payment Notice (BPN) or the details of my benefit payment for my
            confinement/availment through the following methods:)
        </p>
    </div>

    <!-- SECTION I -->
    <div style="margin-bottom:10px;">
        <h6 style="font-weight:bold; font-style:italic; font-size:12px; border-bottom:1px solid black; margin-bottom:5px;">
            I. PAGPILI NG PAMAMARAAN (PLEASE MARK YOUR CHOICE):
        </h6>

        <div style="margin-left:14px; line-height:1.25;">
            <div style="margin-bottom:3px;">
                <input type="checkbox"> <b>SMS / TEXT MESSAGE</b> Cellphone Number:
                <span style="border-bottom:1px solid black;"> {{ $MOBILE_NO }} </span>
            </div>

            <div style="margin-bottom:3px;">
                <input type="checkbox"> <b>EMAIL</b> Email Address:
                <span style="border-bottom:1px solid black;"> {{ $EMAIL }} </span>
            </div>

            <div>
                <input type="checkbox"> <b>Others:</b> _______________________________
            </div>
        </div>
    </div>

    <!-- SECTION II -->
    <div style="margin-bottom:10px;">
        <h6 style="font-weight:bold; font-style:italic; font-size:12px; border-bottom:1px solid black; margin-bottom:6px;">
            II. PAGSANG-AYON AT PAGKILALA (AGREEMENT AND ACKNOWLEDGMENT):
        </h6>

        <ol style="margin-left:16px; line-height:1.25;">
            <li style="margin-bottom:2px;">Nauunawaan ko na ang aking BPN ay naglalaman ng impormasyon tungkol sa aking health insurance benefits.</li>
            <li style="margin-bottom:2px;">Kinikilala ko na ang pagbibigay ng maling impormasyon (cellphone number o email) ay maaaring maging sanhi ng hindi ko pagtanggap ng abiso.</li>
            <li style="margin-bottom:2px;">Sumasang-ayon ako na ang PhilHealth ay walang pananagutan kung ang impormasyon ay mabasa ng ibang tao dahil sa maling numerong naibigay o kung ang aking mobile device/email account ay hindi ligtas.</li>
            <li>Ang pahintulot na ito ay mananatiling may bisa hanggang hindi ko binabawi sa pamamagitan ng isang nakasulat na abiso sa PhilHealth NCR.</li>
        </ol>

        <p style="margin-top:5px; font-style:italic; font-size:11px;">
            (I understand that my BPN contains sensitive information. I acknowledge that providing
            incorrect contact details may result in non-receipt of the notice. I agree that PhilHealth
            shall not be held liable for unauthorized access to the information if the provided contact
            details are incorrect or if my device/account is compromised. This consent remains valid
            until revoked in writing.)
        </p>
    </div>

    <!-- SIGNATURE -->
    <div style="margin-top:14px;">
        <div style="margin-bottom:6px;">
            LAGDA NG MIYEMBRO / KINATAWAN (Signature of Member / Authorized Representative)
        </div>

        <div style="margin-bottom:5px;">
            PANGALAN (PRINTED NAME):
            <span style="border-bottom:1px solid black;">
                {{ $MEMBER_LAST_NAME }}, {{ $MEMBER_FIRST_NAME }} {{ $MEMBER_MIDDLE_NAME }}
            </span>
        </div>

        <div>
            PhilHealth ID Number (PIN):
            <span style="border-bottom:1px solid black;"> {{ $PIN }} </span>

            <span style="margin-left:14px;">Petsa (Date):</span>
            <span style="border-bottom:1px solid black;"> {{ $DATE_DISCHARGED }} </span>
        </div>
    </div>

</div>