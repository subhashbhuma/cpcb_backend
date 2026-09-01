<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\HeadOffice;


class HeadOfficesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Define Modal Content Placeholders
        // Note: Replace these empty strings with your actual HTML content
        $chair_modal = '<div>
        <h3>Chairman </h3>
<h2 style="color: #; font-size:21px;">Sh. Vir Vikram Yadav, IAS</h2>
    <h5 style="color:#0b73b9;"><strong>PS to Chairman : </strong> Smt. Tanushree B.</h5>
    <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102202</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> ccb.cpcb@nic.in</span></h5>
        </div>';
        $chair_modal_hi = '<div>
        <h3>अध्यक्ष </h3>
<h2 style="color: #; font-size:21px;">श्री वीर विक्रम यादव, भा. प्र. से. </h2>
    <h5 style="color:#0b73b9;"><strong>अध्ययक्ष  की निजी सचिव : </strong>  श्रीमति तनुश्री ब. </h5>
    <h5 style="color:#0b73b9;"><strong>दूरभाष : 	</strong>  011- 43102202</h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong>  ccb.cpcb@nic.in</h5>
        </div>';

        $ms_modal = '<div>
         <h3>Member Secratary</h3>
<h2 style="color: #; font-size:21px;">Sh. Bharat Kumar Sharma </h2>
    <h5 style="color:#0b73b9;"><strong>PS to Member Secretary : </strong> -</h5>
    <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 22303655, 43102207</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> mscb.cpcb@nic.in</span></h5>
        </div>';
        $ms_modal_hi = '<div>
         <h3>सदस्य सचिव</h3>
<h2 style="color: #; font-size:21px;"> श्री भरत कुमार शर्मा </h2>
    <h5 style="color:#0b73b9;"><strong>सदस्य सचिव के निजी सचिव : </strong>-</h5>
    <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> 011- 22303655, 43102207</h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong>  mscb.cpcb@nic.in</h5>
        </div>';

        $pcp_modal = '<div>
        <h3>PCP Division</h3>
	<div style="overflow-y:Scroll;height:300px">
    <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Sh. G Thirumurthy, Scientist \'F\'</span></h5>
     <h5 style="color:#0b73b9;"><strong>Phone No : </strong> <span style="float: right;width: 70%; text-align:left;"> 011- 22301932 , 43102336</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : </strong> <span style="float: right;width: 70%; text-align:left;">thiru.cpcb@nic.in, pcp.cpcb@gov.in</span></h5>
         <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
       								<h5>This division deals  following Activities : </h5>
                    <ol style="list-style-type:decimal;">
    <li  style="list-style-type:decimal;"> Annual Plan</li>
     <li  style="list-style-type:decimal;">Perspective Planning for strengthning of CPCB</li>
     <li  style="list-style-type:decimal;">Coordination with MoEF&CC on various issues</li>
     <li  style="list-style-type:decimal;">Parliament Questions </li>
      <li  style="list-style-type:decimal;">	Performance audit of State Board</li>
<li style="list-style-type:decimal;">	Assurance, Zero Hours, Rule 377, calling attention and special mention matter </li>
<li style="list-style-type:decimal;">	Providing material for visit/meetings of Parliamentary Standing Committee on Science and technology, Environment Forests and Committee on Subordinate Legislation Rajya Sabha</li>
 <li style="list-style-type:decimal;">	Evaluation of technical reports</li>
	<li style="list-style-type:decimal;">	Review meetings</li>
	 <li style="list-style-type:decimal;">	RTI matters, VIP references/ complaints & PMO matter </li>
<li style="list-style-type:decimal;">	Utilization of consent fund in the matter of Hon’ble NGT order O.A. No. 681/2018</li>
	<li style="list-style-type:decimal;">	Assistance for abatement of pollution scheme </li>
	<li style="list-style-type:decimal;">	Control of Pollution scheme</li>
