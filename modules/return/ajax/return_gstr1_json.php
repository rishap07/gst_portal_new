<?php
/*
    * 
    *  Developed By        :   Monika Deswal
    *  Date Created        :   July 27, 2017
    *  Last Modification   :   Summary of JSTR1
    * 
*/
$jstr1_array = array();
$getSummary= isset($_POST['json'])?$_POST['json']:'';
$returnmonth= isset($_POST['returnmonth'])?$_POST['returnmonth']:'';
$jstr1_array = json_decode($getSummary,true);

$response = $response_doc = '';
$doc_issue_array = array();
//echo '<pre>';print_r($jstr1_array);
//echo $getSummary;

if(!empty($jstr1_array)) {
	$response = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                    <thead>
                    <tr>
                        <th>Type Of Invoice</th>
                        <th style="text-align:right">No. Invoices</th>
                        <th style="text-align:right">Total Tax ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:right">Total IGST ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:right">Total CGST  ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:right">Total CESS ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:right">Total SGST ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:right">Total value ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                    </tr>';
                    if(isset($jstr1_array['sec_sum'])) {
                       
                        aasort($jstr1_array['sec_sum'],"sec_nm");
                        foreach ($jstr1_array['sec_sum'] as $key1 => $jstr1_value) {

                            if($jstr1_value['sec_nm'] == 'DOC_ISSUE') {
                                $doc_issue_array = $jstr1_value;
                            }
                            else {
                                $invoice_number = $jstr1_value['ttl_rec'];
                                $ttl_val = isset($jstr1_value['ttl_val'])?$jstr1_value['ttl_val']:0;
                                $ttl_tax = isset($jstr1_value['ttl_tax'])?$jstr1_value['ttl_tax']:0;
                                $ttl_igst = isset($jstr1_value['ttl_igst'])?$jstr1_value['ttl_igst']:0;
                                $ttl_cgst = isset($jstr1_value['ttl_cgst'])?$jstr1_value['ttl_cgst']:0;
                                $ttl_cess = isset($jstr1_value['ttl_cess'])?$jstr1_value['ttl_cess']:0;
                                $ttl_sgst = isset($jstr1_value['ttl_sgst'])?$jstr1_value['ttl_sgst']:0;
                                $response .= '<tr>
                                    <td>'.$jstr1_value['sec_nm'].'</td>
                                    <td align="right">'.$invoice_number.'</td>
                                    <td align="right">'.$ttl_tax.'</td>
                                    <td align="right">'.$ttl_igst.'</td>
                                    <td align="right">'.$ttl_cgst.'</td>
                                    <td align="right">'.$ttl_cess.'</td>
                                    <td align="right">'.$ttl_sgst.'</td>
                                    <td align="right">'.$ttl_val.'</td>
                                    <td align="right"><a class="gstr1ViewBtn" href="'.PROJECT_URL.'?page=return_get_summary_view&type='.$jstr1_value['sec_nm'].'&returnmonth='.$returnmonth.'" target="_blank">view</a></td>
                                    <td align="right">';
                                    if($invoice_number>0) {
                                        $response .= '<a href="javascript:;" class="gstr1ViewDeleteBtn" type="'.$jstr1_value['sec_nm'].'" deleteall="all"><i class="fa fa-trash"></i></a>';
                                    }
                                   
                                    $response .= '</td>
                                </tr>';
                            }
                            
                        }   
                    }
                    $response .= '
                </thead>
        	</table>';
}
if(!empty($doc_issue_array)) {
    //echo '<pre>';print_r($doc_issue_array);
    $response_doc .= '<div class="dasboardbox">
                    <div class="lightblue dashtopcol">
                        <div class="dashcoltxt">
                            <span class="boxpricetxt">'.(isset($doc_issue_array['ttl_doc_issued'])?$doc_issue_array['ttl_doc_issued']:'0').'</span><br><div class="txtyear">Total Doc Issued</div>
                        </div>
                    </div>
                </div>
                <div class="dasboardbox">
                    <div class="lightgreen dashtopcol">
                        <div class="dashcoltxt">
                            <span class="boxpricetxt">'.(isset($doc_issue_array['net_doc_issued'])?$doc_issue_array['net_doc_issued']:'0').'</span><br><div class="txtyear">Net Doc Issued</div>
                        </div>
                    </div>
                </div>
                <div class="dasboardbox">
                    <div class="lightyellowbg dashtopcol">
                        <div class="dashcoltxt">
                            <span class="boxpricetxt">'.(isset($doc_issue_array['ttl_rec'])?$doc_issue_array['ttl_rec']:'0').'</span><br><div class="txtyear">Total Recored</div>
                        </div>
                    </div>
                </div>
                <div class="dasboardbox">
                    <div class="pinkbg dashtopcol">
                        <div class="dashcoltxt">
                            <span class="boxpricetxt">'.(isset($doc_issue_array['ttl_doc_cancelled'])?$doc_issue_array['ttl_doc_cancelled']:'0').'</span><br><div class="txtyear">Cancelled Doc</div>
                        </div>
                    </div>
                </div>
                        ';
}

else {
    echo 'No Record found';
}
echo $response_doc;
echo $response;


function aasort (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}
?>







