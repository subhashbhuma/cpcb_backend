<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NgtCourtCasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    DB::table('ngt_court_cases')->truncate();

    $rows = [
    ['Report', 'Committee Report before The National Green Tribunal Principal Bench, New Delhi in Original Application No. 19 of 2021: Air Pollution in Indo Gangetic plains (IGP)', 'OA No. 19 of 2021', 'Report-OANo-19-of-2021.pdf', '2023-04-12'],
    ['Report', 'Joint Committee Report in O.A. No. 304 of 2019, M. Haridasan Vs. State of Kerala & ors.', 'OA No. 304 of 2019', 'Joint-ComtReport-OA-304-of-2019.pdf', '2023-03-03'],
    ['Report', 'Joint Committee Report in O.A. No. 838/2022, Prem Prakash Prajapati Vs. Project Director, PIU Sonipat & Ors..', 'OA No. 838 of 2022', 'Joint-ComtReport-OA-838-of-2022.pdf', '2023-02-21'],
    ['Inspection cum Monitoring Report', 'Fourteenth Report of Joint Inspection-cum-monitoring of CETP-VAPI Industrial Area, Gujarat, in O.A.No. 95 of 2018, in the matter of Aryavart Foundation Vs. Vapi Green Enviro Ltd.', 'OA No. 95 of 2018', '14-REP-JOINTINSPECT-CETP-VAPI.pdf', '2023-01-06'],
    ['Report', 'Final Report of Committee In the matter of OA no. 274/2020 in compliance with order dated 18.12.2020 & 16.08.2021 of the Hon’ble NGT', 'OA No. 274 of 2020', 'Report-OANo-274-of-2020.pdf', '2022-12-20'],
    ['Interim Report', 'Interim Report of the Hon’ble NGT constituted Committee on extent of damage to environment and steps to be taken for restitution of damaged Environment', 'OA No. 274 of 2020', 'interim-Report-OANo-274-of-2020.pdf', '2022-12-20'],
    ['Inspection cum Monitoring Report', 'Thirteenth Report of Joint Inspection-cum-monitoring of CETP-VAPI Industrial Area, Gujarat, in O.A.No. 95 of 2018, in the matter of Aryavart Foundation Vs. Vapi Green Enviro Ltd.', 'OA No. 95 of 2018', '13-REP-JOINTINSPECT-CETP-VAPI.pdf', '2022-12-05'],
    ['Report', 'Report of the committee in OA No. 68 of 2020 (EZ) In the matter of Pradip Kumar Pattnaik Vs. Union of India & Ors.', 'OA No. 68 of 2020', 'TGF0ZXN0RmlsZS8zNTBfMTY2MjA5NDI5NF9tZWRpYXBob3RvMzAwMTIucGRm', '2022-09-02'],
    ['Report', 'Joint Committee Report in OA No 23 of 2017 EZ with OA No 776 of 2018 with OA No 373 of 2019', 'OA No. 23 of 2017', 'Report-O.A.No.-373-of-2019.pdf', '2022-08-26'],
    ['Inspection cum Monitoring Report', 'Twelvth Report of Joint Inspection-cum-monitoring of CETP-VAPI Industrial Area, Gujarat, in O.A.No. 95 of 2018, in the matter of Aryavart Foundation Vs. Vapi Green Enviro Ltd.', 'OA No. 95 of 2018', '12-REP-JOINTINSPECT-CETP-VAPI.pdf', '2022-08-20'],
    ['Compliance Report', 'Compliance Report of CPCB in compliance to Hon’ble NGT Order dated 23.11.2021 in OA No. 200/2014 in the matter of MC Mehta vs UOI & Ors.', 'OA No. 200 of 2014', 'Report-O.A.No.-200-of-2014.pdf', '2022-05-01'],
    ['Report', 'Joint Committee report in Compliance to Hon’ble NGT orders dated 18.03.2021, 12.08.2021 & 09.12.2021 in the matter of Shailesh Singh vs State of U.P. and Ors in OA No. 324/2016 & EA No. 19/2021', 'OA No. 324 of 2016', 'Joint-ComtReport-OA-324-of-2016.pdf', '2022-05-01'],
    ['Inspection cum Monitoring Report', 'Eleventh Report of Joint Inspection-cum-monitoring of CETP-VAPI Industrial Area, Gujarat, in O.A.No. 95 of 2018, in the matter of Aryavart Foundation Vs. Vapi Green Enviro Ltd.', 'OA No. 95 of 2018', '11-REP-JOINTINSPECT-CETP-VAPI.pdf', '2022-03-01'],
    ['Inspection Report', 'Joint Inspection Report in connection with Hon’ble NGT (PB) order dated 10.12.2021 in O.A. No 326/2021 Behari Lal Chaturvedi Vs Ministry of Jal Shakti & Ors regarding encroachment of Yamuna floodplain at Mathura', 'OA No. 326 of 2021', 'TGF0ZXN0RmlsZS8zNDVfMTY0NjIxMzc5OF9tZWRpYXBob3RvMjcxODIucGRm', '2022-03-01'],
    ['Report', 'Report of Committee- OA No. 130 of 2021 related to fatal fire accident at M/s SVS Aqua Technologies Ltd Urawade', 'OA No. 130 of 2021', 'Report_SVS_Aqua_Tech.pdf', '2022-02-01'],
    ['Action Plan', 'Action Plan in compliance to Hon\'ble NGT order dated 04.01.2022 in O. A. No. 368/2021, In News item by Aaj Tak on 28.11.2021 titled “Varanasi: Houses sinking on the bank of River Ganga and reason behind Ganga water turning black coloured.', 'OA No. 368 of 2021', 'Action-Plan-Rej-River-OA-No-368-2021.pdf', '2022-01-01'],
    ['Inspection cum Monitoring Report', 'Tenth Report of Joint Inspection-cum-monitoring of CETP-VAPI Industrial Area, Gujarat, in O.A.No. 95 of 2018, in the matter of Aryavart Foundation Vs. Vapi Green Enviro Ltd.', 'OA No. 95 of 2018', '10-REP-JOINTINSPECT-CETP-VAPI.pdf', '2022-01-01'],
    ['Action Plan', 'Action plan in compliance to Hon\'ble NGT order dated 23.11.21 in OA No 128/2021, Saurabh Tiwari Vs UoI & Ors.', 'OA No. 128 of 2021', 'Actionplan_08122021.pdf', '2021-12-01'],
    ['Report', 'Report of the Joint Committee in the matter of OA No. 79/2021 submitted to Hon\'ble National Green Tribunal, Delhi in compliance to Order dated March 16, 2021', 'OA No. 79 of 2021', 'TGF0ZXN0RmlsZS8zMzRfMTYzMTY4Nzk2OF9tZWRpYXBob3RvMTM4MjIucGRm', '2021-09-01'],
    ['Inspection cum Monitoring Report', 'Ninth Report of Joint Inspection-cum-monitoring of CETP-VAPI Industrial Area, Gujarat, in O.A.No. 95 of 2018, in the matter of Aryavart Foundation Vs. Vapi Green Enviro Ltd.', 'OA No. 95 of 2018', '9-REP-JOINTINSPECT-CETP-VAPI.pdf', '2021-08-01'],
    ['Guidelines', 'Environmental Guidelines for Poultry Farms', '-', 'TGF0ZXN0RmlsZS8zMzFfMTYyOTA5OTQwMF9tZWRpYXBob3RvMjg2MjcucGRm', '2021-08-01'],
    ['Report', 'Report of the Joint Committee in the matter of O.A No. 59 of 2021 regarding \'Six Killed in Quarry Blast in Hirenagavalli, Karnataka"', 'OA No. 59 of 2021', 'JointReport_2021_4.pdf', '2021-07-01'],
    ['NGT Order', 'Hon\'ble NGT in OA 60/2021 related with fire accident at M/s UPL, Jhagadia, Bharuch wherein, Hon\'ll NGT vide its order dated 11.06.2021 directed CPCB', 'OA No. 60 of 2021', 'OA-60-2021-UPL-PlantOrder-and-Report.pdf', '2021-06-01'],
    ['Inspection cum Monitoring Report', 'Eight Report of Joint Inspection-cum-monitoring of CETP-VAPI Industrial Area, Gujarat, in O.A.No. 95 of 2018, in the matter of Aryavart Foundation Vs. Vapi Green Enviro Ltd.', 'OA No. 95 of 2018', '8-REP-JOINTINSPECT-CETP-VAPI.pdf', '2021-06-01'],
    ['NGT Order', 'NGT Order dated: 23.03.2021 passed in :Original Application No. 258/2020 in the matter of In Re: News item published in the “Indian Express” dated 04.11.2020 titled “Ahmedabad: Nine killed as godown collapses after factory blast', 'OA No. 258 of 2020', 'NGT_order_23.03.2021.pdf', '2021-04-01'],
    ['&nbsp;', 'Information regarding OA No. 107/2020 in the matter of " News item published in the local daily “Indian Express Sunday Express” dated 28.06.2020 titled “Gas Leak in Agro Company Claims life of one”. [NGT Order]      [Report of the Joint Committee]', 'OA No. 107 of 2020', 'ngt_order_08.01.2021.pdf', '2021-02-01'],
    ['Report', 'Report Of Committee In Compliance Of Order Of Hon’ble Ngt, In The Matter Of Oa No. 258 Of 2020 With Respect To Fatal Accident At M/S Sahil Enterprise, Godown-12, Revakaka Estate, Shahwadi, Ahmedabad, Gujarat', 'OA No. 258 of 2020', 'Report-Committee-NGT-OANo258-of-2020-Jan2021.pdf', '2021-01-01'],
    ['Independent Report', 'Independent Report of the Committee in compliance of Order dated 08.07.2020 in OA No 108 of 2020, filed on 23.10.2020 and taken uo on 22.12.2020', 'OA No. 108 of 2020', 'Rep-Ind-Com-NGT108of2020_1.pdf', '2020-12-01'],
    ['&nbsp;', 'Information in the matter of OA. No. 134/2020 submitted to Hon’ble national green tribunal, principal bench, Delhi in compliance to Hon’ble NGT order dated July 23, 2020 [NGT Order]      [Report of the Joint Committee]', 'OA No. 134 of 2020', 'NGTORDER1-31.12.2020.pdf', '2020-12-01'],
    ['&nbsp;', 'Information in the matter of OA. No. 106/2020 submitted to Hon’ble national green tribunal, principal bench, Delhi in compliance to Hon’ble NGT order dated July 06, 2020 [Report of the Joint Committee]', 'OA No. 106 of 2020', 'REPORT2-31.12.2020.pdf', '2020-12-01'],
    ['Inspection cum Monitoring Report', 'Seventh Report of Joint Inspection-cum-monitoring of CETP-VAPI Industrial Area, Gujarat, in O.A.No. 95 of 2018, in the matter of Aryavart Foundation Vs. Vapi Green Enviro Ltd.', 'OA No. 95 of 2018', '7-REP-JOINTINSPECT-CETP-VAPI.pdf', '2020-12-01'],
    ['&nbsp;', 'O.A. No. 106/2020 in the matter of News item published in the local daily “Economic Times” dated 30.06.2020', 'OA No. 106 of 2020', '106-OF-2020-17122020.pdf', '2020-12-01'],
    ['Report', 'Report of CPCB in compliance to Hon’ble NGT Order dated 05.11.2020 in OA No. 249/2020 in the matter of Tribunal on its own motion Versus Ministry of Environment, Forest & Climate Change and Ors.', 'OA No. 249 of 2020', 'Report-O.A.No.-249-of-2020.pdf', '2020-11-01'],
    ['Compliance Report', 'Compliance Report of the Order passed by the Hon’ble National Green Tribunal (Principal Bench), New Delhi in the matter Original Application No. 616/2019 (Ramdevbhai Samatbhai Sanjva versus State of Gujarat & Ors.) related to Pollution in River Bhadar due to discharges from textile units in Jetpur', 'OA No. 616 of 2019', 'Report_of_Joint_committee_ GPCB_CPCB.pdf', '2020-11-01'],
    ['Protocol', 'Protocol for Enhanced Monitoring of Pesticides w.r.t. order passed by Hon\'ble NGT on 26.06.2020 in the matter of Original Application No. 46/2020', 'OA No. 46 of 2020', 'Protocoal-Mon-Pesticides-28102020.pdf', '2020-10-01'],
    ['Inspection cum Monitoring Report', 'Sixth Report of Joint Inspection-cum-monitoring of CETP-VAPI Industrial Area, Gujarat, in O.A.No. 95 of 2018, in the matter of Aryavart Foundation Vs. Vapi Green Enviro Ltd.', 'OA No. 95 of 2018', '6-REP-JOINTINSPECT-CETP-VAPI.pdf', '2020-10-01'],
    ['Additional Report', 'Additional Report of Committee on Environmental Damage Assessment in the matter-OA No. 22 of 2020 (Aryavart Foundation Vs M/s Yashashvi Rasayan Pvt Ltd Dahej & Ors)', 'OA No. 22 of 2020', 'ADDITIONAL_REPORT_Air_OA_22_of_2020-SEP-2020.pdf', '2020-09-01'],
    ['Report', 'Joint Inspection report of M/s SSF Polymer Ltd., Bahadurgarh, Haryana in case of OA No. 91/2020', 'OA No. 91 of 2020', 'Joint Report_OANo_91_2020_17092020.pdf', '2020-09-01'],
    ['Report', ' Report of Committee in the matter-OA No. 22 of 2020 (Aryavart Foundation Vs M/s Yashashvi Rasayan Pvt Ltd Dahej & Ors)', 'OA No. 22 of 2020', 'Report_08.09.2020.pdf', '2020-09-01'],
    ['Letter', 'Letter dated 19.03.2020 in the matter of Hon\'ble NGT OA No. 400 of 2017 titled as Westend Green Farms Society Versus Union of India & Ors.', 'OA No. 400 of 2017', 'Letter_19.03.2020.pdf', '2020-09-01'],
    ['Guidelines', 'Mechanism/Guidelines for Control of Pollution and Enforcement of Environment Norms at Individual Establishments and the Area/Cluster of Restaurants/Hotels/Motels /Banquets etc. in the matter of Hon\'ble NGT OA No. 400 of 2017 titled as Westend Green Farms Society Versus Union of India & Ors.', 'OA No. 400 of 2017', 'Mechanism_07.09.2020.pdf', '2020-09-01'],
    ['Format', 'Format in the matter of Hon\'ble NGT OA No. 400 of 2017 titled as Westend Green Farms Society Versus Union of India & Ors.', 'OA No. 400 of 2017', 'Format_07.09.2020.pdf', '2020-09-01'],
    ['Preliminary Report', 'Preliminary Report of the Committee of Experts constituted by the Hon’ble National Green Tribunal, Principal Bench, New Delhi in the matter of I.A. No. 30/2020 in O. A. No. 43 of 2020 (EZ) and I.A. No. 31/2020 in O. A. No. 44/2020(EZ) order dated 02.07.2020 ', 'OA No. 43 & 44 of 2017', 'Preliminary_report_28.07.2020.pdf', '2020-07-01'],
    ['Consolidated Report', 'Consolidated Report on Status of Compliance to Biomedical Waste Management Rules, 2016 in the matter of O.A. No. 710 of 2017 filed by Sh. Shailesh Singh ', 'OA No. 710 of 2017', 'Consolidated_report_18072020.pdf', '2020-07-01'],
    ['Inspection cum Monitoring Report', 'Fifth Report of Joint Inspection-cum-monitoring of CETP-VAPI Industrial Area, Gujarat, in O.A.No. 95 of 2018, in the matter of Aryavart Foundation Vs. Vapi Green Enviro Ltd.', 'OA No. 95 of 2018', '5-REP-JOINTINSPECT-CETP-VAPI.pdf', '2020-07-01'],
    ['NGT Order', 'NGT Order Dated 24.04.2020 regarding COVID-19 Waste Management', '-', 'NGT_Order_covid_28.04.2020.pdf', '2020-05-01'],
    ['Report', 'Report of the Joint Monitoring Committee constituted by the Hon\'ble NGT, Principal Bench, New Delhi as per order dated 08.05.2020 in the Matter of O.A. No. 73 of 2020 titled as In re to the Styrene Leak at M/s LG Polymers Pvt Ltd, Visakhapatnam, Andhra Pradesh', 'OA No. 73 of 2020', 'report_28-05-2020.pdf', '2020-05-01'],
    ['NGT Order', 'NGT Order dated 21.05.2020 of Hon’ble NGT, in the matter of OA No. 593 of 2017 (Paryavaran Suraksha Samiti & Anr. v/s Union of India & Ors.)', 'OA No. 593 of 2017', 'ngt_order_28-05-2020.pdf', '2020-05-01'],
    ['Steps Taken Report', '6th Steps Taken Report (in Compliance of Hon’ble NGT Order dated 03rd August, 2018 and 28th August, 2019, in the matter of OA No. 593/2017 (Paryavaran Suraksha Samiti & Anr. Vs. Union of India & Ors.)) (Status as on 04.02.2020)', 'OA No. 593 of 2017', 'Steps_Taken_Report_04.02.2020.pdf', '2020-02-01'],
    ['Mechanism', 'Mechanism for Environmental Management of Critically and Severely Polluted areas and consideration of activities/projects in such areas in compliance to Hon\'ble NGT order dated 23.08.2019 in the matter of OA no. 1038/2018.', 'OA No. 1038 of 2018', 'mechanism_cpa_28.01.2020.pdf', '2020-01-01'],
    ['Inspection cum Monitoring Report', 'Forth Report of Joint Inspection-cum-monitoring of CETP-VAPI Industrial Area, Gujarat, in O.A.No. 95 of 2018, in the matter of Aryavart Foundation Vs. Vapi Green Enviro Ltd.', 'OA No. 95 of 2018', '4-REP-JOINTINSPECT-CETP-VAPI.pdf', '2020-01-01'],
    ['Inspection cum Monitoring Report', 'Third Report of Joint Inspection-cum-monitoring of CETP-VAPI Industrial Area, Gujarat, in O.A.No. 95 of 2018, in the matter of Aryavart Foundation Vs. Vapi Green Enviro Ltd.', 'OA No. 95 of 2018', '3rd-REP-JOINTINSPECT-CETP-VAPI.pdf', '2019-11-01'],
    ['Steps Taken Report', '5th Steps Taken Report (in Compliance of Hon’ble NGT Order dated 03rd August, 2018 in the matter of Paryavaran Suraksha Samiti & Anr. Vs. Union of India & Ors. (OA No. 593 of 2017))(Status as on 22-10-2019)', 'OA No. 593 of 2017', 'Steps_Taken_Report_22.10.2019.pdf', '2019-10-01'],
    ['&nbsp;', 'Model District Environmental Management Plan for DMs District Environmental Plan Excel Sheet', '&nbsp;', 'OA-360-NGT-District-Envionment-Plan.pdf', '2019-10-01'],
    ['Guidelines', 'Guidelines for Utilisation of Treated Effluent in Irrigation', '&nbsp;', 'Guidelines-UTE-Irrigation.pdf', '2019-10-01'],
    ['Compendium', 'Compendium of Indian Standards on Slaughter House', '&nbsp;', 'slaughter_house_compandium.pdf', '2019-09-01'],
    ['Status Report', 'Status Report on Action Plan on Noise Management in India in O.A. no. 681/2018', 'OA No. 681 of 2018', 'Report_681-2018.pdf', '2019-09-01'],
    ['Inspection cum Monitoring Report', 'Second Report of Joint Inspection-cum-Monitoring of Common Effluent Treatment Plant (CEPT) Vapi Industrial Area, Gujarat', '&nbsp;', 'Final-Report-CETP-Vapi-Inspection.pdf', '2019-08-01'],
    ['Report', 'Revised Report on Damage Assessment of River Damanganga, cost of Restoration, Accountability & Liabilities of Defaulting Industries & CEPT, Vapi, Gujarat As Per Honble NGT Order Dated -11.01.2019 & 13.05.2019', '&nbsp;', 'REVISED-DAMANGANGA-REPORT.pdf', '2019-08-01'],
    ['Data', 'Solid waste Dumpsites Data of States/UTs (as per NGT Order dated 17.07.2019, O.A no. 519/2019)', 'OA No. 519 of 2019', 'TGF0ZXN0RmlsZS8yNjVfMTU2NTc3NDU4NV9tZWRpYXBob3RvMTM3NjQucGRm', '2019-08-01'],
    ['Compliance Report', 'Compliance Report On Order dt. 17.12.2018 of Hon’ble National Green Tribunal (NGT) in the matter Amit Upadhaya Vs SIEAA UP (O/A 263/2018 & 264/2018) {NGT Order + Report + Annexure}', 'OA No. 263 & 264 of 2018', 'Amit_Upadhaya_Report_09.08.2019.pdf', '2019-08-01'],
    ['Steps Taken Report', '4th Steps Taken Report (in Compliance of Hon’ble NGT Order dated 03rd August, 2018 in the matter of Paryavaran Suraksha Samiti & Anr. Vs. Union of India & Ors. (OA No. 593 of 2017))(Status as on 19-07-2019)', 'OA No. 593 of 2017', 'Steps_Taken_Report_19.07.2019.pdf', '2019-07-01'],
    ['Gap Analysis', 'Gap Analysis of the Complaince Reports submitted by states/Union Territories in compliance to Honble NGT order dated 16.01.2019, O.A. No. 606/2018', 'OA No. 606 of 2018', 'TGF0ZXN0RmlsZS9fMTU2NDEzNjgxNF9tZWRpYXBob3RvMTI1MTUucGRmf', '2019-07-01'],
    ['Inspection cum Monitoring Report', 'CPCB & GPCB Report on joint inspection of CETP Vapi in the matter of OA No 95/2018 (Aryavart foundation vs. M/s Vapi Vapi Green Enviro ltd. & Ors.)', 'OA No. 95 of 2018', 'joint_vapi_10.05.2019.pdf', '2019-05-01'],
    ['Report', 'Five Member Committee Report Submitted to Hon\'ble NGT in the matter of OA No 95/2018 (Aryavart foundation vs. M/s Vapi Green Enviro ltd. (CETP, Vapi) & Ors.)', 'OA No. 95 of 2018', 'NGT_Report_10.05.2019.pdf', '2019-05-01'],
    ['Report', 'Final Report of the Expert Committee Submitted to Hon\'ble NGT in the matter of Execution App No.11/2017 in OA No 159/2013 titled All India Lokadhikar Sangathan Vs Govt. of NCT of Delhi and Ors.', 'OA No. 159 of 2013', 'Final-report-24.04.2019.pdf', '2019-04-01'],
    ['Steps Taken Report', '3rd Steps Taken Report (in Compliance of Hon’ble NGT Order dated 03rd August, 2018 in the matter of Paryavaran Suraksha Samiti & Anr. Vs. Union of India & Ors. (OA No. 593 of 2017))(Status as on 15-04-2019)', 'OA No. 593 of 2017', 'Steps_Taken_Report_22.04.2019.pdf', '2019-04-01'],
    ['Steps Taken Report', '2nd Steps Taken Report (in Compliance of Hon’ble NGT Order dated 03rd August, 2018 in the matter of Paryavaran Suraksha Samiti & Anr. Vs. Union of India & Ors. (OA No. 593 of 2017))(Status as on 23-01-2019)', 'OA No. 593 of 2017', 'Steps_Taken_Report_31.01.2019.pdf', '2019-01-01'],
    ['Report', 'Report of the Committee chaired by Former Justice B.P. Katakey in the matter of The State of Meghalaya Vs All Dimasa Students Union Hasao District Committee & Ors.', '&nbsp;', 'Report-NGT-15.01.2019.pdf', '2019-01-01'],
    ['Progress Report', 'Progress Report of the NGT Committee in the matter of OA No. 164 of 2018 in the matter of Ashwany Kumar Dubey Vs UOI', 'OA No. 164 of 2018', 'progress-report-ngt-14.01.2019.pdf', '2019-01-01'],
    ['Steps Taken Report', '1st Steps Taken Report (in Compliance of Hon’ble NGT Order dated 03rd August, 2018 in the matter of Paryavaran Suraksha Samiti & Anr. Vs. Union of India & Ors. (OA No. 593 of 2017))(Status as on 26-10-2018)', 'OA No. 593 of 2017', 'Steps_Report_OANO593.pdf', '2018-11-01'],
    ['Report', 'Report on Status of Heavy Metal Concentrations in Ground Water at Dera Bassi in the NGT matter of OA No. 30/2013 and 33/2013', 'OA No. 30 & 33 of 2013', 'ngt-derabassi-heavy-metal-result.pdf', '2018-10-01'],
    ['Status Report', 'Status of Heavy metal concentrations in ground water at Dera Bassi in the matter of OA No. 33/2013 Karnail Singh vs CPCB & Others.', 'OA No. 33 of 2013', 'ngt-order-25.10.2018.pdf', '2018-10-01'],
    ['Compliance Report', 'Complaince status and inspection report of 87 industries along with 14 other industrial units mentioned in the application. O.A No. 33/2013 (THC) karnail singh & ors. vs cpcb & ors.', 'OA No. 33 of 2013', 'NGT-29.09.2018.pdf', '2018-09-01'],
    ['Status Report', 'Constitution of Two Member Committee on compliance of Hon’ble NGT order dated 30.07.2018 in Vardhman Kaushik Vs UoI & Ors OA 21/2014', 'OA No. 21 of 2014', 'two_member_committee_29.08.2018.pdf', '2018-08-01'],
    ['EOI', 'NGT invites Expression of Interest from Experts/Professionals of eminence in the field of environment', '&nbsp;', 'empanelment_of_experts-10.08.2018.pdf', '2018-08-01'],
    ['EOI', 'NGT invites Expression of Interest for Rendering Professional Services', '&nbsp;', 'rendering_professional_services-10.08.2018.pdf', '2018-08-01'],
    ['Reply', 'CPCB Reply Affidavit - Report on Hindon River - Action Plan', '&nbsp;', 'CPCB-Reply-Affidavit-Report-on-Hindon-Action-Plan.pdf', '2018-08-01'],
    ['Guidelines', 'Guidelines for Pollution Control in Kolhus', '&nbsp;', '20180618152427.pdf', '2018-06-01'],
    ['Status Report', 'Monitoring of use of Petcoke in Lime Kilns/Cement Kilns and Calcium Carbide Industry in NCR States', '&nbsp;', '20180618152427.pdf', '2018-05-01'],
    ['Report', 'Report in the matter of OA No.164/2018(Earlier OA No.276/2013)titled Ashwani kumar dubey Vs Union of India', 'OA No. 164 of 2018', 'Report_Ashwani_dubey_16.05.2018.pdf', '2018-05-01'],
    ['Status Report', 'Annexure 3.1 of Air Quality Assessment, Emission Inventory and Source Apportionment Studies in Compliance of M.A No. 1470/2017 in OA No. 433/2017 titles as Shibani Ghosh vs MoEF & CC & ORS', 'OA No. 433 of 2017', 'Annexure_3.1_27.02.2018.pdf', '2018-02-01'],
    ['Data', 'Ambient Air Quality Data of Delhi-NCR For the Month of December, 2017', '&nbsp;', 'Air_Quality_data_Delhi-NCR_December_2017.pdf', '2018-01-01'],
    ['Letter', 'Letters to SPCBs/PCCs for Compliance in Slaughter House operating under their Jurisdiction.', '&nbsp;', 'Compliance_status_09.01.2018.pdf', '2018-01-01'],
    ['Status Report', 'Ambient Air Quality Data of Delhi-NCR for November 2017: Compliance action of Hon\'ll NGT O.A. No. 21/2014 titled as Vardhman Kaushik Vs Union of India & ors.', 'OA No. 21 of 2014', 'Air-Quality-data-Delhi-NCR-November-2017.pdf', '2017-12-01'],
    ['&nbsp;', 'Misc. Application no. 1031/2017 filed by CPCB before NGT in execution of Application no. 34/2016 in the matter of Samir Mehta vs Union of India & Ors.', 'OA No. 1031 of 2017', 'MA_CPCB_04.10.2017.pdf', '2017-10-01'],
    ['Inspection Report', 'Joint Inspection Report of Crystal Crop Ltd. in compliance of Hon\'ble NGT order dated 15.05.2017', '&nbsp;', 'Jt-Inspec_Rept_Crystal_Crop_03102017.pdf', '2017-10-01'],
    ['Reply', 'Reply of NGT Order in the matter of Selaqui International School Vs Union of India & Ors-OA No. 451 of 2016', 'OA No. 451 of 2016', 'NGT_Selaqui_26.09.17.pdf', '2017-09-01'],
    ['Compliance Report', 'Compliance Statement on behalf of CPCB as directed in the matter of M.C.Mehta vs. UOIndia & ors. vide judgement dated-13.07.2017 Volume II', '&nbsp;', 'M-C-MEHTA-VOL-II.pdf', '2017-09-01'],
    ['Compliance Report', 'Compliance Statement on behalf of CPCB as directed in the matter of M.C.Mehta vs. UOIndia & ors. vide judgement dated-13.07.2017 Volume I', '&nbsp;', 'M-C-MEHTA-VOL-I.pdf', '2017-09-01'],
    ['Compliance Report', 'Compliance status of the Hon\'ble NGT vide order dated 10-07-2017 & 09-08-2017 in the matter of ASHWANI KUMAR & ANR. versus CPCB & ORS.', '&nbsp;', 'ASHWANI_KUMAR.pdf', '2017-09-01'],
    ['Status Report', 'Status of Action Plan Submission & Response to Hon\'ble NGT order dated 25.05.2017 of OA No. 199/2014 in the NGT matter OA No. 61/2017 Almitra H Patel vs Union of India & Ors.', 'OA No. 199 of 2014', 'TkdUL0NvbW1lbnRzX29uX2FjdGlvbl9wbGFuXzEyLjA2LjE3LnBkZg==', '2017-06-01'],
    ['Inspection Report', 'Joint Inspection Report by CPCB & UPPCB of M/s Triveni Engineering Industries Ltd. to NGT Order OA No. 177 of 2017', 'OA No. 177 of 2017', 'TkdUL2luc3BlY3Rpb25fcmVwb3J0XzI2LjA1LjIwMTcucGRm', '2017-05-01'],
    ['Inspection Report', 'Inspection Report of Sub Committee II in respect of Bulk Waste Generators in South Delhi Municipal Corporation Area (Part-I)', '&nbsp;', 'TkdUL05HVF9TdWJDb21taXR0ZWVfSUktUGFydDEucGRm', '2017-05-01'],
    ['Format', 'Format of Sub Committee II in respect of Bulk Waste Generators in South Delhi Municipal Corporation Area (Part-II)', '&nbsp;', 'TkdUL05HVF9TdWJDb21taXR0ZWUtSUktUGFydDIucGRm', '2017-05-01'],
    ['Affidavit', 'Affidavit dated 12.05.2017 by CPCB in response to NGT Order dated 25.04.2017 of OA No. 199/2014 and OA No. 281/2016 & OA No. 61/2017 in the matter of Almitra H Patel vs Union of India & Ors.', 'OA No. 199 of 2014', 'TkdUL2FmZmlkYXZpdF8xMi4wNS4xNy5wZGY=', '2017-05-01'],
    ['Inspection Report', 'Joint Inspection Report of the Special Inspection Committee carried out on 25.04.2017 in the matter of M C Mehta vs Union of India [O.A. NO. 200/2014] on dorection of Hon\'ble NGT dated 24.04.207 (including Inspection report of M/s Umang Dairies ltd., Gajraula , UP)', 'OA No. 200 of 2014', 'TkdUL2RyYWZ0X3JlcG9ydF9TcGVjaWFsX0NvbW1pdHRlZV8wNC4wNS4xNy5wZGY=', '2017-05-01'],
    ['Report', 'Report of Sub-Committee IV (EDMC Area) on Inspection of Bulk Waste Generators and Hospitals (O A No. 199 of 2014 & Original Application No. 281 of 2016 (M.A. No. 1007/2016) filed by Almitra H. Patel & Anr. Vs. Union of India & Ors. & Kudrat Sandhu Vs. Govt. of NCT & Ors)', 'OA No. 281 of 2016', 'TkdUL0ZpbmFsX1JlcG9ydF9TdWJfQ29tbWl0dGVlX0lWX0FwcmlsXzIwMTcucGRm', '2017-04-01'],
    ['Comments', 'Comments on Action plan/Strategy on Solid waste management submitted by States/UTs- Affidavit of CPCB in compliance of Hon\'ble NGT Order dated 07.03.2017 in the matter of OA No. 199/2014 & 61/2017 & 281/2016', 'OA No. 199 of 2014', 'TkdUL05HVF9vcmRlcl8zMC4wMy4xNy5wZGY=', '2017-03-01'],
    ['Report', 'A Report on "Assessment of Pollution of Drains Carrying Sewage /Industrial Effluent Joining River Ganga and its Tributaries (Kali-East/Ramganga) between Haridwar (Down) to Kanpur (Down)” Submitted to Hon\'ble NGT in the matter of M C Mehta vs Union of India & ORS (March 27 , 2017)', '&nbsp;', 'TkdUL0ZpbmFsX0FwcHJvdmVkX0RyYWluX1JlcG9ydF8yNy4wMy4yMDE3LnBkZg==', '2017-03-01'],
    ['Affidavit', 'Affidavit dated 06.03.2017 by CPCB in response to NGT Order of OA No. 199/2014 and OA No. 281/2016 & OA No. 61/2017 in the matter of Almitra H Patel vs Union of India & Ors.', 'OA No. 199 of 2014', 'TkdUL0FmZmlkYXZpdF8wNi4wMy4yMDE3LnBkZg==', '2017-03-01'],
    ['Comments', 'Comments on Action taken Reports submitted by SPCBs/PCCs(In compliance of Hon’ble NGT Order dated 13.01.2017 in the matter of Almitra H. Patel Vs Union of India 199/2014)', 'OA No. 199 of 2014', 'TkdUL0FjdGlvbl9UYWtlbl9SZXBvcnRzXzA0LjAzLjE3LnBkZg==', '2017-03-01'],
    ['Comments', 'Comments on Action plan submitted by States and UTs for Solid Waste Managment(In compliance of Hon’ble NGT Order dated 22.12.2016 & 07.02.2017) ', '&nbsp;', 'TkdUL0FjdGlvbl9QbGFuX2NvbW1lbnRzXzA0LjAzLjE3LnBkZg==', '2017-03-01'],
    ['Affidavit', 'Affidavit in compliance of Hon\'ll NGT Order dated 13.01.2017 in the matter of OA No. 199/2014 & 281/2016', 'OA No. 199 of 2014', 'TkdUL05HVF9vcmRlcl8wMS4wMy4xNy5wZGY=', '2017-03-01'],
    ['Report', 'Report of Chairman Expert Committee Constituted by the Hon\'ble NGT, New Delhi in the matter of OA No. 37/2015 S.P Muthuraman vs Union of India & Ors, February 2017 filed by CPCB', 'OA No. 37 of 2015', 'TkdUL05HVF9hbmphbGlfaW5mcmFfaG91c2luZ19MTFBfMTQuMDIuMTcucGRm', '2017-02-01'],
    ['NGT Order', 'NGT Order in the matter of Selaqui International School Vs Union of India & Ors-OA No. 451 of 2016', 'OA No. 451 of 2016', 'TkdUL05HVF9TZWxhcXVpXzA2LjAyLjE3LnBkZg==', '2017-02-01'],
    ['Report', 'A Report on "Assessment of Pollution of Drains Carrying Sewage /Industrial Effluent Joining River Ganga and its Tributaries (Kali-East/Ramganga) between Haridwar (Down) to Kanpur (Down)” Submitted to Hon\'ble NGT in the matter of M C Mehta vs Union of India & ORS (January 2017)', '&nbsp;', 'TkdUL0ZpbmFsX0RyYWZ0X0RyYWluX1JlcG9ydF8xMC4wMS4yMDE3LnBkZg==', '2017-01-01'],
    ['Report', 'A Report on "Assessment of Pollution of Drains Carrying Sewage /Industrial Effluent Joining River Ganga and its Tributaries (Kali-East/Ramganga) between Haridwar (Down) to Kanpur (Down)" Submitted to Hon\'ble NGT in the matter of M C Mehta vs Union of India & ORS', '&nbsp;', 'TkdUL0ZpbmFsX0RyYWZ0X0RyYWluX1JlcG9ydF8yMS0xMS0yMDE2LnBkZg==', '2016-12-01'],
    ['NGT Order', 'NGT Order dated 22.09.2016 in the matter of Almitra .H. Patel Vs Union of India & Ors-OA No. 199 of 2014', 'OA No. 199 of 2014', 'TkdUL05HVF9NYXR0ZXJfMDctMTEtMTYucGRm', '2016-09-01'],
    ['Action plan', 'Submission of Action plan for Control of Air pollution in Mahul, Ambapada & Chembur areas in Compliance of NGT(WZ)\'s Order dated 18.12.15 in Application no. 40/2014 (WZ) (dated-17.10.16)', 'OA No. 40 of 2014', 'TkdUL01haHVsX0FtYmFwYWRhX0NoZW1idXJfQVBfMDctMTEtMTYucGRm', '2016-09-01'],
    ['NGT Order', 'NGT Order dated 09.09.2016 in the matter of Almitra .H. Patel Vs Union of India & Ors-OA No. 199 of 2014', 'OA No. 199 of 2014', 'TkdUL05HVF9BTE1JVFJBX0hfUEFURUwucGRm', '2016-09-01'],
    ['Proposal', 'Restoration/Rejuvenation of River Ganga suggestions/Proposals for phase-1, Segment "B" Submitted before Honrable NGT in the matter of O.A. No. 501 of 2014 and in compliance of Order dated 05.07.2016', 'OA No. 501 of 2014', 'TkdUL0ZJTkFMX0dBTkdBX1JFSlVWRU5BVElPTl9SRVNUT1JBVElPTl84LjkuMjAxNi5wZGY=', '2016-08-01'],
    ['Satus Report', 'Water Quality of River Yamuna in Delhi Submitted to Hon\'ble NGT in the matter of Manoj Mishra vs Union of India & ORS (CWP 300 of 2013)', '&nbsp;', 'TkdUL3dhdGVyX3F1YWxpdHlfcml2ZXJfeWFtdW5hXzA4LTA5LTIwMTYucGRm', '2016-08-01'],
    ['Compliance Report', 'Compliance Statement on behalf of MoEFCC and CPCB', '&nbsp;', 'TkdUL0NvbXBsaWFuY2Vfc3RhdGVtZW50X29uX2JlaGFsZl9vZl9Nb0VGXyZfQ0NfYW5kX0NQQ0IucGRm', '2016-08-01'],
    ['Report', 'Report on Ganga Matter of Uttarakhand & Uttar pradesh', '&nbsp;', 'TkdUL1JlcG9ydF9vbl9HYW5nYV9NYXR0ZXJfb2ZfVXR0YXJha2hhbmRfYW5kX1VQLnBkZg==', '2016-06-01'],
    ['Report', 'Restoration Rejuvenation of River Ganga', '&nbsp;', 'TkdUL1Jlc3RvcmF0aW9uX1JlanV2ZW5hdGlvbl9vZl9SaXZlcl9HYW5nYS5wZGY=', '2016-06-01'],
    ['Inspection Report', 'Inspection Report on Solid Waste Management at Haridwar and Integrated SW Management Facility (under construction) at Sarai, Haridwar', '&nbsp;', 'TkdUL1JlcG9ydF9vbl9IYXJpZHdhci5wZGY=', '2016-06-01'],
    ['Report', 'Selection criteria for Waste Processing Technologies ', '&nbsp;', 'TkdUL1NXX3RyZWF0bWVudF9UZWNobm9sb2dpZXMucGRm', '2016-06-01'],
    ['Action Plan', 'Action Plan Compliance review report by CPCB for Noida CPA in the matter of Sanjay Agnihotri vs Union of India', '&nbsp;', 'TkdUL1NhbmpheV9hZ25paG90cmlfdnNfVW5pb25fb2ZfaW5kaWEucGRm', '2016-06-01'],
    ['Inspection Report', 'Inspection Report of STPs in Agra with reference to Hon\'ble NGT Court Order dated 09.12.2015 in the matter of O.A No.145 of 2015', 'OA No. 145 of 2015', 'TkdUL05HVF9DT1VSVF9NQVRURVIxMi0wNC0yMDE2LnBkZg==', '2016-02-01'],
    ['Report', 'Report of the Expert Committee constituted by NGT,Principal bench ,New Delhi in the Matter of O.A NO.37 of 2015', 'OA No. 37 of 2015', 'TkdUL2ZpbmFsLnBkZg==', '2015-12-01'],
    ['Affidavit', 'Affidavit ( including minutes of meeting ) of CPCB in the Matter of Shweta Narayan Vs Union of India ( Application no. 178/2014)', 'OA No. 178 of 2014', 'TkdUL0FmZmlkYXZpdF8xNzgtMjAxNF8yNTAyMjAxNi5wZGY=', '2015-12-01'],
    ['NGT Order', 'NGT Judgement on online monitoring in sugar industries, order 03-11-2015', '&nbsp;', 'TkdULzAzLTIwMTUoQ1opQVBMLTAzLTExLTIwMTUucGRm', '2015-11-01'],
    ['Report', 'Report of Core Committee Constituted by NGT for "Monitoring of Potential Hazards of Ind. Development in Singrauli Area', '&nbsp;', 'TkdUL05HVF9SZXBvcnRfMzAwOTE1LnBkZg==', '2015-09-01'],
    ['NGT Order', 'NGT order dated August 03, 2015 in case of M/S Ramaa Shayama Paper Ltd.', '&nbsp;', 'TkdUL05HVF9PcmRlcl8wMzA4MjAxNS5wZGY=', '2015-08-01'],
    ['MoM', 'Minutes of the Meeting held pursuant to Direction of National Green Tribunal in Application No. 102 of 2014 by Sandplast (India) & Anr. regarding Utilization of Fly Ash', 'OA No. 102 of 2014', 'TkdUL01pbnV0ZXMtU2FuZC1QbGFzdC1GSW5hbC5wZGY=', '2015-01-01'],
    ['MoM', 'Minutes of the meeting held pursuant to the Direction of NGT in Application No. 199 of 2014 in Matter of Almitra H.Patel & Anr regarding Standards for MSW Incinerators for Power Generation and visit of the MSW sites in Haryana', 'OA No. 199 of 2014', 'TkdUL01pbnV0ZXNfTkdUX01TV18ucGRm', '2015-01-01'],
    ['Standards', 'Standards for Incinerators of MSW Proposed in the Final Draft of the MSW(Management and Handling ) Rules, 2015 [At schedule II A (11) (c)]', '&nbsp;', 'TkdUL1N0YW5kYXJkX0luY2luZXJhdG9yc19NU1cucGRm', '2015-01-01'],
    ['Affidavit', 'Affidavit filed in compliance of the oral order of Hon\'ble NGT dated 20-02-2015', '&nbsp;', 'TkdUL0FmZmlkYXZpdC1OR1QtMjMtMTItMjAxNS5wZGY=', '2015-01-01'],
    ['Guideline', 'Guidelines for in-use Generator Sets as per direction of Hon\'ble NGT, Delhi', '&nbsp;', 'TkdUL0d1aWRlbGluZXMtZm9yLVVzZWRHZW5lcmF0b3JTZXRzLnBkZg==', '2015-01-01'],
    ['Report', 'Final Report on Air Quality Status in the NCR (as per NGT order in application no. 21 of 2014 (In the matter of Vardhman Kaushik Vs. Union of India))', 'OA No. 21 of 2014', 'TkdUL1JlcG9ydF9BUVN0YXR1c19pbl9OQ1JfMjUwMjIwMTUucGRm', '2015-01-01'],
    ['Action Plan', 'Suggestive/Indicative "Action Plan for Management of Municipal Solid Waste" [In compliance with Hon\'ble National Green Tribunal Order Dated 5th February, 2015 in the Matter of OA No. 199 of 2014, Almitra H. Patel & Anr. Vs Union of India & Ors.]', 'OA No. 199 of 2014', 'TkdUL05HVC1vcmRlcl8yMDE1LTAyLTA1LnBkZg==', '2015-01-01'],
    ['Report', 'Report on inspection carried out by team in compliance of NGT order dated 20-01-2015', '&nbsp;', 'TkdUL1JlcG9ydE5HVDIwLTAxLTE1LnBkZg==', '2015-01-01'],
    ['Report', 'Report of the Committee Constituted by Hon\'ll National Green Tribunal, Principal Bench, New Delhi in the Case No. 667/2014 arising out of O.A. No. 102/2014, M/s. Sandplast India Pvt. Ltd., Vs. MoEF and Others', 'OA No. 102 of 2014', 'TkdUL05HVC1SZXBvcnQtMTUtMDEtMjAxNS5wZGY=', '2015-01-01'],
    ['Consolidated Report', 'Consolidated Annual Review Report 2013-14 on Implementation of MSW (Management & Handling) Rules, 2000', '&nbsp;', 'TkdUL0FudWFsLVJldy1SZXAtTVNXLTIwMTMtMTQucGRm', '2015-01-01'],
    ['NGT Order', 'NGT order dated November 3, 2014 and December 02, 2014 in the matter of Almitra H. Patel Vs Union of India & Ors-OA.No. 199 0f 2014', 'OA No. 199 of 2014', 'TkdUL05HVC5wZGY=', '2015-01-01'],
    ['Interim Report', 'INTERIM REPORT OF THE CORE COMMITTEE CONSTITUTED BY THE HON\'BLE NATIONAL GREEN TRIBUNAL (NGT), NEW DELHI FOR "MONITORING OFPOTENTIAL HAZARDS OF INDUSTRIALDEVELOPMENT IN SINGRAULI AREA', '&nbsp;', 'TkdUL05HVC1GSU5BTC5wZGY=', '2015-01-01'],
    ['Criteria', 'Criteria for Seriously Polluting Industries', '&nbsp;', 'TkdUL0dQSV9Dcml0ZXJpYU1vZGlmaWVkLnBkZg==', '2015-01-01'],
    ['Report', 'The compliance statement on behalf of CPCB for the order 29.10.14 in M.A. No. 879 in NGT application No. 299 of 2013', 'OA No. 299 of 2013', 'TkdUL05HVF9BcHBOT18yOTlfMjkxMDIwMTQucGRm', '2015-01-01'],
    ['Report', 'The compliance statement on behalf of CPCB for the order 15.09.14 in M.A. No. 879 in NGT application No. 299 of 2013', 'OA No. 299 of 2013', 'TkdUL05HVF9BcHBOT18yOTlfMTcwOTIwMTQucGRm', '2015-01-01'],
    ['Report', 'In compliance of the order passed by the Hon\'ble Tribunal dated July 04, 2014, the report of committee submitted by CPCB to the Tribunal in accordance to order May 06, 2014', '&nbsp;', 'TkdUL0NvbWxpYW5jZVJyZXBvcnRMZWdhbEZpbmFsLnBkZg==', '2015-01-01'],
    ['Report', 'The compliance statement for theorder passed passed by the Hon\'ble Tribunal dated 05-02-2014 the details of Grossly Polluting Industries (GPI) operating in 5 Ganga states', '&nbsp;', 'TkdUL0NvbWxpYW5jZVJyZXBvcnRMZWdhbEZpbmFsLnBkZg==', '2015-01-01'],
];

    $this->command->info('Starting Migration for NGT Court Cases...');

    if (empty($rows)) {
        $this->command->warn('No records found in the legacy table.');
        return;
    }

    $insertData = [];

    foreach ($rows as $row) {

        $fileName = $row[3];

        if (!str_contains($fileName, '.pdf')) {
            $decoded = base64_decode($fileName, true);
            if ($decoded) {
                $fileName = basename($decoded);
            }
        }

        $pDate = $row[4];
        $type = ($row[0] == '&nbsp;' || empty($row[0])) ? 'General' : $row[0];
        $qa   = ($row[2] == '&nbsp;' || empty($row[2])) ? '-' : $row[2];

        $insertData[] = [
            'type'         => $type,
            'type_hi'      => translateToHindi($type),
            'title'        => $row[1],
            'title_hi'     => translateToHindi($row[1]),
            'qa_number'    => $qa,
            'qa_number_hi' => translateToHindi($qa),
            'file_name'    => $fileName,
            'publish_date' => $pDate,
            'is_approved'  => 1,
            'is_published' => 1,
            'created_by'   => 1,
            'created_at'   => $pDate,
            'updated_at'   => $pDate,
        ];
    }

    foreach (array_chunk($insertData, 10) as $chunk) {
        DB::table('ngt_court_cases')->insert($chunk);
        $this->command->getOutput()->write('.');
    }
    $this->command->info('NGT Court Cases migration completed successfully.');
}
}