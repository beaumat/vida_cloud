<div class="print-container bg-white mx-auto text-black"
     style="width: 210mm; min-height: 297mm; padding: 14mm 16mm 12mm 16mm; font-family: Arial, Helvetica, sans-serif; font-size: 13px; line-height: 1.4;">

    <!-- Header with logo and Annex -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
        <div>
            <img src="{{ asset('dist/logo/philhealth_logo.png') }}"
                 alt="PhilHealth Logo"
                 style="width: 150px; height: auto;">
        </div>
        <div style="font-weight: bold; font-size: 13px; padding-top: 4px;">
            ANNEX A
        </div>
    </div>

    <!-- Title -->
    <div style="text-align: center; margin-bottom: 12px;">
        <div style="font-weight: bold; font-size: 18px; line-height: 1.2;">
            PHILHEALTH NCR CONSENT FORM
        </div>
    </div>

    <!-- Intro -->
    <div style="margin-bottom: 12px; text-align: justify;">
        <div style="font-weight: bold; font-size: 14px; margin-bottom: 8px; line-height: 1.3;">
            PAHINTULOT PARA SA PAGPAPADALA NG BENEFIT PAYMENT NOTICE (BPN) SA<br>
            PAMAMAGITAN NG SMS AT/O EMAIL
        </div>

        <div style="margin-bottom: 6px;">
            Sa pamamagitan nito, malaya at boluntaryo kong ibinibigay ang aking pahintulot sa PhilHealth
            Regional Office NCR na padalhan ako ng impormasyon tungkol sa aking Benefit Payment
            Notice (BPN) o ang detalye ng bayad sa aking confinement/availment gamit ang sumusunod na
            pamamaraan:
        </div>

        <div style="font-style: italic; font-size: 12px; color: #333; line-height: 1.35;">
            (I hereby freely and voluntarily authorize PhilHealth Regional Office NCR to send information
            regarding my Benefit Payment Notice (BPN) or the details of my benefit payment for my
            confinement/availment through the following methods:)
        </div>
    </div>

    <!-- Section I -->
    <div style="margin-bottom: 14px;">
        <div style="font-weight: bold; font-size: 13px; margin-bottom: 8px;">
            I. PAGPILI NG PAMAMARAAN (PLEASE MARK YOUR CHOICE):
        </div>

        <div style="padding-left: 20px;">
            <div style="margin-bottom: 6px;">
                [ ] <b>SMS / TEXT MESSAGE</b>
                <span style="margin-left: 6px;">Cellphone Number:</span>
                <span style="display: inline-block; min-width: 230px; border-bottom: 1px solid #000; line-height: 1.1;">
                    {{ $MOBILE_NO ?? '' }}
                </span>
            </div>

            <div style="margin-bottom: 6px;">
                [ ] <b>EMAIL</b>
                <span style="margin-left: 6px;">Email Address:</span>
                <span style="display: inline-block; min-width: 250px; border-bottom: 1px solid #000; line-height: 1.1;">
                    {{ $EMAIL ?? '' }}
                </span>
            </div>

            <div>
                [ ] <b>Others:</b>
                <span style="display: inline-block; min-width: 300px; border-bottom: 1px solid #000; line-height: 1.1;">
                    &nbsp;
                </span>
            </div>
        </div>
    </div>

    <!-- Section II -->
    <div style="margin-bottom: 16px; text-align: justify;">
        <div style="font-weight: bold; font-size: 13px; margin-bottom: 8px;">
            II. PAGSANG-AYON AT PAGKILALA (AGREEMENT AND ACKNOWLEDGMENT):
        </div>

        <ol style="margin: 0; padding-left: 24px;">
            <li style="margin-bottom: 5px;">
                Nauunawaan ko na ang aking BPN ay naglalaman ng impormasyon tungkol sa aking health insurance benefits.
            </li>
            <li style="margin-bottom: 5px;">
                Kinikilala ko na ang pagbibigay ng maling impormasyon (cellphone number o email) ay maaaring maging sanhi ng hindi ko pagtanggap ng abiso.
            </li>
            <li style="margin-bottom: 5px;">
                Sumasang-ayon ako na ang PhilHealth ay walang pananagutan kung ang impormasyon ay mabasa ng ibang tao dahil sa maling numerong naibigay o kung ang aking mobile device/email account ay hindi ligtas.
            </li>
            <li>
                Ang pahintulot na ito ay mananatiling may bisa hanggang hindi ko binabawi sa pamamagitan ng isang nakasulat na abiso sa PhilHealth NCR.
            </li>
        </ol>

        <div style="font-style: italic; font-size: 12px; margin-top: 8px; color: #333; line-height: 1.35;">
            (I understand that my BPN contains sensitive information. I acknowledge that providing
            incorrect contact details may result in non-receipt of the notice. I agree that PhilHealth
            shall not be held liable for unauthorized access to the information if the provided contact
            details are incorrect or if my device/account is compromised. This consent remains valid
            until revoked in writing.)
        </div>
    </div>

    <!-- Signature -->
    <div style="margin-top: 22px;">
        <div style="margin-bottom: 16px;">
            <div style="width: 280px; border-bottom: 1px solid #000; margin-left: auto; margin-bottom: 4px;"></div>
            <div style="text-align: right; font-size: 12px;">
                LAGDA NG MIYEMBRO / KINATAWAN (Signature of Member / Authorized Representative)
            </div>
        </div>

        <div style="margin-bottom: 10px;">
            PANGALAN (PRINTED NAME):
            <span style="display: inline-block; min-width: 360px; border-bottom: 1px solid #000; line-height: 1.1;">
                {{ $MEMBER_LAST_NAME ?? '' }}, {{ $MEMBER_FIRST_NAME ?? '' }} {{ $MEMBER_MIDDLE_NAME ?? '' }}
            </span>
        </div>

        <div>
            PhilHealth ID Number (PIN):
            <span style="display: inline-block; min-width: 170px; border-bottom: 1px solid #000; line-height: 1.1;">
                {{ $PIN ?? '' }}
            </span>

            <span style="margin-left: 20px;">Petsa (Date):</span>
            <span style="display: inline-block; min-width: 140px; border-bottom: 1px solid #000; line-height: 1.1;">
                {{ $DATE_DISCHARGED ?? '' }}
            </span>
        </div>
    </div>
</div>