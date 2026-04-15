<div class="print-container bg-white mx-auto p-6 text-[11px] leading-snug text-black"
     style="width: 210mm; height: 297mm; font-family: sans-serif; overflow: hidden;">

    <div class="flex justify-between items-start mb-3">
        <div class="w-1/3">
            <img class="print-logo" style="width:170px;" src="{{ asset('dist/logo/philhealth_logo.png') }}" alt="PhilHealth Logo" />
        </div>
        {{-- <div class="w-1/3 text-right">
            <h2 class="font-bold text-sm">ANNEX A</h2>
        </div> --}}
    </div>

    <div class="text-center mb-4">
        <h1 class="font-bold text-base mb-2">PHILHEALTH NCR CONSENT FORM</h1>
        <div class="border-y border-black py-2"></div>
    </div>

    <div class="mb-4 text-justify">
        <p class="mb-1">
            <b>PAHINTULOT PARA SA PAGPAPADALA NG BENEFIT PAYMENT NOTICE (BPN) SA<br>
            PAMAMAGITAN NG SMS AT/O EMAIL</b>
        </p>
        <p class="mb-1">
            Sa pamamagitan nito, malaya at boluntaryo kong ibinibigay ang aking pahintulot sa <b>PhilHealth
            Regional Office NCR</b> na padalhan ako ng impormasyon tungkol sa aking <b>Benefit Payment
            Notice (BPN)</b> o ang detalye ng bayad sa aking confinement/availment gamit ang sumusunod na
            pamamaraan:
        </p>
        <p class="italic text-gray-700 text-[10px] leading-snug">
            (I hereby freely and voluntarily authorize <b>PhilHealth Regional Office NCR</b> to send information
            regarding my Benefit Payment Notice (BPN) or the details of my benefit payment for my
            confinement/availment through the following methods:)
        </p>
    </div>

    <div class="mb-5">
        <h6 class="font-bold italic border-b border-black mb-3">
            I. PAGPILI NG PAMAMARAAN (PLEASE MARK YOUR CHOICE):
        </h6>

        <div class="space-y-2 ml-4">
            <div class="flex items-center">
                <input type="checkbox" id="sms" name="sms" value="SMS">
                <span class="ml-2"><b>SMS / TEXT MESSAGE</b> Cellphone Number: <u>{{ $MOBILE_NO }}</u></span>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="email" name="email" value="EMAIL">
                <span class="ml-2"><b>EMAIL</b> Email Address: <u>{{ $EMAIL }}</u></span>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="others" name="others" value="OTHERS">
                <span class="ml-2"><b>Others:</b> _______________________________</span>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <h6 class="font-bold italic border-b border-black mb-4">
            II. PAGSANG-AYON AT PAGKILALA (AGREEMENT AND ACKNOWLEDGMENT):
        </h6>

        <ol class="list-decimal ml-6 space-y-1 text-justify">
            <li>Nauunawaan ko na ang aking BPN ay naglalaman ng impormasyon tungkol sa aking health insurance benefits.</li>
            <li>Kinikilala ko na ang pagbibigay ng maling impormasyon (cellphone number o email) ay maaaring maging sanhi ng hindi ko pagtanggap ng abiso.</li>
            <li>Sumasang-ayon ako na ang PhilHealth ay walang pananagutan kung ang impormasyon ay mabasa ng ibang tao dahil sa maling numerong naibigay o kung ang aking mobile device/email account ay hindi ligtas.</li>
            <li>Ang pahintulot na ito ay mananatiling may bisa hanggang hindi ko binabawi sa pamamagitan ng isang nakasulat na abiso sa PhilHealth NCR.</li>
        </ol>

        <div class="mt-3 italic text-gray-700 text-[10px] leading-snug px-2 text-justify">
            (I understand that my BPN contains sensitive information. I acknowledge that providing
            incorrect contact details may result in non-receipt of the notice. I agree that PhilHealth
            shall not be held liable for unauthorized access to the information if the provided contact
            details are incorrect or if my device/account is compromised. This consent remains valid
            until revoked in writing.)
        </div>
    </div>

    <div class="col-12 mt-4">
        <div class="row">
            <div class="col-10">
                <div class="row mt-2">
                    <div class="col-5"></div>
                    <div class="col-5 bottom-line"></div>
                    <div class="col-12 text-[10px]">
                        LAGDA NG MIYEMBRO / KINATAWAN: (Signature of Member / Authorized Representative)
                    </div>

                    <div class="col-10 mt-1">
                        PANGALAN (PRINTED NAME): 
                        <u>{{ $MEMBER_LAST_NAME }}, {{ $MEMBER_FIRST_NAME }} {{ $MEMBER_MIDDLE_NAME }}</u>
                    </div>

                    <div class="col-6 mt-1">
                        PhilHealth ID Number (PIN): <u>{{ $PIN }}</u>
                    </div>

                    <div class="col-6 mt-1">
                        Petsa (Date): <u>{{ $DATE_DISCHARGED }}</u>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>