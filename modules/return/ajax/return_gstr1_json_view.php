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
$type= isset($_POST['type'])?$_POST['type']:'';
$returnmonth= isset($_POST['returnmonth'])?$_POST['returnmonth']:'';

$jstr1_array = json_decode($getSummary,true);
$response = '';

//echo $getSummary;
//echo '<pre>'; print_r($_POST);
if(!empty($jstr1_array)) {

    if($type == 'B2B') {         
        if(isset($jstr1_array['b2b'])) {
            $response .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
            <thead>
            <tr>
                <th>Number</th>
                <th style="text-align:center">Invoice number</th> 
                <th style="text-align:center">Ctin</th> 
                <th style="text-align:center">Pos </th> 
                <th style="text-align:center">Item </th>
                <th style="text-align:center">Invoice type</th>    
                <th style="text-align:center">Invoice date</th>
                <th style="text-align:center">Value ( <i class="fa fa-inr"></i> )</th>
                <th style="text-align:center">Csamt ( <i class="fa fa-inr"></i> )</th>
                <th style="text-align:center">Rate</th>
                <th style="text-align:center">Tax value ( <i class="fa fa-inr"></i> )</th>
                <th style="text-align:center">Iamt ( <i class="fa fa-inr"></i> )</th>                
                <th style="text-align:center">Updby </th>
                <th style="text-align:center">Rchrg</th>
                <th></th>
                
            </tr>';
            foreach ($jstr1_array['b2b'] as $key1 => $inv_value) {
                if(isset($inv_value['inv'])) {
                    $ctin = isset($inv_value['ctin'])?$inv_value['ctin']:'';
                    foreach ($inv_value['inv'] as $key2 => $jstr1_value) {
                        $val = isset($jstr1_value['val'])?$jstr1_value['val']:0;
                        $itms = isset($jstr1_value['itms'])?$jstr1_value['itms']:array();
                        $inv_typ = isset($jstr1_value['inv_typ'])?$jstr1_value['inv_typ']:'';
                        $pos = isset($jstr1_value['pos'])?$jstr1_value['pos']:0;
                        $updby = isset($jstr1_value['updby'])?$jstr1_value['updby']:'';
                        $idt = isset($jstr1_value['idt'])?$jstr1_value['idt']:'';
                        $rchrg = isset($jstr1_value['rchrg'])?$jstr1_value['rchrg']:'';
                        $inum = isset($jstr1_value['inum'])?$jstr1_value['inum']:'';



                        if(!empty($itms)) {
                            $i=1;
                            foreach ($itms as $key3 => $value) {
                                $num = isset($value['num'])?$value['num']:0;
                                $csamt = isset($value['itm_det']['csamt'])?$value['itm_det']['csamt']:0;
                                $rt = isset($value['itm_det']['rt'])?$value['itm_det']['rt']:0;
                                $txval = isset($value['itm_det']['txval'])?$value['itm_det']['txval']:0;
                                $iamt = isset($value['itm_det']['iamt'])?$value['itm_det']['iamt']:0;

                                $response .='<tr>
                                    <td align="right">'.$num.'</td>
                                    <td align="center">'.$inum.'</td>
                                    <td align="center">'.$ctin.'</td>
                                    <td align="center">'.$pos.'</td>
                                    <td align="center">'.$i++.'</td>
                                    <td align="center">'.$inv_typ.'</td>
                                    <td align="center">'.$idt.'</td>
                                    <td align="center">'.$val.'</td>
                                    <td align="center">'.$csamt.'</td>
                                    <td align="center">'.$rt.'</td>
                                    <td align="center">'.$txval.'</td>
                                    <td align="center">'.$iamt.'</td>
                                    <td align="center">'.$updby.'</td>
                                    <td align="center">'.$rchrg.'</td>
                                    <td align="center"><a href="javascript:;" class="gstr1ViewDeleteBtn" ctin = '.$ctin.' idt = '.$idt.' inum = '.$inum.' type="B2B"><i class="fa fa-trash"></i></a></td>
                                </tr>';
                            }
                        }


                    }
                }
            }
            $response .= '
                </thead>
            </table>';
        }
    }

    if($type == 'B2CS') {
       //echo '<pre>'; print_r($jstr1_array);
	   $response .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                    <thead>
                    <tr>
                        <th>Supply Type</th>
                        <th style="text-align:center">Pos</th>
                        <th style="text-align:center">Type </th>
                        <th style="text-align:center">Samt ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:center">Csamt ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:center">Rate</th>
                        <th style="text-align:center">Tax Value  ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:center">Iamt ( <i class="fa fa-inr"></i> )</th>
                        <th></th>
                    </tr>';
                    if(isset($jstr1_array['b2cs'])) {
                        foreach ($jstr1_array['b2cs'] as $key1 => $jstr1_value) {
                           $samt = isset($jstr1_value['samt'])?$jstr1_value['samt']:0;
                           $csamt = isset($jstr1_value['csamt'])?$jstr1_value['csamt']:0;
                            $rt = isset($jstr1_value['rt'])?$jstr1_value['rt']:0;
                            $pos = isset($jstr1_value['pos'])?$jstr1_value['pos']:0;
                            $txval = isset($jstr1_value['txval'])?$jstr1_value['txval']:0;
                            $typ = isset($jstr1_value['typ'])?$jstr1_value['typ']:0;
                            $iamt = isset($jstr1_value['iamt'])?$jstr1_value['iamt']:0;
                            $sply_ty = isset($jstr1_value['sply_ty'])?$jstr1_value['sply_ty']:0;
                            $chksum = isset($jstr1_value['chksum'])?$jstr1_value['chksum']:'';
                            $response .= '<tr>
                              <td>'.$sply_ty.'</td>
                              <td align="center">'.$pos.'</td>
                              <td align="center">'.$typ.'</td>
                                <td align="center">'.$samt.'</td>
                                <td align="center">'.$csamt.'</td>
                                <td align="center">'.$rt.'</td>
                                <td align="center">'.$txval.'</td>
                                <td align="center">'.$iamt.'</td>
                                <td align="center"><a href="javascript:;" class="gstr1ViewDeleteBtn" sply_ty = '.$sply_ty.' pos = '.$pos.' iamt = '.$iamt.' typ = '.$typ.' rt = '.$rt.' chksum = '.$chksum.' type="B2CS" ><i class="fa fa-trash"></i></a></td>
                            </tr>';
                        }   
                    }
                    $response .= '
                </thead>
        	</table>';
    }

    if($type == 'B2CL') {
        //echo '<pre>';print_r($jstr1_array);
       $response .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                    <thead>
                    <tr>
                        <th>Number</th>
                        <th style="text-align:center">Invoice number</th>
                        <th style="text-align:center">Pos </th>
                        <th style="text-align:center">Item </th>
                        <th style="text-align:center">Invoice date </th>
                        <th style="text-align:center">Value ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:center">Csamt ( <i class="fa fa-inr"></i> ) </th>
                        <th style="text-align:center">Rate</th>
                        <th style="text-align:center">Tax value ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:center">Iamt ( <i class="fa fa-inr"></i> )</th>
                        <th></th>
                        
                    </tr>';
                    foreach ($jstr1_array['b2cl'] as $key1 => $inv_value) {
                        if(isset($inv_value['inv'])) {
                            foreach ($inv_value['inv'] as $key2 => $jstr1_value) {
                                $val = isset($jstr1_value['val'])?$jstr1_value['val']:0;
                                $itms = isset($jstr1_value['itms'])?$jstr1_value['itms']:array();
                                $pos = isset($inv_value['pos'])?$inv_value['pos']:0;
                                $idt = isset($jstr1_value['idt'])?$jstr1_value['idt']:'';
                                $inum = isset($jstr1_value['inum'])?$jstr1_value['inum']:'';
                                
                                if(!empty($itms)) {
                                    $i=1;
                                    foreach ($itms as $key3 => $value) {
                                        $num = isset($value['num'])?$value['num']:0;
                                        $csamt = isset($value['itm_det']['csamt'])?$value['itm_det']['csamt']:0;
                                        $rt = isset($value['itm_det']['rt'])?$value['itm_det']['rt']:0;
                                        $txval = isset($value['itm_det']['txval'])?$value['itm_det']['txval']:0;
                                        $iamt = isset($value['itm_det']['iamt'])?$value['itm_det']['iamt']:0;

                                        $response .='<tr>
                                            <td align="right">'.$num.'</td>
                                            <td align="center">'.$inum.'</td>
                                            <td align="center">'.$pos.'</td>
                                            <td align="center">'.$i++.'</td>
                                            <td align="center">'.$idt.'</td>
                                            <td align="center">'.$val.'</td>
                                            <td align="center">'.$csamt.'</td>
                                            <td align="center">'.$rt.'</td>
                                            <td align="center">'.$txval.'</td>
                                            <td align="center">'.$iamt.'</td>
                                            <td align="center"><a href="javascript:;" class="gstr1ViewDeleteBtn"><i class="fa fa-trash"></i></a></td>
                                        </tr>';
                                    }
                                }


                            }
                        }
                    }   
                    $response .= '
                </thead>
            </table>';
    }

    if($type == 'HSN') {
        $response .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                    <thead>
                    <tr>
                        <th>Number</th>
                        <th style="text-align:center">Hsn code</th>
                        <th style="text-align:center">Value ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:center">Unit</th>
                        <th style="text-align:center">Quantity</th>
                        <th style="text-align:center">Tax Value ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:center">Samt ( <i class="fa fa-inr"></i> ) </th>
                        <th style="text-align:center">Csamt ( <i class="fa fa-inr"></i> ) </th>
                        <th style="text-align:center">Camt ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:center">Iamt ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:center">Description</th>
                        <th></th>
                    </tr>';
                    if(isset($jstr1_array['hsn']['data'])) {
                        $hsn = $jstr1_array['hsn']['data'];
                        
                        foreach ($hsn as $key1 => $jstr1_value) {
                            $num = isset($jstr1_value['num'])?$jstr1_value['num']:0;
                            $val = isset($jstr1_value['val'])?$jstr1_value['val']:0;
                            $hsn_sc = isset($jstr1_value['hsn_sc'])?$jstr1_value['hsn_sc']:'';
                            $samt = isset($jstr1_value['samt'])?$jstr1_value['samt']:0;
                            $csamt = isset($jstr1_value['csamt'])?$jstr1_value['csamt']:0;
                            $uqc = isset($jstr1_value['uqc'])?$jstr1_value['uqc']:0;
                            $qty = isset($jstr1_value['qty'])?$jstr1_value['qty']:0;
                            $txval = isset($jstr1_value['txval'])?$jstr1_value['txval']:0;
                            $camt = isset($jstr1_value['camt'])?$jstr1_value['camt']:0;
                            $iamt = isset($jstr1_value['iamt'])?$jstr1_value['iamt']:0;
                            $desc = isset($jstr1_value['desc'])?$jstr1_value['desc']:'';
                            $response .= '<tr>
                                <td align="right">'.$num.'</td>
                                <td align="center">'.$hsn_sc.'</td>
                                <td align="center">'.$val.'</td>
                                <td align="center">'.$uqc.'</td>
                                <td align="center">'.$qty.'</td>
                                 <td align="center">'.$txval.'</td>
                                <td align="center">'.$samt.'</td>
                                <td align="center">'.$csamt.'</td>
                                <td align="center">'.$camt.'</td>
                                <td align="center">'.$iamt.'</td>
                                <td align="center">'.$desc.'</td>
                                <td align="center"><a href="javascript:;" class="gstr1ViewDeleteBtn"><i class="fa fa-trash"></i></a></td>
                            </tr>';
                        }   
                    }
                    $response .= '
                </thead>
            </table>';
    }
    
    if($type == 'CDNR') {
        echo '<pre>';print_r($jstr1_array);
        $response .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
            <thead>
            <tr>
                <th>Num</th>
                <th style="text-align:center">Inum</th> 
                <th style="text-align:center">Item </th>
                <th style="text-align:center">Nt num</th>    
                <th style="text-align:center">Nt date</th>
                <th style="text-align:center">Pgst</th>
                <th style="text-align:center">Idt</th>
                <th style="text-align:center">Val ( <i class="fa fa-inr"></i> )</th>
                <th style="text-align:center">Csamt ( <i class="fa fa-inr"></i> )</th>
                <th style="text-align:center">Rate</th>
                <th style="text-align:center">Txval ( <i class="fa fa-inr"></i> )</th>
                <th style="text-align:center">Iamt ( <i class="fa fa-inr"></i> )</th>                
                <th style="text-align:center">Updby </th>
                <th style="text-align:center">Ntty</th>
                <th></th>
                
            </tr>';
            foreach ($jstr1_array['cdnr'] as $key1 => $inv_value) {
                $cfs = isset($inv_value['cfs'])?$inv_value['cfs']:'';
                $nt = isset($inv_value['nt'])?$inv_value['nt']:array();
                $ctin = isset($inv_value['ctin'])?$inv_value['ctin']:'';
                if(isset($nt) && !empty($nt)) {
                    foreach ($nt as $key2 => $jstr1_value) {
                       
                        $val = isset($jstr1_value['val'])?$jstr1_value['val']:0;
                        $itms = isset($jstr1_value['itms'])?$jstr1_value['itms']:array();
                        $updby = isset($jstr1_value['updby'])?$jstr1_value['updby']:'';
                        $nt_num = isset($jstr1_value['nt_num'])?$jstr1_value['nt_num']:'';
                        $inum = isset($jstr1_value['inum'])?$jstr1_value['inum']:'';
                        $rsn = isset($jstr1_value['rsn'])?$jstr1_value['rsn']:0;
                        
                        $idt = isset($jstr1_value['idt'])?$jstr1_value['idt']:'';
                        $nt_dt = isset($jstr1_value['nt_dt'])?$jstr1_value['nt_dt']:'';
                        $p_gst = isset($jstr1_value['p_gst'])?$jstr1_value['p_gst']:'';
                        $ntty = isset($jstr1_value['ntty'])?$jstr1_value['ntty']:'';


                        if(!empty($itms)) {
                            $i=1;
                            foreach ($itms as $key3 => $value) {
                                $num = isset($value['num'])?$value['num']:0;
                                $csamt = isset($value['itm_det']['csamt'])?$value['itm_det']['csamt']:0;
                                $rt = isset($value['itm_det']['rt'])?$value['itm_det']['rt']:0;
                                $txval = isset($value['itm_det']['txval'])?$value['itm_det']['txval']:0;
                                $iamt = isset($value['itm_det']['iamt'])?$value['itm_det']['iamt']:0;

                                $response .='<tr>
                                    <td align="right">'.$num.'</td>
                                    <td align="center">'.$inum.'</td>
                                    <td align="center">'.$i++.'</td>
                                    <td align="center">'.$nt_num.'</td>
                                    <td align="center">'.$nt_dt.'</td>
                                    <td align="center">'.$p_gst.'</td>
                                    <td align="center">'.$idt.'</td>
                                    <td align="center">'.$val.'</td>
                                    <td align="center">'.$csamt.'</td>
                                    <td align="center">'.$rt.'</td>
                                    <td align="center">'.$txval.'</td>
                                    <td align="center">'.$iamt.'</td>
                                    <td align="center">'.$updby.'</td>
                                    <td align="center">'.$ntty.'</td>
                                    <td align="center"><a href="javascript:;" class="gstr1ViewDeleteBtn" ctin = '.$ctin.' idt = '.$idt.' inum = '.$inum.' nt_num = '.$nt_num.' nt_dt = '.$nt_dt.' type="CDNR"><i class="fa fa-trash"></i></a></td>
                                </tr>';
                            }
                        }


                    }
                }
            }
            $response .= '
                </thead>
            </table>';
    }

    if($type == 'CDNUR') {
        //echo '<pre>';print_r($jstr1_array);
        $response .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
            <thead>
            <tr>
                <th>Num</th>
                <th >Inum</th> 
                <th>Item </th>
                <th >Idt</th>
                <th >Typ</th>
                <th >NtNum</th>    
                <th >NtDt</th>
                <th>Pgst</th>
                
                <th >Val(<i class="fa fa-inr"></i>)</th>
                <th >Csamt(<i class="fa fa-inr"></i>)</th>
                <th >Rt</th>
                <th >Txval(<i class="fa fa-inr"></i>)</th>
                <th >Iamt(<i class="fa fa-inr"></i>)</th>                
                <th >Rsn</th>
                <th >Ntty</th>
                <th></th>
                
            </tr>';
            foreach ($jstr1_array['cdnur'] as $key1 => $jstr1_value) {
                   
                $val = isset($jstr1_value['val'])?$jstr1_value['val']:0;
                $itms = isset($jstr1_value['itms'])?$jstr1_value['itms']:array();
                $nt_num = isset($jstr1_value['nt_num'])?$jstr1_value['nt_num']:'';
                $inum = isset($jstr1_value['inum'])?$jstr1_value['inum']:'';
                $rsn = isset($jstr1_value['rsn'])?$jstr1_value['rsn']:0;
                
                $idt = isset($jstr1_value['idt'])?$jstr1_value['idt']:'';
                $nt_dt = isset($jstr1_value['nt_dt'])?$jstr1_value['nt_dt']:'';
                $p_gst = isset($jstr1_value['p_gst'])?$jstr1_value['p_gst']:'';
                $rsn = isset($jstr1_value['rsn'])?$jstr1_value['rsn']:'';
                $ntty = isset($jstr1_value['ntty'])?$jstr1_value['ntty']:'';
                $typ = isset($jstr1_value['typ'])?$jstr1_value['typ']:'';

                if(!empty($itms)) {
                    $i=1;
                    foreach ($itms as $key3 => $value) {
                        $num = isset($value['num'])?$value['num']:0;
                        $csamt = isset($value['itm_det']['csamt'])?$value['itm_det']['csamt']:0;
                        $rt = isset($value['itm_det']['rt'])?$value['itm_det']['rt']:0;
                        $txval = isset($value['itm_det']['txval'])?$value['itm_det']['txval']:0;
                        $iamt = isset($value['itm_det']['iamt'])?$value['itm_det']['iamt']:0;

                        $response .='<tr>
                            <td align="right">'.$num.'</td>
                            <td align="center">'.$inum.'</td>
                            <td align="center">'.$i++.'</td>
                            <td align="center">'.$typ.'</td>
                            <td align="center">'.$idt.'</td>
                            <td align="center">'.$nt_num.'</td>
                            <td align="center">'.$nt_dt.'</td>
                            <td align="center">'.$p_gst.'</td>
                            
                            <td align="center">'.$val.'</td>
                            <td align="center">'.$csamt.'</td>
                            <td align="center">'.$rt.'</td>
                            <td align="center">'.$txval.'</td>
                            <td align="center">'.$iamt.'</td>
                            <td align="center">'.$rsn.'</td>
                            <td align="center">'.$ntty.'</td>
                            <td align="center"><a href="javascript:;" class="gstr1ViewDeleteBtn"  idt = '.$idt.' inum = '.$inum.' nt_num = '.$nt_num.' nt_dt = '.$nt_dt.' type="CDNUR"><i class="fa fa-trash"></i></a></td>
                        </tr>';
                    }
                }


            }
            $response .= '
                </thead>
            </table>';
    }

    if($type == 'TXPD') {
        $response .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                    <thead>
                    <tr>
                        <th>Supply Type</th>
                        <th style="text-align:center">Place of supply</th>
                        <th style="text-align:center">Item</th>
                        <th style="text-align:center">Csamt ( <i class="fa fa-inr"></i> ) </th>
                        <th style="text-align:center">Rate</th>
                        <th style="text-align:center">Iamt ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:center">Ad Amt ( <i class="fa fa-inr"></i> )</th>
                        <th></th>
                    </tr>';
                    if(isset($jstr1_array['txpd'])) {
                        foreach ($jstr1_array['txpd'] as $key1 => $jstr1_value) {
                            $pos = isset($jstr1_value['pos'])?$jstr1_value['pos']:0;
                            $itms = isset($jstr1_value['itms'])?$jstr1_value['itms']:array();
                            $sply_ty = isset($jstr1_value['sply_ty'])?$jstr1_value['sply_ty']:'';
                            if(!empty($itms)) {
                                $i=1;
                                foreach ($itms as $key3 => $value) {
                                    $csamt = isset($value['csamt'])?$value['csamt']:0;
                                    $rt = isset($value['rt'])?$value['rt']:0;
                                    $ad_amt = isset($value['ad_amt'])?$value['ad_amt']:0;
                                    $iamt = isset($value['iamt'])?$value['iamt']:0;
                                    $response .='<tr>
                                        <td >'.$sply_ty.'</td>
                                        <td align="center">'.$pos.'</td>
                                        <td align="center">'.$i++.'</td>
                                        <td align="center">'.$csamt.'</td>
                                        <td align="center">'.$rt.'</td>
                                        <td align="center">'.$iamt.'</td>
                                        <td align="center">'.$ad_amt.'</td>
                                        <td align="center"><a href="javascript:;" class="gstr1ViewDeleteBtn"><i class="fa fa-trash"></i></a></td>
                                    </tr>';
                                }
                            }   
                        }   
                    }
                    $response .= '
                </thead>
            </table>';
    }
    if($type == 'AT') {
        //echo '<pre>';print_r($jstr1_array);
        $response .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                    <thead>
                    <tr>
                        <th>Supply Type</th>
                        <th style="text-align:center">Place of supply</th>
                        <th style="text-align:center">Item</th>
                        <th style="text-align:center">Csamt ( <i class="fa fa-inr"></i> ) </th>
                        <th style="text-align:center">Rate</th>
                        <th style="text-align:center">Iamt ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:center">Ad Amt ( <i class="fa fa-inr"></i> )</th>
                        <th></th>
                    </tr>';
                    if(isset($jstr1_array['at'])) {
                        foreach ($jstr1_array['at'] as $key1 => $jstr1_value) {
                            $pos = isset($jstr1_value['pos'])?$jstr1_value['pos']:0;
                            $chksum = isset($jstr1_value['chksum'])?$jstr1_value['chksum']:'';
                            $itms = isset($jstr1_value['itms'])?$jstr1_value['itms']:array();
                            $sply_ty = isset($jstr1_value['sply_ty'])?$jstr1_value['sply_ty']:'';
                            $i=1;
                            if(!empty($itms)) {
                                foreach ($itms as $key3 => $value) {
                                    $csamt = isset($value['csamt'])?$value['csamt']:0;
                                    $rt = isset($value['rt'])?$value['rt']:0;
                                    $ad_amt = isset($value['ad_amt'])?$value['ad_amt']:0;
                                    $iamt = isset($value['iamt'])?$value['iamt']:0;
                                    $response .='<tr>
                                        <td >'.$sply_ty.'</td>
                                        <td align="center">'.$pos.'</td>
                                        <td align="center">'.$i++.'</td>
                                        <td align="center">'.$csamt.'</td>
                                        <td align="center">'.$rt.'</td>
                                        <td align="center">'.$iamt.'</td>
                                        <td align="center">'.$ad_amt.'</td>
                                        <td align="center"><a href="javascript:;" class="gstr1ViewDeleteBtn" sply_ty = '.$sply_ty.' pos = '.$pos.' chksum = '.$chksum.' type="AT" ><i class="fa fa-trash"></i></a></td>
                                    </tr>';
                                }
                            }   
                        }   
                    }
                    $response .= '
                </thead>
            </table>';
    }
    if($type == 'EXP') {
       $response .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                    <thead>
                    <tr>
                        <th>Exp Type</th>
                        <th style="text-align:center">Invoice number</th>
                        <th style="text-align:center">Item</th>
                        <th style="text-align:center">Invoice date  </th>
                        <th style="text-align:center">Sb Num </th>
                        <th style="text-align:center">Sb Code</th>
                        <th style="text-align:center">Sb Date</th>
                        <th style="text-align:center">Value ( <i class="fa fa-inr"></i> ) </th>
                        <th style="text-align:center">Rate</th>
                        <th style="text-align:center">Tax value ( <i class="fa fa-inr"></i> )</th>
                        <th style="text-align:center">Iamt ( <i class="fa fa-inr"></i> )</th>
                        <th></th>
                    </tr>';
                    foreach ($jstr1_array['exp'] as $key1 => $inv_value) {
                        if(isset($inv_value['inv'])) {
                            $exp_typ = isset($inv_value['exp_typ'])?$inv_value['exp_typ']:'';
                            foreach ($inv_value['inv'] as $key2 => $jstr1_value) {
                                $val = isset($jstr1_value['val'])?$jstr1_value['val']:0;
                                $itms = isset($jstr1_value['itms'])?$jstr1_value['itms']:array();
                                $sbnum = isset($jstr1_value['sbnum'])?$jstr1_value['sbnum']:0;
                                $idt = isset($jstr1_value['idt'])?$jstr1_value['idt']:'';
                                $sbdt = isset($jstr1_value['sbdt'])?$jstr1_value['sbdt']:'';
                                $inum = isset($jstr1_value['inum'])?$jstr1_value['inum']:'';
                                $sbpcode = isset($jstr1_value['sbpcode'])?$jstr1_value['sbpcode']:'';

                                if(!empty($itms)) {
                                    $i=1;
                                    foreach ($itms as $key3 => $value) {
                                        $rt = isset($value['rt'])?$value['rt']:0;
                                        $txval = isset($value['txval'])?$value['txval']:0;
                                        $iamt = isset($value['iamt'])?$value['iamt']:0;

                                        $response .='<tr>
                                            <td align="center">'.$exp_typ.'</td>
                                            <td align="center">'.$inum.'</td>
                                            <td align="center">'.$i++.'</td>
                                            <td align="center">'.$idt.'</td>
                                            <td align="center">'.$sbnum.'</td>
                                            <td align="center">'.$sbpcode.'</td>
                                             <td align="center">'.$sbdt.'</td>
                                            <td align="center">'.$val.'</td>
                                            <td align="center">'.$rt.'</td>
                                            <td align="center">'.$txval.'</td>
                                            <td align="center">'.$iamt.'</td>
                                            <td align="center"><a href="javascript:;" class="gstr1ViewDeleteBtn" idt = '.$idt.' inum = '.$inum.' exp_typ = '.$exp_typ.' type="EXP" ><i class="fa fa-trash"></i></a></td>
                                        </tr>';
                                    }
                                }

                            }
                        }
                    }   
                    $response .= '
                </thead>
            </table>';
    }
}
else {
    echo 'No Record found';
}
echo $response;
?>
<script type="text/javascript">    
    $(document).ready(function () {
        $('.gstr1ViewDeleteBtn').on('click', function () {
            var type = $(this).attr('type');
            var returnmonth = "<?php echo $returnmonth;?>";
            var arrValues = [];
            if(type == 'B2B') {
                var ctin = $(this).attr('ctin');
                var inum = $(this).attr('inum');
                var idt = $(this).attr('idt');
                arrValues = [ctin,inum,idt];
            }
            if(type == 'CDNR') {
                var ctin = $(this).attr('ctin');
                var inum = $(this).attr('inum');
                var idt = $(this).attr('idt');
                var nt_num = $(this).attr('nt_num');
                var nt_dt = $(this).attr('nt_dt');
                arrValues = [ctin,inum,idt,nt_num,nt_dt];
            }
            if(type == 'CDNUR') {
                var inum = $(this).attr('inum');
                var idt = $(this).attr('idt');
                var nt_num = $(this).attr('nt_num');
                var nt_dt = $(this).attr('nt_dt');
                arrValues = [inum,idt,nt_num,nt_dt];
            }
            
            if(type == 'EXP') {
                var inum = $(this).attr('inum');
                var idt = $(this).attr('idt');
                var exp_typ = $(this).attr('exp_typ');
                arrValues = [inum,idt,exp_typ];
            }
            if(type == 'AT') {
                var sply_ty = $(this).attr('sply_ty');
                var pos = $(this).attr('pos');
                var chksum = $(this).attr('chksum');
                arrValues = [sply_ty,pos,chksum];
            }
            if(type == 'B2CS') {
                var chksum = $(this).attr('chksum');
                var sply_ty = $(this).attr('sply_ty');
                var pos = $(this).attr('pos');
                var typ = $(this).attr('typ');
                var rt = $(this).attr('rt');
                arrValues = [sply_ty,pos,typ,rt,chksum];
            }
                        
            delete_item_invoice(type,returnmonth,arrValues);          
        });
    });

    /******* To delele invoice of GSTR1 ********/
    function delete_item_invoice(type,returnmonth,arrValues) {
        if(type!= '' && arrValues != '') {
            $.ajax({
                url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr1_delete_item_invoice",
                type: "post",
                data: {type:type,returnmonth:returnmonth,arrValues: arrValues},
                success: function (response) {
                   // location.reload();
                },
                error: function() {
                }
            });
        }
        else {
            alert('Invalid procces');
        }
        
    }
    /******* To delele invoice of GSTR1 ********/
</script>


