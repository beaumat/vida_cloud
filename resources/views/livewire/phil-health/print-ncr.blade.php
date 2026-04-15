<div class="print-container bg-white mx-auto text-[12px] leading-tight text-black"
     style="width: 210mm; min-height: 297mm; padding: 18mm 16mm 14mm 16mm; font-family: Arial, sans-serif;">

    <div class="text-right font-bold text-sm mb-2">ANNEX A</div>

    <div class="text-center mb-4">
        <h1 class="font-bold text-base mb-2">PHILHEALTH NCR CONSENT FORM</h1>
    </div>

    <div class="mb-4 text-justify">
        <p class="font-bold mb-2">
            PAHINTULOT PARA SA PAGPAPADALA NG BENEFIT PAYMENT NOTICE (BPN) SA<br>
            PAMAMAGITAN NG SMS AT/O EMAIL
        </p>

        <p class="mb-2">
            Sa pamamagitan nito, malaya at boluntaryo kong ibinibigay ang aking pahintulot sa PhilHealth
            Regional Office NCR na padalhan ako ng impormasyon tungkol sa aking Benefit Payment
            Notice (BPN) o ang detalye ng bayad sa aking confinement/availment gamit ang sumusunod na
            pamamaraan:
        </p>

        <p class="italic text-[11px]">
            (I hereby freely and voluntarily authorize PhilHealth Regional Office NCR to send information
            regarding my Benefit Payment Notice (BPN) or the details of my benefit payment for my
            confinement/availment through the following methods:)
        </p>
    </div>

    <div class="mb-5">
        <p class="font-bold mb-2">
            I. PAGPILI NG PAMAMARAAN (PLEASE MARK YOUR CHOICE):
        </p>

        <div class="ml-4 space-y-2">
            <div>
                [ ] <span class="font-bold">SMS / TEXT MESSAGE</span>
                &nbsp; Cellphone Number:
                <span class="inline-block border-b border-black align-middle" style="width: 260px; line-height: 1;">{{ $MOBILE_NO ?? '' }}</span>
            </div>

            <div>
                [ ] <span class="font-bold">EMAIL</span>
                &nbsp; Email Address:
                <span class="inline-block border-b border-black align-middle" style="width: 285px; line-height: 1;">{{ $EMAIL ?? '' }}</span>
            </div>

            <div>
                [ ] <span class="font-bold">Others:</span>
                <span class="inline-block border-b border-black align-middle" style="width: 360px; line-height: 1;"></span>
            </div>
        </div>
    </div>

    <div class="mb-5 text-justify">
        <p class="font-bold mb-2">
            II. PAGSANG-AYON AT PAGKILALA (AGREEMENT AND ACKNOWLEDGMENT):
        </p>

        <ol class="list-decimal ml-6 space-y-1">
            <li>Nauunawaan ko na ang aking BPN ay naglalaman ng impormasyon tungkol sa aking health insurance benefits.</li>
            <li>Kinikilala ko na ang pagbibigay ng maling impormasyon (cellphone number o email) ay maaaring maging sanhi ng hindi ko pagtanggap ng abiso.</li>
            <li>Sumasang-ayon ako na ang PhilHealth ay walang pananagutan kung ang impormasyon ay mabasa ng ibang tao dahil sa maling numerong naibigay o kung ang aking mobile device/email account ay hindi ligtas.</li>
            <li>Ang pahintulot na ito ay mananatiling may bisa hanggang hindi ko binabawi sa pamamagitan ng isang nakasulat na abiso sa PhilHealth NCR.</li>
        </ol>

        <p class="italic text-[11px] mt-3">
            (I understand that my BPN contains sensitive information. I acknowledge that providing
            incorrect contact details may result in non-receipt of the notice. I agree that PhilHealth
            shall not be held liable for unauthorized access to the information if the provided contact
            details are incorrect or if my device/account is compromised. This consent remains valid
            until revoked in writing.)
        </p>
    </div>

    <div class="mt-8">
        <div class="mb-3">
            LAGDA NG MIYEMBRO / KINATAWAN (Signature of Member / Authorized Representative)
        </div>

        <div class="mb-3">
            PANGALAN (PRINTED NAME):
            <span class="inline-block border-b border-black align-middle" style="width: 390px; line-height: 1;">
                {{ $MEMBER_LAST_NAME ?? '' }}, {{ $MEMBER_FIRST_NAME ?? '' }} {{ $MEMBER_MIDDLE_NAME ?? '' }}
            </span>
        </div>

        <div>
            PhilHealth ID Number (PIN):
            <span class="inline-block border-b border-black align-middle" style="width: 180px; line-height: 1;">
                {{ $PIN ?? '' }}
            </span>

            <span class="ml-4">Petsa (Date):</span>
            <span class="inline-block border-b border-black align-middle" style="width: 150px; line-height: 1;">
                {{ $DATE_DISCHARGED ?? '' }}
            </span>
        </div>
    </div>
</div>