<li style="list-style-type:decimal;">Third party evaluation of central sector scheme, project funded by MoEF&CC to HUDCO</li>
												          </ol> </span>
                                                        </h5>
      <div class="clear"></div>
	  <h5 style="color:#0b73b9;"><strong> Personnel :      </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
               		    <li style="list-style-type:decimal;">Smt. Anjana Singh, Scientist \'E\'</li>
						 <li style="list-style-type:decimal;">Sh. Mukul Mani Tripathi, Scientist \'B\'</li>
						  <li style="list-style-type:decimal;">Sh. Rahul, UDC</li>
					 </ol> </span>
                                                        </h5>
        </div>';
        $pcp_modal_hi ='<div>
         <h3>पीसीपी प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
 <h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्री जी. थिरुमूर्थी, (वैज्ञानिक ‘एफ’)</span></h5>

     <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 22301932 , 43102336</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> thiru.cpcb@nic.in, pcp.cpcb@gov.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;"> यह प्रभाग निम्नलिखित कार्यकलापों को देखता है : 
                                                           <ol>
                                                          <li  style="list-style-type:decimal;">वार्षिक योजना</li>
                                                       </ol> </span>
        </h5>
        <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
 <li style="list-style-type:decimal;">श्रीमती अंजना सिंह, (वैज्ञानिक ‘ई’)</li>
 <li style="list-style-type:decimal;">श्री पी चैतन्य, (वैज्ञानिक ‘बी’)</li>
 <li style="list-style-type:decimal;">श्री मुकुल मनी त्रिपाठी, (वैज्ञानिक ‘बी’)</li>
 <li style="list-style-type:decimal;">श्री राहुल शर्मा, (यू डी सी)</li>


          </ol> </span>
        </h5>
        </div>';

        $pr_modal ='<div>
        <h3>PR Division</h3>
	<div style="overflow-y:Scroll;height:300px">
    
     <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong><span style="float: right;width: 70%; text-align:left;">Sh. Sharandeep Singh (Scientist \'E\')</span></h5>
     <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-  43102306</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> sharandeep.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
       														 <h5>This division deals  following Activities : </h5>
                                                           <ol style="list-style-type:decimal;">
                                                           <li  style="list-style-type:decimal;">	Participation in Exhibition/ Workshops/Mass awareness activities.</li>
                            <li  style="list-style-type:decimal;">	Printing & Publication of CPCB technical reports 	</li>
						<li  style="list-style-type:decimal;">Advertisements.	</li>
						<li  style="list-style-type:decimal;">	Hospitality arrangements.   </li>
						  <li style="list-style-type:decimal;">ENVIS</li>		
                                                        </ol> </span>
                                                        </h5>
      <div class="clear"></div>
	  <h5 style="color:#0b73b9;"><strong> Personnel :      </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
		       
            <li style="list-style-type:decimal;">Sh. Satish Kumar, Pub. Asst.</li>
            <li style="list-style-type:decimal;">Sh. Rajpal, Asst.</li>
              <li style="list-style-type:decimal;">Sh. Ravindra Kumar, Attendent</li>						
														</ol> </span>
                                                        </h5></div>';
        $pr_modal_hi ='<div>
         <h3>जनसंपर्क प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px"><br>
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong><span style="float: right;width: 70%; text-align:left;">श्री  शरणदीप  सिंह (वैज्ञानिक ‘ई’)</span></h5>
     <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-43102306</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> sharandeep.cpcb@nic.in</span></h5>
	 

        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;"> यह प्रभाग निम्नलिखित कार्यकलापों को देखता है : 
                                                           <ol style="list-style-type:decimal;">
                                                         <li  style="list-style-type:decimal;">	जन जागरूकता</li>
                                                          <li  style="list-style-type:decimal;">समन्वय कार्यकलाप</li>
                                                           <li style="list-style-type:decimal;">प्रकाशन और मीडिया संपर्क</li>
                                                          <li style="list-style-type:decimal;">	आरटीआई/एनजीओ</li>
                                                                       </ol> </span>
                                                        </h5>
        <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                                              <li style="list-style-type:decimal;">श्री सतीश कुमार, प्रकाशन सहायक</li>
											<li style="list-style-type:decimal;">श्री राजपाल सिंह (सहायक)</li>
                                                       <li style="list-style-type:decimal;">श्री कुन्दन सिंह (क्षेत्र परिचर)</li>
                                                         </ol> </span>
                                                        </h5></div>';

        $etu_modal ='<div>
        <h3>ETU Division</h3>
<h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Sh. Anil Ranveer, Scientist \'F\' </span></h5>
     <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102267</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> anilranveer.cpcb@gov.in </span></h5>

	 <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
       														 <h5>This division deals  following Activities : </h5>
                                                           <ol style="list-style-type:decimal;">
                               <li  style="list-style-type:decimal;">Environmental Training </li>
                                                         		
                                                        </ol> </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
       <li style="list-style-type:decimal;">Sh. Budhram Shah, UDC</li>
	 <li style="list-style-type:decimal;">Ms. Anju Bhardwaj, LDC</li>
			 </ol> </span>
                                                        </h5></div>';
        $etu_modal_hi ='<div>
         <h3>पर्यावरण प्रशिक्षण इकाई  प्रभाग</h3>
	
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;"> श्री अनिल रणवीर, वैज्ञानिक \'एफ\'</span></h5>
     <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102332</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> anilranveer.cpcb@gov.in</span></h5>

	        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
                                                           <ol style="list-style-type:decimal;">
                                                          <li  style="list-style-type:decimal;">	पर्यावरण सम्बन्धी प्रशिक्षण </li>
                                                            </ol> </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                                                     <li style="list-style-type:decimal;">	श्री बुद्धराम शाह, उच्च क्षेणी लिपिक</li>
                                                       <li style="list-style-type:decimal;">श्रीमती अंजु भारद्वाज, अवर क्षेणी लिपिक </li>
                                                        </ol> </span>
                                                        </h5></div>';

        $ipc1_modal ='<div>
        <h3>Industrial Pollution Control -I Division</h3>
	<div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Sh. Nazimuddin (Scientist \'F\')</span> </h5>
      <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102310</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> nazim.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
       														 This division deals  following Industry Sectors : <strong>Chemical Industries</strong>
                                                           <ol style="list-style-type:decimal;">
                            <li  style="list-style-type:decimal;">Chlor Alkali </li> 
                                    <li  style="list-style-type:decimal;">Dyes & Dye Intermediate </li> 	
                           <li  style="list-style-type:decimal;">Fertilizer </li> 	
                                     <li  style="list-style-type:decimal;">Oil Refineries </li>
									 <li  style="list-style-type:decimal;">Pesticides </li> 	
									<li  style="list-style-type:decimal;">Petro-Chemicals </li> 	
									<li  style="list-style-type:decimal;">Pharmaceuticals </li> 	
									<li  style="list-style-type:decimal;">Paints & Enamel </li> 
									<li  style="list-style-type:decimal;">Industries of Chemical in nature (Organic and In-organic) </li>			
                                                        </ol> 
                                                        </span>
                                                        </h5>
			
         <div class="clear"></div>
       <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
          <ol>
            <li style="list-style-type:decimal;">Sh. R N Pankaj, Scientist \'E\'</li>
            <li style="list-style-type:decimal;">Sh. Ashbir Singh, Scientist \'D\'</li>
            <li style="list-style-type:decimal;">Sh. M K Gupta, SSA</li>
            
            </ol> </span>
        </h5></div>';
        $ipc1_modal_hi ='<div>
        <h3>औद्योगिक प्रदूषण नियंत्रण -I प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">	श्री नजीमुद्दीन (वैज्ञानिक ‘एफ’)</span> </h5>
      <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 22304812, 43102310</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> nazim.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  
<span style="float: right;width: 70%; text-align:left;"> यह प्रभाग निम्नलिखित उद्योग क्षेत्रों को देखता है : <strong>रासायनिक उद्योग</strong>
                                                           <ol style="list-style-type:decimal;">
                                                          <li  style="list-style-type:decimal;">	क्लोर एल्कली</li> 
                                                           <li  style="list-style-type:decimal;">	डाई और डाई मध्यवर्ती </li> 	
                                                            <li  style="list-style-type:decimal;">	उर्वरक </li> 	
                                                             <li  style="list-style-type:decimal;">तेल रिफाइनरी </li> 	
                                                              <li  style="list-style-type:decimal;">कीटनाशक </li> 	
                                                         <li  style="list-style-type:decimal;">पेट्रो-रसायन </li> 	
                                                         <li  style="list-style-type:decimal;">	फार्मास्यूटिकल्स</li> 	
                                            				 <li  style="list-style-type:decimal;">पेंट और इनेमल</li> 
                                					  <li  style="list-style-type:decimal;">रासायनिक स्वरूप के उद्योग (कार्बनिक और अकार्बनिक) </li>			
                                                        </ol> 
                                                        </span>
                                                        </h5>
			
         <div class="clear"></div>
       <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
          <ol>
          <li style="list-style-type:decimal;">	श्री आर एन पंकज, (वैज्ञानिक ई)</li>
            <li style="list-style-type:decimal;">श्री अशबीर सिंह, (वैज्ञानिक सी)</li>
           </ol> </span>
        </h5></div>';

        $ipc2_modal ='<div>
        <h3>Industrial Pollution Control -II Division</h3>
	<div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Sh. Prasoon Gargava (Scientist \'F\')</span> </h5>
     <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102444</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> prasoon.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">This division deals  following Industry Sectors : <strong>Energy, Metallurgical</strong>
             <ol style="list-style-type:decimal;">
              <li  style="list-style-type:decimal;">Aluminum </li> 
               <li  style="list-style-type:decimal;">Copper </li> 	
               <li  style="list-style-type:decimal;">Zinc </li> 	
                 <li  style="list-style-type:decimal;">Integrated Iron & Steel </li> 	
                  <li  style="list-style-type:decimal;">Dust (PM) Emitting Industries</li> 	
                    <li  style="list-style-type:decimal;">Mining </li> 	
                        <li  style="list-style-type:decimal;">Cement </li> 	
                           <li  style="list-style-type:decimal;">Thermal Power Plants </li> 
                                  <li  style="list-style-type:decimal;">Coal Mining</li>
                                    <li  style="list-style-type:decimal;">Non-Coal Mining/Asbestos</li>			
                                                        </ol> </span>
                                                        </h5>
			 <div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                            <ol>			
<li style="list-style-type:decimal;">Sh. Vinay Kumar Upadhyay, Scientist \'C\'</li>
<li style="list-style-type:decimal;">Sh. Gaurav Gehlot, Scientist \'C\'</li>
<li style="list-style-type:decimal;">Sh. Puneet Tyagi, Scientist \'B\'</li>
<li style="list-style-type:decimal;">Sh. Arvind Kumar, Scientist \'B\'</li>
<li style="list-style-type:decimal;">Sh. Saurabh Gairola, Scientist \'B\'</li>
<li style="list-style-type:decimal;">Sh. Abinash Sahoo, SSA</li>

								 </ol> </span>
                                                        </h5></div>';
        $ipc2_modal_hi ='<div>
        <h3>औद्योगिक प्रदूषण नियंत्रण -II प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
     <h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्री प्रसून गार्गव (वैज्ञानिक ‘एफ’)</span> </h5>
     <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 22381707, 43102444</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> prasoon.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
       														 यह प्रभाग निम्नलिखित उद्योग क्षेत्रों को देखता है : <strong>ऊर्जा, धातुकर्म</strong>
                                                           <ol style="list-style-type:decimal;">
                                                          <li  style="list-style-type:decimal;">अल्युमीनियम </li> 
                                                           <li  style="list-style-type:decimal;">तांबा </li> 	
                                                            <li  style="list-style-type:decimal;">	जस्ता </li> 	
                                                             <li  style="list-style-type:decimal;">	एकीकृत लौह और इस्पात </li> 	
                                                              <li  style="list-style-type:decimal;">	धूल (पीएम) उत्सर्जित करने वाले उद्योग</li> 	                                                         <li  style="list-style-type:decimal;">	खनन </li> 	
                                                         <li  style="list-style-type:decimal;">	सीमेंट </li> 	
                                             <li  style="list-style-type:decimal;">	ताप विद्युत संयंत्र</li> 
                                  <li  style="list-style-type:decimal;">	कोयला खनन</li>
                                    <li  style="list-style-type:decimal;">	गैर-कोयला खनन/ऐस्बेस्टस</li>			
                                                        </ol> </span>
                                                        </h5>
			 <div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                                     <!--li style="list-style-type:decimal;">	श्री अंकुर तिवारी, (वैज्ञानिक ‘डी’)</li-->
	 <li style="list-style-type:decimal;">श्री विनय कुमार उपाध्याय, (वैज्ञानिक ‘सी’)</li>
                <li style="list-style-type:decimal;">श्री गौरव गेहलोत,  (वैज्ञानिक ‘सी’)</li>
<li style="list-style-type:decimal;">श्री पुनीत त्यागी, वैज्ञानिक बी</li>
<li style="list-style-type:decimal;">श्री अरविंद कुमार, वैज्ञानिक बी</li>
<li style="list-style-type:decimal;">श्री सौरभ गैरोला, वैज्ञानिक बी</li>
<li style="list-style-type:decimal;">श्री अबिनाश साहू, एस एस ए</li>
                    
														 </ol> </span>
                                                        </h5></div>';

        $ipc3_modal ='<div><h3>Industrial Pollution Control -III Division</h3>
	<div style="overflow-y:Scroll;height:300px">
     <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Sh. Kamlesh Singh (Scientist \'E\')</span> </h5>
      <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 22306179, 43102425</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> kamleshsingh.cpcb@gov.in,</span></h5>
      <h5 style="color:#0b73b9;"><strong>Profile & Activity :</strong> <span style="float: right;width: 70%; text-align:left;"> This division deals  following Industry Sectors <strong>: Agro Based</strong>
                                                           <ol style="list-style-type:decimal;">
                                   <li  style="list-style-type:decimal;">Sugar</li>
                                      <li  style="list-style-type:decimal;">Distilleries</li>
                                    <li  style="list-style-type:decimal;">Pulp &amp; Paper</li>
                                     <li  style="list-style-type:decimal;">Textiles</li>
                                     <li  style="list-style-type:decimal;">Food &amp; Beverages</li>
									<li  style="list-style-type:decimal;">Soft Drinks     </li>
          </ol>
        </span>
                                                        </h5>
			 <div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
           
             <li style="list-style-type:decimal;"> Sh. Rishabh Srivastava, Scientist \'C\'</li>
            
        </ol> </span>
                                                        </h5></div>';
        $ipc3_modal_hi ='<div>
         <h3>औद्योगिक प्रदूषण नियंत्रण -III प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्री  कमलेश सिंह (वैज्ञानिक ‘ई’)</span> </h5>
      <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 22306179, 43102443</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> kamleshsingh.cpcb@gov.in</span></h5>
      <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप :</strong> <span style="float: right;width: 70%; text-align:left;">
       														 यह प्रभाग निम्नलिखित उद्योग क्षेत्रों को देखता है: <strong>: कृषि आधारित</strong>
                                                           <ol style="list-style-type:decimal;">
                                                              <li  style="list-style-type:decimal;">	चीनी</li>
                                                              <li  style="list-style-type:decimal;">	डिस्टिलरी</li>
                                                              <li  style="list-style-type:decimal;">	लुगदी और कागज</li>
                                                              <li  style="list-style-type:decimal;">	वस्त्र</li>
                                                              <li  style="list-style-type:decimal;">	खाद्य और पेय</li>
                                                              <li  style="list-style-type:decimal;">	सॉफ्ट ड्रिंक   </li>
          </ol>
        </span>
                                                        </h5>
			 <div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                                                <li style="list-style-type:decimal;">श्री ऋषभ श्रीवास्तव, (वैज्ञानिक ‘बी’)</li>
                                                        </ol> 
          </span>
                                                        </h5></div>';

        $ipc4_modal ='<div>
         <h3>Industrial Pollution Control -IV Division</h3>
	<div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Smt. Anamika Sagar (Scientist \'E\')</span> </h5>
     <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102440</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> anamika.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  This division deals  following Industry Sectors : 	<strong>Animal Products</strong>
            <ol style="list-style-type:decimal;">
                 <li  style="list-style-type:decimal;">Dairies</li>
            <li  style="list-style-type:decimal;">Tanneries</li>
        <li  style="list-style-type:decimal;">Slaughter House</li>
          </ol>
        </span></h5>
			 <div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
              <li  style="list-style-type:decimal;">Sh. Rambabu, Scientist \'E\'</li>
                <li style="list-style-type:decimal;">Sh. Y.N. Mishra, Scientist \'D\'</li>
                <li style="list-style-type:decimal;">Sh. Vinay Prabhakar, Scientist \'C\'</li> </ol> </span>
        </h5></div>';
        $ipc4_modal_hi ='<div>
         <h3>औद्योगिक प्रदूषण नियंत्रण -IV प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
 <h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्रीमती अनामिका सागर, (वैज्ञानिक ‘ई’)</span> </h5>
     <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 22381707,43102440</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;">anamika.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		 यह प्रभाग निम्नलिखित उद्योग क्षेत्रों को देखता है : 	<strong>पशु उत्पाद</strong>
                                                           <ol style="list-style-type:decimal;">
                                                              <li  style="list-style-type:decimal;">	डेरी	</li>
                                                               <li  style="list-style-type:decimal;">	चमड़े के कारखाने</li>
                                                               <li  style="list-style-type:decimal;">	बूचड़खाने    </li>
          </ol>
        </span>
        </h5>
			 <div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                                                           <li  style="list-style-type:decimal;">श्री जी. रामबाबू, (वैज्ञानिक ‘ई’)</li>
                                                           <li style="list-style-type:decimal;"> श्री वाई.एन. मिश्रा, (वैज्ञानिक ‘डी’)</li>
														<li style="list-style-type:decimal;">	श्री विनय प्रभाकर, (वैज्ञानिक ‘सी’)</li> 
                                                        </ol> </span>
        </h5></div>';

        $ipc5_modal ='<div>
          <h3>Industrial Pollution Control-V Division</h3>
	<div style="overflow-y:Scroll;height:300px">
    <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Smt. Anamika Sagar (Scientist \'E\')</span> </h5>
     <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102440</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> anamika.cpcb@nic.in</span></h5>
          <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This division deals  following Industry Sectors : <strong>SSI</strong></h5>
                    <ol style="list-style-type:decimal;">
       <li  style="list-style-type:decimal;">Foundry and Furnaces</li>
               <li  style="list-style-type:decimal;">Stone Crushers</li>
                                                               <li  style="list-style-type:decimal;">Hot Mix Plants</li>
                                                               <li  style="list-style-type:decimal;">Brick Kilns</li>
        <li  style="list-style-type:decimal;">Rice Mills &amp; other Small Scale Industries</li>
        <li  style="list-style-type:decimal;">Siting Policies &amp; Technology Development  </li>
          </ol>
        </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
														
                 <li style="list-style-type:decimal;">Dr. Alka Srivastava, Scientist \'B\'</li>
                  <li style="list-style-type:decimal;">Sh. Abdul Mateen, Scientist \'B\'</li>  </ol> </span>
                                                        </h5></div>';
        $ipc5_modal_hi ='<div>
         <h3>औद्योगिक प्रदूषण नियंत्रण -V प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्रीमती अनामिका सागर, (वैज्ञानिक ‘ई’)</span> </h5>
     <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 22381707,43102440</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;">anamika.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह प्रभाग निम्नलिखित उद्योग क्षेत्रों को देखता है: <strong>SSI</strong></h5>
                                                           <ol style="list-style-type:decimal;">
                                                               <li  style="list-style-type:decimal;">	फाउंड्री और भट्टियां</li>
                                                               <li  style="list-style-type:decimal;">	स्टोन क्रशर</li>
                                                               <li  style="list-style-type:decimal;">	हॉट मिक्स प्लांट</li>
                                                               <li  style="list-style-type:decimal;">	ईंट के भट्टे</li>
                                                               <li  style="list-style-type:decimal;">	चावल मिल्स और अन्य लघु उद्योग</li>
                                                              <li  style="list-style-type:decimal;">	स्थान संबंधी नीतियों और प्रौद्योगिकी विकास                                                            </li>
          </ol>
        </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
														   <li style="list-style-type:decimal;">	श्रीमती अल्का श्रीवास्तव, (वैज्ञानिक ‘बी’) </li>
                                                             <li style="list-style-type:decimal;">श्री अब्दुल मतीन, (वैज्ञानिक ‘बी’) </li>
                                                         </ol> </span>
                                                        </h5></div>';

        $ipc6_modal ='<div>
         <h3>Industrial Pollution Control-VI Division</h3>
	<div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">-</span> </h5>
     <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102431</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;">-</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
													    <h5>This division deals  following Industry Sectors:          <strong>Industrial Pollution  Coordination</strong></h5>
        </span></h5>
<h5 style="color:#0b73b9;"><span style="float: right;width: 70%; text-align:left;">
          <ol style="list-style-type:decimal;">
              <li  style="list-style-type:decimal;">17 Categories of Industries</li>
             <li  style="list-style-type:decimal;">Red Categories of Industries</li>
              <li  style="list-style-type:decimal;">Categorization of Industries</li>
              <li  style="list-style-type:decimal;">Review of Standards</li>
              <li  style="list-style-type:decimal;">On-line Installations</li>
              <li  style="list-style-type:decimal;">Grossly Polluting Industries (other than Ganga)</li>
          </ol> 
          </span>
        </h5>
			<div class="clear"></div>
		  <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
            <ol>
            <li style="list-style-type:decimal;">Sh. Saubhagya Dixit, Scientist \'D\'</li>
		    <li style="list-style-type:decimal;">Sh. Prakhar Srivastava, Scientist \'B\'</li>
    <li style="list-style-type:decimal;">Sh. Gokul Kannan, Scientist \'B\'</li>
    <li style="list-style-type:decimal;">Ms. Chitrakshi, Scientist \'B\'</li>
    <li style="list-style-type:decimal;">Sh. Chandra Prakash Yadav, SSA</li>              
              </ol> </span>
          </h5>
        </div>';
        $ipc6_modal_hi ='<div>
        <h3>औद्योगिक प्रदूषण नियंत्रण -VI प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
    <h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">-</span> </h5>
     <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-43102432 </span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;">-</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
													    <h5>यह प्रभाग निम्नलिखित उद्योग क्षेत्रों को देखता है : <strong>औद्योगिक प्रदूषण समन्वय</strong></h5>
        </span></h5>
        <h5 style="color:#0b73b9;"><span style="float: right;width: 70%; text-align:left;">
          <ol style="list-style-type:decimal;">
              <li  style="list-style-type:decimal;">	17 उद्योग श्रेणियां</li>
             <li  style="list-style-type:decimal;">	लाल उद्योग श्रेणियां</li>
              <li  style="list-style-type:decimal;">	उद्योगों का श्रेणीकरण</li>
              <li  style="list-style-type:decimal;">	मानकों की समीक्षा</li>
              <li  style="list-style-type:decimal;">	ऑनलाइन स्थापनाएं</li>
              <li  style="list-style-type:decimal;">अत्यधिक प्रदूषणकारी उद्योग (गंगा के अलावा)</li>
          </ol> 
          </span>
        </h5>
			<div class="clear"></div>
		  <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
            <ol>
             
             <li style="list-style-type:decimal;">श्री सौभाग्य दिक्षित, (वैज्ञानिक ‘डी’) </li>
			   <li style="list-style-type:decimal;">Sh. Prakhar Srivastava, Scientist \'B\'</li>
    <li style="list-style-type:decimal;">Sh. Gokul Kannan, Scientist \'B\'</li>
    <li style="list-style-type:decimal;">Ms. Chitrakshi, Scientist \'B\'</li>
    <li style="list-style-type:decimal;">Sh. Chandra Prakash Yadav, SSA</li> 
			              
              </ol> </span>
          </h5></div>';

        $ipc7_modal ='<div>
         <h3>Industrial Pollution Control-VII Division</h3>
	<div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">-</span> </h5>
     <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102431</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;">-</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
       <h5>This division deals  following Industry Sectors : <strong>Industrial Clusters and  Enforcement </strong></h5>
       <ol style="list-style-type:decimal;">
     <li  style="list-style-type:decimal;">Critically Polluted Industrial Clusters</li>
     <li  style="list-style-type:decimal;">CETPs</li>
    <li  style="list-style-type:decimal;">Vigilance and Directions</li>
     <li  style="list-style-type:decimal;">Environmental Clearances</li>
    <li  style="list-style-type:decimal;">Enforcement Issues</li>
    <li  style="list-style-type:decimal;">Ecomark Scheme </li>
          </ol>
        </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
   <!--li  style="list-style-type:decimal;">	Sh. Amit R Thakkar, Scientist \'E\'</li-->
 -
     </ol> </span>
                                                        </h5>
        </div>';
        $ipc7_modal_hi ='<div>
         <h3>औद्योगिक प्रदूषण नियंत्रण -VII प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
    <h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">-</span> </h5>
     <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-43102432 </span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;">-</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
													    <h5>यह प्रभाग निम्नलिखित उद्योग क्षेत्रों को देखता है : <strong>औद्योगिक प्रदूषण समन्वय</strong></h5>
        </span></h5>
		
		  <h5 style="color:#0b73b9;"><span style="float: right;width: 70%; text-align:left;">
                                                           <ol style="list-style-type:decimal;">
                                                               <li  style="list-style-type:decimal;">	अत्यंत प्रदूषित क्लस्टर</li>
                                                               <li  style="list-style-type:decimal;">	सीईटीपी</li>
                                                               <li  style="list-style-type:decimal;">	सतर्कता और निर्देश</li>
                                                               <li  style="list-style-type:decimal;">	पर्यावरण सम्बन्धी मंजूरियां</li>
                                                               <li  style="list-style-type:decimal;">	प्रवर्तन संबंधी समस्याएं</li>
                                                              </ol>
        </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                    <ol>
                                               	                                                     
                                                       </ol> </span>
                                                        </h5></div>';

        $upc1_modal ='<div>
          <h3>Urban Pollution Control-I Division</h3>
	<div style="overflow-y:Scroll;height:300px">
  <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Sh. Sharandeep Singh (Scientist \'E\')</span> </h5>
     <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102306</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> sharandeep.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
     <h5>This division deals  following Activities : </h5>
  <ol style="list-style-type:decimal;">
   <li  style="list-style-type:decimal;">Implementation of Construction & Demolition waste Rules 2016</li>
     <li  style="list-style-type:decimal;">Implementation of Noise (Regulation & control) Rules 2000</li>
	 
	    <li  style="list-style-type:decimal;">	Noise Mapping, Hot Spot identification and Mitigation plan for Noise Pollution Control in Delhi
    <li  style="list-style-type:decimal;">Implementation of emission and noise Compliance for Power Genset (< 800 KW)</li>
    <li  style="list-style-type:decimal;">Railway Locomotives – emission standard (ongoing project)</li>
    <li  style="list-style-type:decimal;">	Railway siding</li>
    <li  style="list-style-type:decimal;">	Dust mitigation from construction activities.</li>
    <li  style="list-style-type:decimal;">	Environmental norms in Hotels </li>
           </ol>
        </span>
       </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
         <li  style="list-style-type:decimal;">Dr. C K Dixit, Scientist \'D\'</li> 
  <li  style="list-style-type:decimal;">Sh. Danish Meena, Scientist \'C\'</li> 
   <li  style="list-style-type:decimal;">Miss. Juli Patel, Scientist ‘B’</li> </ol>  </span>
                                                        </h5></div>';
        $upc1_modal_hi ='<div>
        <h3>शहरी प्रदूषण नियंत्रण -I प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्री  शरणदीप  सिंह (वैज्ञानिक ‘ई’)</span> </h5>
     <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-43102306</span></h5>
<h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;">sharandeep.cpcb@nic.in </span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
       														 <h5>यह प्रभाग निम्नलिखित उद्योग क्षेत्रों को देखता है: </h5>
                                                           <ol style="list-style-type:decimal;">
                         <li  style="list-style-type:decimal;">	निर्माण एवं विध्वंस अपशिष्ट प्रबंधन नियमावली, 2016 का क्रियान्वयन</li>
                                  <li  style="list-style-type:decimal;">	ध्वनि प्रदूषण (विनियमन और नियंत्रण) नियमावली, 2000 का क्रियान्वयन</li>
    <li  style="list-style-type:decimal;">सात मेट्रो शहरों में केंद्रीय प्रदूषण नियंत्रण बोर्ड के राष्ट्रीय ध्वनि प्रदूषण निगरानी नेटवर्क का प्रबंधन।</li>
    <li  style="list-style-type:decimal;">	अन्य सरकारी विभागों के साथ दूसरे ध्वनि प्रदूषण संबंधी मुद्दे - वाहन (MoRTH), पटाखे (PESO), हवाई अड्डा क्षेत्र (DGCA) </li>
       <li  style="list-style-type:decimal;">	गंध निगरानी और एमएसडब्ल्यू लैंडफिल्स में प्रबंधन (जारी परियोजना) </li>
                            
          </ol>
        </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                                   <li style="list-style-type:decimal;">डॉ. सी के दीक्षित,  वैज्ञानिक ‘डी’</li>   
                                   <li style="list-style-type:decimal;">श्री दानिश मीना, (वैज्ञानिक ‘सी’)</li>
														 </ol> 
                                                        </span>
                                                        </h5></div>';

        $upc2_modal ='<div>
         <h3>Urban Pollution Control - II Division</h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>Divisional Head : </strong><span style="float: right;width: 70%; text-align:left;">Sh. G Thirumurthy (Scientist \'F\')</span></h5>
     <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong><span style="float: right;width: 70%; text-align:left;">011- 43102336</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong><span style="float: right;width: 70%; text-align:left;">thiru.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This division deals  following Activities : </h5>
                                                           <ol style="list-style-type:decimal;">
              <li  style="list-style-type:decimal;">Plastic Waste Management</li>
               <li style="list-style-type:decimal;">Solid Waste Management</li>
                        </ol>
        </span> </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;"> 
		  <ol>
          <li style="list-style-type:decimal;">Smt. Suniti Parashar, Scientist \'D\'</li>		   
          <li style="list-style-type:decimal;">Ms. Yogesh Chandra, Scientist \'C\'</li>
		  <li style="list-style-type:decimal;"> Sh. Madnesh Kumar Dubey, Scientist ‘B’</li>
<li style="list-style-type:decimal;"> Sh. Mayank Raj Purbey, Scientist ‘B’</li>
<li style="list-style-type:decimal;"> Smt. Divya Gupta, Scientist ‘B’</li>
		 </ol> </span>
      </h5>
        </div>';
        $upc2_modal_hi ='<div>
         <h3>शहरी प्रदूषण नियंत्रण - II प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्रीमती दिव्या सिन्हा (वैज्ञानिक ‘एफ’)</span> </h5>
     <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong><span style="float: right;width: 70%; text-align:left;">011- 43102453</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong><span style="float: right;width: 70%; text-align:left;">divyasinha.cpcb@nic.in </span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह प्रभाग निम्नलिखित उद्योग क्षेत्रों को देखता है: </h5>
                                                           <ol style="list-style-type:decimal;">
                                                               <li  style="list-style-type:decimal;">	प्लास्टिक अपशिष्ट प्रबंधन</li>
                                                               <li style="list-style-type:decimal;">ठोस अपशिष्ट प्रबंधन</li>
                                                                    </ol>
        </span>
                                                        </h5>
														
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>              
							  
							    <li style="list-style-type:decimal;">श्रीमती सुनीति पराशर, वैज्ञानिक ‘डी’</li>
          						<li style="list-style-type:decimal;">श्रीमती योगेश चंद्रा,  वैज्ञानिक ‘सी’</li>
									<li style="list-style-type:decimal;">श्री मदनेश कुमार दुबे, वैज्ञानिक ‘बी ’</li>
										<li style="list-style-type:decimal;">श्री मयंक राज पुर्बे, वैज्ञानिक ‘बी ’</li>
										<li style="list-style-type:decimal;">श्रीमती दिव्या गुप्ता, वैज्ञानिक ‘बी’</li>
								</ol> 
                                                        </span>
                                                        </h5></div>';

        $wm1_modal ='<div>
         <h3>Waste Management-I Division</h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Sh. Runa Oraon (Scientist \'E\')</span> </h5>
     <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102324</span></h5>
<h5 style="color:#0b73b9;"><strong>E-mail :</strong> <span style="float: right;width: 70%; text-align:left;"> -</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
 <h5>This division deals  following Activities :  : <strong>Hazardous Waste Management</strong></h5>
        </span></h5>
        <h5 style="color:#0b73b9;"><span style="float: right;width: 70%; text-align:left;">
          <ol style="list-style-type:decimal;">
              <li  style="list-style-type:decimal;">Remediation &amp; Policies</li>
              <li  style="list-style-type:decimal;">Ship Breaking</li>
              <li  style="list-style-type:decimal;">Bio-Medical Waste</li>
              <li  style="list-style-type:decimal;">Batteries</li>			
            </ol> 
          </span>
        </h5>
			<div class="clear"></div>
  <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
		<li style="list-style-type:decimal;">Smt. Niralee Verma, Scientist \'C\'</li>
	    <li style="list-style-type:decimal;">Sh. Harshit Patel, Scientist \'B\'</li>
     </ol> </span> </h5></div>';
        $wm1_modal_hi ='<div>
        <h3>अपशिष्ट प्रबंधन -I प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">-</span> </h5>
     <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102336</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> -</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
													    <h5>यह प्रभाग निम्नलिखित उद्योग क्षेत्रों को देखता है  : <strong> खतरनाक अपशिष्ट प्रबंधन</strong></h5>
        </span></h5>
        <h5 style="color:#0b73b9;"><span style="float: right;width: 70%; text-align:left;">
          <ol style="list-style-type:decimal;">
              <li  style="list-style-type:decimal;">	उपचार और नीतियां</li>
              <li  style="list-style-type:decimal;">	शिप ब्रेकिंग</li>
              <li  style="list-style-type:decimal;">	जैव-चिकित्सा अपशिष्ट</li>
              <li  style="list-style-type:decimal;">बैटरियां</li>			
            </ol> 
          </span>
        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                                     <li style="list-style-type:decimal;">श्रीमती निराली, (वैज्ञानिक ‘सी’)</li>
 				 <li style="list-style-type:decimal;">श्री हर्षित पटेल, (वैज्ञानिक ‘बी’)</li>
</ol> </span></h5>
        </div>';

        $wm2_modal ='<div>
        <h3>Waste Management-II Division</h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Sh. Dinabandhu Gouda (Scientist \'F\')</span> </h5>
     <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102319</span></h5>
<h5 style="color:#0b73b9;"><strong>E-mail :</strong> <span style="float: right;width: 70%; text-align:left;"> dinabandhu.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
       														 <h5>This division deals  following Activities : </h5>
                                                           <ol style="list-style-type:decimal;">
                              <li  style="list-style-type:decimal;">Enforcement of Hazardous & other wastes (Management & Transboundary Movement) Rules, 2016</li>
i. Common TSDFs
</br>ii. Captive TSDFs</br>
iii. Incinerators used for HW</br>
<li  style="list-style-type:decimal;">Development of Standard Operating Procedure for Processing the Proposals for Utilization of Hazardous Waste under Rule 9 of the Hazardous and Other Wastes (Management and Transboundary Movement) Rules, 2016</li>
<li  style="list-style-type:decimal;">Enforcement of CPCB Guidelines of End-of-life Vehicles(ELVs)</li>
<li  style="list-style-type:decimal;">Inventorization of Hazardous waste</li>  
                                                        </ol> </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                        <li style="list-style-type:decimal;">Sh. Sahil Patel, Scientist \'C\'</li>
		                                                        </span>
                                                        </h5>
        </div>';
        $wm2_modal_hi ='<div><h3>अपशिष्ट प्रबंधन - II प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
     <h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्रीमती दीप्ति कपिल (वैज्ञानिक ‘ई’)</span> </h5>
     <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-43102319</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> deepti.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
       														 <h5>यह प्रभाग निम्नलिखित कार्यकलापों को देखता है : </h5>
                                                           <ol style="list-style-type:decimal;">
<li  style="list-style-type:decimal;">See English version.</li></br>			
                                                        </ol> </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                                                     <li style="list-style-type:decimal;">श्री साहिल पटेल, (वैज्ञानिक ‘सी’)</li>
						  <li style="list-style-type:decimal;">श्रीमती मेधा शर्मा, (वैज्ञानिक ‘सी’)</li>
						   <li  style="list-style-type:decimal;">श्रीमती विनीता, (वैज्ञानिक ‘सी’)</li>
                                                          </ol> 
                                                        </span>
                                                        </h5></div>';

        $wm3_modal ='<div>
         <h3>Waste Management -III Division</h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Smt. Deepti Kapil (Scientist \'E\')</span> </h5>
     <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-43102321, 43102467</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> deepti.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This division deals  following Activities : </h5>
                   <ol style="list-style-type:decimal;">
   <li  style="list-style-type:decimal;">Electronic Waste Management</li>			
  </ol> </span><div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                        <ol>
                        	  <li  style="list-style-type:decimal;">Smt. Vineeta, Scientist \'C\'</li></ol> 
							  <li style="list-style-type:decimal;">Smt. Meetu Puri, Scientist \'C\'</li>
                            <li style="list-style-type:decimal;">Smt. Riya Soni, Scientist \'B\'</li>
								  <li  style="list-style-type:decimal;">Sh. Kottur Rajkumar, Scientist \'B\'</li></ol> 
							</ol> </span>
                                   </h5></div>';
        $wm3_modal_hi ='<div>
         <h3>अपशिष्ट प्रबंधन -III प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
     <h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्रीमती यूथिका पुरी (वैज्ञानिक ‘ई’)</span> </h5>
     <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102321</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> youthika.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह प्रभाग निम्नलिखित कार्यकलापों को देखता है: </h5>
                                                           <ol style="list-style-type:decimal;">
                                                               <li  style="list-style-type:decimal;">	इलेक्ट्रॉनिक अपशिष्ट प्रबंधन</li>			
                                                        </ol> </span>

                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                                                           <li  style="list-style-type:decimal;">श्रीमती मीतू पुरी,  (वैज्ञानिक ‘सी’)</li>
                                                             <li  style="list-style-type:decimal;">श्री नवीन कुमार, (वैज्ञानिक ‘बी’)</li>
															     <li  style="list-style-type:decimal;">सुश्री रिया सोनी, (वैज्ञानिक ‘बी’)</li> 
                                                         </ol> </span>
                                                        </h5></div>';

        $aqm_modal ='<div>
         <h3>Air Quality Management Division</h3>
	<div style="overflow-y:Scroll;height:300px">
     <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Sh. Pankaj Aggarwal (Scientist \'F\')</span> </h5>
         <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-43102432</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> pagarwal.cpcb@gov.in</span></h5>
     <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This division deals  following Activities : </h5>
                                                           <ol style="list-style-type:decimal;">
     <li  style="list-style-type:decimal;">Air Quality Management Policy &amp; related Bilateral  issues</li>
     <li  style="list-style-type:decimal;">Air Quality Management Plans </li>
     <li  style="list-style-type:decimal;">Source Apportionment Studies</li>
  <li  style="list-style-type:decimal;">Vehicular Pollution Control</li>
    <li  style="list-style-type:decimal;">Utilization of EPC Funds</li>
     <li  style="list-style-type:decimal;"> National Clean Air program (NCAP)</li>
          </ol>
        </span>
        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                          <li style="list-style-type:decimal;">Sh. Ankush Tiwani, Scientist \'E\'</li>
							 <li style="list-style-type:decimal;">Sh. Gautam Kumar Sharma, Scientist \'C\'</li>
							 <li  style="list-style-type:decimal;">Smt. Sakshi Batra, Scientist \'C\'</li>                                                
                                                        </ol> 
                                                        </span>
      </h5>
        </div>';
        $aqm_modal_hi ='<div>
         <h3>वायु गुणवत्ता प्रबंधन प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">	श्री. पंकज  अग्रवाल (वैज्ञानिक ‘एफ’) </span> </h5>
         <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102370</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> pagarwal.cpcb@gov.in</span></h5>
     <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
	 
		  <h5>यह प्रभाग निम्नलिखित कार्यकलापों को देखता है: </h5>
                                                           <ol style="list-style-type:decimal;">
                                 <li  style="list-style-type:decimal;">	वायु गुणवत्ता प्रबन्धन नीति और संबंधित द्विपक्षीय मुद्दे</li>
                                                               <li  style="list-style-type:decimal;">	वायु गुणवत्ता प्रबन्धन योजनाएं </li>
                                                               <li  style="list-style-type:decimal;">	स्रोत आवंटन अध्ययन</li>
                                                               <li  style="list-style-type:decimal;">	वाहन प्रदूषण नियंत्रण</li>
                                                            <li  style="list-style-type:decimal;">	ईपीसी निधियों का उपयोग</li>
  <li  style="list-style-type:decimal;"> National Clean Air program (NCAP)</li>
          </ol>
        </span>
        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                               <ol> 
                                                     	<li style="list-style-type:decimal;"> श्री. अंकुश तिवानी, (वैज्ञानिक \'ई\')</li>
												<li style="list-style-type:decimal;"> श्री. गौतम कुमार शर्मा,(वैज्ञानिक \'सी\')</li>
                                                          <li  style="list-style-type:decimal;">श्रीमती साक्षी बत्रा, (वैज्ञानिक \'सी\')</li></ol> 
                                                        </span> </h5></div>';

        $aqmn_modal ='<div>
        <h3>Air Quality Monitoring Network (AQMN) Division</h3>
	<div style="overflow-y:Scroll;height:300px">
     <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Sh. Aditya Sharma(Scientist \'E\')</span> </h5>
         <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102428</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> aditya.cpcb@nic.in</span></h5>
     <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This division deals  following Activities : </h5>
                                                           <ol style="list-style-type:decimal;">
     <li  style="list-style-type:decimal;">National Air Quality Monitoring Programme (NAMP)</li>
    <li  style="list-style-type:decimal;">Continuous Ambient Air Quality Monitoring System (CAAQMS)</li>
	<li  style="list-style-type:decimal;">Rural Network</li>
	<li  style="list-style-type:decimal;">Satellite and Sensor-based Monitoring</li>
	<li  style="list-style-type:decimal;">Air Quality Forecast</li>
	<li  style="list-style-type:decimal;">Data Management and </li>
	<li  style="list-style-type:decimal;">Air Quality Health Assessment </li>
          </ol>
        </span>
        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                          <li style="list-style-type:decimal;">Sh. Tarun Darbari, Scientist \'E\'</li>
							 <li style="list-style-type:decimal;">Sh. Fasiur Rehman, Scientist \'D\'</li>
							<li style="list-style-type:decimal;">Sh. Neeraj Katiyar, Scientist \'B\'</li>
<li style="list-style-type:decimal;">Sh. Deepak Gautam, Scientist \'B\'</li>
                             </ol> 
                                                        </span>
      </h5></div>';
        $aqmn_modal_hi ='<div>
        
         <h3>वायु गुणवत्ता निगरानी नेटवर्क (एक्यूएमएन) प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
     <h5 style="color:#0b73b9;"><strong>विभाग प्रमुख : </strong> <span style="float: right;width: 70%; text-align:left;">श्री आदित्य शर्मा (वैज्ञानिक \'ई\')</span> </h5>
         <h5 style="color:#0b73b9;"><strong>	फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102428</span></h5>
     <h5 style="color:#0b73b9;"><strong>ईमेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> aditya.cpcb@nic.in</span></h5>
     <h5 style="color:#0b73b9;"><strong>प्रोफ़ाइल और गतिविधि : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह प्रभाग निम्नलिखित गतिविधियों से संबंधित है: </h5>
                                                           <ol style="list-style-type:decimal;">
     <li  style="list-style-type:decimal;">राष्ट्रीय वायु गुणवत्ता निगरानी कार्यक्रम (एनएएमपी)</li>
    <li  style="list-style-type:decimal;">सतत परिवेशी वायु गुणवत्ता निगरानी प्रणाली (सीएएक्यूएमएस)</li>
	<li  style="list-style-type:decimal;">ग्रामीण नेटवर्क</li>
	<li  style="list-style-type:decimal;">उपग्रह और सेंसर आधारित निगरानी</li>
		<li  style="list-style-type:decimal;">डेटा प्रबंधन और </li>
          </ol>
        </span>
        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                      					 <li style="list-style-type:decimal;">श्री तरुण दरबारी, (वैज्ञानिक ‘ई’)</li>
							 <li style="list-style-type:decimal;">श्री फसीउर रहमान, वैज्ञानिक \'डी\'</li>
							<li style="list-style-type:decimal;">श्री नीरज कटियार, वैज्ञानिक  \'बी\'</li>
							<li style="list-style-type:decimal;">डॉ दीपक गौतम, वैज्ञानिक \'बी\'</li>
                                                 </ol> 
                                                        </span>
      </h5></div>';

        $wqm1_modal ='<div> <h3>Water Quality Management -I Division</h3>
	<div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Sh. Nazimuddin, Scientist \'F\'</span> </h5>
      <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102310</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> nazim.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This division deals  following Activities  : <strong>Water Quality  Assessment and Monitoring</strong></h5>
        </span></h5>
        <h5 style="color:#0b73b9;"><span style="float: right;width: 70%; text-align:left;">
          <ol style="list-style-type:decimal;">
 <li  style="list-style-type:decimal;">Water Quality Monitoring of rivers, lakes,  groundwater and coastal waters.</li>
              <li  style="list-style-type:decimal;">Setting up of Real Time Water Quality  Monitoring Stations</li>
              <li  style="list-style-type:decimal;">Water Quality Restoration Plan</li>
              <li  style="list-style-type:decimal;">Water Quality Monitoring of river Ganga including  RTWQM</li>
  		
  </ol> 
           
        </span>
        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
         <li  style="list-style-type:decimal;">Sh. Vishal Gandhi, Scientist \'E\'</li> 
		     <li style="list-style-type:decimal;">Smt. Alpana Narula, Scientist \'B\'</li> 
         <li style="list-style-type:decimal;">Sh. Gautam Sondhi, Scientist \'B\'</li> </ol> </span>
                                                        </h5></div>';
        $wqm1_modal_hi ='<div> <h3>जल गुणवत्ता प्रबंधन -I प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
 <h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्री नजीमुद्दीन (वैज्ञानिक ‘एफ’)</span> </h5>
     <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 22381707, 43102310</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> nazim.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह प्रभाग निम्नलिखित कार्यकलापों को देखता है  : <strong>जल गुणवत्ता निर्धारण और निगरानी</strong></h5>
        </span></h5>
        <h5 style="color:#0b73b9;"><span style="float: right;width: 70%; text-align:left;">
          <ol style="list-style-type:decimal;">
              <li  style="list-style-type:decimal;">	नदियों, झीलों, भूजल और तटीय जल की जल गुणवत्ता निगरानी</li>
              <li  style="list-style-type:decimal;">	तत्काल जल गुणवत्ता निगरानी स्टेशनों की स्थापना</li>
              <li  style="list-style-type:decimal;">	जल गुणवत्ता बहाली योजना</li>
              <li  style="list-style-type:decimal;">	गंगा नदी की जल गुणवत्ता निगरानी जिसमें तत्काल जल गुणवत्ता निगरानी शामिल है
     </li>
          </ol>
        </span>
        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                                                      <li  style="list-style-type:decimal;">श्री विशाल गांधी, (वैज्ञानिक ‘ई’)</li>
                                                      <li style="list-style-type:decimal;">श्रीमती अल्पना नरुला, (वैज्ञानिक  \'बी\')</li>
													  
                                                         </ol> </span>
                                                        </h5></div>';

        $wqm2_modal ='<div><h3>Water Quality Management-II Division</h3>
	<div style="overflow-y:Scroll;height:300px">
 
     <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Sh. Dinabandhu Gouda (Scientist \'F\')</span> </h5>
      <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-43102445</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> dinabandhu.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This division deals  following Activities under three NMCG-sponsored projects :  <strong> Pollution Inventorization, Assessment and Surveillance (PIAS), Strengthening of Environmental Regulators (SER) & Water Quality Monitoring (WQM) System for Rivers Ganga & Yamuna:
</strong></h5>
        </span></h5>
        <h5 style="color:#0b73b9;"><span style="float: right;width: 70%; text-align:left;">
          <ol style="list-style-type:decimal;">
              <li  style="list-style-type:decimal;"> Annual inspection of Grossly Polluting Industries (GPIs), including Common Effluent Treatment Plants (CETPs), in basins of Rivers Ganga & Yamuna and their tributaries.
</li>
              <li  style="list-style-type:decimal;">Tri-annual monitoring of Sewage Treatment Plants (STPs) in front towns of Rivers Ganga and Yamuna.</li>
              <li  style="list-style-type:decimal;">Tri-annual monitoring of CETPs in River Ganga main stem.</li>			
            <li  style="list-style-type:decimal;">Half-yearly (pre- and post-monsoon) monitoring of drains discharging into Rivers Ganga & Yamuna and their tributaries.</li>		
    </ol> 
          </span>
        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
    <li style="list-style-type:decimal;">Smt. Reena Satavan,Scientist \'E\'</li>
      </ol> </span>
                                                        </h5></div>';
        $wqm2_modal_hi ='<div> <h3>जल गुणवत्ता प्रबंधन - II प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्री दीनबंधु गौड़ा, (वैज्ञानिक ‘एफ’)</span> </h5>
      <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 22391519, 43102445</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> dinabandhu.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This division deals  following Activities under three NMCG-sponsored projects :  <strong> Pollution Inventorization, Assessment and Surveillance (PIAS), Strengthening of Environmental Regulators (SER) & Water Quality Monitoring (WQM) System for Rivers Ganga & Yamuna:</strong></h5>
        </span></h5>
        <h5 style="color:#0b73b9;"><span style="float: right;width: 70%; text-align:left;">
          <ol style="list-style-type:decimal;">
              <li  style="list-style-type:decimal;"> Annual inspection of Grossly Polluting Industries (GPIs), including Common Effluent Treatment Plants (CETPs), in basins of Rivers Ganga & Yamuna and their tributaries.
</li>
              <li  style="list-style-type:decimal;">Tri-annual monitoring of Sewage Treatment Plants (STPs) in front towns of Rivers Ganga and Yamuna.</li>
              <li  style="list-style-type:decimal;">Tri-annual monitoring of CETPs in River Ganga main stem.</li>			
            <li  style="list-style-type:decimal;">Half-yearly (pre- and post-monsoon) monitoring of drains discharging into Rivers Ganga & Yamuna and their tributaries.</li>		
    </ol>
          </span>
        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                              <li style="list-style-type:decimal;">	श्रीमती रीना सतावन, (वैज्ञानिक ‘ई’)</li>
                                </ol> </span>
                                                        </h5></div>';

        $it_modal ='<div><h3>Information Technology Division</h3>
	<div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Sh. P Jgaan, Scientist \'F\'</span> </h5>
      <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-43102296</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> jagan.cpcb@gov.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This division deals  following Activities : </h5>
              <ol style="list-style-type:decimal;">
           <li  style="list-style-type:decimal;">Networking</li>
          <li  style="list-style-type:decimal;">Website  updation</li>
        <li  style="list-style-type:decimal;">Enterprise  Resource Planning</li>
         <li  style="list-style-type:decimal;">SPCB  Support</li>
              <li  style="list-style-type:decimal;">GIS  Development</li>
              <li  style="list-style-type:decimal;">Data  Storage and Security</li>
           <!--li  style="list-style-type:decimal;">E-Samiksha</li-->
          <!--li  style="list-style-type:decimal;">MIS - SPCBs</li-->
          </ol>
        </span>
        </h5>
		<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
	  <li style="list-style-type:decimal;">Sh. Archit Uprit, Scientist \'E\'</li>  
      <li style="list-style-type:decimal;">Sh. Anurag Sharma, Scientist \'C\'</li>
	   <li style="list-style-type:decimal;">Sh. Saransh Bamaniya, Scientist \'B\'</li>
	    <li style="list-style-type:decimal;">Sh. Ashish Kumar, Scientist \'B\'</li>
		 <li style="list-style-type:decimal;">Sh. Krishan Dev, Scientist \'B\'</li>		        
	  <li  style="list-style-type:decimal;">Smt. Shashi Goel, DPA</li>
	  <li  style="list-style-type:decimal;">Sh. V.N. Murthy, STS</li>
       <li  style="list-style-type:decimal;">Ms. Pooja Rani , DPA</li>
		<li  style="list-style-type:decimal;">Sh. Naman Srivastava, DPA</li>
        <li  style="list-style-type:decimal;">Sh. Shubham Tiwari, TS</li>
				  <li  style="list-style-type:decimal;">Sh. Mujeeb Ahmad Ansari, SLA</li>
         </ol> 
                                                        </span>
      </h5></div>';
        $it_modal_hi ='<div><h3>सूचना प्रौद्योगिकी प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्री पी जगन, वैज्ञानिक \'एफ\'</span> </h5>
      <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-43102296</span></h5>
      <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> jagan.cpcb@gov.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह प्रभाग निम्नलिखित कार्यकलापों को देखता है : </h5>
                                                           <ol style="list-style-type:decimal;">
                                                               <li  style="list-style-type:decimal;">	नेटवर्किंग</li>
                                                               <li  style="list-style-type:decimal;">	वेबसाइट प्रबंधन </li>
                                                               <li  style="list-style-type:decimal;">	उपक्रम संसाधन आयोजना</li>

                                                                <li  style="list-style-type:decimal;">	डाटा भंडारण और सुरक्षा</li>
								 <li  style="list-style-type:decimal;">	ई-गवर्नेंस पोर्टल्स - विकास और प्रबंधन </li>
          </ol>
        </span>
        </h5>
		<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                                          			 	  <li style="list-style-type:decimal;">	श्री अर्चित उप्रित, (वैज्ञानिक ‘ई’)</li>
                                                          <li style="list-style-type:decimal;">	श्री अनुराग शर्मा, (वैज्ञानिक ‘सी\')</li>
						  <li style="list-style-type:decimal;">	श्री सारांश बमनिया, (वैज्ञानिक ‘बी\')</li>
	    						<li style="list-style-type:decimal;">	श्री  आशीष कुमार, (वैज्ञानिक ‘बी\')</li>
		 					<li style="list-style-type:decimal;">	श्री  कृष्ण देव,(वैज्ञानिक ‘बी\')</li>														  
                                                   <li  style="list-style-type:decimal;">	श्री वी.एन. मूर्ति, वरिष्ठ तकनीकी पर्यवेक्षक</li>
						<li  style="list-style-type:decimal;">	श्रीमती शशि गोयल, डीपीए</li>
                                                <li  style="list-style-type:decimal;">	श्रीमती पूजा रानी, डीपीए</li>
							<li  style="list-style-type:decimal;">श्री नमन श्रीवास्तव, डीपीए</li>
                       					 <li  style="list-style-type:decimal;">	श्री शुभम तिवारी, तकनीकी पर्यवेक्षक</li>
			 				 <li  style="list-style-type:decimal;">	श्री मुजीब अहमद अंसारी, जे एल ए</li>						  
                                                        </ol> 
                                                        </span>
      </h5></div>';

        $cec_modal ='<div><h3>Circular Economy Cell</h3>
	<div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Smt. Deepti Kapil, Scientist \'E\'</span> </h5>
      <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-43102319</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> deepti.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This division deals  following Activities : </h5>
              <ol style="list-style-type:decimal;">
           <li  style="list-style-type:decimal;">Identify activities of IPC\'s, WM and UPC Divisions that contributes to circular economy objectives and report the progress along with times lines.</li>
		       <li  style="list-style-type:decimal;">To coordinate with concerned Divisions to complete action points assigned to CPCB for different waste streams.</li>
			       <li  style="list-style-type:decimal;">To address any other matter as referred to the Cell.</li>
				       <li  style="list-style-type:decimal;">Publish SOPs / guideline prepared by CPCB in Niti Ayog Initiative </li>
					            </ol>
        </span>
        </h5>
		<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
      -
				 	          </ol> 
                                                        </span>
      </h5></div>';
        $cec_modal_hi ='<div><h3>सर्कुलर ईकानमी सेल  </h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;"> श्रीमती दीप्ति कपिल (वैज्ञानिक ‘ई’)</span> </h5>
      <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> -</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> deepti.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह प्रभाग निम्नलिखित कार्यकलापों को देखता है : </h5>
           <ol style="list-style-type:decimal;">
  <li  style="list-style-type:decimal;">IPCs, WM और UPC डिवीजनों की गतिविधियों की पहचान करना, जो परिपत्र अर्थव्यवस्था के उद्देश्यों में योगदान करती हैं और समय-सीमा के साथ प्रगति की रिपोर्ट करती हैं।</li>
<li  style="list-style-type:decimal;">विभिन्न अपशिष्टों के लिए सीपीसीबी को सौंपे गए कार्रवाई बिंदुओं को पूरा करने के लिए संबंधित प्रभागों के साथ समन्वय करना।</li>
 <li  style="list-style-type:decimal;">सेल को संदर्भित किसी अन्य मामले को संबोधित करने के लिए।</li>
<li  style="list-style-type:decimal;">नीति आयोग की पहलों में सीपीसीबी द्वारा तैयार एसओपी/दिशानिर्देश प्रकाशित करना </li>
	 </ol>
        </span>
        </h5>
		<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
      <li style="list-style-type:decimal;">-</li>
		  	          </ol> 
                                                        </span>
      </h5></div>';

        $randd_modal ='<div><h3><strong>Air Lab </strong></h3>
	<div style="overflow-y:Scroll;height:300px">
 <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Sh. Aditya Sharma, Scientist \'E\'</span> </h5>
      <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102441</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong>  <span style="float: right;width: 70%; text-align:left;">aditya.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
       		 <h5>This division deals  following Activities : </h5>
     <ol style="list-style-type:decimal;">
      <li  style="list-style-type:decimal;">Ambient  Air Quality (Delhi and  NCR) – CAAQMS  &amp; Manual </li>
     <li  style="list-style-type:decimal;">CAAQMS (Nation-wide)</li>
     <li  style="list-style-type:decimal;">Stack Monitoring</li>
    <li  style="list-style-type:decimal;">Calibration Facilities</li>
  <li  style="list-style-type:decimal;">Method Standardization</li>	 </ol> </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
      <ol>
       <li  style="list-style-type:decimal;">Dr. Dolly Kulshreshtha, Scientist \'C\'</li>
       <li  style="list-style-type:decimal;">Sh. Navkant Juyal, SSA</li>
   <li  style="list-style-type:decimal;">Sh. Gyas Mohd. Khan, SSA</li>
 <li  style="list-style-type:decimal;">Sh. Vinod Kumar, SSA</li>
  <li  style="list-style-type:decimal;">Sh. Pravin Kumar Gupta, SSA</li>
 <li  style="list-style-type:decimal;">Sh. Amit Kumar Sharma, SSA</li>
 <li  style="list-style-type:decimal;">Sh. K P Rathi, SSA</li>
 <li  style="list-style-type:decimal;">Sh. Surendra Prasad, SLA</li> 
 <li  style="list-style-type:decimal;">Sh. Amit Kumar Sharma, SLA</li>
 <li  style="list-style-type:decimal;">Sh. Surendra Singh Rawat, JLA</li>
 <li  style="list-style-type:decimal;">Sh. Nandu Prasad, JLA</li>
 <li  style="list-style-type:decimal;">Sh. C P Singh, JLA</li>
 <li  style="list-style-type:decimal;">Sh. Krishan Kumar, FA</li></ol> 
        </span>
                     </h5></div>';
        $randd_modal_hi ='<div><h3><strong>वायु प्रयोगशाला </strong></h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्री आदित्य शर्मा (वैज्ञानिक \'ई\')</span> </h5>
      <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 22301071, 43102341</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> -</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
       														 <h5>यह प्रभाग निम्नलिखित कार्यकलापों को देखता है : </h5>
                                                           <ol style="list-style-type:decimal;">
                              <li  style="list-style-type:decimal;">	परिवेशी वायु गुणवत्ता (दिल्ली और एनसीआर) - सीएएक्यूएमएस और मैनुअल </li>
                                                               <li  style="list-style-type:decimal;">सीएएक्यूएमएस (राष्ट्रव्यापी)</li>
                                                               <li  style="list-style-type:decimal;">	चिमनी निगरानी</li>
                                                               <li  style="list-style-type:decimal;">	अंशांकन सुविधाएं</li>
                                                               <li  style="list-style-type:decimal;">	विधि मानकीकरण</li>			
                                                        </ol> </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
           			  <li  style="list-style-type:decimal;">डॉ. डॉली कुलश्रेष्ठा, वैज्ञानिक सी</li>
	 <li  style="list-style-type:decimal;">श्री नवकान्त जुयाल, एस एस ए</li>
  <li  style="list-style-type:decimal;">श्री ग्यास मुहम्मद खान,  एस एस ए</li>
 <li  style="list-style-type:decimal;">श्री विनोद कुमार, एस एस ए</li>
  <li  style="list-style-type:decimal;">श्री प्रवीण कुमार गुप्ता, एस एस ए</li>
 <li  style="list-style-type:decimal;">श्री अमित कुमार शर्मा, एस एस ए</li>
 <li  style="list-style-type:decimal;">श्री के पी राठी, एस एस ए</li>
 <li  style="list-style-type:decimal;">श्री सुरेन्द्र प्रसाद, एस एल ए</li> 
 <li  style="list-style-type:decimal;">श्री अमित कुमार शर्मा, एस एल ए</li>
 <li  style="list-style-type:decimal;">श्री सुरेन्द्र सिंह रावत, एस एल ए </li>
 <li  style="list-style-type:decimal;">श्री नंदू प्रसाद, एस एल ए </li>
 <li  style="list-style-type:decimal;">श्री सी पी सिंह, जे एल ए </li>
 <li  style="list-style-type:decimal;">श्री कृशन कुमार, एफ एल ए  </li>
                                   </ol> 
                                                        </span>
                                                        </h5></div>';

        $law_modal ='<div><h3><strong>Trace Organic Lab</strong></h3>
	<div style="overflow-y:Scroll;height:300px">
     <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong><span style="float: right;width: 70%; text-align:left;">Smt. Namita Mishra (Scientist \'E\')</span></h5>
      <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102367</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> namita.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This division deals  following Activities : </h5>
                                                           <ol style="list-style-type:decimal;">
<li  style="list-style-type:decimal;">Analysis of Organic Compounds (Pesticides, Insecticides, PAHs, Pharmaceutical Compounds) at trace levels.</li>
<li  style="list-style-type:decimal;">Support to Air Laboratory for stationary emission sources using Sampling Kit.</li>
<li  style="list-style-type:decimal;">Conducting Analytical Quality Comparison (AQC) Exercise for Pesticide Compounds.</li>
<li  style="list-style-type:decimal;">Training to Students and Officials of State Pollution Control Boards / Committees.</li>
<li  style="list-style-type:decimal;">Analytical support to State Pollution Control Boards, Pollution Control Committees and other Govt. Organizations.</li>
		
                                                        </ol> </span>
        </h5>
		<div class="clear"></div>
    <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
   <li style="list-style-type:decimal;">Sh. Bhupander Kumar, Scientist \'E\'</li>
         <li  style="list-style-type:decimal;">Sh. Charu Sharma, Scientist \'B\'</li>
   <li  style="list-style-type:decimal;">Sh. V K Verma, Sc. B</li>                                                 
   <li  style="list-style-type:decimal;">Sh. Beerendra Singh, SSA</li>   
   <li  style="list-style-type:decimal;">Smt. Rashmi Mittal, SSA</li>    
                                                        </ol> 
                                                        </span>
    </h5></div>';
        $law_modal_hi ='<div><h3><strong>ट्रेस कार्बनिक प्रयोगशाला</strong></h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong><span style="float: right;width: 70%; text-align:left;">श्रीमती नमिता मिश्रा, (वैज्ञानिक ‘ई’)</span></h5>
      <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-43102367</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> namita.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह प्रभाग निम्नलिखित कार्यकलापों को देखता है : </h5>
                                                           <ol style="list-style-type:decimal;">
  <li  style="list-style-type:decimal;">ट्रेस ऑर्गेनिक्स प्रयोगशाला विभिन्न पर्यावरणीय मैट्रिक्स जैसे पानी, हवा, मिट्टी, ठोस अपशिष्ट आदि में ट्रेस स्तरों पर कुछ स्थायी कार्बनिक प्रदूषकों सहित विभिन्न कार्बनिक प्रदूषकों का आकलन करने में महत्वपूर्ण भूमिका निभाती है।</li> 
  <li  style="list-style-type:decimal;"> सीपीसीबी, राज्य प्रदूषण नियंत्रण बोर्ड, प्रदूषण नियंत्रण समितियाँ और सरकार  के अन्य संगठन /प्रभागों को विश्लेषणात्मक सहायता और सेवाएं प्रदान करता है</li>
  <li  style="list-style-type:decimal;">ट्रेस स्तरों पर विभिन्न कार्बनिक यौगिकों का विश्लेषण करता है।</li>
  <li  style="list-style-type:decimal;"> कानूनी मामलों, पर्यावरण निगरानी आदि से संबंधित विश्लेषण करता है।</li>
  <li  style="list-style-type:decimal;">जैविक प्रदूषकों के विश्लेषण के लिए पद्धतियों का मानकीकरण।</li>
  <li  style="list-style-type:decimal;"> डेटा संकलन, व्याख्या और रिपोर्ट तैयार करना।</li>
  <li  style="list-style-type:decimal;">परियोजना प्रस्तावों पर तकनीकी समीक्षा और टिप्पणियाँ।</li>
  <li  style="list-style-type:decimal;">तकनीकी रिपोर्ट, दस्तावेज़ आदि पर टिप्पणियाँ।</li>
  <li  style="list-style-type:decimal;">राज्य प्रदूषण नियंत्रण बोर्डों/समितियों को प्रशिक्षण और तकनीकी सहायता।</li>
			
                                                        </ol> </span>
        </h5>
		<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
            <ol>
             <li style="list-style-type:decimal;">श्री बी. कुमार, वैज्ञानिक ‘ई’</li>
	     <li  style="list-style-type:decimal;">श्रीमती चारु शर्मा , वैज्ञानिक \'बी\'</li>
   <li  style="list-style-type:decimal;">श्री वी के वर्मा , वैज्ञानिक \'बी\'</li>                                                 
   <li  style="list-style-type:decimal;">श्री बीरेंद्र  सिंह, एस एस ए </li>   
   <li  style="list-style-type:decimal;">श्रीमती रश्मी मित्तल, एस एस ए </li>                           
              </ol> 
                                                        </span>
        </h5></div>';

        $aq1_modal ='<div><h3><strong>Water &amp; Wastewater Lab</strong></h3>
	<div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong><span style="float: right;width: 70%; text-align:left;">Sh. Sanjay Kumar (Scientist \'E\')</span></h5>
      <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102361</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> skumar.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This division deals  following Activities : </h5>
                                                           <ol style="list-style-type:decimal;">
                                                               <li  style="list-style-type:decimal;">Water</li>
                                                               <li  style="list-style-type:decimal;">Wastewater</li>
         <li  style="list-style-type:decimal;">Soil     </li>
          </ol>
        </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
      		   <li style="list-style-type:decimal;">Sh. Syed M Bilal, Scientist \'C\'</li>
			   <li style="list-style-type:decimal;">Sh. A K Tyagi, Scientist \'C\'</li>
			 <li style="list-style-type:decimal;">Smt. B Sasi Devi, Scientist \'C\'</li>
         <li style="list-style-type:decimal;">Smt. Gargi Gurtu, Scientist \'C\'</li>
         <li style="list-style-type:decimal;">Smt. Vijaya Laxmi, Scientist \'C\'</li>
         <li style="list-style-type:decimal;">Sh. Uma Shankar Mishra, JLA</li>
         <li style="list-style-type:decimal;">Sh. Atul Sharma, JLA</li>
         <li style="list-style-type:decimal;">Sh. Inder Mohan, JLA</li>
         <li style="list-style-type:decimal;">Sh. Basant Kumar, JLA</li>
          </ol> </span>
                                                        </h5></div>';
        $aq1_modal_hi ='<div> <h3><strong>जल और अपशिष्ट जल प्रयोगशाला</strong></h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्री संजय कुमार (वैज्ञानिक ‘ई’)</span> </h5>
      <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102376</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;">skumar.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह प्रभाग निम्नलिखित कार्यकलापों को देखता है : </h5>
                                                           <ol style="list-style-type:decimal;">
                                                               <li  style="list-style-type:decimal;">जल</li>
                                                               <li  style="list-style-type:decimal;">	अपशिष्ट जल</li>
                                                               <li  style="list-style-type:decimal;">	मृदा      </li>
          </ol>
        </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
														<li style="list-style-type:decimal;">	श्री. सय्यद एम बिलाल, (वैज्ञानिक ‘सी \')</li>
														<li style="list-style-type:decimal;">	श्री. ए के त्यागी, (वैज्ञानिक ‘सी \')</li>
														<li style="list-style-type:decimal;">	श्रीमती बी. ससी देवी, (वैज्ञानिक ‘सी’)</li>
														<li style="list-style-type:decimal;">	श्रीमती गार्गी गुरतू, (वैज्ञानिक ‘सी’)</li>
                                                     </ol> </span>
                                                        </h5></div>';

        $int_modal ='<div><h3><strong>Instrumentation Lab</strong></h3>
	<div style="overflow-y:Scroll;height:300px">
       <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong><span style="float: right;width: 70%; text-align:left;">Smt. Namita Mishra (Scientist \'E\')</span></h5>
      <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102367</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> namita.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
       														 <h5>This division deals  following Activities :  </h5>
                                                           <ol style="list-style-type:decimal;">
 <li  style="list-style-type:decimal;">Analysis of Heavy metals (as per the scope) and trace elements in various environmental samples </li>
<li  style="list-style-type:decimal;"> Analysis of Total Organic Carbon (TOC) and Total organic Halides (TOX) in environmental samples</li>
<li  style="list-style-type:decimal;"> Joint activity of EPA recognition of the PVT/NGO environmental laboratories under Environmental Protection Act, 1986 with MOEFCC </li>
<li  style="list-style-type:decimal;"> Performance evaluation of EPA 1986 recognized laboratories through Analytical Quality Control (AQC) Exercise </li>
 </ol> </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
              <ol>
     <li style="list-style-type:decimal;">Sh. B K Jena, Scientist \'D\'</li>
<li style="list-style-type:decimal;">Dr. Yogita Kharayat, Scientist \'D\'</li> 
 <li style="list-style-type:decimal;">Sh. Rahul Kumar, Scientist \'B\'</li> 
<li style="list-style-type:decimal;"> Sh. Maneesh N, SSA</li>
  <li style="list-style-type:decimal;">Sh. Nirmal Kumar Sharma, SSA </li>
  <li style="list-style-type:decimal;">Smt. Prachi, SSA </li>
<li style="list-style-type:decimal;">Sh. Ashutosh Kumar Bharti, TS </li>
<li style="list-style-type:decimal;">Ms. Omyanka Shree, SLA </li>
<li style="list-style-type:decimal;">Sh. Sujit Kumar Chourasia, JLA </li>
  <li style="list-style-type:decimal;"> Sh. Jatin Kasliwal, JLA</li>  
  <li style="list-style-type:decimal;"> Sh. Sunil Kumar, Sr. MTS</li>
<li style="list-style-type:decimal;"> Sh. Md. Azaz, MTS</li> 
<li style="list-style-type:decimal;"> Sh. Vijay Adhikari, FA</li>   
 </ol> </span>
                                                        </h5></div>';
        $int_modal_hi ='<div><h3><strong>उपकरण प्रयोगशाला</strong></h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्रीमती नमिता मिश्रा, (वैज्ञानिक ‘ई’)</span> </h5>
      <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102367</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> namita.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
       														 <h5>यह प्रभाग निम्नलिखित कार्यकलापों को देखता है:  </h5>
                                                           <ol style="list-style-type:decimal;">
                                                               <li  style="list-style-type:decimal;">1.	भारी धातुएं</li>
                                                               <li  style="list-style-type:decimal;">2.	परिवेशी पार्टिकुलेट सामग्री में तत्व और आयन</li>			
                                                        </ol> </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                                     <li style="list-style-type:decimal;">श्री. बी. के. जेना, (वैज्ञानिक ‘डी\')</li>
										<li style="list-style-type:decimal;">डॉ. योगिता खरायत, (वैज्ञानिक ‘डी\')</li>
									<li style="list-style-type:decimal;">श्री राहुल कुमार, (वैज्ञानिक ‘बी\')</li> 
<li style="list-style-type:decimal;"> श्री  मनीष एन, एस एस ए</li>
  <li style="list-style-type:decimal;">श्री Nirmal Kumar Sharma, SSA </li>
  <li style="list-style-type:decimal;">Smt. Prachi, SSA </li>
<li style="list-style-type:decimal;">Sh. Ashutosh Kumar Bharti, TS </li>
<li style="list-style-type:decimal;">Ms. Omyanka Shree, SLA </li>
<li style="list-style-type:decimal;">Sh. Sujit Kumar Chourasia, JLA </li>
  <li style="list-style-type:decimal;"> Sh. Jatin Kasliwal, JLA</li>  
  <li style="list-style-type:decimal;"> Sh. Sunil Kumar, Sr. MTS</li>
<li style="list-style-type:decimal;"> Sh. Md. Azaz, MTS</li> 
<li style="list-style-type:decimal;"> Sh. Vijay Adhikari, FA</li>
  </ol> </span>
                                                        </h5></div>';

        $tol_modal ='<div><h3><strong>Bio Lab</strong></h3>
	<div style="overflow-y:Scroll;height:300px">
  <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong><span style="float: right;width: 70%; text-align:left;">Sh. Sanjay Kumar (Scientist \'E\')</span></h5>
      <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102361</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> skumar.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This division deals  following Activities : </h5>
     <ol style="list-style-type:decimal;">
                                                              
   <li  style="list-style-type:decimal;">Upgradation of biomonitoring, Microbiology and toxicity lab at state boards and their zonal labs along the river Ganga.</li>
	  <li  style="list-style-type:decimal;">Microbiology</li>
 <li  style="list-style-type:decimal;">Toxicity</li>
    <li  style="list-style-type:decimal;">Bio-monitoring  (to be reported to Incharge WQM-I)</li>
   <li  style="list-style-type:decimal;">Inter-state Yamuna Monitoring  (Supreme Court Matter )  </li><li  style="list-style-type:decimal;">Activities for evaluation of microbiological pathogenic population from gangotri to varanasi in under progress.</li>
	 <li  style="list-style-type:decimal;">Shortlisting of labs for enumeration of pathogens is under progress.</li></ol>
        </span>
        </h5>
			<div class="clear"></div>
        <h5 style="color:#0b73b9;"><strong>Personnel : </strong>        
         <span style="float: right; width: 70%;text-align:left;">
         <ol>
              <li style="list-style-type:decimal;">Sh. Yashpal Yadav, Scientist \'C\'</li>
              <li style="list-style-type:decimal;">Smt. V Himajwala, Scientist \'C\'</li>
                      <!--li style="list-style-type:decimal;">Sh. Anand B Salve, SSA</li--></ol> </span>
          </h5></div>';
        $tol_modal_hi ='<div><h3><strong>जैविक प्रयोगशाला</strong></h3>
	<div style="overflow-y:Scroll;height:300px">
 <h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्री संजय कुमार (वैज्ञानिक ‘ई’)</span> </h5>
      <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102376</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> skumar.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह प्रभाग निम्नलिखित कार्यकलापों को देखता है: </h5>
                                                           <ol style="list-style-type:decimal;">
                                                              
     <li  style="list-style-type:decimal;">	गंगा नदी के साथ-साथ राज्य बोर्डों और उनकी क्षेत्रीय प्रयोगशालाओं में जैव-निगरानी, सूक्ष्म-जीवविज्ञान और विषाक्तता प्रयोगशाला का उन्नयन</li>
	  <li  style="list-style-type:decimal;">	सूक्ष्म-जीवविज्ञान</li>
 <li  style="list-style-type:decimal;">विषाक्तता</li>
  <li  style="list-style-type:decimal;">जैव-निगरानी (डब्ल्यूक्यूएम-I के प्रभारी को रिपोर्ट किया जाएगा)</li>
  <li  style="list-style-type:decimal;">अंतर्राज्यीय यमुना निगरानी (उच्चतम न्यायालय का मामला एक्यूएफएमवाई)</li>															    <li  style="list-style-type:decimal;">गंगोत्री से वाराणसी तक सूक्ष्म जीवविज्ञान संबंधी रोगाणुओं की जनसंख्या के मूल्यांकन संबंधी कार्यकलाप चलाए जा रहे हैं</li>
	<li  style="list-style-type:decimal;">	रोगाणुओं की गणना हेतु प्रयोगशालाओं की संक्षिप्त सूची बनाने का कार्य चल रहा है।</li>
			</ol>
        </span>
        </h5>
			<div class="clear"></div>
        <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>        
         <span style="float: right; width: 70%;text-align:left;">
            <ol>
			 <li style="list-style-type:decimal;">श्री. यशपाल यादव, (वैज्ञानिक \'सी\')</li>
            <li style="list-style-type:decimal;">श्रीमती. वी. हिमाज्वाला, (वैज्ञानिक \'सी\')</li>
	  </ol> </span>
        </h5></div>';

        $wtr_modal ='<div> <h3>R & D Division</h3>
	<div style="overflow-y:Scroll;height:300px">
 <h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Sh. Anil Ranveer, Sc. \'F\'</span> </h5>
     <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-43102410, 22304812</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> anilranveer.cpcb@gov.in</span></h5></div>';
        $wtr_modal_hi ='<div> <h3>आर एण्ड डी प्रभाग </h3>
	<div style="overflow-y:Scroll;height:300px">
 <h5 style="color:#0b73b9;"><strong>प्रभाग प्रमुख  : </strong> <span style="float: right;width: 70%; text-align:left;">श्री अनिल रणवीर, वैज्ञानिक \'एफ\'</span> </h5>
     <h5 style="color:#0b73b9;"><strong>फोन नं  : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-43102410</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई मेल  : 	</strong> <span style="float: right;width: 70%; text-align:left;"> anilranveer.cpcb@gov.in</span></h5></div>';

        $bio_modal ='<div>    <h3>Law Division</h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>Divisional Head : </strong> <span style="float: right;width: 70%; text-align:left;">Smt. Youthika Puri(Scientist \'E\')</span> </h5>
     <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011-43102444</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> youthika.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This division deals  following Activities : </h5>
                                                           <ol style="list-style-type:decimal;">
  <li  style="list-style-type:decimal;">All  Court Cases of Supreme Court, High Courts, NGT, CAT & District Courts</li>	 </ol> </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong> 
		  <span style="float: right; width: 70%;text-align:left;">
            <ol>
			 <li  style="list-style-type:decimal;">Sh. Avnish N Tripathi, Sr. LO</li>
                <li  style="list-style-type:decimal;">Smt. Urmila Thakur, LO</li>
              <li style="list-style-type:decimal;">Sh. Kamal Bandhu, ALO</li>
              <li style="list-style-type:decimal;">Sh. Krishan Kumar Gupta, ALO</li>
			  <li style="list-style-type:decimal;">Sunil Kumar Sharma, ALO</li>
			 <li style="list-style-type:decimal;">	Rahul Rajput, ALO	</li>
 <li style="list-style-type:decimal;">	Rishabh Singh, ALO</li>
 <li style="list-style-type:decimal;"> Narendra Gurjar, UDC	</li>			  
               </ol> </span>
      </h5></div>';
        $bio_modal_hi ='<div><h3>विधि प्रभाग</h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>विभागीय प्रधान : </strong> <span style="float: right;width: 70%; text-align:left;">श्री प्रसून गार्गव, वैज्ञानिक \'एफ\'</span> </h5>
     <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 011- 43102444</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> prasoon.cpcb@nic.in</span></h5>
        <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह प्रभाग निम्नलिखित कार्यकलापों को देखता है: </h5>
                                                           <ol style="list-style-type:decimal;">
                                                          <li  style="list-style-type:decimal;">सभी न्यायालय मामले </li>			
                                                        </ol> </span>
                                                        </h5>
						<div class="clear"></div>
			
			<h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>        
         <span style="float: right; width: 70%;text-align:left;">
            <ol>
			  <li style="list-style-type:decimal;">श्री अवनीश नाथ त्रिपाठी, वरिष्ठ विधि अधिकारी</li>
			 <li  style="list-style-type:decimal;">श्रीमती उर्मिला ठाकुर, विधि अधिकारी</li>
              <li style="list-style-type:decimal;">Sh. Kamal Bandhu, ALO</li>
              <li style="list-style-type:decimal;">Sh. Krishan Kumar Gupta, ALO</li>
           <li style="list-style-type:decimal;">Sunil Kumar Sharma, ALO</li>
			 <li style="list-style-type:decimal;">	Rahul Rajput, ALO	</li>
 <li style="list-style-type:decimal;"> Narendra Gurjar, UDC	</li>
              
          </ol> </span>
          </h5></div>';

        $build_modal ='<div><h3>Regional Directorate Lucknow </h3>
    <div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong>Regional Director: </strong><span style="float: right;width: 70%; text-align:left;">Smt. Divya Sinha, Scientist \'F\'</span></h5>
 <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 0522-4087601, 2721915</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;">rdlucknow.cpcb@gov.in, divyasinha.cpcb@nic.in</span></h5>
      <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This Office  deals  following Activities  : </h5>
                    <ol style="list-style-type:decimal;">
  <li  style="list-style-type:decimal;">Co-ordination with SPCBs and PCC</li>
  <li  style="list-style-type:decimal;">Pollution Assessment, Survey &amp; Monitoring</li>
   <li  style="list-style-type:decimal;">Pollution Control Enforcement</li>
   <li  style="list-style-type:decimal;">Waste Management</li>
   <li  style="list-style-type:decimal;">O & M of Laboratory</li>
 <li  style="list-style-type:decimal;">Data Base Management</li>
  <li  style="list-style-type:decimal;">Training &amp; Mass Awareness Activities</li>			
   <li  style="list-style-type:decimal;">R & D Activities</li> </ol> </span>
        </h5>
		<div class="clear">***</div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
        		   <li style="list-style-type:decimal;">Sh. Kamal Kumar, Scientist \'E\'</li>
    <!--li  style="list-style-type:decimal;">Sh. J. P. Meena , Scientist \'D\'</li>
    <li  style="list-style-type:decimal;">Dr. H. P. S. Rathore , Scientist \'D\'</li-->
	 <li  style="list-style-type:decimal;">Sh. Sandeep Roy, Scientist \'D\'</li>
  <li  style="list-style-type:decimal;">Ms. Anamika Singh  , Scientist \'C\'</li>
 <li  style="list-style-type:decimal;">Sh. Arvind Kumar, Scientist \'C\'</li>
  <li  style="list-style-type:decimal;">Sh. Karnveer Singh, Scientist \'B\'</li>
 <!--li  style="list-style-type:decimal;">Sh. C S Meena, STS</li-->
  <li  style="list-style-type:decimal;">Sh. Muhammad Fisal, SSA</li>
  <li  style="list-style-type:decimal;">Sh. R K Mishra, SSA</li>
    <li  style="list-style-type:decimal;">Sh. R K Saxena, SSA</li>
        <li  style="list-style-type:decimal;">Sh. B M Singh, TS</li>  
          <li  style="list-style-type:decimal;"> Sh. Jivender Kumar Saini, AACO</li> 
          <!--li  style="list-style-type:decimal;">Smt. Manju Srivastava, SLA</li>
            <li  style="list-style-type:decimal;">Sh. Shahid Khan, SLA</li-->
              <li  style="list-style-type:decimal;">Sh. Rajesh Prasad Shakya, SLA</li>
			  <li  style="list-style-type:decimal;">Sh. Abhishek Mishra, JT</li>
			 <li  style="list-style-type:decimal;"> Ms. Vishakha Singh 	JLA </li>
                <!--li  style="list-style-type:decimal;">Sh. Shreeram Shah, Asst.</li-->
                  <li  style="list-style-type:decimal;">Sh. Vinay K Agarwal, LDC</li>
                    <li  style="list-style-type:decimal;">Smt. Ranjana Saxena, LDC</li> 
					  <li  style="list-style-type:decimal;">Sh. Ashok Shukla, LDC</li> 
                      <li  style="list-style-type:decimal;">Shri Ajeet yadav, Driver</li>
					  <li  style="list-style-type:decimal;">Sh. Kunwar Singh, FA</li>
                      <li  style="list-style-type:decimal;">Sh. Jwala Prasad, Attendent</li>
                        <li  style="list-style-type:decimal;">Sh. R K Pandey, Attendent</li>
                          <li  style="list-style-type:decimal;">Sh. Prakash Pant, Attendent</li>
                          </ol>                              </span>
                                                        </h5></div>';
        $build_modal_hi ='<div><h3>क्षेत्रीय निदेशालय लखनऊ </h3>
    <div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong>क्षेत्रीय निदेशक: </strong><span style="float: right;width: 70%; text-align:left;">श्री कमल कुमार, (वैज्ञानिक \'ई\')</span></h5>
      <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 0522-4087601,
2721915</span></h5>
      <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> rdlucknow.cpcb@gov.in, kamalkumar.cpcb@nic.in</span></h5>
      <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह कार्यालय निम्नलिखित कार्यकलापों को देखता है: </h5>
                                                           <ol style="list-style-type:decimal;">
                                                               <li  style="list-style-type:decimal;">	एसपीसीबी और पीसीसी के साथ समन्वय</li>
                                                               <li  style="list-style-type:decimal;">	प्रदूषण निर्धारण, सर्वेक्षण और निगरानी</li>
                                                               <li  style="list-style-type:decimal;">	प्रदूषण नियंत्रण प्रवर्तन</li>
                                                               <li  style="list-style-type:decimal;">	खतरनाक अपशिष्ट प्रबंधन</li>
                                                               <li  style="list-style-type:decimal;">	जैव-चिकित्सा अपशिष्ट प्रबंधन</li>
                                                               <li  style="list-style-type:decimal;">	प्रयोगशाला प्रबंधन और परीक्षण</li>
                                                               <li  style="list-style-type:decimal;">	डाटा बेस का प्रबंधन</li>
                                                               <li  style="list-style-type:decimal;">प्रशिक्षण और जन जागरूकता कार्यकलाप</li>			
                                                        </ol> </span>
        </h5>
		<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol> 
   <li  style="list-style-type:decimal;">श्री संदीप राय, (वैज्ञानिक ‘डी’)</li>                                    
 <!--li  style="list-style-type:decimal;">श्री जे. पी. मीणा, (वैज्ञानिक ‘डी’)</li-->
   <li  style="list-style-type:decimal;">सुश्री अनामिका सिंह, (वैज्ञानिक ‘सी ’)</li>
 <!--li style="list-style-type:decimal;">श्री ए के त्रिपाठी, (वैज्ञानिक ‘बी’)</li-->
 <li  style="list-style-type:decimal;">Sh. Arvind Kumar, Scientist \'C\'</li>
  <li  style="list-style-type:decimal;">Sh. Karnveer Singh, Scientist \'B\'</li>
 <!--li  style="list-style-type:decimal;">Sh. C S Meena, STS</li-->
  <li  style="list-style-type:decimal;">Sh. Muhammad Fisal, SSA</li>
  <li  style="list-style-type:decimal;">Sh. R K Mishra, SSA</li>
    <li  style="list-style-type:decimal;">Sh. R K Saxena, SSA</li>
        <li  style="list-style-type:decimal;">Sh. B M Singh, TS</li>  
          <li  style="list-style-type:decimal;"> Sh. Jivender Kumar Saini, AACO</li> 
          <!--li  style="list-style-type:decimal;">Smt. Manju Srivastava, SLA</li>
            <li  style="list-style-type:decimal;">Sh. Shahid Khan, SLA</li-->
              <li  style="list-style-type:decimal;">Sh. Rajesh Prasad Shakya, SLA</li>
			  <li  style="list-style-type:decimal;">Sh. Abhishek Mishra, JT</li>
			 <li  style="list-style-type:decimal;"> Ms. Vishakha Singh 	JLA </li>
                <!--li  style="list-style-type:decimal;">Sh. Shreeram Shah, Asst.</li-->
                  <li  style="list-style-type:decimal;">Sh. Vinay K Agarwal, LDC</li>
                    <li  style="list-style-type:decimal;">Smt. Ranjana Saxena, LDC</li> 
					  <li  style="list-style-type:decimal;">Sh. Ashok Shukla, LDC</li> 
                      <li  style="list-style-type:decimal;">Shri Ajeet yadav, Driver</li>
					  <li  style="list-style-type:decimal;">Sh. Kunwar Singh, FA</li>
                      <li  style="list-style-type:decimal;">Sh. Jwala Prasad, Attendent</li>
                        <li  style="list-style-type:decimal;">Sh. R K Pandey, Attendent</li>
                          <li  style="list-style-type:decimal;">Sh. Prakash Pant, Attendent</li>
   </ol>  </span>
                                                        </h5></div>';

        $lib_modal ='<div><h3>Project Office Agra  </h3>
    <div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong> Regional Director: </strong> <span style="float: right;width: 70%; text-align:left;"> Sh. Ankur Tiwari (Scientist \'E\')</span></h5>
      <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 0562-4005877, 2421568</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> poagra.cpcb@nic.in, ankur.cpcb@nic.in</span></h5>
      <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
     <h5>This Office  deals  following Activities  : </h5>
      <ol style="list-style-type:decimal;">
      <li  style="list-style-type:decimal;">Ambient air quality monitoring at 04 locations of Agra as per direction of Hon’ble Supreme Court of India and Execution of other projects/ directions of HO/RD(N)</li>
     <li  style="list-style-type:decimal;">Co-ordination with Local SPCB’s (UP and Rajasthan), Taj Trapezium Zone Pollution (Prevention and Control) Authority, IIT, NEERI, ASI, ANN, ADA and other Govt. departments working in Agra/TTZ area.</li>
     <li  style="list-style-type:decimal;">Inspection & monitoring of common environmental infrastructure facilities (CETP, STPs, MSWTF, BMWTF), River, Drains, Groundwater, E-waste, Noise & special Dipawali monitoring and NAMP stations in TTZ area</li>
   <li  style="list-style-type:decimal;">Strengthening, operation and maintenance of laboratory and Data Base Management</li>
   <li  style="list-style-type:decimal;">Inspections, Survey & monitoring as per direction of Courts and VIP references/ Public complaints and deal with matters pertaining to parliament and parliamentary standing committee.</li>
  <li  style="list-style-type:decimal;">Pollution Control Enforcementin Co-ordination with Govt. departments working in Agra/TTZ area.</li>
 <li  style="list-style-type:decimal;">Assessment of the progress on implementation of Air Quality Management plans formulated by concerned SPCBs for non-attainment cities in TTZ i.e. Agra and Firozabad and inspection. </li>
   <li  style="list-style-type:decimal;">Review of implementation of status of action plan of critically polluted/CEPI areas.</li>
 <li  style="list-style-type:decimal;">Compilation of information and making it available under Right to Information (RTI) Act. </li>	
 <li  style="list-style-type:decimal;">Implementation of Rajbhasa Hindi policy as per HO/RD (N) Lucknow directions.</li>	
 <li  style="list-style-type:decimal;">Training to students, State board technical staff </li>	
 <li  style="list-style-type:decimal;">Mass Awareness Activities in Agra/TTZ.</li>	 
                                                        </ol> </span>
        </h5>
		<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
             <li style="list-style-type:decimal;">Vipul Kumar Singh, Scientist \'C\'</li>
          <li style="list-style-type:decimal;">Adarsh Kumar, SSA</li>
               <li style="list-style-type:decimal;">Desh Samrat Gautam, TS</li>
                    <li style="list-style-type:decimal;">Himmat Singh, SLA</li>
				   <li style="list-style-type:decimal;">Manoj Kumar Yadv, JLA</li>	
                         <li style="list-style-type:decimal;">Kali Charan, FA</li>	     </ol> </span>
      </h5></div>';
        $lib_modal_hi ='<div><h3>परियोजना कार्यालय आगरा  </h3>
    <div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong> नोडल अधिकारी: </strong> <span style="float: right;width: 70%; text-align:left;"> 	श्री अंकुर तिवारी, (वैज्ञानिक \'डी\')</span></h5>
      <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 0562-4005877, 2421568</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> poagra.cpcb@nic.in, ankur.cpcb@nic.in</span></h5>
      <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
       														 <h5>यह कार्यालय निम्नलिखित कार्यकलापों को देखता है: </h5>
                                                           <ol style="list-style-type:decimal;">
<li  style="list-style-type:decimal;">माननीय सर्वोच्च न्यायालय के निर्देशानुसार आगरा शहर स्थित 04 स्थानकों पर परिवेशीय वायु गुणवत्ता प्रबोधन तथा अन्य परियोजनाओं एवं मुख्यालय दिल्ली / क्षेत्रीय निदेशालय (उ), लखनऊ के निर्देशों का कार्यान्वयन।</li>
<li  style="list-style-type:decimal;">टी॰टी॰जेड॰ क्षेत्र / आगरा शहर में कार्यरत संस्थाओं जैसे: ए॰एस॰आई॰, नगर निगम तथा विकास प्राधिकरण, आगरा, आई॰आई॰टी॰, नीरी, टी॰टी॰जेड॰ प्राधिकरण, उ॰प्र॰ प्रदूषण नियंत्रण बोर्ड एवं राजस्थान प्रदूषण नियंत्रण मंडल के साथ समन्वयन।</li>
<li  style="list-style-type:decimal;">पर्यावरण संरक्षण की सार्वजनिक सुविधाओं जैसे सी॰ई॰टी॰पी॰, एस॰टी॰पी॰, एम॰एस॰डब्ल्यू॰टी॰एफ॰, बी॰एम॰डब्ल्यू॰टी॰एफ॰ तथा नदी, नाले-नालियों, भूगर्भीय जल, ई-वेस्ट, एन॰ए॰एम॰पी॰ स्थानकों का निरीक्षण, ध्वनि एवं विशेष दीपावली प्रबोधन।</li>
<li  style="list-style-type:decimal;">प्रयोगशाला सुदृढ़ता, संचालन एवं रखरखाव तथा आँकड़ों का प्रबंधन।</li>
<li  style="list-style-type:decimal;">न्यायालय निर्देश, वी॰आई॰पी॰ संदर्भ / जन-शिकायत के अनुसार निरीक्षण, सर्वेक्षण तथा प्रबोधन। संसदीय एवं संसदीय स्थायी समिति संबंधी मुद्दों पर कार्यवाही।</li>
<li  style="list-style-type:decimal;">टी॰टी॰जेड॰ क्षेत्र / आगरा शहर में कार्यरत सरकारी विभागों के समन्वय द्वारा प्रदूषण नियंत्रण हेतु प्रवर्तन कार्यवाही।</li>
<li  style="list-style-type:decimal;">टी॰टी॰जेड॰ क्षेत्र स्थित नॉन-अट्टेंमेंट शहर आगरा तथा फ़िरोज़ाबाद में संबंधित राज्य प्रदूषण नियंत्रण बोर्ड द्वारा निर्मित वायु गुणवत्ता प्रबंधन योजना लागू किए जाने की प्रगति का मूल्यांकन एवं समय समय पर निरीक्षण।</li>
<li  style="list-style-type:decimal;">गंभीर रूप से प्रदूषित / सी॰ई॰पी॰आई॰ क्षेत्रों हेतु लागू कार्ययोजना की समीक्षा।</li>
<li  style="list-style-type:decimal;">सूचना का अधिकार अधिनियम के अंतर्गत वांछित जानकारी का संकलन एवं उपलब्ध कराना।</li>
<li  style="list-style-type:decimal;">मुख्यालय दिल्ली / क्षेत्रीय निदेशालय (उ), लखनऊ के निर्देशानुसार राजभाषा हिन्दी नीति का कार्यान्वयन।</li>
<li  style="list-style-type:decimal;">विध्यार्थियों तथा राज्य प्रदूषण नियंत्रण बोर्ड के तकनीकी स्टाफ को प्रशिक्षण।</li>
<li  style="list-style-type:decimal;">टी॰टी॰जेड॰ क्षेत्र / आगरा शहर में जन-जागरूकता गतिविधियाँ।</li> 		
                                                        </ol> </span>
        </h5>
		<div class="clear"></div>
		
		 <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
   <li style="list-style-type:decimal;">विपुल कुमार सिंह, वैज्ञानिक \'सी\'</li>
 <li style="list-style-type:decimal;">आदर्श कुमार, एस एस ए</li>
<li style="list-style-type:decimal;">देश सम्राट गौतम, टी  एस</li>
<li style="list-style-type:decimal;">हिम्मत सिंह, एस एल ए</li>
<li style="list-style-type:decimal;">मनोज कुमार यादव, जे एल ए</li>
<li style="list-style-type:decimal;">काली चरण, एफ ए</li>

															
                                                        </ol> 
                                                        </span>
                                                        </h5></div>';

        $accounts_modal ='<div><h3>Regional Directorate Kolkata </h3>
	<div style="overflow-y:Scroll;height:300px">
<h5 style="color:#0b73b9;"><strong>Regional Director: </strong><span style="float: right;width: 70%; text-align:left;"> Sh. M.K. Biswas(Scientist \'E\')</span> </h5>
    <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 033-24416634</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> mkbiswas.cpcb@nic.in</span></h5> <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  This Office  deals  following Activities  : 
      <ol style="list-style-type:decimal;">
    <li  style="list-style-type:decimal;">Inspection and monitoring as per direction Hon’ble High Court/ NGT.</li> 
     <li  style="list-style-type:decimal;">Pollution Control Enforcement.</li> 	
   <li  style="list-style-type:decimal;">Inspection and Monitoring of STP/rivers/drains and industries under National Ganga River Basin Authority. (NGRBA)</li> 	
  <li  style="list-style-type:decimal;">Inspection & Monitoring with reference to VIP/ Public complaints.</li> 	
   <li  style="list-style-type:decimal;">Inspection & monitoring of facilities (CETP, STPs, TSDF, CBMWTF).</li> 	
   <li  style="list-style-type:decimal;">Surveillance of National Air & Water Quality monitoring stations.</li> 	
  <li  style="list-style-type:decimal;">Monitoring of HW co-processing activities.</li> 	
  <li  style="list-style-type:decimal;">Compilation of information and making it available under ‘Right to Information (RTI) Act’</li>
  <li  style="list-style-type:decimal;">Co-ordination with SPCBs & PCCs</li>
  <li  style="list-style-type:decimal;">Strengthening, operation and maintenance of laboratory.</li>
 <li  style="list-style-type:decimal;">To deal with matters pertaining to parliament and parliamentary standing committee</li>
   <li  style="list-style-type:decimal;">Implementation of OHSMSC and NABL Accreditation</li>
   <li  style="list-style-type:decimal;">Review of implementation of status of action plan of critically polluted/CEPI areas.</li>
   <li  style="list-style-type:decimal;">Inter-state river monitoring.</li>
   <li  style="list-style-type:decimal;">Air & Noise monitoring during Deepawali Festival.</li> </ol> </span>
                                                        </h5>
                                          
		<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
           <ol>
         		  <li style="list-style-type:decimal;">Sh. A K Tripathi, Scientist \'C\'</li>
	    <li  style="list-style-type:decimal;">Sh. A K Naskar, Scientist \'C\'</li>
  <li  style="list-style-type:decimal;">Sh. Tofic Aslam, Scientist \'C\'</li> 
   <li  style="list-style-type:decimal;">Smt. Tinneti Sudha, AACO</li>
   <li  style="list-style-type:decimal;">Sh. Md. A Rafique, SSA</li>
   <li  style="list-style-type:decimal;">Sh. Sukhendu Biswas, SSA</li>
   <li  style="list-style-type:decimal;">Sh. A Kumar, SSA</li>
   <li  style="list-style-type:decimal;">Sh. A K Dubey, SSA</li>
    <li  style="list-style-type:decimal;">Sh. Animesh Mondal, SSA</li>
   <li  style="list-style-type:decimal;">Sh. A K Varshney, AACO</li>
   <li  style="list-style-type:decimal;">Sh. Chippada Jagadeesh Kumar, JT</li>
   <li  style="list-style-type:decimal;">Sh. Suman Bhattacharya, Asst.</li>
     <li  style="list-style-type:decimal;">Smt. Minati Bagchi, SLA</li>
   <li  style="list-style-type:decimal;">Smt. Pratima Saha, SLA</li>
   <li  style="list-style-type:decimal;">Sh. D Sen Gupta, SLA</li>
   <li  style="list-style-type:decimal;">Sh. S N Bhattacharya, SLA</li>
   <li  style="list-style-type:decimal;">Sh. A N Thakur, SLA</li>
   <li  style="list-style-type:decimal;">Sh. Tapan K Mukhopadhyay, SLA</li>
   <li  style="list-style-type:decimal;">Sh. Debdeep Koner, JLA</li>
   <li  style="list-style-type:decimal;">Sh. Bodhisatva Mondal, JLA</li>
       <li  style="list-style-type:decimal;">Smt. Chandrani Bhattacharya, DEO</li>
       <li  style="list-style-type:decimal;">Sh. Syam, MTS</li>
       </ol>
                                                        </span>
                                                        </h5></div>';
        $accounts_modal_hi ='<div><h3>क्षेत्रीय निदेशालय कोलकाता </h3>
	<div style="overflow-y:Scroll;height:300px">
 <h5 style="color:#0b73b9;"><strong>क्षेत्रीय निदेशक: </strong><span style="float: right;width: 70%; text-align:left;">श्री एम.के. बिस्वास, (वैज्ञानिक ‘ई’)</span> </h5>
    <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 033-24416634</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> mkbiswas.cpcb@nic.in, rdkolkata.cpcb@gov.in</span></h5>
     <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		 यह कार्यालय निम्नलिखित कार्यकलापों को देखता है:
 <ol style="list-style-type:decimal;">
   <li  style="list-style-type:decimal;">	माननीय उच्च न्यायालय/राष्ट्रीय हरित अधिकरण (एनजीटी) के निर्देशानुसार निरीक्षण और निगरानी</li> 
    <li  style="list-style-type:decimal;">प्रदूषण नियंत्रण प्रवर्तन</li> 	
   <li  style="list-style-type:decimal;">	राष्ट्रीय गंगा नदी घाटी प्राधिकरण के अधीन एसटीपी/नदियों/नालों और उद्योगों का निरीक्षण और निगरानी (एनजीआरबीए)</li> 	
  <li  style="list-style-type:decimal;">	वीआईपी/ जन शिकायतों के संदर्भ में निरीक्षण और निगरानी</li> 	
 <li  style="list-style-type:decimal;">	सुविधाओं का निरीक्षण और निगरानी (सीईटीपी, एसटीपी, टी(वैज्ञानिक ‘डी’)एफ, सीबीएमडब्ल्यूटीएफ)।</li> 	
  <li  style="list-style-type:decimal;">	राष्ट्रीय वायु एवं जल गुणवत्ता निगरानी स्टेशनों की चौकसी</li> 	
   <li  style="list-style-type:decimal;">	एचडब्ल्यू सह-प्रसंस्करण कार्यकलापों की निगरानी</li> 	
  <li  style="list-style-type:decimal;">	सूचना का संकलन करना और उसे \'सूचना का अधिकार (आरटीआई) अधिनियम\' के तहत उपलब्ध कराना</li>
   <li  style="list-style-type:decimal;">	एसपीसीबी और पीसीसी के साथ समन्वय</li>
   <li  style="list-style-type:decimal;">	प्रयोगशाला का सुदृढ़ीकरण, परिचालन और रखरखाव।</li>
  <li  style="list-style-type:decimal;">	संसद और संसदीय स्थायी समिति से संबंधित मामले देखना</li>
  <li  style="list-style-type:decimal;">	ओएचएसएम(वैज्ञानिक ‘सी’) और एनएबीएल प्रत्यायन का क्रियान्वयन</li>
   <li  style="list-style-type:decimal;">	नाजुक रूप से प्रदूषित/सीईपीआई क्षेत्रों की कार्य योजना के क्रियान्वयन की स्थिति की समीक्षा करना।</li>
   <li  style="list-style-type:decimal;">अंतर्राज्यीय नदी निगरानी।</li>
   <li  style="list-style-type:decimal;">	दीपावली के त्योहार के दौरान वायु और शोर की निगरानी।</li></ol> </span>
                                                        </h5>
                                                        
                <div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
					<li style="list-style-type:decimal;">सुश्री सुस्मिता एक्का, (वैज्ञानिक ‘ई’)</li>
					  <li  style="list-style-type:decimal;">	श्री तौफीक असलम, (वैज्ञानिक ‘सी’)</li>
                   <li  style="list-style-type:decimal;">	श्री आशीष कुमार नस्कर, (वैज्ञानिक ‘बी’)</li>
				    <li  style="list-style-type:decimal;">	श्री देबब्रत दास, (वैज्ञानिक ‘बी’)</li>
                  </ol> 
                                                        </span>
                                                        </h5></div>';

        $adminr_modal ='<div><h3>Regional Directorate Vadodara </h3>
	<div style="overflow-y:Scroll;height:300px">
	  <h5 style="color:#0b73b9;"><strong>Regional Director: </strong><span style="float: right;width: 70%; text-align:left;"> Dr. Arvind Kumar Jha (Scientist \'E\')</span></h5>
      <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 0265-2392603</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> arvindjha.cpcb@gov.in</span></h5>
	  <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This Office  deals  following Activities  : </h5>
            <ol style="list-style-type:decimal;">
          <li  style="list-style-type:decimal;">Pollution Assessment (Survey & Monitoring)</li>
           <li  style="list-style-type:decimal;">Pollution Control Enforcement</li>
          <li  style="list-style-type:decimal;">Scientific and Technical Activities and R & D</li>
          <li  style="list-style-type:decimal;">Conducting mass awareness programmes.</li>
          <li  style="list-style-type:decimal;">Development and maintenance of Library in office building.</li>
		  <li  style="list-style-type:decimal;">Operation and maintenance of IT infrastructure & data base management.</li>
 <li  style="list-style-type:decimal;">Maintenance of office cum laboratory building.</li>
                                                         </ol> </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
          <li  style="list-style-type:decimal;">Sh. S. Pradeep Raj, Scientist \'E\'</li>
     <li  style="list-style-type:decimal;">Smt. Kavita B.V, Scientist \'E\'</li>
        <li  style="list-style-type:decimal;">Dr. Nirpendra Semwal, Scientist \'D\'</li>
		<li  style="list-style-type:decimal;">Sh. Manoj Kumar Sharma, Scientist \'C\'</li>
            <li  style="list-style-type:decimal;">Sh. B D Pandey, Scientist \'B\'</li> 
			<li  style="list-style-type:decimal;">Sh. Madiv Kumar Sehara, Scientist \'B\'</li>
           <li  style="list-style-type:decimal;">Smt. Ashtha H Trivedi, SSA</li> 
           <li  style="list-style-type:decimal;">Sh. Dharmesh J Rana, SSA</li> 
		     <li  style="list-style-type:decimal;">Sh. Swapnil M Dudhwadkar, SSA</li>
           <li  style="list-style-type:decimal;">Smt. Ashma Kadiwala, SSA</li> 
    <li  style="list-style-type:decimal;">Sh. Gopidesi Srinu, Technical Supervisor</li>
    <li  style="list-style-type:decimal;">Sh. Ajeet Kumar Gupta, SLA</li>
       <li  style="list-style-type:decimal;">Sh. Mayank Nimbark, JLA</li> 
 <li  style="list-style-type:decimal;">Sh. Ajay Kharoliya, Junior Technician</li>
 <li  style="list-style-type:decimal;"> Smt. Priyanka Solanki, LDC</li>
                                                           </ol> 
                                                        </span>
                                                      </h5></div>';
        $adminr_modal_hi ='<div><h3>क्षेत्रीय निदेशालय वडोदरा </h3>
	<div style="overflow-y:Scroll;height:300px">
	  <h5 style="color:#0b73b9;"><strong>क्षेत्रीय निदेशक: </strong><span style="float: right;width: 70%; text-align:left;">अरविंद कुमार झा (वैज्ञानिक ‘ई’)</span></h5>
      <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 0265-2392603, 2392604</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> arvindjha.cpcb@gov.in</span></h5>
	  <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह कार्यालय निम्नलिखित कार्यकलापों को देखता है  : </h5>
   <ol style="list-style-type:decimal;">
    <li  style="list-style-type:decimal;">	प्रदूषण नियंत्रण प्रवर्तन।</li>
   <li  style="list-style-type:decimal;">	वीआईपी/ जन शिकायतों, न्यायालय मामलों के संदर्भ में निरीक्षण और निगरानी</li>
   <li  style="list-style-type:decimal;">	नाजुक रूप से प्रदूषित/सीईपीआई क्षेत्रों की कार्य योजना के क्रियान्वयन की स्थिति की समीक्षा करना।</li>
    <li  style="list-style-type:decimal;">	साझा पर्यावरण ढांचागत सुविधाओं का निरीक्षण और निगरानी (सीईटीपी, एसटीपी, सीएचडब्ल्यूआई, बीएमडब्ल्यूटीएफ)।</li>
    <li  style="list-style-type:decimal;">	अंतर्राज्यीय नदी निगरानी।</li>
  <li  style="list-style-type:decimal;">	राष्ट्रीय वायु एवं जल गुणवत्ता निगरानी स्टेशनों की चौकसी</li>
  <li  style="list-style-type:decimal;">	वार्षिक कार्य योजना के अनुसार अन्य परियोजनाओं का कार्यान्वयन।</li>
  <li  style="list-style-type:decimal;">	विभिन्न कार्यकलापों जैसे कि पर्यावरण संबंधी प्रयोगशालाओं की मान्यता, मानकों का विकास, पर्यावरण, वन और जलवायु परिवर्तन मंत्रालय द्वारा गठित विभिन्न विशेषज्ञ समितियों को जानकारियां देना, के तहत सीपीसीबी-एचओ तथा पर्यावरण, वन और जलवायु परिवर्तन मंत्रालय द्वारा वांछित निरीक्षण और निगरानी। </li>
  <li  style="list-style-type:decimal;">	पर्यावरण (संरक्षण) अधिनियम, 1986 के अधीन मान्यता हेतु प्रयोगशालाओं का निर्धारण।</li>
    <li  style="list-style-type:decimal;">	एचडब्ल्यू सह-प्रसंस्करण कार्यकलापों की निगरानी एवं अनुवर्ती कार्रवाई।</li>
    <li  style="list-style-type:decimal;">	\'सूचना का अधिकार (आरटीआई) अधिनियम\' के तहत सूचना का संकलन करना और उसे उपलब्ध कराना।</li>
    <li  style="list-style-type:decimal;">	एसपीसीबी और पीसीसी के साथ समन्वय</li>
 <li  style="list-style-type:decimal;">	प्रयोगशाला का सुदृढ़ीकरण, परिचालन और रखरखाव।</li>
  <li  style="list-style-type:decimal;">	जन जागरूकता कार्यक्रम तथा प्रशिक्षण कार्यक्रम चलाना</li>
   <li  style="list-style-type:decimal;">	संसद और संसदीय स्थायी समिति से संबंधित मामले देखना</li>			
                                                        </ol> </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
<li  style="list-style-type:decimal;">श्री एस. प्रदीप राज, (वैज्ञानिक ‘ई’)</li>
   <li  style="list-style-type:decimal;">श्रीमती  कविथा बी वी, (वैज्ञानिक ‘ई’)</li>
 <li  style="list-style-type:decimal;">	डॉ. नृपेंद्र सेमवाल, (वैज्ञानिक ‘डी’)</li>
<li  style="list-style-type:decimal;">श्री मनोज कुमार शर्मा, वैज्ञानिक ‘सी’</li>
<li  style="list-style-type:decimal;">श्री बी डी पांडे, वैज्ञानिक ‘बी’</li> 
<li  style="list-style-type:decimal;">श्री मादिव कुमार सेहारा, वैज्ञानिक ‘बी’</li>
 <li  style="list-style-type:decimal;">श्रीमती अशथा एच त्रिवेदी, एस एस ए</li> 
 <li  style="list-style-type:decimal;">श्री धर्मेश जे राणा, एस एस ए</li> 
<li  style="list-style-type:decimal;">श्री स्वपनिल एम दुधवादकर, एस एस ए</li>
<li  style="list-style-type:decimal;">श्रीमती  अशमा कडीवाला, एस एस ए</li> 
 <li  style="list-style-type:decimal;">श्री गोपीडेसी श्रीनु, तकनीकी पर्यवेक्षक</li>
 <li  style="list-style-type:decimal;">श्री अजीत कुमार गुप्ता, एस एल ए</li>
<li  style="list-style-type:decimal;">श्री मयंक निंबार्क, जे एल ए</li> 
 <li  style="list-style-type:decimal;">श्री अजय कुमार खरोलिया, कनिष्ठ तकनीशियन</li>
 <li  style="list-style-type:decimal;">श्रीमती  प्रियंका सोलंकी, एल डी सी</li>
                                                                                            </ol> 
                                                        </span>
                                                      </h5></div>';

        $adminp_modal ='<div><h3>Regional Directorate Bhopal </h3>
	<div style="overflow-y:Scroll;height:300px">
 <h5 style="color:#0b73b9;"><strong>Regional Director : </strong> <span style="float: right;width: 70%; text-align:left;"> Sh. A K Vidyarthi (Scientist \'F\')</span></h5>
 <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 0755-2775385</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;"> cpcb.bhopal@gov.in, vidyarthi.cpcb@nic.in </span></h5>
 
 <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This Office  deals  following Activities  : </h5>
                                                           <ol style="list-style-type:decimal;">
<li  style="list-style-type:decimal;">	Surveillance of Ambient Air Quality and Noise monitoring during Diwali Festival.</li> 
<li  style="list-style-type:decimal;">	Audit and Verification of NAMP & NWMP stations in the Central Region.</li> 
<li  style="list-style-type:decimal;">	Co-ordination of activities and review of action plans under NCAP, PRS, CEPI, Contaminated sites.</li> 
<li  style="list-style-type:decimal;">	Analysis of Air, Water and soil samples for the required parameters, as per the E(P)A requirement.</li> 
<li  style="list-style-type:decimal;">	Random monitoring of STPs and data compilation on status of Hazardous waste, MSW, Plastic Waste, E-waste, Biomedical waste, vehicular pollution and batteries in coordination with SPCBs in Central Zone</li> 
<li  style="list-style-type:decimal;">	To perform inspections under SMS alert, Public/ VIP complaints, Legal matters, Rule 9and other surprise inspections.</li> 
<li  style="list-style-type:decimal;">	Verification of Laboratories for recognition as Environmental Laboratory under E(P)A Act, 1986.</li> 
<li  style="list-style-type:decimal;">	Mass awareness activities on WED, Ozone Day, SUP Ban, Mission Life etc.</li> 
                                                        </ol> </span>
                                                        </h5>
                                                                                   
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
  <li style="list-style-type:decimal;">Sh. Sunil Kumar Meena, Scientist \'E\'</li>
     <li style="list-style-type:decimal;">Sh. Milind Kumar Nimje, Scientist \'C\'</li>
	  <li style="list-style-type:decimal;">	Dr. Ranu Chouksey Verma, Scientist \'C\'</li>
  <li style="list-style-type:decimal;">	Dr. Anoop Chaturvedi, Scientist \'C\'</li>
  <li style="list-style-type:decimal;">	Sh. Praveen Kumar Jain, Scientist \'C\'</li>
  <li style="list-style-type:decimal;">	Dr. Poulami C Patil, Scientist \'B\'</li>
  <li style="list-style-type:decimal;">	Sh. Sanjay Kumar Mukati, Scientist \'B\'</li>
    <li style="list-style-type:decimal;">Sh. Rupender Kumar, Scientist \'B\'</li>
	  <li style="list-style-type:decimal;">	Sh. Raj Deep Singh, Assistant Law Officer</li>
    <li style="list-style-type:decimal;">	Smt. Rashmi Thakur, SSA</li>
  <li style="list-style-type:decimal;">	Sh. Rameshwar Bandewar, SSA</li>
   <li style="list-style-type:decimal;">Sh. Sunil Kolhatkar, SSA</li>
     <li style="list-style-type:decimal;">Sh. Aarsh Tiwari, SSA</li>
  <li style="list-style-type:decimal;">	Sh. Rajeev Sharma, TS (CPE)</li>
   <li style="list-style-type:decimal;">Sh. Jagjeevan Ram, JLA</li>
  <li style="list-style-type:decimal;">	Smt. Alfa Monika Lakra, JLA</li>
   <li style="list-style-type:decimal;">Sh. Shiv Singh Kushwaha, JLA</li>
   <li style="list-style-type:decimal;">Dr. Sonal Wankhede, RA III</li>  
   <li style="list-style-type:decimal;">Sh. Sachin Kumar Sahu, SRF</li>
   <li style="list-style-type:decimal;">Ms.  Kiran Kanere, UDC</li> 
  <li style="list-style-type:decimal;">	Smt. Farzana khan, DEO</li>
   <li style="list-style-type:decimal;">Sh. Prahlad Baghel, LDC</li>
  <li style="list-style-type:decimal;">	Sh. Shiv Shankar Shukla, Hindi Typist</li>
  <li style="list-style-type:decimal;">	Sh. Salamuddin, Driver</li>
  <li style="list-style-type:decimal;">	Sh. Suresh Kumar Chouhan, Driver (CPE)</li>
  <li style="list-style-type:decimal;">	Sh. Surendra Kumar Bhatia, MTS</li>
  <li style="list-style-type:decimal;">	Sh. Suresh Choudhary, MTS (Ad-hoc)</li>
   <li style="list-style-type:decimal;">Sh. Amit Khedikar, MTS</li>
 
                                                        </ol> 
                                                        </span>

                                                        </h5></div>';
        $adminp_modal_hi ='<div><h3>क्षेत्रीय निदेशालय  बेंगलोरु </h3>
	<div style="overflow-y:Scroll;height:300px">
 <h5 style="color:#0b73b9;"><strong> क्षेत्रीय निदेशक: </strong> <span style="float: right;width: 70%; text-align:left;"> श्री जे सी बाबू (वैज्ञानिक ‘एफ’)</span></h5>
 <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 080-23233827(D)
23233739</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> jcb.cpcb@nic.in</span></h5>
 <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह कार्यालय निम्नलिखित कार्यकलापों को देखता है  : </h5>
                                                          <ol style="list-style-type:decimal;">
       <li  style="list-style-type:decimal;"> 	पर्यावरण प्रदूषण की रोकथाम और नियंत्रण, पर्यावरण संरक्षण संबंधी कार्यकलापों में क्षमता निर्माण और उनके कार्यान्वयन व प्रवर्तन से संबंधित मामलों पर छह एसपीसीबी (आंध्र प्रदेश, कर्नाटक, केरल, गोवा, तमिलनाडु और तेलंगाना) तथा दो पीसीसी (पुडुचेरी और लक्षद्वीप) के साथ समन्वय करना। </li> 
    <li  style="list-style-type:decimal;">	एनएएमपी और एनडब्ल्यूएमपी स्टेशनों के परिचालन की स्थिति तथा अंतरराज्यीय नदी जल गुणवत्ता की निगरानी</li> 	
  <li  style="list-style-type:decimal;">	सतत परिवेशी वायु गुणवत्ता निगरानी स्टेशनों (सीएएक्यूएमएस), ईएक्यूडीईएस, और ईएक्यूडीईएस से संबंधित मुद्दों का समन्वय</li> 	
 <li  style="list-style-type:decimal;">	एनजीटी और अन्य न्यायालय निर्देशों तथा वीआईपी संदर्भों/जन शिकायतों के अनुसार निरीक्षण</li> 	
 <li  style="list-style-type:decimal;">	ओसीईएमएस तथा अन्य निर्देशों के तहत उद्योगों का निरीक्षण एवं अनुवर्ती कार्रवाई</li> 	
   <li  style="list-style-type:decimal;">  	साझा सुविधाओं, एसटीपी, एमएसडब्ल्यूएम, सीईटीपी, टी(वैज्ञानिक ‘डी’)एफ, एचसीएफ और सीबीएमडब्ल्यूटीएफ के निरीक्षण और निगरानी तथा निर्देशों पर अनुवर्ती कार्रवाई</li> 	
  <li  style="list-style-type:decimal;">	ई-समीक्षा में उठाए गए मुद्दों पर प्रतिक्रिया तथा इंडिया ई-ट्रैक में जानकारी को अद्यतन करना</li> 	
	<li  style="list-style-type:decimal;"> 	पर्यावरण, वन और जलवायु परिवर्तन मंत्रालय के साथ नौ नाजुक रूप से प्रदूषित क्षेत्रों (मंगलुरू, भद्रावती, कोच्चि, पटनशेरु, विशाखापट्टनम, कुड्डालोर, वेल्लोर, मनाली और कोयंबटूर) और  अन्य अत्यधिक प्रदूषित क्षेत्रों की निगरानी</li> 
	<li  style="list-style-type:decimal;">	प्रयोगशाला सुदृढ़ीकरण और पीटी जांचों में सहभागिता</li>	
 <li  style="list-style-type:decimal;">  	कोडाईकनाल के दूषित स्थल पर (पारा प्रदूषण) उपचारात्मक उपायों के कार्यान्वयन में टीएनपीसीबी के साथ समन्वय</li> 	
 <li  style="list-style-type:decimal;">	बीपीसीएल द्वारा तोंदियारपेट दूषित साइट पर चलाए जाने वाले उपचारात्मक कार्यकलापों का पर्यवेक्षण और समय-समय पर सुधारात्मक उपाय उपलब्ध कराना</li> 	
 <li  style="list-style-type:decimal;">     	सीमेंट भट्टियों में खतरनाक कचरे का सह-प्रसंस्करण (परीक्षण अध्ययन) - निरीक्षण और निगरानी</li> 				
    </ol> </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
                              <li  style="list-style-type:decimal;">	श्री एस. जयपॉल, (वैज्ञानिक ‘डी’)</li>
                                 <li  style="list-style-type:decimal;">	श्रीमती टी. महिमा (वैज्ञानिक ‘डी’)</li>
								   <li  style="list-style-type:decimal;">डॉ. दीपेश. वी, (वैज्ञानिक ‘डी’)  </li>
                               <li  style="list-style-type:decimal;">	सुश्री पूर्णिमा बी. एम., (वैज्ञानिक ‘सी’)</li>
							  <li  style="list-style-type:decimal;">	श्री एस. कार्तिकेयन, (वैज्ञानिक ‘सी’)</li>
                              <li  style="list-style-type:decimal;">	डॉ. बी.एस. अनुपमा, (वैज्ञानिक ‘बी’)</li>
                                                           </ol> 
                                                        </span>
                                                        </h5></div>';

        $material_modal ='<div><h3>Regional Directorate Chennai </h3>
    <div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong>Regional DDDirector: </strong><span style="float: right;width: 70%; text-align:left;"> Smt. H. D. Varalaxmi (Scientist \'F\')</span></h5>
 <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 044 2999 8683(D), 044 2956 7019 (reception)</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;">laxmi.cpcb@nic.in , rdchennai.cpcb@gov.in </span></h5>
      <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This office  deals  following Activities  : </h5>
                    <ol style="list-style-type:decimal;">
<li  style="list-style-type:decimal;">Co-ordination with three SPCBs (Andhra Pradesh, Tamil Nadu & Telangana States) and one PPC (Puducherry UT) with regard to prevention and control of environmental pollution and, capacity building thereof for enforcement of various environmental Acts/Rules including six Waste Management Rules, 2016 in the South-eastern Region. </li>
<li  style="list-style-type:decimal;">Providing technical assistance and guidance to SPCBs/PCC to carry out surveillance, monitoring, laboratory and research activities relating to pollution control and abatement programs.</li>
<li  style="list-style-type:decimal;">	Audit and Verification of National Air Quality Monitoring Programme (NAMP) Stations & National Water Quality monitoring Programme (NWMP) Stations. Surveillance of Ambient Air Quality and Noise monitoring during Diwali Festival.</li>
<li  style="list-style-type:decimal;">	Co-ordination with SPCBs/PCC for issues relating to CAAQMS, EWQDES & EAQDES. </li>
<li  style="list-style-type:decimal;">	Co-ordination and review of action plans under NCAP, PRS, CEPI, Contaminated sites.</li>
<li  style="list-style-type:decimal;">	Environmental Surveillance (Inspection and Monitoring) of 17 Categories of High Pollution Potential Industries & Common Environmental Infrastructure Facilities based on Online Continuous Effluent/Emission Monitoring System (OCEMS) data and follow-up of directions for compliance verifications. </li>
<li  style="list-style-type:decimal;">	Pollution Assessment (Survey & Monitoring) of Common Environmental Infrastructure Facilities viz. STP, MSWM, CETP, CHWTSDF, HCF, TSDF & BMWTF and follow up of directions for compliance verifications.</li>
<li  style="list-style-type:decimal;">	Attending VIP reference complaints, Public Complaints, Parliamentary references and providing information under RTI Act, 2005; and follow up with SPCBs/PCC. </li>
<li  style="list-style-type:decimal;">	Inspections under Hon’ble NGT & other Hon’ble Court Directions, Filing of Reply Affidavits to Hon’ble NGT/ Hon’ble High Courts of AP, TN & TG States. </li>
<li  style="list-style-type:decimal;">	Development & establish of Environmental Laboratory.</li>
<li  style="list-style-type:decimal;">	Scientific and Technical Activities as per approved Annual Action Plan.</li>
<li  style="list-style-type:decimal;">	Carry out R&D projects to address state /Regional specific issues.</li>
<li  style="list-style-type:decimal;">Organizing & participation in Mass-awareness Programs such as World Environment Day, Ozone Day, SUP Ban, Mission Life etc. and observance/ Celebration of National Events like Republic Day, Independence Day, Swachhata week, etc. </li>
 </ol> </span>
        </h5>
		<div class="clear">***</div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
       <li style="list-style-type:decimal;">Sh. Rajkumar R , Scientist \'E\'</li>
   	    <li style="list-style-type:decimal;">Smt. Soumya D, Scientist \'E\'</li>
	   <li style="list-style-type:decimal;">Smt. Poornima B. M., Scientist \'D\'</li>
	 <li style="list-style-type:decimal;">	Ms. Kokila M, Scientist ‘B’</li>
 <li style="list-style-type:decimal;">Smt. Budigi Reddy Soni, Scientist ‘B’</li>
 <li style="list-style-type:decimal;">  Sh. Prabhu H Ganchi, Scientist ‘B’</li>
		  <li style="list-style-type:decimal;">Sh. K. Karunagaran, STS</li>
		 <li style="list-style-type:decimal;">	Sh. T. Raghavendra Rao, ALO</li>
 <li style="list-style-type:decimal;">	Sh. Sidda Sailu, SSA</li>
 <li style="list-style-type:decimal;">	  Ms. Cristeena Devassia, JLA</li>
 <li style="list-style-type:decimal;">	   Sh. Viswanathan P, Field Attendant</li>
                            </ol>                              </span>
                                                        </h5></div>';
        $material_modal_hi ='<div><h3>क्षेत्रीय निदेशालय भोपाल </h3>
	<div style="overflow-y:Scroll;height:300px">
 <h5 style="color:#0b73b9;"><strong>क्षेत्रीय निदेशक: : </strong> <span style="float: right;width: 70%; text-align:left;">श्री ए के विद्यार्थी   (वैज्ञानिक ‘एफ’)</span></h5>
 <h5 style="color:#0b73b9;"><strong>फोन नंबर : </strong> <span style="float: right;width: 70%; text-align:left;"> 0755-2775385</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> cpcb.bhopal@gov.in, vidyarthi.cpcb@nic.in </span></h5>
 
 <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह कार्यालय निम्नलिखित कार्यकलापों को देखता है : </h5>
                                                           <ol style="list-style-type:decimal;">
   <li  style="list-style-type:decimal;">	Surveillance of Ambient Air Quality and Noise monitoring during Diwali Festival.</li> 
<li  style="list-style-type:decimal;">	Audit and Verification of NAMP & NWMP stations in the Central Region.</li> 
<li  style="list-style-type:decimal;">	Co-ordination of activities and review of action plans under NCAP, PRS, CEPI, Contaminated sites.</li> 
<li  style="list-style-type:decimal;">	Analysis of Air, Water and soil samples for the required parameters, as per the E(P)A requirement.</li> 
<li  style="list-style-type:decimal;">	Random monitoring of STPs and data compilation on status of Hazardous waste, MSW, Plastic Waste, E-waste, Biomedical waste, vehicular pollution and batteries in coordination with SPCBs in Central Zone</li> 
<li  style="list-style-type:decimal;">	To perform inspections under SMS alert, Public/ VIP complaints, Legal matters, Rule 9and other surprise inspections.</li> 
<li  style="list-style-type:decimal;">	Verification of Laboratories for recognition as Environmental Laboratory under E(P)A Act, 1986.</li> 
<li  style="list-style-type:decimal;">	Mass awareness activities on WED, Ozone Day, SUP Ban, Mission Life etc.</li>  </ol> </span>
                                                        </h5>
              <div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
         <li style="list-style-type:decimal;">	श्री सुनील कुमार मीणा, (वैज्ञानिक ‘ई’)</li>
 <li style="list-style-type:decimal;">श्री मिलिंद कुमार बी. निम्जे, (वैज्ञानिक \'सी\')	</li> 
  <li style="list-style-type:decimal;">डाॅ. रानू चौकसे वर्मा, वैज्ञानिक \'सी\' </li>
  <li style="list-style-type:decimal;">	डाॅ. अनूप चतुवेर्दी, वैज्ञानिक \'सी\' </li>
  <li style="list-style-type:decimal;">श्री प्रवीण कुमार जैन, वैज्ञानिक \'सी\' </li>
  <li style="list-style-type:decimal;">	डाॅ. पौलमी सी पाटिल, वैज्ञानिक \'बी\'</li>
  <li style="list-style-type:decimal;">श्री संजय कुमार मुकाती, वैज्ञानिक \'बी\' </li>
<li style="list-style-type:decimal;">श्री रुपेन्द्र कुमार, वैज्ञानिक \'बी\' </li>
  <li style="list-style-type:decimal;">श्री राज दीप सिंह, सहायक विधि अधिकारी </li>
<li style="list-style-type:decimal;">श्रीमती रश्मि ठाकुर, वरिष्‍ठ वैज्ञानिक सहायक</li>
  <li style="list-style-type:decimal;">	श्री रामेश्वर बंदेवार,  वरिष्‍ठ वैज्ञानिक सहायक</li>
   <li style="list-style-type:decimal;">श्री सुनील कोल्हटकर,  वरिष्‍ठ वैज्ञानिक सहायक </li>
 <li style="list-style-type:decimal;">श्री आर्श तिवारी,  वरिष्‍ठ वैज्ञानिक सहायक </li>
  <li style="list-style-type:decimal;">श्री राजीव शर्मा, तकनीकी पर्यावेक्षक (सीपीई)</li>
     <li style="list-style-type:decimal;">श्री जगजीवन राम, कनिष्‍ठ प्रयोगशाला सहायक</li>
  <li style="list-style-type:decimal;">	श्रीमती अल्फा मोनिका लाकड़ा, कनिष्‍ठ प्रयोगशाला सहायक</li>
 <li style="list-style-type:decimal;">श्री शिव सिंह कुशवाहा, कनिष्‍ठ प्रयोगशाला सहायक</li>
   <li style="list-style-type:decimal;">डाॅ. सोनल वानखेडे, आर ए-III</li>  
   <li style="list-style-type:decimal;">श्री सचिन कुमार साहू, एसआरएफ</li>
   <li style="list-style-type:decimal;">सुश्री किरन कनेरे, अवर क्ष्रेणी लिपिक</li> 
  <li style="list-style-type:decimal;">	श्रीमती फरजाना खान, डीईओ </li>
  <li style="list-style-type:decimal;">	श्री प्रहलाद बघेल, अवर श्रेणी लिपिक  </li>
<li style="list-style-type:decimal;">	श्री शिव शंकर शुक्ल, हिंदी टाइपिस्ट </li>
 <li style="list-style-type:decimal;">श्री सलामुद्दीन, चालक</li>
  <li style="list-style-type:decimal;">	श्री सुरेश कुमार चौहान, चालक</li>
  <li style="list-style-type:decimal;">	श्री सुरेंद्र कुमार भाटिया, एमटीएस </li>
  <li style="list-style-type:decimal;">श्री सुरेश चौधरी, एमटीएस </li>
    <li style="list-style-type:decimal;">श्री  अमित खेदीकर, एमटीएस</li>   
   
                                        </ol> 
                                                        </span>
                                                        </h5></div>';

        $coord_modal ='<div><h3>Regional Directorate Chandigarh </h3>
    <div style="overflow-y:Scroll;height:300px">
      <h5 style="color:#0b73b9;"><strong>Regional Director: </strong><span style="float: right;width: 70%; text-align:left;">Sh. Narender Sharma, Scientist \'F\'</span></h5>
 <h5 style="color:#0b73b9;"><strong>Phone No : 	</strong> <span style="float: right;width: 70%; text-align:left;"> +91-7840015561</span></h5>
     <h5 style="color:#0b73b9;"><strong>E-mail : 	</strong> <span style="float: right;width: 70%; text-align:left;">narendersharma.cpcb@gov.in</span></h5>
      <h5 style="color:#0b73b9;"><strong>Profile & Activity : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>This office  deals  following Activities  : </h5>
                    <ol style="list-style-type:decimal;">
    <li  style="list-style-type:decimal;">Pollution Assessment, Survey &amp; Monitoring</li>
   <li  style="list-style-type:decimal;">Pollution Control Enforcement</li>
  <li  style="list-style-type:decimal;">R & D Activities</li> </ol> </span>
        </h5>
		<div class="clear">***</div>
          <h5 style="color:#0b73b9;"><strong>Personnel : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>
       
	   
		<li style="list-style-type:decimal;">-</li>
	                               </ol>                              </span>
                                                        </h5></div>';
        $coord_modal_hi ='<div><h3>क्षेत्रीय निदेशालय शिलांग </h3>
	<div style="overflow-y:Scroll;height:300px">
	  <h5 style="color:#0b73b9;"><strong>क्षेत्रीय निदेशक: </strong><span style="float: right;width: 70%; text-align:left;"> श्री शांतनु दत्ता, वैज्ञानिक \'एफ\'</span></h5>
      <h5 style="color:#0b73b9;"><strong>फोन नंबर : 	</strong> <span style="float: right;width: 70%; text-align:left;"> 0364-2522859</span></h5>
     <h5 style="color:#0b73b9;"><strong>ई-मेल : 	</strong> <span style="float: right;width: 70%; text-align:left;"> -</span></h5>
	  <h5 style="color:#0b73b9;"><strong>प्रोफाइल और कार्यकलाप : </strong>  <span style="float: right;width: 70%; text-align:left;">
		  <h5>यह कार्यालय निम्नलिखित कार्यकलापों को देखता है:</h5>
               <ol style="list-style-type:decimal;">
 <li style="list-style-type:decimal;"> पर्यावरण प्रदूषण की रोकथाम और नियंत्रण के संबंध में आठ एसपीसीबी (असम, अरुणाचल प्रदेश, मेघालय, मणिपुर, मिजोरम, नागालैंड, सिक्किम और त्रिपुरा) के साथ समन्वय और छह सहित विभिन्न पर्यावरणीय अधिनियमों/नियमों के प्रवर्तन के लिए क्षमता निर्माण पूर्वोत्तर क्षेत्र में अपशिष्ट प्रबंधन नियम, 2016।</li>
<li style="list-style-type:decimal;"> प्रदूषण नियंत्रण और उपशमन कार्यक्रमों से संबंधित निगरानी, निगरानी, प्रयोगशाला और अनुसंधान गतिविधियों को करने के लिए एसपीसीबी को तकनीकी सहायता और मार्गदर्शन प्रदान करें।</li>
<li style="list-style-type:decimal;">एनएएमपी और एनडब्ल्यूएमपी स्टेशनों के संचालन की निगरानी करना जिसमें अंतर्राज्यीय सीमावर्ती नदियां और गंभीर रूप से प्रदूषित जल निकायों के खंड शामिल हैं।</li>
<li style="list-style-type:decimal;">राज्यों और प्रधान कार्यालय के साथ CAAQMS, EWQDES और EAQDES से संबंधित मुद्दों का समन्वय।</li>
<li style="list-style-type:decimal;">पीडब्ल्यूएम नियमों के प्रभावी कार्यान्वयन के लिए एनसीएपी और एसयूपी/ईपीआर के तहत कार्य योजना के कार्यान्वयन के लिए एसपीसीबी के साथ समन्वय।</li>
<li style="list-style-type:decimal;">प्रदूषण नियंत्रण मानदंडों के अनुपालन के लिए ईपी अधिनियमों के तहत एसपीसीबी/उद्योगों को जारी सीपीसीबी के निर्देशों का पालन।</li>
<li style="list-style-type:decimal;">वीआईपी शिकायतों, सार्वजनिक शिकायतों, संसदीय संदर्भों पर ध्यान देना और आरटीआई अधिनियम, 2005 के तहत सूचना प्रदान करना; और शीघ्र निपटान के लिए राज्यों के साथ अनुवर्ती कार्रवाई करें।</li>
<li style="list-style-type:decimal;">माननीय एनजीटी के आदेशों और अन्य न्यायालयों के अनुपालन के लिए एसपीसीबी के साथ समन्वय।</li>
<li style="list-style-type:decimal;">आर्म-फोर्स अस्पतालों सहित एसटीपी, एमएसडब्ल्यू प्रबंधन स्थलों, सीईटीपी, एचसीएफ और सीबीडब्ल्यूटीएफ का निरीक्षण।</li>
<li style="list-style-type:decimal;">राज्य/क्षेत्रीय विशिष्ट मुद्दों के समाधान के लिए अनुसंधान एवं विकास परियोजनाओं को कार्यान्वित करना।</li>
<li style="list-style-type:decimal;">प्रयोगशाला का सुदृढ़ीकरण और प्रवीणता परीक्षा (पीटी) में भागीदारी।</li>
<li style="list-style-type:decimal;">जन-जागरूकता कार्यक्रमों में भागीदारी और गणतंत्र दिवस, स्वतंत्रता दिवस, विश्व पर्यावरण दिवस, स्वच्छता सप्ताह आदि जैसे राष्ट्रीय आयोजनों का पालन/उत्सव।</li>

  </ol> 
                                                        </span>
                                                        </h5>
			<div class="clear"></div>
          <h5 style="color:#0b73b9;"><strong>कर्मचारी वर्ग : </strong>  <span style="float: right; width: 70%;text-align:left;">
                                                           <ol>											
	 		<li  style="list-style-type:decimal;">	डॉ. ज्ञान प्रकाश सिंह, (वैज्ञानिक ‘डी’)</li>
			<li style="list-style-type:decimal;"> श्री  अर्नब मण्डल, वैज्ञानिक ‘बी\'</li>
	     <li style="list-style-type:decimal;">श्री  राकेश बसुमतरी, एस एस ए</li>
          <li style="list-style-type:decimal;"> श्री  आनंद  कुमार  नगंगोम, वरिष्ठ  वैज्ञानिक सहायक</li>
           <li style="list-style-type:decimal;"> श्री  एफ  लीनग्राह,  अनुभाग अधिकारी </li>
            <li style="list-style-type:decimal;"> श्री एस  के  एम  मिहसिल, वरिष्ठ  प्रयोगशाला  सहायक </li>
             <li style="list-style-type:decimal;"> श्री  रतन मुतयेन्त, वरिष्ठ  प्रयोगशाला  सहायक </li>                                 		
                                                        </ol> </span>
        </h5></div>';

        $hindi_modal ='<div></div>';
        $hindi_modal_hi ='<div></div>';


        // 2. Define Division Data
        $divisions = [
            [
                'division' => 'Chairman Section',
                'division_hi' => 'अध्यक्ष अनुभाग',
                'title' => 'Sh. Amandeep Garg, IAS',
                'title_hi' => 'श्री अमनदीप गर्ग, भा.प्र.से.',
                'email' => 'ccb.cpcb@nic.in',
                'ext_number' => '201',
                'description' => $chair_modal,
                'description_hi' => $chair_modal_hi,
            ],
            [
                'division' => 'Member Secretary Section',
                'division_hi' => 'सदस्य सचिव अनुभाग',
                'title' => 'Sh. Bharat Kumar Sharma',
                'title_hi' => 'श्री भरत कुमार शर्मा',
                'email' => 'mscb.cpcb@nic.in',
                'ext_number' => '206',
                'description' => $ms_modal,
                'description_hi' => $ms_modal_hi,
            ],
            [
                'division' => 'PCP Division',
                'division_hi' => 'पीसीपी विभाग',
                'title' => 'Sh. G Thirumurthy (Sc. \'E\')',
                'title_hi' => 'श्री जी. थिरुमूर्थी (वैज्ञानिक \'एफ\')',
                'email' => 'thiru.cpcb@nic.in',
                'ext_number' => '336',
                'description' => $pcp_modal,
                'description_hi' => $pcp_modal_hi, // Corrected from $pcp_modal
            ],
            [
                'division' => 'PR Division',
                'division_hi' => 'जन संपर्क प्रभाग',
                'title' => 'Sh. Sharandeep Singfh (Sc. \'E\')',
                'title_hi' => 'श्री शरणदीप सिंह (वैज्ञानिक ‘ई’)',
                'email' => 'sharandeep.cpcb@nic.in',
                'ext_number' => '454',
                'description' => $pr_modal,
                'description_hi' => $pr_modal_hi, // Corrected
            ],
            [
                'division' => 'ETU Division',
                'division_hi' => 'ईटीयू प्रभाग',
                'title' => 'Sh. Sanghmitter (AO)',
                'title_hi' => 'श्री संघमित्र (एओ)',
                'email' => 'smitter.cpcb@nic.in',
                'ext_number' => '267',
                'description' => $etu_modal,
                'description_hi' => $etu_modal_hi, // Corrected
            ],
            [
                'division' => 'IPC -I Division',
                'division_hi' => 'आईपीसी -1 प्रभाग',
                'title' => 'Sh. Nazimuddin (Sc. \'F\')',
                'title_hi' => 'श्री नजीमुद्दीन (वैज्ञानिक \'एफ\')',
                'email' => 'nazim.cpcb@nic.in',
                'ext_number' => '444',
                'description' => $ipc1_modal,
                'description_hi' => $ipc1_modal_hi, // Corrected
            ],
            [
                'division' => 'IPC-II Division',
                'division_hi' => 'आईपीसी -2 प्रभाग',
                'title' => 'Sh. Prasoon Gargava (Sc. \'F\')',
                'title_hi' => 'श्री प्रसून (वैज्ञानिक \'एफ\')',
                'email' => 'prasoon.cpcb@nic.in',
                'ext_number' => '444',
                'description' => $ipc2_modal,
                'description_hi' => $ipc2_modal_hi, // Corrected
            ],
            [
                'division' => 'IPC-III Division',
                'division_hi' => 'आईपीसी-3 प्रभाग',
                'title' => 'Sh. Kamlesh Singh (Sc. \'E\')',
                'title_hi' => 'श्री कमलेश सिंह (वैज्ञानिक \'ई\')',
                'email' => 'kamleshsingh.cpcb@nic.in',
                'ext_number' => '447',
                'description' => $ipc3_modal,
                'description_hi' => $ipc3_modal_hi, // Corrected
            ],
            [
                'division' => 'IPC-IV Division',
                'division_hi' => 'आईपीसी-4 प्रभाग',
                'title' => 'Smt. Anamika Sagar (Sc. \'E\')',
                'title_hi' => 'श्रीमती अनामिका सागर, (वैज्ञानिक ‘ई’)',
                'email' => 'anamika.cpcb@nic.in',
                'ext_number' => '440',
                'description' => $ipc4_modal,
                'description_hi' => $ipc4_modal_hi, // Corrected
            ],
            [
                'division' => 'IPC-V Division',
                'division_hi' => 'आईपीसी-5 प्रभाग',
                'title' => '-',
                'title_hi' => '-',
                'email' => '-',
                'ext_number' => '-',
                'description' => $ipc5_modal,
                'description_hi' => $ipc5_modal_hi, // Corrected
            ],
            [
                'division' => 'IPC-VI Division',
                'division_hi' => 'आईपीसी-6 प्रभाग',
                'title' => '-',
                'title_hi' => 'श्री अमित ठक्कर (वैज्ञानिक \'ई\')', 
                'email' => '-',
                'ext_number' => '432',
                'description' => $ipc6_modal,
                'description_hi' => $ipc6_modal_hi, // Corrected
            ],
             [
                'division' => 'IPC-VII Division',
                'division_hi' => 'आईपीसी-7 प्रभाग',
                'title' => '-',
                'title_hi' => '-',
                'email' => '-',
                'ext_number' => '-',
                'description' => $ipc7_modal,
                'description_hi' => $ipc7_modal_hi, // Corrected
            ],
            [
                'division' => 'UPC - I Division',
                'division_hi' => 'यूपीसी - 1 प्रभाग',
                'title' => 'Sh. Sharandeep Singh (Sc. \'E\')',
                'title_hi' => 'श्री शरणदीप सिंह (वैज्ञानिक \'ई\')',
                'email' => 'sharandeep.cpcb@nic.in',
                'ext_number' => '306',
                'description' => $upc1_modal,
                'description_hi' => $upc1_modal_hi, // Corrected
            ],
            [
                'division' => 'UPC - II Division',
                'division_hi' => 'यूपीसी - 2 प्रभाग',
                'title' => 'Smt. Divya Sinha (Sc. \'F\')',
                'title_hi' => 'श्रीमती दिव्या सिन्हा (वैज्ञानिक \'एफ\')',
                'email' => 'divyasinha.cpcb@nic.in',
                'ext_number' => '453',
                'description' => $upc2_modal,
                'description_hi' => $upc2_modal_hi, // Corrected
            ],
            [
                'division' => 'WM - I Division',
                'division_hi' => 'डब्ल्यूएम - 1 प्रभाग',
                'title' => 'Sh. V P Yadav (Sc. \'F\')',
                'title_hi' => 'श्री वी. पी. यादव (वैज्ञानिक \'एफ\')',
                'email' => 'vpyadav.cpcb@nic.in',
                'ext_number' => '324',
                'description' => $wm1_modal,
                'description_hi' => $wm1_modal_hi, // Corrected
            ],
            [
                'division' => 'WM - II Division',
                'division_hi' => 'डब्ल्यूएम - 2 प्रभाग',
                'title' => 'Smt. Deepti Kapil (Sc. \'E\')',
                'title_hi' => '-',
                'email' => 'deepti.cpcb@nic.in',
                'ext_number' => '319',
                'description' => $wm2_modal,
                'description_hi' => $wm2_modal_hi, // Corrected
            ],
            [
                'division' => 'WM - III Division',
                'division_hi' => 'डब्ल्यूएम - 3 प्रभाग',
                'title' => 'Smt. Youthika (Sc. \'E\')',
                'title_hi' => 'श्रीमती यूथिका पुरी (वैज्ञानिक \'ई\')',
                'email' => 'youthika.cpcb@nic.in',
                'ext_number' => '321',
                'description' => $wm3_modal,
                'description_hi' => $wm3_modal_hi, // Corrected
            ],
            [
                'division' => 'AQM Division',
                'division_hi' => 'एक्यूएम प्रभाग',
                'title' => 'Sh. Pankaj Agarwal (Sc. \'F\')',
                'title_hi' => 'श्री पंकज अग्रवाल (वैज्ञानिक \'एफ\')',
                'email' => 'pagarwal.cpcb@gov.in',
                'ext_number' => '370',
                'description' => $aqm_modal,
                'description_hi' => $aqm_modal_hi, // Corrected
            ],
            [
                'division' => 'AQMN  Division',
                'division_hi' => 'एक्यूएमन प्रभाग',
                'title' => 'Sh. Aditya Sharma (Sc. \'E\')',
                'title_hi' => 'श्री आदित्य शर्मा (वैज्ञानिक \'ई\')',
                'email' => 'aditya.cpcb@nic.in',
                'ext_number' => '428',
                'description' => $aqmn_modal,
                'description_hi' => $aqmn_modal_hi, // Corrected
            ],
            [
                'division' => 'WQM - I Division',
                'division_hi' => 'डब्ल्यूक्यूएम - 1 प्रभाग',
                'title' => 'Sh. Nazimuddin, Sc. \'F\'',
                'title_hi' => 'श्री नजीमुद्दीन (वैज्ञानिक \'एफ\')',
                'email' => 'nazim.cpcb@nic.in',
                'ext_number' => '444',
                'description' => $wqm1_modal,
                'description_hi' => $wqm1_modal_hi, // Corrected
            ],
            [
                'division' => 'WQM - II Division',
                'division_hi' => 'डब्ल्यूक्यूएम - 2 प्रभाग',
                'title' => 'Sh. Dinabandhu Gouda (Sc. \'F\')',
                'title_hi' => 'श्री दीनबंधु गौड़ा (वैज्ञानिक \'एफ\')',
                'email' => 'dinabandhu.cpcb@nic.in',
                'ext_number' => '445',
                'description' => $wqm2_modal,
                'description_hi' => $wqm2_modal_hi, // Corrected
            ],
            [
                'division' => 'IT  Division',
                'division_hi' => 'सूचना प्रौद्योगिकी प्रभाग',
                'title' => 'Sh. P.Jagan (Sc. \'E\')',
                'title_hi' => '-',
                'email' => 'jagan191.cpcb@gov.in',
                'ext_number' => '296',
                'description' => $it_modal,
                'description_hi' => $it_modal_hi, // Corrected
            ],
            [
                'division' => 'Circular Economy Cell',
                'division_hi' => 'Circular Economy Cell',
                'title' => '-',
                'title_hi' => '-',
                'email' => '-',
                'ext_number' => '-',
                'description' => $cec_modal,
                'description_hi' => $cec_modal_hi, // Corrected
            ],
            [
                'division' => 'R & D Division',
                'division_hi' => 'R & D Division',
                'title' => 'Sh. Anil Ranveer (Sc. \'E\')',
                'title_hi' => '-', 
                'email' => 'anilranveer.cpcb@gov.in',
                'ext_number' => '-',
                'description' => $randd_modal,
                'description_hi' => $randd_modal_hi, // Corrected
            ],
             [
                'division' => 'Law  Division',
                'division_hi' => 'कानून प्रभाग',
                'title' => 'Sh. Prasoon Gargava (Sc. F)',
                'title_hi' => 'श्री प्रसून गार्गव (वैज्ञानिक \'एफ\')',
                'email' => 'prasoon.cpcb@gov.in',
                'ext_number' => '402',
                'description' => $law_modal,
                'description_hi' => $law_modal_hi, // Corrected
            ],
            [
                'division' => 'Air Quality Lab',
                'division_hi' => 'वायु गुणवत्ता प्रयोगशाला',
                'title' => 'Sh. S K Sharma (Sc. \'E\')',
                'title_hi' => 'श्री एस के शर्मा (वैज्ञानिक \'ई\')',
                'email' => 'sks.cpcb@nic.in',
                'ext_number' => '428',
                'description' => $aq1_modal,
                'description_hi' => $aq1_modal_hi, // Corrected
            ],
            [
                'division' => 'Instrumentation Lab',
                'division_hi' => 'इंस्ट्रुमेंटेशन प्रयोगशाला',
                'title' => 'Dr. K. Ranganathan (Sc. E)',
                'title_hi' => '-', 
                'email' => 'kranga.cpcb@nic.in',
                'ext_number' => '393',
                'description' => $int_modal,
                'description_hi' => $int_modal_hi, // Corrected
            ],
             [
                'division' => 'Trace Organic Lab',
                'division_hi' => 'ट्रेस ऑर्गेनिक प्रयोगशाला',
                'title' => 'Sh. Sanjay Kumar (Sc. \'E\')',
                'title_hi' => 'श्री संजय कुमार (वैज्ञानिक \'ई\')',
                'email' => 'skumar.cpcb@nic.in',
                'ext_number' => '376',
                'description' => $tol_modal,
                'description_hi' => $tol_modal_hi, // Corrected
            ],
            [
                'division' => 'Water & Wastewater Lab',
                'division_hi' => 'जल और अपशिष्ट प्रयोगशाला',
                'title' => 'Dr. K. Ranganathan (Sc. \'E\')',
                'title_hi' => 'डॉ. के. रंगनाथन (वैज्ञानिक \'ई\')',
                'email' => 'kranga.cpcb@nic.in',
                'ext_number' => '393',
                'description' => $wtr_modal,
                'description_hi' => $wtr_modal_hi, // Corrected
            ],
            [
                'division' => 'Bio Lab',
                'division_hi' => 'Bio Lab',
                'title' => '-',
                'title_hi' => '-',
                'email' => '-',
                'ext_number' => '-',
                'description' => $bio_modal,
                'description_hi' => $bio_modal_hi, // Corrected
            ],
            [
                'division' => 'Building Division',
                'division_hi' => 'बिल्डिंग प्रभाग',
                'title' => 'Sh. P. Jagan (Sc. ‘E’)',
                'title_hi' => 'श्री. दीनबंधु गौड़ा (वैज्ञानिक \'एफ\')',
                'email' => 'jagan191.cpcb@gov.in',
                'ext_number' => '420',
                'description' => $build_modal,
                'description_hi' => $build_modal_hi, // Corrected
            ],
            [
                'division' => 'Library',
                'division_hi' => 'लाइब्रेरी',
                'title' => 'Sh. Sharandeep Singh (Sc. \'E\')',
                'title_hi' => 'श्री शरणदीप सिंह (वैज्ञानिक ‘ई’)',
                'email' => 'sharandeep.cpcb@nic.in',
                'ext_number' => '258',
                'description' => $lib_modal,
                'description_hi' => $lib_modal_hi, // Corrected
            ],
            [
                'division' => 'Account Section',
                'division_hi' => 'लेखा अनुभाग',
                'title' => 'Sh. D Kalita (ACO)',
                'title_hi' => 'श्री डी. कलिता (एसीओ)',
                'email' => 'dkalita.cpcb@nic.in',
                'ext_number' => '223',
                'description' => $accounts_modal,
                'description_hi' => $accounts_modal_hi, // Corrected
            ],
            [
                'division' => 'Admin. Recruitment Section',
                'division_hi' => 'व्यवस्थापक भर्ती अनुभाग',
                'title' => 'Sh. Rameshwar (AO)',
                'title_hi' => 'श्रीमती मधु लूथरा (एओ)',
                'email' => 'rameshwar.cpcb@nic.in',
                'ext_number' => '266',
                'description' => $adminr_modal,
                'description_hi' => $adminr_modal_hi, // Corrected
            ],
            [
                'division' => 'Admin. Personnel Section',
                'division_hi' => 'व्यवस्थापक कार्मिक अनुभाग',
                'title' => 'Smt. Madhu Luthra (AO)',
                'title_hi' => 'श्रीमती मधु लूथरा (एओ)',
                'email' => 'madhu.cpcb@nic.in',
                'ext_number' => '246',
                'description' => $adminp_modal,
                'description_hi' => $adminp_modal_hi, // Corrected
            ],
            [
                'division' => 'Admin. Material Section',
                'division_hi' => 'सामग्री अनुभाग',
                'title' => 'Smt. Meena Sharma (AO)',
                'title_hi' => 'श्रीमती मीना शर्मा (एओ)',
                'email' => 'msharma.cpcb@gov.in',
                'ext_number' => '243',
                'description' => $material_modal,
                'description_hi' => $material_modal_hi, // Corrected
            ],
            [
                'division' => 'Admin. Coordination Div.',
                'division_hi' => 'व्यवस्थापक समन्वय और चिकित्सा अनुभाग',
                'title' => 'Smt. Rekha Narang (AO)',
                'title_hi' => 'श्रीमती रेखा नारंग (एओ)',
                'email' => 'rekhanarang.cpcb@nic.in',
                'ext_number' => '267',
                'description' => $coord_modal,
                'description_hi' => $coord_modal_hi, // Corrected
            ],
            [
                'division' => 'Hindi Section',
                'division_hi' => 'हिंदी अनुभाग',
                'title' => 'Sh. Sanghmitter (AO)',
                'title_hi' => 'श्री संघमित्र (एओ)',
                'email' => 'smitter.cpcb@nic.in',
                'ext_number' => '248',
                'description' => $hindi_modal,
                'description_hi' => $hindi_modal_hi, // Corrected
            ],
        ];

        // 3. Insert Data
        $dbDivisions = \Illuminate\Support\Facades\DB::table('divisions')->get();

        foreach ($divisions as $div) {
          
            $matchedDivisionId = null;

            if (!empty($div['division'])) {
                $divText = trim($div['division']);

                if ($divText) {
                    $matchedDiv = $divisions->first(function ($d) use ($divText) {
                        return strcasecmp(trim($d->title), $divText) === 0;
                    });

                    if ($matchedDiv) {
                        $matchedDivisionId = $matchedDiv->id;
                    }
                }
            }

            HeadOffice::create([
                'division_id' => $matchedDivisionId,
                'title' => $div['title'],
                'title_hi' => $div['title_hi'],
                'email' => $div['email'],
                'ext_number' => $div['ext_number'],
                'description' => $div['description'],
                'description_hi' => $div['description_hi'],
                'is_approved' => 1,
                'is_published' => 1,
            ]);
        }
    }
}
?>

