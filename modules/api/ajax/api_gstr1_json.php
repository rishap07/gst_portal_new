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
$jstr1_array = json_decode($getSummary,true);
//echo '<pre>';print_r($jstr1_array);
$response = '';
if(!empty($jstr1_array)) {
	$response .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
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
                    </tr>';
                    if(isset($jstr1_array['sec_sum'])) {
                        foreach ($jstr1_array['sec_sum'] as $key1 => $jstr1_value) {
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
                            </tr>';
                        }   
                    }
                    $response .= '
                </thead>
        	</table>';
}

echo $response;

