<div class="print-container bg-white mx-auto p-10 text-sm leading-tight text-black" style="width: 210mm; min-height: 297mm; font-family: sans-serif;">
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

    <div class="text-center mb-6">
        <h1 class="font-bold text-lg mb-4">PHILHEALTH NCR CONSENT FORM</h1>
        <div class="border-y border-black py-3">
            
        </div>
    </div>

    <div class="mb-6 text-justify">
        <p class="mb-2">
             <b>PAHINTULOT PARA SA PAGPAPADALA NG BENEFIT PAYMENT NOTICE (BPN) SA<br>
                PAMAMAGITAN NG SMS AT/O EMAIL</b>
            
        </p>
        <p class="mb-2">
            Sa pamamagitan nito, malaya at boluntaryo kong ibinibigay ang aking pahintulot sa<b> PhilHealth
            Regional Office NCR</b> na padalhan ako ng impormasyon tungkol sa aking <b>Benefit Payment
            Notice (BPN)</b> o ang detalye ng bayad sa aking confinement/availment gamit ang sumusunod na
            pamamaraan:
        </p>
        <p class="italic text-gray-700">
            (I hereby freely and voluntarily authorize  <b>PhilHealth Regional Office NCR</b> to send information
            regarding my Benefit Payment Notice (BPN) or the details of my benefit payment for my
            confinement/availment through the following methods:)
        </p>
    </div>

    <div class="mb-8">
        <h6 class="font-bold italic border-b border-black mb-4">  I. PAGPILI NG PAMAMARAAN (PLEASE MARK YOUR CHOICE):</h6>
        <div class="space-y-4 ml-6">

            
           
                        <div class="flex items-center">
                             <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                                <span class="ml-2"><b>SMS / TEXT MESSAGE</b> Cellphone Number:  <u>{{ $MOBILE_NO }}</u> </span>
                                
                            </div>

                              <div class="flex items-center">
                             <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                                <span class="ml-2"><b>EMAIL</b> Email Address: <u>{{ $EMAIL }}</u></span>
                                
                            </div>

                              <div class="flex items-center">
                             <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                                <span class="ml-2"><b>Others:</b>_______________________________</span>
                                
                            </div>

  



           
            {{-- <div class="flex items-center">
             
                <span class="ml-2">Email Address:</span>&nbsp; {{ $EMAIL }}
            </div>
            <div class="flex items-center">
                <span>Others:</span>
            </div> --}}
        </div>
    </div>


     <br>
    <div class="mb-10">
        
        <h6 class="font-bold italic border-b border-black mb-10">  II. PAGSANG-AYON AT PAGKILALA (AGREEMENT AND ACKNOWLEDGMENT):</h6>
        <ol class="list-decimal ml-8 space-y-2 text-justify">
            
            <li>Nauunawaan ko na ang aking BPN ay naglalaman ng impormasyon tungkol sa aking health insurance benefits.</li>
            <li>Kinikilala ko na ang pagbibigay ng maling impormasyon (cellphone number o email) ay maaaring maging sanhi ng hindi ko pagtanggap ng abiso.</li>
            <li>Sumasang-ayon ako na ang PhilHealth ay walang pananagutan kung ang impormasyon ay mabasa ng ibang tao dahil sa maling numerong naibigay o kung ang aking mobile device/email account ay hindi ligtas.</li>
            <li>Ang pahintulot na ito ay mananatiling may bisa hanggang hindi ko binabawi sa pamamagitan ng isang nakasulat na abiso sa PhilHealth NCR.</li>
        </ol>
        <div class="mt-4 italic text-gray-700 text-xs leading-relaxed px-4">
            (I understand that my BPN contains sensitive information. I acknowledge that providing
            incorrect contact details may result in non-receipt of the notice. I agree that PhilHealth
            shall not be held liable for unauthorized access to the information if the provided contact
            details are incorrect or if my device/account is compromised. This consent remains valid
            until revoked in writing.)
        </div>
    </div>
<br>


                     
   


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
                                    PhilHealth ID Number (PIN): <u>{{ $PIN }}</u>  </div> Petsa (Date): <u>{{ now()->format('m/d/Y') }}</u>
                                </div>

                                 

                            
                            
                                
                            </div>
                        </div>
                       
                    </div>
                </div>
                                
                                

 
</div>