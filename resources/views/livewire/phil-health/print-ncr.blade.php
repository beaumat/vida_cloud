<div class="print-container bg-white mx-auto text-black"
     style="width: 210mm; min-height: 297mm; padding: 18mm 16mm 14mm 16mm; font-family: Arial, Helvetica, sans-serif; font-size: 11px; line-height: 1.3;">

        <br>
            <br>
    <div class="flex justify-between items-start mb-4">
        <div class="w-1/3">
            <img class="print-logo" style="width:200px;" src="{{ asset('dist/logo/philhealth_logo.png') }}" alt="PhilHealth Logo" />
        </div>
        {{-- <div class="w-1/3 text-right">
            <h2 class="font-bold text-base">ANNEX A</h2>
        </div> --}}
    </div>

      <div style="text-align: right; font-weight: bold; font-size: 11px; margin-bottom: 4px;">
        ANNEX A
    </div>

    <!-- TITLE -->
    <div style="text-align: center; margin-bottom: 10px;">
        <div style="font-weight: bold; font-size: 14px;">
            PHILHEALTH NCR CONSENT FORM
        </div>
    </div>

    <!-- BODY -->
    <div style="margin-bottom: 10px; text-align: justify;">
        <div style="font-weight: bold; margin-bottom: 6px;">
            PAHINTULOT PARA SA PAGPAPADALA NG BENEFIT PAYMENT NOTICE (BPN) SA<br>
            PAMAMAGITAN NG SMS AT/O EMAIL
        </div>

        <div style="margin-bottom: 6px;">
            Sa pamamagitan nito, malaya at boluntaryo kong ibinibigay ang aking pahintulot sa PhilHealth
            Regional Office NCR na padalhan ako ng impormasyon tungkol sa aking Benefit Payment
            Notice (BPN) o ang detalye ng bayad sa aking confinement/availment gamit ang sumusunod na
            pamamaraan:
        </div>

        <div style="font-style: italic; font-size: 10px;">
            (I hereby freely and voluntarily authorize PhilHealth Regional Office NCR to send information
            regarding my Benefit Payment Notice (BPN) or the details of my benefit payment for my
            confinement/availment through the following methods:)
        </div>
    </div>

    <!-- SECTION I -->
    <div style="margin-bottom: 12px;">
        <div style="font-weight: bold; margin-bottom: 6px;">
            I. PAGPILI NG PAMAMARAAN (PLEASE MARK YOUR CHOICE):
        </div>

        <div style="margin-left: 16px; line-height: 1.4;">
            <div style="margin-bottom: 4px;">
                [ ] <b>SMS / TEXT MESSAGE</b> Cellphone Number:
                <span style="display:inline-block; border-bottom:1px solid black; width:260px;">
                    {{ $MOBILE_NO ?? '' }}
                </span>
            </div>

            <div style="margin-bottom: 4px;">
                [ ] <b>EMAIL</b> Email Address:
                <span style="display:inline-block; border-bottom:1px solid black; width:285px;">
                    {{ $EMAIL ?? '' }}
                </span>
            </div>

            <div>
                [ ] <b>Others:</b>
                <span style="display:inline-block; border-bottom:1px solid black; width:360px;"></span>
            </div>
        </div>
    </div>

    <!-- SECTION II -->
    <div style="margin-bottom: 12px; text-align: justify;">
        <div style="font-weight: bold; margin-bottom: 6px;">
            II. PAGSANG-AYON AT PAGKILALA (AGREEMENT AND ACKNOWLEDGMENT):
        </div>

        <ol style="margin-left: 18px; padding-left: 6px;">
            <li style="margin-bottom: 3px;">Nauunawaan ko na ang aking BPN ay naglalaman ng impormasyon tungkol sa aking health insurance benefits.</li>
            <li style="margin-bottom: 3px;">Kinikilala ko na ang pagbibigay ng maling impormasyon (cellphone number o email) ay maaaring maging sanhi ng hindi ko pagtanggap ng abiso.</li>
            <li style="margin-bottom: 3px;">Sumasang-ayon ako na ang PhilHealth ay walang pananagutan kung ang impormasyon ay mabasa ng ibang tao dahil sa maling numerong naibigay o kung ang aking mobile device/email account ay hindi ligtas.</li>
            <li>Ang pahintulot na ito ay mananatiling may bisa hanggang hindi ko binabawi sa pamamagitan ng isang nakasulat na abiso sa PhilHealth NCR.</li>
        </ol>

        <div style="font-style: italic; font-size: 10px; margin-top: 6px;">
            (I understand that my BPN contains sensitive information. I acknowledge that providing
            incorrect contact details may result in non-receipt of the notice. I agree that PhilHealth
            shall not be held liable for unauthorized access to the information if the provided contact
            details are incorrect or if my device/account is compromised. This consent remains valid
            until revoked in writing.)
        </div>
    </div>

    <!-- SIGNATURE -->
    <div style="margin-top: 20px;">
        <div style="margin-bottom: 8px;">
            LAGDA NG MIYEMBRO / KINATAWAN (Signature of Member / Authorized Representative)
        </div>

        <div style="margin-bottom: 8px;">
            PANGALAN (PRINTED NAME):
            <span style="display:inline-block; border-bottom:1px solid black; width:380px;">
                {{ $MEMBER_LAST_NAME ?? '' }}, {{ $MEMBER_FIRST_NAME ?? '' }} {{ $MEMBER_MIDDLE_NAME ?? '' }}
            </span>
        </div>

        <div>
            PhilHealth ID Number (PIN):
            <span style="display:inline-block; border-bottom:1px solid black; width:180px;">
                {{ $PIN ?? '' }}
            </span>

            <span style="margin-left: 20px;">Petsa (Date):</span>
            <span style="display:inline-block; border-bottom:1px solid black; width:150px;">
                {{ $DATE_DISCHARGED ?? '' }}
            </span>
        </div>
    </div>

</div>


                     
   


<div class="col-12">
                    <div class="row">
                        <div class="col-10">
                           
                            <div class="row mt-4">
                                <div class="col-5">   </div>
                                <div class="col-5 bottom-line" ></div>
                                <div class="col-12">LAGDA NG MIYEMBRO / KINATAWAN:(Signature of Member / Authorized Representative)</div>
                                <div class="col-10">
                                    PANGALAN (PRINTED NAME) : <u>{{ $MEMBER_LAST_NAME }}, {{ $MEMBER_FIRST_NAME }} {{ $MEMBER_MIDDLE_NAME }}</u></div>
                                 <div class="col-6">
                                    PhilHealth ID Number (PIN): <u>{{ $PIN }}</u>  </div> Petsa (Date): <u>{{ $DATE_DISCHARGED }}</u>
                                </div>

                                 

                            
                            
                                
                            </div>
                        </div>
                       
                    </div>
                </div>
                                
                                

 
</div>