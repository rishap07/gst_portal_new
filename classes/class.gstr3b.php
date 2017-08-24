<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class gstr3b extends validation {
    
    function __construct() {
        parent::__construct();
    }
    
   public function deleteSaveGstr3b()
   {
		$return_id =   isset($_POST['returnid']) ? $_POST['returnid'] : '';
		$userid = $_SESSION['user_detail']['user_id'];
		 if($this->update(TAB_PREFIX.'client_return_gstr3b', array('is_deleted' => 1), array('return_id' => $return_id)))
		 {
		 $this->setSuccess('GSTR3B Data clear successfully');
		   $this->logMsg("GSTR3B ClearData Financial month :".$this->sanitize($_GET['returnmonth']),"gstr_3b");
  
		 return true;
		 }
   }
  	public  function write_excel()
	{    $returnmonth='';
		if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '')
	{
	    $returnmonth= $_REQUEST['returnmonth'];
	}
		
		$objPHPExcel = new PHPExcel();
		//Activate the First Excel Sheet
		$ActiveSheet = $objPHPExcel->setActiveSheetIndex(0);
		$Header = array('Nature of Supplies','Total Taxable Value', 'Integrated Tax','Central Tax','State/UT Tax','Cess Tax');
	     $sql = "select  *  from ".TAB_PREFIX."client_return_gstr3b where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0'  order by return_id desc limit 0,1";
	 
	    $returndata = $this->get_results($sql);
		    
	   $dataitem = array();
	   $data2 = array();
	   $data3 = array();
	   $datalate_fees = array();
	   $datapayment_integrated = array();
	   $datapayment_central = array();
	   $datapayment_state = array();
	   $datapayment_cess = array();
	   $data_tds = array();
		$data_tcs = array();
			foreach($returndata as $data)
			{
			array_push($dataitem, array('(a) Outward taxable supplies (other than zero rated, nil rated and exempted)',$data->total_tax_value_supplya,$data->integrated_tax_value_supplya,$data->central_tax_value_supplya,$data->state_tax_value_supplya,$data->cess_tax_value_supplya));
			array_push($dataitem, array('(b) Outward taxable supplies (zero rated )',$data->total_tax_value_supplyb,$data->integrated_tax_value_supplyb,$data->central_tax_value_supplyb,$data->state_tax_value_supplyb,$data->cess_tax_value_supplyb));
			array_push($dataitem, array('(c) Other outward supplies (Nil rated, exempted)',$data->total_tax_value_supplyc,$data->integrated_tax_value_supplyc,$data->central_tax_value_supplyc,$data->state_tax_value_supplyc,$data->cess_tax_value_supplyc));
			array_push($dataitem, array('(d) Inward supplies (liable to reverse charge)',$data->total_tax_value_supplyd,$data->integrated_tax_value_supplyd,$data->central_tax_value_supplyd,$data->state_tax_value_supplyd,$data->cess_tax_value_supplyd));
			array_push($dataitem, array('(e) Non-GST outward supplies',$data->total_tax_value_supplye,$data->integrated_tax_value_supplye,$data->central_tax_value_supplye,$data->state_tax_value_supplye,$data->cess_tax_value_supplye));
	        array_push($data2, array('(A) ITC Available (whether in full or part)','','','',''));
			array_push($data2, array('(1) Import of goods',$data->integrated_tax_import_of_goods,$data->central_tax_import_of_goods,$data->state_tax_import_of_goods,$data->cess_tax_import_of_goods));
			array_push($data2, array('(2) Import of services',$data->integrated_tax_import_of_services,$data->central_tax_import_of_services,$data->state_tax_import_of_services,$data->cess_tax_import_of_services));
			array_push($data2, array('(3) Inward supplies liable to reverse charge (other than 1 & 2 above)	',$data->integrated_tax_inward_supplies_reverse_charge,$data->central_tax_inward_supplies_reverse_charge,$data->state_tax_inward_supplies_reverse_charge,$data->cess_tax_inward_supplies_reverse_charge));
			array_push($data2, array('(4)Inward supplies from ISD',$data->integrated_tax_inward_supplies,$data->central_tax_inward_supplies,$data->state_tax_inward_supplies,$data->cess_tax_inward_supplies));
			array_push($data2, array('(5) All other ITC',$data->integrated_tax_allother_itc,$data->central_tax_allother_itc,$data->state_tax_allother_itc,$data->cess_tax_allother_itc));
			array_push($data2, array('(B) ITC Reversed','','','',''));
			array_push($data2, array('(1) As per rules 42 & 43 of CGST Rules',$data->integrated_tax_itc_reversed_cgstrules,$data->central_tax_itc_reversed_cgstrules,$data->state_tax_itc_reversed_cgstrules,$data->cess_tax_itc_reversed_cgstrules));
			array_push($data2, array('(2) Others',$data->integrated_tax_itc_reversed_other,$data->central_tax_itc_reversed_other,$data->state_tax_itc_reversed_other,$data->cess_tax_itc_reversed_other));
			array_push($data2, array('(C) Net ITC Available (A) – (B)',$data->integrated_tax_net_itc_a_b,$data->central_tax_net_itc_a_b,$data->state_tax_net_itc_a_b,$data->cess_tax_net_itc_a_b));
			array_push($data2, array('(D) Ineligible ITC',$data->integrated_tax_inligible_itc,$data->central_tax_inligible_itc,$data->state_tax_inligible_itc,$data->cess_tax_inligible_itc));
			array_push($data2, array('(1) As per section 17(5)',$data->integrated_tax_inligible_itc_17_5,$data->central_tax_inligible_itc_17_5,$data->state_tax_inligible_itc_17_5,$data->cess_tax_inligible_itc_17_5));
			array_push($data2, array('(2) Others',$data->integrated_tax_inligible_itc_others,$data->central_tax_inligible_itc_others,$data->state_tax_inligible_itc_others,$data->cess_tax_inligible_itc_others));
			array_push($data3, array('From a supplier under composition scheme, Exempt and Nil rated supply',$data->inter_state_supplies_composition_scheme,$data->intra_state_supplies_composition_scheme));
			array_push($data3, array('Non GST supply',$data->inter_state_supplies_nongst_supply,$data->intra_state_supplies_nongst_supply));
	        array_push($datalate_fees, array('Interest amount',$data->interest_latefees_integrated_tax,$data->interest_latefees_central_tax,$data->interest_latefees_state_tax,$data->interest_latefees_cess_tax));
			array_push($datapayment_integrated, array('Integrated Tax',$data->tax_payable_integrated_tax,$data->integrated_fee_integrated_tax,$data->central_integrated_tax,$data->state_integrated_tax,$data->cess_integrated_tax,$data->taxpaid_tdstcs_integrated_tax,$data->taxpaid_cess_integrated_tax,$data->interest_integrated_tax,$data->latefee_integrated_tax));
	       	array_push($datapayment_central, array('Central Tax',$data->tax_payable_central_tax,$data->integrated_fee_central_tax,$data->central_central_tax,$data->state_central_tax,$data->cess_central_tax,$data->taxpaid_tdstcs_central_tax,$data->taxpaid_cess_central_tax,$data->interest_central_tax,$data->latefee_central_tax));
	        array_push($datapayment_state, array('State/UT Tax',$data->tax_payable_stateut_tax,$data->integrated_stateut_tax,$data->central_stateut_tax,$data->state_stateut_tax,$data->cess_stateut_tax,$data->taxpaid_tcs_stateut_tax,$data->taxpaid_cess_stateut_tax,$data->interest_stateut_tax,$data->latefee_stateut_tax));
	        array_push($datapayment_cess, array('Cess',$data->tax_payable_cess_tax,$data->integrated_cess_tax,$data->central_cess_tax,$data->state_cess_tax,$data->cess_stateut_tax,$data->taxpaid_tcs_cess_tax,$data->taxpaid_cess_cess_tax,$data->interest_cess_tax,$data->latefee_stateut_tax));
	        array_push($data_tds, array('TDS',$data->integrated_tax_tds,$data->central_tax_tds,$data->state_tax_tds));
			array_push($data_tcs, array('TCS',$data->integrated_tax_tcs,$data->central_tax_tcs,$data->state_tax_tcs));

	 
			}
		//Write the Header
		$i=0;
		foreach($Header as $ind_el)
		{
			//Convert index to Excel compatible Location
			$Location = PHPExcel_Cell::stringFromColumnIndex($i) . '2';
			$ActiveSheet->setCellValue($Location, $ind_el);
			$i++;
		}
		
		//Insert that data from Row 2, Column A (index 0)
		$rowIndex=3;
		$columnIndex=0; //Column A
		foreach($dataitem as $row)
		{			
			foreach($row as $ind_el)
			{
				$Location = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . $rowIndex;
				//var_dump($Location);
				$ActiveSheet->setCellValue($Location, $ind_el); 	//Insert the Data at the specific cell specified by $Location
				$columnIndex++;
			}
			
			$rowIndex++;
			$columnIndex = 0;
		}		
		
		//code for 3.2 supply made to unregistered person	
		
		$Header = array('','Place of Supply (State/UT)', 'Total Taxable value','Amount of Integrated Tax');
		$data1 = array();
		$state='';
		$total_taxable_value='';
		$total_amount_of_integrated_tax='';
		$sql="select *,final_submit,count(returnid) as totalinvoice,final_submit from ".TAB_PREFIX."client_return_gstr3b_pos as s INNER join ".TAB_PREFIX."client_return_gstr3b as client3b on client3b.financial_month=s.financial_month and s.added_by='".$_SESSION["user_detail"]["user_id"]."' and s.financial_month like '%".$returnmonth."%' and type='0'";
	     $editflag=0;
	                            $return_a = $this->get_results($sql);
								if($return_a[0]->totalinvoice > 0 )
								{
									 if (isset($return_a[0]->totalinvoice)) {
										 $editflag=1;
										    $str1  = substr($return_a[0]->place_of_supply,0,-1);
											$str1 = (explode(",",$str1));
											$str2  = substr($return_a[0]->totaltaxable_value,0,-1);
											$total_taxable_value  = $str2;
											$str2 = (explode(",",$str2));
											$str3  = substr($return_a[0]->amount_of_integrated_tax,0,-1);
											$total_amount_of_integrated_tax=$str3;
											$str3 = (explode(",",$str3));
									
							
									 } 
									
									  for($i=0;$i < sizeof($str1); $i++) {
	                                $sql="select state_name from gst_master_state as s where state_id=".$str1[$i]."";
	                                $return_state = $this->get_results($sql);
									if(!empty($return_state))
									{
									$state = $state.$return_state[0]->state_name.',';	
									 //array_push($data1, array('Supplies made to Unregistered Persons',$return_state[0]->state_name,$str2[$i],$str3[$i]));
									}
										 array_push($data1, array('Supplies to Unregistered Persons',$return_state[0]->state_name,$str2[$i],$str3[$i]));

									  }
									
								}                    
	                            
		$state='';
		$total_taxable_value='';
		$total_amount_of_integrated_tax='';
		$sql="select *,final_submit,count(returnid) as totalinvoice,final_submit from ".TAB_PREFIX."client_return_gstr3b_pos as s INNER join ".TAB_PREFIX."client_return_gstr3b as client3b on client3b.financial_month=s.financial_month and s.added_by='".$_SESSION["user_detail"]["user_id"]."' and s.financial_month like '%".$returnmonth."%' and type='1'";
	                        
	                             $editflag=0;
	                            $return_a = $this->get_results($sql);
								if($return_a[0]->totalinvoice > 0 )
								{
									 if (isset($return_a[0]->totalinvoice)) {
										 $editflag=1;
										    $str1  = substr($return_a[0]->place_of_supply,0,-1);
											$str1 = (explode(",",$str1));
											$str2  = substr($return_a[0]->totaltaxable_value,0,-1);
											$total_taxable_value  = $str2;
											$str2 = (explode(",",$str2));
											$str3  = substr($return_a[0]->amount_of_integrated_tax,0,-1);
											$total_amount_of_integrated_tax=$str3;
											$str3 = (explode(",",$str3));
									
							
									 } 
									
									  for($i=0;$i < sizeof($str1); $i++) {
	                                $sql="select state_name from gst_master_state as s where state_id=".$str1[$i]."";
	                                $return_state = $this->get_results($sql);
									if(!empty($return_state))
									{
									$state = $state.$return_state[0]->state_name.',';	
									 //array_push($data1, array('Supplies made to Unregistered Persons',$return_state[0]->state_name,$str2[$i],$str3[$i]));
									}
									array_push($data1, array('Supplies made to Composition Taxable Persons',$return_state[0]->state_name,$str2[$i],$str3[$i]));
		
									  }
									
								}
		$state='';
		$total_taxable_value='';
		$total_amount_of_integrated_tax='';
		$sql="select *,final_submit,count(returnid) as totalinvoice,final_submit from ".TAB_PREFIX."client_return_gstr3b_pos as s INNER join ".TAB_PREFIX."client_return_gstr3b as client3b on client3b.financial_month=s.financial_month and s.added_by='".$_SESSION["user_detail"]["user_id"]."' and s.financial_month like '%".$returnmonth."%' and type='2'";
	                        
	                             $editflag=0;
	                            $return_a = $this->get_results($sql);
								if($return_a[0]->totalinvoice > 0 )
								{
									 if (isset($return_a[0]->totalinvoice)) {
										 $editflag=1;
										    $str1  = substr($return_a[0]->place_of_supply,0,-1);
											$str1 = (explode(",",$str1));
											$str2  = substr($return_a[0]->totaltaxable_value,0,-1);
											$total_taxable_value  = $str2;
											$str2 = (explode(",",$str2));
											$str3  = substr($return_a[0]->amount_of_integrated_tax,0,-1);
											$total_amount_of_integrated_tax=$str3;
											$str3 = (explode(",",$str3));
									
							
									 } 
									
									  for($i=0;$i < sizeof($str1); $i++) {
	                                $sql="select state_name from gst_master_state as s where state_id=".$str1[$i]."";
	                                $return_state = $this->get_results($sql);
									if(!empty($return_state))
									{
									$state = $state.$return_state[0]->state_name.',';	
									 //array_push($data1, array('Supplies made to Unregistered Persons',$return_state[0]->state_name,$str2[$i],$str3[$i]));
									}
									array_push($data1, array('Supplies made to UIN holders',$return_state[0]->state_name,$str2[$i],$str3[$i]));
		
									  }
									
								}	
								
	  
		//Write the Header
		$i=0;
		foreach($Header as $ind_el)
		{
			//Convert index to Excel compatible Location
			$Location = PHPExcel_Cell::stringFromColumnIndex($i) . '9';
			$ActiveSheet->setCellValue($Location, $ind_el);
			$i++;
		}
		
	  //Insert that data from Row 2, Column A (index 0)
		$rowIndex=10;
		$columnIndex=0; //Column A
		foreach($data1 as $row)
		{			
			foreach($row as $ind_el)
			{
				$Location = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . $rowIndex;
				//var_dump($Location);
				$ActiveSheet->setCellValue($Location, $ind_el); 	//Insert the Data at the specific cell specified by $Location
				$columnIndex++;
				 $Range = 'A'.$rowIndex;
		         $color = 'fdede8'; // Subheading color
		         $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
	  
			}
			
			$rowIndex++;
			$columnIndex = 0;
		}		
		
		//code end here
		//code for eligible ITc
		
		$Header = array('Details','Integrated Tax', 'Central Tax','State/UT Tax','Cess');
	    
		
		
		//Write the Header
		    $cell='A'.$rowIndex.':'.'J'.$rowIndex;
		    $Range = $cell;
			$color = 'adadad'; // Topheading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		 
			$ActiveSheet->mergeCells($cell);
			$ActiveSheet->setCellValue('A'.$rowIndex, '4. Eligible ITC');
			$newrow=0;
			$newrow= $rowIndex+1;
			$cell='';
			$cell='A'.$newrow.':'.'J'.$newrow;
		    $Range = $cell;
			$color = 'f0f0f0'; // heading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		   
			$i=0;
			
		     $start =$rowIndex+1;
			
			foreach($Header as $ind_el)
			{
				//Convert index to Excel compatible Location
				$Location = PHPExcel_Cell::stringFromColumnIndex($i) . $start;
				$ActiveSheet->setCellValue($Location, $ind_el);
				$i++;
			}
			
		  //Insert that data from Row 2, Column A (index 0)
			$rowIndex=$rowIndex+2;
			$columnIndex=0; //Column A
			foreach($data2 as $row)
			{			
				foreach($row as $ind_el)
				{
					$Location = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . $rowIndex;
					//var_dump($Location);
					$ActiveSheet->setCellValue($Location, $ind_el); 	//Insert the Data at the specific cell specified by $Location
					$columnIndex++;
					 $Range = 'A'.$rowIndex;
			         $color = 'fdede8'; // Subheading color
			         $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		  
				}
				
				$rowIndex++;
				$columnIndex = 0;
			}		
			//end here eligible ITC Code
			//code for value exempt,nil-rated and non-gst inward supplies
			$Header = array('Nature of supplies','Inter-State supplies', 'Intra-State supplies');
			
			 $cell='A'.$rowIndex.':'.'J'.$rowIndex;
		    $Range = $cell;
			$color = 'adadad'; // Topheading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		 
			$ActiveSheet->mergeCells($cell);
			$ActiveSheet->setCellValue('A'.$rowIndex, '5. Values of exempt, nil-rated and non-GST inward supplies');
			$newrow=0;
			$newrow= $rowIndex+1;
			$cell='';
			$cell='A'.$newrow.':'.'J'.$newrow;
		    $Range = $cell;
			$color = 'f0f0f0'; // heading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		   
		    
			//Write the Header
			$i=0;
			$start=0;
			$start =$rowIndex+1;
			foreach($Header as $ind_el)
			{
				//Convert index to Excel compatible Location
				$Location = PHPExcel_Cell::stringFromColumnIndex($i) . $start;
				$ActiveSheet->setCellValue($Location, $ind_el);
				$i++;
			}
			
		  //Insert that data from Row 2, Column A (index 0)
				$rowIndex=$rowIndex+2;
			$columnIndex=0; //Column A
			foreach($data3 as $row)
			{			
				foreach($row as $ind_el)
				{
					$Location = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . $rowIndex;
					//var_dump($Location);
					$ActiveSheet->setCellValue($Location, $ind_el); 	//Insert the Data at the specific cell specified by $Location
					$columnIndex++;
					 $Range = 'A'.$rowIndex;
			         $color = 'fdede8'; // Subheading color
			         $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		               $ActiveSheet->getStyle($Range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
				}
				
				$rowIndex++;
				$columnIndex = 0;
			}
			//code for interest & late fees header and data code
			$Header = array('Interest and late fee','Integrated Tax', 'Central Tax','State/UT','Cess');
			
			
		    
			//Write the Header
			 $cell='A'.$rowIndex.':'.'J'.$rowIndex;
		    $Range = $cell;
			$color = 'adadad'; // Topheading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		 
			$ActiveSheet->mergeCells($cell);
			$ActiveSheet->setCellValue('A'.$rowIndex, '5.1 Interest and late fee payable');
			$newrow=0;
			$newrow= $rowIndex+1;
			$cell='';
			$cell='A'.$newrow.':'.'J'.$newrow;
		    $Range = $cell;
			$color = 'f0f0f0'; // heading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		   
			$i=0;
			$start=0;
			$start =$rowIndex+1;
			foreach($Header as $ind_el)
			{
				//Convert index to Excel compatible Location
				$Location = PHPExcel_Cell::stringFromColumnIndex($i) . $start;
				$ActiveSheet->setCellValue($Location, $ind_el);
				$i++;
			}
			
		  //Insert that data from Row 2, Column A (index 0)
			$rowIndex=$rowIndex+2;
			$columnIndex=0; //Column A
			foreach($datalate_fees as $row)
			{			
				foreach($row as $ind_el)
				{
					$Location = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . $rowIndex;
					//var_dump($Location);
					$ActiveSheet->setCellValue($Location, $ind_el); 	//Insert the Data at the specific cell specified by $Location
					$columnIndex++;
					 $Range = 'A'.$rowIndex;
			         $color = 'fdede8'; // Subheading color
			         $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		             $ActiveSheet->getStyle($Range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
				}
				
				$rowIndex++;
				$columnIndex = 0;
			}
			//Code for 6.1 payment of tax
			//code for interest & late fees header and data code
			$Header = array('Description','Tax payable', 'Paid through ITC','Paid through ITC','Paid through ITC','Paid through ITC','Tax paid TDS./TCS','Tax/Cess paid in cash','Interest','LateFee');
			
			
		    
			//Write the Header
			 $cell='A'.$rowIndex.':'.'J'.$rowIndex;
		    $Range = $cell;
			$color = 'adadad'; // Topheading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		 
			$ActiveSheet->mergeCells($cell);
			$ActiveSheet->setCellValue('A'.$rowIndex, '6.1 Payment of tax');
			$newrow=0;
			$newrow= $rowIndex+1;
			$cell='';
			$cell='A'.$newrow.':'.'J'.$newrow;
		    $Range = $cell;
			$color = 'f0f0f0'; // heading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		   
			$i=0;
			$start=0;
			$start =$rowIndex+1;
			foreach($Header as $ind_el)
			{
				//Convert index to Excel compatible Location
				$Location = PHPExcel_Cell::stringFromColumnIndex($i) . $start;
				
				$ActiveSheet->setCellValue($Location, $ind_el);
				$i++;
			}
			//second header payment of tax 
			$Header = array('','', 'Integrated Fee Tax','Central Tax','State/UTTax','Cess','','','','','');
			
			
		    $rowIndex=$rowIndex+1;
			//Write the Header
			
			$newrow=0;
			$newrow= $rowIndex+1;
			$cell='';
			$cell='A'.$newrow.':'.'J'.$newrow;
		    $Range = $cell;
			$color = 'f0f0f0'; // heading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		   
			$i=0;
			$start=0;
			$start =$rowIndex+1;
			foreach($Header as $ind_el)
			{
				//Convert index to Excel compatible Location
				$Location = PHPExcel_Cell::stringFromColumnIndex($i) . $start;
				$ActiveSheet->setCellValue($Location, $ind_el);
				$i++;
			}
			//code end here for payment of tax
			
		  //Insert that data from Row 2, Column A (index 0)
			$rowIndex=$rowIndex+2;
			$columnIndex=0; //Column A
			foreach($datapayment_integrated as $row)
			{			
				foreach($row as $ind_el)
				{
					$Location = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . $rowIndex;
					//var_dump($Location);
					$ActiveSheet->setCellValue($Location, $ind_el); 	//Insert the Data at the specific cell specified by $Location
					$columnIndex++;
					 $Range = 'A'.$rowIndex;
			         $color = 'fdede8'; // Subheading color
			         $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		             $ActiveSheet->getStyle($Range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
				}
				
				$rowIndex++;
				$columnIndex = 0;
			}
			//Insert that data from Row 2, Column A (index 0)
			$rowIndex=$rowIndex;
			$columnIndex=0; //Column A
			foreach($datapayment_central as $row)
			{			
				foreach($row as $ind_el)
				{
					$Location = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . $rowIndex;
					//var_dump($Location);
					$ActiveSheet->setCellValue($Location, $ind_el); 	//Insert the Data at the specific cell specified by $Location
					$columnIndex++;
					 $Range = 'A'.$rowIndex;
			         $color = 'fdede8'; // Subheading color
			         $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		             $ActiveSheet->getStyle($Range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			       if($columnIndex==4)
					 {
					 $Range = 'E'.$rowIndex; 
			         $color = '000000'; // Subheading color
			         $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		             $ActiveSheet->getStyle($Range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					 }
				}
				
				$rowIndex++;
				$columnIndex = 0;
			}
			//Insert that data from Row 2, Column A (index 0)
			$rowIndex=$rowIndex;
			$columnIndex=0; //Column A
			foreach($datapayment_state as $row)
			{			
				foreach($row as $ind_el)
				{
					$Location = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . $rowIndex;
					//var_dump($Location);
					$ActiveSheet->setCellValue($Location, $ind_el); 	//Insert the Data at the specific cell specified by $Location
					$columnIndex++;
					 $Range = 'A'.$rowIndex;
			         $color = 'fdede8'; // Subheading color
			         $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		             $ActiveSheet->getStyle($Range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			         if($columnIndex==3)
					 {
					 $Range = 'D'.$rowIndex; 
			         $color = '000000'; // Subheading color
			         $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		             $ActiveSheet->getStyle($Range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					 }
				}
				
				$rowIndex++;
				$columnIndex = 0;
			}
			//Insert that data from Row 2, Column A (index 0)
			$rowIndex=$rowIndex;
			$columnIndex=0; //Column A
			foreach($datapayment_cess as $row)
			{			
				foreach($row as $ind_el)
				{
					$Location = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . $rowIndex;
					//var_dump($Location);
					
					$ActiveSheet->setCellValue($Location, $ind_el); 	//Insert the Data at the specific cell specified by $Location
					$columnIndex++;
					
					 $Range = 'A'.$rowIndex;
			         $color = 'fdede8'; // Subheading color
			         $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		             $ActiveSheet->getStyle($Range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					 if($columnIndex==2) 
					 {
					 $Range = 'C'.$rowIndex; 
			         $color = '000000'; // Subheading color
			         $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		             $ActiveSheet->getStyle($Range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					 }
					  if($columnIndex==3)
					 {
					 $Range = 'D'.$rowIndex; 
			         $color = '000000'; // Subheading color
			         $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		             $ActiveSheet->getStyle($Range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					 }
					  if($columnIndex==4) 
					 {
					 $Range = 'E'.$rowIndex; 
			         $color = '000000'; // Subheading color
			         $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		             $ActiveSheet->getStyle($Range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					 }
				}
				
				$rowIndex++;
				$columnIndex = 0;
			}
			
			//code for 6.2 TDS Credit 
			$Header = array('Details','Integrated Tax', 'Central Tax','State/UT');
			
			
		    
			//Write the Header
			 $cell='A'.$rowIndex.':'.'J'.$rowIndex;
		    $Range = $cell;
			$color = 'adadad'; // Topheading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		 
			$ActiveSheet->mergeCells($cell);
			$ActiveSheet->setCellValue('A'.$rowIndex, '6.2 TDS/TCS Credit');
			$newrow=0;
			$newrow= $rowIndex+1;
			$cell='';
			$cell='A'.$newrow.':'.'J'.$newrow;
		    $Range = $cell;
			$color = 'f0f0f0'; // heading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		   
			$i=0;
			$start=0;
			$start =$rowIndex+1;
			foreach($Header as $ind_el)
			{
				//Convert index to Excel compatible Location
				$Location = PHPExcel_Cell::stringFromColumnIndex($i) . $start;
				$ActiveSheet->setCellValue($Location, $ind_el);
				$i++;
			}
			
		  //Insert that data from Row 2, Column A (index 0)
			$rowIndex=$rowIndex+2;
			$columnIndex=0; //Column A
			foreach($data_tds as $row)
			{			
				foreach($row as $ind_el)
				{
					$Location = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . $rowIndex;
					//var_dump($Location);
					$ActiveSheet->setCellValue($Location, $ind_el); 	//Insert the Data at the specific cell specified by $Location
					$columnIndex++;
					 $Range = 'A'.$rowIndex;
			         $color = 'fdede8'; // Subheading color
			         $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		             $ActiveSheet->getStyle($Range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
				}
				
				$rowIndex++;
				$columnIndex = 0;
			}
			//code for TCS 6.2
			
		  //Insert that data from Row 2, Column A (index 0)
			$rowIndex=$rowIndex;
			$columnIndex=0; //Column A
			foreach($data_tds as $row)
			{			
				foreach($row as $ind_el)
				{
					$Location = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . $rowIndex;
					//var_dump($Location);
					$ActiveSheet->setCellValue($Location, $ind_el); 	//Insert the Data at the specific cell specified by $Location
					$columnIndex++;
					 $Range = 'A'.$rowIndex;
			         $color = 'fdede8'; // Subheading color
			         $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		             $ActiveSheet->getStyle($Range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
				}
				
				$rowIndex++;
				$columnIndex = 0;
			}
			//code for tcs 6.2
			// code for 6.1 payment of tax end here
			
			
			
			########### Optional    ##################
			########### Cell -Style ##################
			$color = 'adadad'; // top heading color
			$color = 'fdede8'; // Sub heading color
			$color = 'adadad'; // top heading color
			//1. Mark the Header Row  in Color Red
			$Range = 'A1:J1';
			$color = 'adadad'; // top heading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
			$Range = 'A2:J2';
			$color = 'f0f0f0'; // heading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		    $Range = 'A3:A7';
			$color = 'fdede8'; // Subheading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		     $Range = 'A9:E9';
			$color = 'f0f0f0'; // heading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
				$Range = 'A8:J8';
			$color = 'adadad';
			
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		    $ActiveSheet->mergeCells('A8:J8');
			$ActiveSheet->setCellValue('A8', '3.2 Of the supplies shown in 3.1 (a) above, details of inter-State supplies made to unregistered persons, composition taxable persons and UIN holders');
		    $ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		    $ActiveSheet->mergeCells('A1:J1');
			$ActiveSheet->setCellValue('A1', '3.1 Details of Outward Supplies and inward supplies liable to reverse charge.');

			/*
		     $Range = 'A10:A12';
			$color = 'fdede8'; // Subheading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		     $Range = 'A13:F13';
			$color = 'adadad'; // Topheading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		      $Range = 'A14:E14';
			$color = 'f0f0f0'; // heading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		     $Range = 'A15:A27';
			$color = 'fdede8'; // heading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		    $Range = 'A28:F28';
			$color = 'adadad'; // Topheading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		 
			$ActiveSheet->mergeCells('A1:E1');
			$ActiveSheet->setCellValue('A1', '3.1 Details of Outward Supplies and inward supplies liable to reverse charge');
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
			$Range = 'A32:F32';
			$color = 'adadad';
			
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
			$Range = 'A29:F29';
			$color = 'f0f0f0'; // heading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
			$Range = 'A30:A31';
			$color = 'fdede8'; // SUB heading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
			$Range = 'A33:E33';
			$color = 'f0f0f0'; // heading color
			$ActiveSheet->getStyle($Range)->getFill($Range)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
			$ActiveSheet->mergeCells('A13:F13');
			$ActiveSheet->setCellValue('A13', '4. Eligible ITC');
			$ActiveSheet->mergeCells('A28:F28');
			$ActiveSheet->setCellValue('A28', '5. Values of exempt, nil-rated and non-GST inward supplies');
			$ActiveSheet->mergeCells('A32:F32');
			*/
			
			$ActiveSheet->getStyle('A2:A500')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$ActiveSheet->getStyle('B2:B500')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$ActiveSheet->getStyle('C2:C500')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$ActiveSheet->getStyle('D2:D500')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$ActiveSheet->getStyle('E2:E500')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$ActiveSheet->getStyle('F2:F500')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$ActiveSheet->getStyle('F2:F500')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			for($i=0; $i<count($Header);$i++)
			{
				$Location = PHPExcel_Cell::stringFromColumnIndex($i) ;
				$ActiveSheet->getColumnDimension($Location)->setAutoSize(true);	
			}
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				//Result File name

			$randno = rand(100000, 999999);

		//	$fileName = "ResultExcel-" . $randno . ".xlsx";
	    $fileName =  "GSTR-3B".$_SESSION["user_detail"]["user_id"]."-".$randno . ".xlsx";
		$folder = "upload/gstr3b-file";

		//Create the Result Directory if Directory is not created already
		if (!file_exists($folder))
			mkdir($folder);

		$fullpath = $folder . '/' . $fileName;
	   
		$objWriter->save($fullpath);
		//$this->redirect(PROJECT_URL."/?page=return_gstr3b_file&returnmonth=".$returnmonth);
	 	//$this->setSuccess('GSTR3B excel successfully downloded');
			echo "<div id='sucmsg' style='background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;'><i class='fa fa-check'></i> <b>GSTR3B excel successfully downloded</b>&nbsp;<a href='". PROJECT_URL ."/?page=return_gstr3b_file&returnmonth=". $returnmonth ."'>Back</a></div>";

		$this->downlodfile($fileName);
		
		return $fullpath;
		
	}
  	public function downlodfile($filepath)
	{
	  $_GET['download_file'] = $filepath;
 
		ignore_user_abort(true);
		set_time_limit(0); // disable the time limit for this script
		 $path = @"upload/gstr3b-file/"; // change the path to fit your websites document structure
		 
		$dl_file = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).]|[\.]{2,})", '', $_GET['download_file']); // simple file name validation
		$dl_file = filter_var($dl_file, FILTER_SANITIZE_URL); // Remove (more) invalid characters
		$fullPath = $path.$dl_file;
		 
		if ($fd = fopen ($fullPath, "r")) {
		    $fsize = filesize($fullPath);
		    $path_parts = pathinfo($fullPath);
		    $ext = strtolower($path_parts["extension"]);
		    switch ($ext) {
		        case "pdf":
		        header("Content-type: application/pdf");
		        header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a file download
		        break;
		        // add more headers for other content types here
		        default;
		       
		      
				   header('Content-Description: File Transfer');
		          header('Content-Type: application/vnd.ms-excel');
				  header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
		          //header('Content-Disposition: attachment; filename='.basename($file));
		          header('Content-Transfer-Encoding: binary');
		          header('Expires: 0');
		          header('Cache-Control: must-revalidate');
		          header('Pragma: public');
		          header('Content-Length: ' . filesize($fullPath));
		          ob_clean();
		          flush();
		          readfile($fullPath);
		        break;
		    }
		   // header("Content-length: $fsize");
		    //header("Cache-control: private"); //use this to open files directly
		    while(!feof($fd)) {
		        $buffer = fread($fd, 2048);
		        echo $buffer;
		    }
		}
		fclose ($fd);

		exit;
	}
    public function finalSaveGstr3b()
    {
		$return_id =   isset($_POST['returnid']) ? $_POST['returnid'] : '';
		$fmonth =   $this->sanitize($_GET['returnmonth']);
		$userid = $_SESSION['user_detail']['user_id'];


		$obj_gst = new gstr();
        $payload = $this->gstr3bData($userid, $fmonth);
        $dataArr = $payload['data_arr'];
        $response = $obj_gst->returnSave($dataArr, $fmonth,'gstr3b');
       die;

		if($this->update(TAB_PREFIX.'client_return_gstr3b', array('final_submit' => 1), array('return_id' => $return_id)))
		{
			$this->setSuccess('GSTR3B Submitted Successfully');
			$this->logMsg("GSTR3B final submit financial month :".$this->sanitize($_GET['returnmonth']),"gstr_3b");

			return true;
		}
    }
    public function gst3bPayloadHeader($user_id, $returnmonth) {
        $obj_gst = new gstr();
        $dataArr = array();
        $api_return_period = $obj_gst->getRetrunPeriodFormat($returnmonth);
        $dataArr["gstin"] = $obj_gst->gstin();
        $dataArr["ret_period"] = $api_return_period ;
        //$dataArr["fp"] = $api_return_period;
       // $dataArr["gt"] = (float) $obj_gst->gross_turnover($user_id);
        //$dataArr["cur_gt"] = (float) $obj_gst->cur_gross_turnover($user_id);
        return $dataArr;
    }

    public function gstr3bData($user_id, $returnmonth) {
        $dataArr = $this->gst3bPayloadHeader($user_id, $returnmonth);

        /***** Start Code For Payload ********** */
        $data = $this->gstGSTR3BPayload($user_id, $returnmonth);
        if (!empty($data)) {
            $data_arr = $data['data_arr'];
            $dataArr = array_merge($dataArr, $data_arr);
        }
        /***** End Code For Payload ********** */


        $response['data_arr'] = $dataArr;
        return $response;
    }

    public function gstGSTR3BPayload($user_id, $returnmonth) {
    	$dataArr = $data_ids = array();
        $query =  "select * from ".$this->getTableName('client_return_gstr3b')." a where  a.status='1' and a.added_by='".$user_id."'  and a.financial_month like '%".$returnmonth."%'  and a.final_submit = '0' ";

        $dataInv= $this->get_results($query);
        if (isset($dataInv) && !empty($dataInv)) {
            $x = 0;
            $y=0;
            foreach ($dataInv as $dataIn) {
               
                $dataArr['sup_details']['osup_det']['txval'] = $dataIn->total_tax_value_supplya;
                $dataArr['sup_details']['osup_det']['iamt'] = $dataIn->integrated_tax_value_supplya;
                $dataArr['sup_details']['osup_det']['camt'] = $dataIn->central_tax_value_supplya;
                $dataArr['sup_details']['osup_det']['camt'] = $dataIn->state_tax_value_supplya;
                $dataArr['sup_details']['osup_det']['csamt'] = $dataIn->cess_tax_value_supplya;

                $dataArr['sup_details']['osup_zero']['txval'] = $dataIn->total_tax_value_supplyb;
                $dataArr['sup_details']['osup_zero']['iamt'] = $dataIn->integrated_tax_value_supplyb;
                $dataArr['sup_details']['osup_zero']['csamt'] = $dataIn->central_tax_value_supplyb;
                $dataArr['sup_details']['osup_zero']['camt'] = $dataIn->state_tax_value_supplyb;
                $dataArr['sup_details']['osup_zero']['csamt'] = $dataIn->cess_tax_value_supplyb;

                $dataArr['sup_details']['osup_nil_exmp']['txval'] = $dataIn->total_tax_value_supplyc;
                $dataArr['sup_details']['osup_nil_exmp']['iamt'] = $dataIn->integrated_tax_value_supplyc;
                $dataArr['sup_details']['osup_nil_exmp']['csamt'] = $dataIn->central_tax_value_supplyc;
                $dataArr['sup_details']['osup_nil_exmp']['camt'] = $dataIn->state_tax_value_supplyc;
                $dataArr['sup_details']['osup_nil_exmp']['csamt'] = $dataIn->cess_tax_value_supplyc;


                $dataArr['sup_details']['isup_rev']['txval'] = $dataIn->total_tax_value_supplyd;
                $dataArr['sup_details']['isup_rev']['iamt'] = $dataIn->integrated_tax_value_supplyd;
                $dataArr['sup_details']['isup_rev']['csamt'] = $dataIn->central_tax_value_supplyd;
                $dataArr['sup_details']['isup_rev']['camt'] = $dataIn->state_tax_value_supplyd;
                $dataArr['sup_details']['isup_rev']['csamt'] = $dataIn->cess_tax_value_supplyd;

                $dataArr['sup_details'][$x]['osup_nongst'][$y]['txval'] = $dataIn->total_tax_value_supplye;
                $dataArr['sup_details'][$x]['osup_nongst'][$y]['iamt'] = $dataIn->integrated_tax_value_supplye;
                $dataArr['sup_details'][$x]['osup_nongst'][$y]['csamt'] = $dataIn->central_tax_value_supplye;
                $dataArr['sup_details'][$x]['osup_nongst'][$y]['camt'] = $dataIn->state_tax_value_supplye;
                $dataArr['sup_details'][$x]['osup_nongst'][$y]['csamt'] = $dataIn->cess_tax_value_supplye;

/*
                $gstr3bUnRegisteredData = $this->getGstr3bType($user_id, $returnmonth,'0');
                if(!empty($gstr3bUnRegisteredData)) {
                	$u=0;
                	$str1  = substr($gstr3bUnRegisteredData[0]->place_of_supply,0,-1);
					$str1 = (explode(",",$str1));
					$str2  = substr($gstr3bUnRegisteredData[0]->totaltaxable_value,0,-1);
					$str2 = (explode(",",$str2));
					$str3  = substr($gstr3bUnRegisteredData[0]->amount_of_integrated_tax,0,-1);
					$str3 = (explode(",",$str3));
                	foreach ($str1 as $ukey => $str1_val) {

	                	$dataArr['inter_sup'][$x]['unreg_details'][$u]['pos'] = $str1_val;
		                $dataArr['inter_sup'][$x]['unreg_details'][$u]['txval'] = $str2[$ukey];
		                $dataArr['inter_sup'][$x]['unreg_details'][$u]['iamt'] = $str3[$ukey];
		                $u++;
	                }
                }
                
                $gstr3bTaxableData = $this->getGstr3bType($user_id, $returnmonth,'1');
                if(!empty($gstr3bTaxableData)) {
                	$u=0;
                	$str1  = substr($gstr3bTaxableData[0]->place_of_supply,0,-1);
					$str1 = (explode(",",$str1));
					$str2  = substr($gstr3bTaxableData[0]->totaltaxable_value,0,-1);
					$str2 = (explode(",",$str2));
					$str3  = substr($gstr3bTaxableData[0]->amount_of_integrated_tax,0,-1);
					$str3 = (explode(",",$str3));
                	foreach ($str1 as $ukey => $str1_val) {

	                	$dataArr['inter_sup'][$x]['comp_details'][$u]['pos'] = $str1_val;
		                $dataArr['inter_sup'][$x]['comp_details'][$u]['txval'] = $str2[$ukey];
		                $dataArr['inter_sup'][$x]['comp_details'][$u]['iamt'] = $str3[$ukey];
		                $u++;
	                }
                }

                $gstr3bUinHolderData = $this->getGstr3bType($user_id, $returnmonth,'2');
                if(!empty($gstr3bUinHolderData)) {
                	$u=0;
                	$str1  = substr($gstr3bUinHolderData[0]->place_of_supply,0,-1);
					$str1 = (explode(",",$str1));
					$str2  = substr($gstr3bUinHolderData[0]->totaltaxable_value,0,-1);
					$str2 = (explode(",",$str2));
					$str3  = substr($gstr3bUinHolderData[0]->amount_of_integrated_tax,0,-1);
					$str3 = (explode(",",$str3));
                	foreach ($str1 as $ukey => $str1_val) {

	                	$dataArr['inter_sup'][$x]['uin_details'][$u]['pos'] = $str1_val;
		                $dataArr['inter_sup'][$x]['uin_details'][$u]['txval'] = $str2[$ukey];
		                $dataArr['inter_sup'][$x]['uin_details'][$u]['iamt'] = $str3[$ukey];
		                $u++;
	                }
                }

                $dataArr['itc_elg'][$x]['itc_avl'][$y]['ty'] = "IMPG";
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['iamt'] = $dataIn->integrated_tax_itcavailable_a;
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['camt'] = $dataIn->central_tax_itcavailable_a;
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['samt'] = $dataIn->state_tax_itcavailable_a;
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['csamt'] = $dataIn->cess_tax_itcavailable_a;

                $dataArr['itc_elg'][$x]['itc_avl'][$y]['ty'] = "IMPS";
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['iamt'] = $dataIn->integrated_tax_import_of_goods;
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['camt'] = $dataIn->central_tax_import_of_goods;
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['samt'] = $dataIn->state_tax_import_of_goods;
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['csamt'] = $dataIn->cess_tax_import_of_goods;

                $dataArr['itc_elg'][$x]['itc_avl'][$y]['ty'] = "ISRC";
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['iamt'] = $dataIn->integrated_tax_import_of_services;
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['camt'] = $dataIn->central_tax_import_of_services;
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['samt'] = $dataIn->state_tax_import_of_services;
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['csamt'] = $dataIn->cess_tax_import_of_services;

                $dataArr['itc_elg'][$x]['itc_avl'][$y]['ty'] = "ISD";
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['iamt'] = $dataIn->integrated_tax_inward_supplies_reverse_charge;
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['camt'] = $dataIn->central_tax_inward_supplies_reverse_charge;
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['samt'] = $dataIn->state_tax_inward_supplies_reverse_charge;
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['csamt'] = $dataIn->cess_tax_inward_supplies_reverse_charge;

                $dataArr['itc_elg'][$x]['itc_avl'][$y]['ty'] = "OTH";
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['iamt'] = $dataIn->integrated_tax_inward_supplies;
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['camt'] = $dataIn->central_tax_inward_supplies;
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['samt'] = $dataIn->state_tax_inward_supplies;
                $dataArr['itc_elg'][$x]['itc_avl'][$y]['csamt'] = $dataIn->cess_tax_inward_supplies;



                $dataArr['itc_elg'][$x]['itc_rev'][$y]['ty'] = "RUL";
                $dataArr['itc_elg'][$x]['itc_rev'][$y]['iamt'] = $dataIn->integrated_tax_allother_itc;
                $dataArr['itc_elg'][$x]['itc_rev'][$y]['camt'] = $dataIn->central_tax_allother_itc;
                $dataArr['itc_elg'][$x]['itc_rev'][$y]['samt'] = $dataIn->state_tax_allother_itc;
                $dataArr['itc_elg'][$x]['itc_rev'][$y]['csamt'] = $dataIn->cess_tax_allother_itc;

                $dataArr['itc_elg'][$x]['itc_rev'][$y]['ty'] = "OTH";
                $dataArr['itc_elg'][$x]['itc_rev'][$y]['iamt'] = $dataIn->integrated_tax_itc_reversed_b;
                $dataArr['itc_elg'][$x]['itc_rev'][$y]['camt'] = $dataIn->central_tax_itc_reversed_b;
                $dataArr['itc_elg'][$x]['itc_rev'][$y]['samt'] = $dataIn->state_tax_itc_reversed_b;
                $dataArr['itc_elg'][$x]['itc_rev'][$y]['csamt'] = $dataIn->cess_tax_itc_reversed_b;


                $dataArr['itc_elg'][$x]['itc_net'][$y]['iamt'] = $dataIn->integrated_tax_itc_reversed_cgstrules;
                $dataArr['itc_elg'][$x]['itc_net'][$y]['camt'] = $dataIn->central_tax_itc_reversed_cgstrules;
                $dataArr['itc_elg'][$x]['itc_net'][$y]['samt'] = $dataIn->state_tax_itc_reversed_cgstrules;
                $dataArr['itc_elg'][$x]['itc_net'][$y]['csamt'] = $dataIn->cess_tax_itc_reversed_cgstrules;


                $dataArr['itc_elg'][$x]['itc_inelg'][$y]['ty'] ="RUL";
                $dataArr['itc_elg'][$x]['itc_inelg'][$y]['iamt'] = $dataIn->integrated_tax_itc_reversed_other;
                $dataArr['itc_elg'][$x]['itc_inelg'][$y]['camt'] = $dataIn->central_tax_itc_reversed_other;
                $dataArr['itc_elg'][$x]['itc_inelg'][$y]['samt'] = $dataIn->state_tax_itc_reversed_other;
                $dataArr['itc_elg'][$x]['itc_inelg'][$y]['csamt'] = $dataIn->cess_tax_itc_reversed_other;

                $dataArr['itc_elg'][$x]['itc_inelg'][$y]['ty'] ="OTH";
                $dataArr['itc_elg'][$x]['itc_inelg'][$y]['iamt'] = $dataIn->integrated_tax_net_itc_a_b;
                $dataArr['itc_elg'][$x]['itc_inelg'][$y]['camt'] = $dataIn->central_tax_net_itc_a_b;
                $dataArr['itc_elg'][$x]['itc_inelg'][$y]['samt'] = $dataIn->state_tax_net_itc_a_b;
                $dataArr['itc_elg'][$x]['itc_inelg'][$y]['csamt'] = $dataIn->cess_tax_net_itc_a_b;



                $dataArr['inward_sup'][$x]['isup_details'][$y]['ty'] = "GST";
                $dataArr['inward_sup'][$x]['isup_details'][$y]['inter'] = $dataIn->inter_state_supplies_composition_scheme;
                $dataArr['inward_sup'][$x]['isup_details'][$y]['intra'] = $dataIn->intra_state_supplies_composition_scheme;

                $dataArr['inward_sup'][$x]['isup_details'][$y]['ty'] = "NONGST";
                $dataArr['inward_sup'][$x]['isup_details'][$y]['inter'] = $dataIn->inter_state_supplies_nongst_supply;
                $dataArr['inward_sup'][$x]['isup_details'][$y]['intra'] = $dataIn->intra_state_supplies_nongst_supply;


                $dataArr['intr_ltfee'][$x]['intr_details'][$y]['iamt'] = $dataIn->interest_latefees_integrated_tax;
                $dataArr['intr_ltfee'][$x]['intr_details'][$y]['camt'] = $dataIn->interest_latefees_central_tax;
                $dataArr['intr_ltfee'][$x]['intr_details'][$y]['samt'] = $dataIn->interest_latefees_state_tax;
                $dataArr['intr_ltfee'][$x]['intr_details'][$y]['csamt'] =$dataIn->interest_latefees_cess_tax;
*/
                /*$this->pr($dataArr);
				die;*/

                $x++;
              
            }
        }
        $response['data_arr'] = $dataArr;
        //$this->pr($dataArr);die;
        return $response;
    }

    public function getGstr3bType($user_id, $returnmonth,$type) {
    	$query =  "select a.place_of_supply,a.totaltaxable_value, a.amount_of_integrated_tax from ".$this->getTableName('client_return_gstr3b_pos')." a where  a.is_deleted='0' and a.added_by='".$user_id."'  and a.financial_month like '%".$returnmonth."%'  and a.type = '".$type."' ";
    	return $this->get_results($query);
    }

    public function sendMail($module = '', $module_message = '', $to_send, $from_send, $cc = '', $bcc = '', $attachment = '', $subject, $body) {
        $dataInsertArray['module'] = $module;
        $dataInsertArray['module_message'] = $module_message;
        $dataInsertArray['to_send'] = $to_send;
        $dataInsertArray['from_send'] = $from_send;
        $dataInsertArray['cc'] = $cc;
        $dataInsertArray['bcc'] = $bcc;
        $dataInsertArray['attachment'] = $attachment;
        $dataInsertArray['subject'] = $subject;
        $dataInsertArray['body'] = $body;
        if ($this->insert($this->tableNames['email'], $dataInsertArray)) {
            return true;
        } else {
            return false;
        }
    }
	public function UpdateGstr3b()
	{
	   $dataArr = $this->getGSTR3bData();
	   $sql = "select * from " . TAB_PREFIX . "client_kyc where added_by='" . $_SESSION['user_detail']['user_id'] . "' order by id desc limit 0,1";
	   $clientdata = $this->get_results($sql);
	   $client_gstin_number;
	   if(count($clientdata) > 0 )
	   {
		   $client_gstin_number = $clientdata[0]->gstin_number;
	   }
	   $dataArr['client_gstin_number'] = $client_gstin_number;
	   if ($this->insert(TAB_PREFIX.'client_return_gstr3b', $dataArr)) {
			return true;
		}
		else
		{
	       return false;    	   
	   }
	   
	}
    
	public function generategstr3bHtml($returnid,$returnmonth)
	{
	     
	       $htmlResponse = $this->generategstr3bPdf($_SESSION['user_detail']['user_id'],$returnid,$returnmonth);
	        if ($htmlResponse === false) {

	            $obj_client->setError("No Plan Pdf found.");
	            return false;
	        }
	        $obj_mpdf = new mPDF();
	        $obj_mpdf->SetHeader('GSTR 3B File');
	        $obj_mpdf->WriteHTML($htmlResponse);
	        $datetime=date('Y-m-d-His');
	       
	       $taxInvoicePdf = 'gstr3bfile-' . $_SESSION['user_detail']['user_id'] . '_' .$datetime. '.pdf';
		   $filepath ="/upload/gstr3b-file/".$taxInvoicePdf;
	        ob_clean();
	        //$proof_photograph = $this->gstr3bUploads($taxInvoicePdf, 'plan-invoice', 'upload','.pdf');
	        $pic = $taxInvoicePdf;
	     
			  ob_clean();
			  if($_GET['action'] == 'printInvoice')
			  {
				  
			 $obj_mpdf->Output($taxInvoicePdf, 'I');
			  }
			  else if($_GET['action'] == 'emailInvoice')
			  {
				  //$obj_mpdf->Output($taxInvoicePdf, PROJECT_URL ."/upload/gstr3b-file/");
				  $dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));
			$sendmail = $dataCurrentUserArr['data']->kyc->email;
			$name = $dataCurrentUserArr['data']->kyc->name;
			$userid = $_SESSION["user_detail"]["user_id"];
				  $obj_mpdf->Output("upload/gstr3b-file/".$taxInvoicePdf);
				  $mpdfHtml = $this->gstr3bemail($name,$returnmonth);
				 // return $mpdfHtml;
				  
			 if ($this->sendMail('Email GSTR-3Bfile', 'User ID : ' . $userid . ' email GSTR-3B', $sendmail, 'noreply@gstkeeper.com', '', 'rishap07@gmail.com,sheetalprasad95@gmail.com', $filepath, 'GSTR-3B return month '.$returnmonth.'',$mpdfHtml )) {

					$this->setSuccess('Kindly check your email');
					$this->redirect(PROJECT_URL . "?page=return_gstr3b_file&returnmonth=" . $returnmonth);
	               // return true;
	            } else {
	                $this->setError('Try again some issue in sending in email.');
						$this->redirect(PROJECT_URL . "?page=return_gstr3b_file&returnmonth=" . $returnmonth);
	               // return false;
	            }
			  }
			  else
			  {
				  $obj_mpdf->Output($taxInvoicePdf, 'D');
			  }
		 
				$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " in User has been updated");
			   
	}
   
	private function gstr3bemail($name,$returnmonth)
	{
		$mpdfHtml ='';
		$mpdfHtml .='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> <html xmlns="http://www.w3.org/1999/xhtml"> <head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <title>gst</title> </head> <body> <div style="width:720px; margin:auto; border:solid #CCC 1px;"> <table cellpadding="0" cellspacing="0" width="100%" > <tbody> <tr> <td height="auto"><table width="720" cellpadding="0" cellspacing="0" bgcolor="#fff" style="font-family:Arial, Helvetica, sans-serif;margin:0px auto;"> <tbody> <tr> <td width="30"></td> <td><table width="100%" cellpadding="0" cellspacing="0"> <tbody> <tr> <td align="left" valign="middle" height="80"><a target="_blank" href="https://www.gstkeeper.com/"><img src="https://gstkeeper.com/newsletter/4july2017/gst-logo.png" alt="" border="0"></a></td> <td align="right" valign="middle" style="font-size:18px;color:#cf3502;font-family:Arial, Helvetica, sans-serif;" height="80px"> <span><img src="https://gstkeeper.com/newsletter/6july2017/phone-icon.jpg" alt=""></span>1-800-212-2022<br> <span><img src="https://gstkeeper.com/newsletter/6july2017/mail-icon.jpg" alt=""></span><a href="mailto:contact@gstkeeper.com" style="font-size:14px;color:#cf3502;text-decoration:none;"> contact@gstkeeper.com</a></td> </tr> </tbody> </table></td> <td width="30"></td> </tr> <tr> <td width="30"></td> <td><table width="100%" cellpadding="0" cellspacing="0"> <tbody> <tr> <td align="center" valign="middle"><img src="https://www.gstkeeper.com/newsletter/7july-planpurchase/images/banner.jpg" width="700" height="132" /></td> </tr> </tbody> </table></td> <td width="30"></td> </tr> <tr> <td width="30" ></td> <td><table width="100%" cellpadding="0" cellspacing="0"> <tbody> <tr> <td height="157" align="center" valign="top"><table width="100%" cellpadding="0px" cellspacing="0" > <tbody> <tr> <td width="13"></td> <td width="350" style="font-size:15px;color:#090909;font-family:Arial, Helvetica, sans-serif; padding-top:10px; "><strong>Hi '.$name.'! </strong></td> <td width="20"></td> </tr> <tr> <td colspan="3" height="10"></td> </tr> <tr> <td width="13"></td> <td height="110" align="justify" valign="top" style="font-size:13px;color:#191919;font-family:Arial, Helvetica, sans-serif; line-height:18px; ">';
		$mpdfHtml .='<p>Please find the attachment enclosed here along with GSTR 3B return month '.$returnmonth.' file.</p><p><strong>Thanks!</strong><BR /> The GST Keeper Team </p></td> <td width="20"></td> </tr> </tbody> </table></td> </tr> </tbody> </table></td> </tr> <!--<tr> <td align="center" height="29"><img src="http://cdn.go4hosting.in/mailer/12-oct/resources-img.jpg" alt="" /></td> </tr>--> <tr> <td colspan="3" height="15"></td> </tr> <tr> <td width="30"></td> <td><table width="98%" align="right" cellpadding="0" cellspacing="0" style="background-color:#f1f1f1; height:80px; padding:10px;"> <tbody> <tr> <td width="47%"><a href="http://www.cyfuture.com/" target="_blank"><img src="https://gstkeeper.com/newsletter/4july2017/cyfuture-logo.png" alt="" border="0" /></a></td> <td width="53%" align="right"><table width="100%" cellpadding="0" cellspacing="0"> <tbody> <tr> <td width="20" height="50"></td> <td valign="middle" style="font-size:14px;color:#333;font-family:Arial, Helvetica, sans-serif;"><strong><i>Connect with us</i></strong></td> <td valign="middle" width="50" align="center"><a target="_blank" href="https://www.facebook.com/GST-Keeper-632910016898628/"><img src="https://gstkeeper.com/newsletter/4july2017/fb-icon.png" alt="" border="0" /></a></td> <td valign="middle" width="40" align="left"><a target="_blank" href="https://plus.google.com/101841021110541536034"><img src="https://gstkeeper.com/newsletter/4july2017/g+-icon.png" alt="" border="0" /></a></td> <td valign="middle" width="40" align="left"><a target="_blank" href="https://twitter.com/GstKeeper"><img src="https://gstkeeper.com/newsletter/4july2017/twit-icon.png" alt="" border="0" /></a></td> <td valign="middle" width="40" align="left"><a target="_blank" href="https://www.youtube.com/channel/UCsDdNFR8kJ3YVWpEvBrFeSA"><img src="https://gstkeeper.com/newsletter/4july2017/utube-icon.png" alt="" border="0" /></a></td> <td valign="middle" width="40" align="left"><a target="_blank" href="https://www.linkedin.com/company/gst-keeper"><img src="https://gstkeeper.com/newsletter/4july2017/in-icon.jpg" alt="" border="0" /></a></td> </tr> </tbody> </table></td> </tr> </tbody> </table></td> <td width="30"></td> </tr> <tr> <td width="30"></td> <td height="76" valign="middle"><table width="100%" cellpadding="0" cellspacing="0"> <tbody> <tr> <td width="20"></td> <td align="center"><font style="font-size:14px;color:#444;font-family:Arial, Helvetica, sans-serif;">Cyfuture ( India ) Pvt. Ltd.</font><br> <font style="font-size:12px;color:#444;font-family:Arial, Helvetica, sans-serif;">Plot No. 197-198 Noida Special Economic Zone (NSEZ) Phase II, Noida 201 305</font><br> <font style="font-size:12px;color:#444;font-family:Arial, Helvetica, sans-serif;">E-mail: <a style="text-decoration:none;color:#3194d5;" href="mailto:contact@gstkeeper.com">contact@gstkeeper.com</a></font><br></td> <td width="15" align="left">&nbsp;</td> </tr> </tbody> </table></td> </tbody> </table></td> </tr> </tbody> </table> </div> </body> </html>';
		return $mpdfHtml;
	}

	private function getPlaceOfSupplyUnregistered()
	{    
		$dataArr = array();
	$dataArr['place_of_supply']='';
	if(!empty($_POST['place_of_supply_unregistered_person'])){
	// Loop to store and display values of individual checked checkbox.
	foreach($_POST['place_of_supply_unregistered_person'] as $selected){
	 
	 $dataArr['place_of_supply'] = $dataArr['place_of_supply'].$selected.',';

	} 
	}
	$dataArr['totaltaxable_value']='';
	  if(!empty($_POST['total_taxable_value_unregistered_person'])){
	// Loop to store and display values of individual checked checkbox.
	foreach($_POST['total_taxable_value_unregistered_person'] as $selected){
	 
	 $dataArr['totaltaxable_value'] = $dataArr['totaltaxable_value'].$selected.',';

	} 
	}
	$dataArr['amount_of_integrated_tax']='';
	  if(!empty($_POST['amount_of_integrated_tax_unregistered_person'])){
	// Loop to store and display values of individual checked checkbox.
	foreach($_POST['amount_of_integrated_tax_unregistered_person'] as $selected){
	 
	 $dataArr['amount_of_integrated_tax'] = $dataArr['amount_of_integrated_tax'].$selected.',';

	} 
	}
	$sql="select * from gst_client_return_gstr3b_pos where added_by='".$_SESSION['user_detail']['user_id']."' and financial_month='".$this->sanitize($_GET['returnmonth'])."' and type='0'";		
	$data = $this->get_results($sql);
	if(empty($data))
	{
	$dataArr['financial_month']=$this->sanitize($_GET['returnmonth']);
	$dataArr['type']=0;
	$dataArr['added_by']=$_SESSION["user_detail"]["user_id"];

	if ($this->insert('gst_client_return_gstr3b_pos', $dataArr)) {

		$this->setSuccess('GSTR3B Saved Successfully');

		//return true;
	}
	else
	{
		$this->setError('Failed to save GSTR3B data');
		
	   return false;    	   
	}

	}
	else
	{
	if ($this->update('gst_client_return_gstr3b_pos', $dataArr,array('added_by'=>$_SESSION['user_detail']['user_id'],'type'=>'0','financial_month'=>$this->sanitize($_GET['returnmonth'])))) {
		
	                  
		$this->setSuccess('GSTR3B Saved Successfully');
		
		return true;
	}
	else
	{
		$this->setError('Failed to save GSTR3B data');
	   return false;    	   
	}
	}

		 			
	}
    public function checkVerifyUser() {
        if (isset($_SESSION['user_detail']['user_id']) && $_SESSION['user_detail']['user_id'] != '') {
			$data = $this->get_results("select * from " . TAB_PREFIX . "user where user_id='" . $_SESSION['user_detail']['user_id'] . "'");
          
                if ($data[0]->email_verify == '0' || $data[0]->mobileno_verify == '0') {
					
					 $this->setError("GSTR-3B File first verify your email and mobile number");
					return "notverify";
				}
				return 'verify';
         
        }
    }
    private function getPlaceOfSupplyComposition()
    {
		$dataArr['place_of_supply']='';
		if(!empty($_POST['place_of_supply_taxable_person'])){
		// Loop to store and display values of individual checked checkbox.
			foreach($_POST['place_of_supply_taxable_person'] as $selected){
			 
             $dataArr['place_of_supply'] = $dataArr['place_of_supply'].$selected.',';
			
			} 
			}
			$dataArr['totaltaxable_value']='';
			if(!empty($_POST['total_taxable_value_taxable_person'])){
			// Loop to store and display values of individual checked checkbox.
			foreach($_POST['total_taxable_value_taxable_person'] as $selected){
			 
             $dataArr['totaltaxable_value'] = $dataArr['totaltaxable_value'].$selected.',';
			
			} 
			}
			$dataArr['amount_of_integrated_tax']='';
				if(!empty($_POST['amount_of_integrated_tax_taxable_person'])){
			// Loop to store and display values of individual checked checkbox.
			foreach($_POST['amount_of_integrated_tax_taxable_person'] as $selected){
			 
             $dataArr['amount_of_integrated_tax'] = $dataArr['amount_of_integrated_tax'].$selected.',';
			
			} 
			}
			 $sql="select * from gst_client_return_gstr3b_pos where added_by='".$_SESSION['user_detail']['user_id']."' and financial_month='".$this->sanitize($_GET['returnmonth'])."' and type='1'";		
	   $data = $this->get_results($sql);
		if(empty($data))
		{
			$dataArr['financial_month']=$this->sanitize($_GET['returnmonth']);
			$dataArr['type']=1;
			$dataArr['added_by']=$_SESSION["user_detail"]["user_id"];
			
			
			if ($this->insert('gst_client_return_gstr3b_pos', $dataArr)) {
			
				$this->setSuccess('GSTR3B Saved Successfully');
				
				//return true;
			}
			else
			{
				$this->setError('Failed to save GSTR3B data');
			
			   return false;    	   
		   }

		}
		else
		{
			if ($this->update('gst_client_return_gstr3b_pos', $dataArr,array('added_by'=>$_SESSION['user_detail']['user_id'],'type'=>'1','financial_month'=>$this->sanitize($_GET['returnmonth'])))) {
					
		                      
				//$this->setSuccess('GSTR3B Saved Successfully');
				return true;
			}
			else
			{
				$this->setError('Failed to save GSTR3B data');
			   return false;    	   
		   }
		}
    }
    private function getPlaceOfSupplyUinHolder()
    {
		$dataArr['place_of_supply']='';
		if(!empty($_POST['place_of_supply_uin_holder'])){
			// Loop to store and display values of individual checked checkbox.
			foreach($_POST['place_of_supply_uin_holder'] as $selected){
			 
             $dataArr['place_of_supply'] = $dataArr['place_of_supply'].$selected.',';
			
			} 
			}
			$dataArr['totaltaxable_value']='';
		if(!empty($_POST['total_taxable_value_uin_holder'])){
			// Loop to store and display values of individual checked checkbox.
			foreach($_POST['total_taxable_value_uin_holder'] as $selected){
			 
             $dataArr['totaltaxable_value'] = $dataArr['totaltaxable_value'].$selected.',';
			
			} 
			}
				$dataArr['amount_of_integrated_tax']='';
		if(!empty($_POST['amount_of_integrated_uin_holder'])){
			// Loop to store and display values of individual checked checkbox.
			foreach($_POST['amount_of_integrated_uin_holder'] as $selected){
			 
             $dataArr['amount_of_integrated_tax'] = $dataArr['amount_of_integrated_tax'].$selected.',';
			
			} 
			}
		
			 $sql="select * from gst_client_return_gstr3b_pos where added_by='".$_SESSION['user_detail']['user_id']."' and financial_month='".$this->sanitize($_GET['returnmonth'])."' and type='2'";		
	   	$data = $this->get_results($sql);
		if(empty($data))
		{
			$dataArr['financial_month']=$this->sanitize($_GET['returnmonth']);
			$dataArr['type']=2;
			$dataArr['added_by']=$_SESSION["user_detail"]["user_id"];
			
			if ($this->insert('gst_client_return_gstr3b_pos', $dataArr)) {
			
				$this->setSuccess('GSTR3B Saved Successfully');
				
				//return true;
			}
			else
			{
				$this->setError('Failed to save GSTR3B data');
				
			   return false;    	   
		   }

		}
		else
		{
			if ($this->update('gst_client_return_gstr3b_pos', $dataArr,array('added_by'=>$_SESSION['user_detail']['user_id'],'type'=>'2','financial_month'=>$this->sanitize($_GET['returnmonth'])))) {
					
		                      
				//$this->setSuccess('GSTR3B Saved Successfully');
				return true;
			}
			else
			{
				$this->setError('Failed to save GSTR3B data');
			   return false;    	   
		   }
		}
    }
    private function getGSTR3bData()
	{
		$dataArr = array();
		 $dataArr['total_tax_value_supplya'] = isset($_POST['total_tax_value_supplya']) ? $_POST['total_tax_value_supplya'] : '';
        $dataArr['integrated_tax_value_supplya'] = isset($_POST['integrated_tax_value_supplya']) ? $_POST['integrated_tax_value_supplya'] : '';
        $dataArr['central_tax_value_supplya'] = isset($_POST['central_tax_value_supplya']) ? $_POST['central_tax_value_supplya'] : '';
        $dataArr['state_tax_value_supplya'] = isset($_POST['state_tax_value_supplya']) ? $_POST['state_tax_value_supplya'] : '';
        $dataArr['cess_tax_value_supplya'] = isset($_POST['cess_tax_value_supplya']) ? $_POST['cess_tax_value_supplya'] : '';
        $dataArr['total_tax_value_supplyb'] = isset($_POST['total_tax_value_supplyb']) ? $_POST['total_tax_value_supplyb'] : '';
        $dataArr['integrated_tax_value_supplyb'] = isset($_POST['integrated_tax_value_supplyb']) ? $_POST['integrated_tax_value_supplyb'] : '';
        $dataArr['central_tax_value_supplyb'] = isset($_POST['central_tax_value_supplyb']) ? $_POST['central_tax_value_supplyb'] : '';
        $dataArr['state_tax_value_supplyb'] = isset($_POST['state_tax_value_supplyb']) ? $_POST['state_tax_value_supplyb'] : '';
        $dataArr['cess_tax_value_supplyb'] = isset($_POST['cess_tax_value_supplyb']) ? $_POST['cess_tax_value_supplyb'] : '';
        $dataArr['total_tax_value_supplyc'] = isset($_POST['total_tax_value_supplyc']) ? $_POST['total_tax_value_supplyc'] : '';
        $dataArr['integrated_tax_value_supplyc'] = isset($_POST['integrated_tax_value_supplyc']) ? $_POST['integrated_tax_value_supplyc'] : '';
        $dataArr['central_tax_value_supplyc'] = isset($_POST['central_tax_value_supplyc']) ? $_POST['central_tax_value_supplyc'] : '';
        $dataArr['state_tax_value_supplyc'] = isset($_POST['state_tax_value_supplyc']) ? $_POST['state_tax_value_supplyc'] : '';
         $dataArr['cess_tax_value_supplyc'] = isset($_POST['cess_tax_value_supplyc']) ? $_POST['cess_tax_value_supplyc'] : '';
        $dataArr['total_tax_value_supplyd'] = isset($_POST['total_tax_value_supplyd']) ? $_POST['total_tax_value_supplyd'] : '';
        $dataArr['integrated_tax_value_supplyd'] = isset($_POST['integrated_tax_value_supplyd']) ? $_POST['integrated_tax_value_supplyd'] : '';
         $dataArr['central_tax_value_supplyd'] = isset($_POST['central_tax_value_supplyd']) ? $_POST['central_tax_value_supplyd'] : '';
         $dataArr['state_tax_value_supplyd'] = isset($_POST['state_tax_value_supplyd']) ? $_POST['state_tax_value_supplyd'] : '';
         $dataArr['cess_tax_value_supplyd'] = isset($_POST['cess_tax_value_supplyd']) ? $_POST['cess_tax_value_supplyd'] : '';
         $dataArr['total_tax_value_supplye'] = isset($_POST['total_tax_value_supplye']) ? $_POST['total_tax_value_supplye'] : '';
		 /*
         $dataArr['integrated_tax_value_supplye'] = isset($_POST['integrated_tax_value_supplye']) ? $_POST['integrated_tax_value_supplye'] : '';
         $dataArr['central_tax_value_supplye'] = isset($_POST['central_tax_value_supplye']) ? $_POST['central_tax_value_supplye'] : '';
		 $dataArr['state_tax_value_supplye'] = isset($_POST['state_tax_value_supplye']) ? $_POST['state_tax_value_supplye'] : '';
		$dataArr['cess_tax_value_supplye'] = isset($_POST['cess_tax_value_supplye']) ? $_POST['cess_tax_value_supplye'] : '';
		*/
		$dataArr['integrated_tax_itcavailable_a'] = isset($_POST['integrated_tax_itcavailable_a']) ? $_POST['integrated_tax_itcavailable_a'] : '';
    	$dataArr['central_tax_itcavailable_a'] = isset($_POST['central_tax_itcavailable_a']) ? $_POST['central_tax_itcavailable_a'] : '';
	   	$dataArr['state_tax_itcavailable_a'] = isset($_POST['state_tax_itcavailable_a']) ? $_POST['state_tax_itcavailable_a'] : '';
		$dataArr['cess_tax_itcavailable_a'] = isset($_POST['cess_tax_itcavailable_a']) ? $_POST['cess_tax_itcavailable_a'] : '';
		$dataArr['integrated_tax_import_of_goods'] = isset($_POST['integrated_tax_import_of_goods']) ? $_POST['integrated_tax_import_of_goods'] : '';
		$dataArr['central_tax_import_of_goods'] = isset($_POST['central_tax_import_of_goods']) ? $_POST['central_tax_import_of_goods'] : '';
		$dataArr['state_tax_import_of_goods'] = isset($_POST['state_tax_import_of_goods']) ? $_POST['state_tax_import_of_goods'] : '';
		$dataArr['cess_tax_import_of_goods'] = isset($_POST['cess_tax_import_of_goods']) ? $_POST['cess_tax_import_of_goods'] : '';
		$dataArr['integrated_tax_import_of_services'] = isset($_POST['integrated_tax_import_of_services']) ? $_POST['integrated_tax_import_of_services'] : '';
		$dataArr['central_tax_import_of_services'] = isset($_POST['central_tax_import_of_services']) ? $_POST['central_tax_import_of_services'] : '';
		$dataArr['state_tax_import_of_services'] = isset($_POST['state_tax_import_of_services']) ? $_POST['state_tax_import_of_services'] : '';
		$dataArr['cess_tax_import_of_services'] = isset($_POST['cess_tax_import_of_services']) ? $_POST['cess_tax_import_of_services'] : '';
		$dataArr['integrated_tax_inward_supplies_reverse_charge'] = isset($_POST['integrated_tax_inward_supplies_reverse_charge']) ? $_POST['integrated_tax_inward_supplies_reverse_charge'] : '';
		$dataArr['central_tax_inward_supplies_reverse_charge'] = isset($_POST['central_tax_inward_supplies_reverse_charge']) ? $_POST['central_tax_inward_supplies_reverse_charge'] : '';
		$dataArr['state_tax_inward_supplies_reverse_charge'] = isset($_POST['state_tax_inward_supplies_reverse_charge']) ? $_POST['state_tax_inward_supplies_reverse_charge'] : '';
		$dataArr['cess_tax_inward_supplies_reverse_charge'] = isset($_POST['cess_tax_inward_supplies_reverse_charge']) ? $_POST['cess_tax_inward_supplies_reverse_charge'] : '';
		$dataArr['integrated_tax_inward_supplies'] = isset($_POST['integrated_tax_inward_supplies']) ? $_POST['integrated_tax_inward_supplies'] : '';
		$dataArr['central_tax_inward_supplies'] = isset($_POST['central_tax_inward_supplies']) ? $_POST['central_tax_inward_supplies'] : '';
		$dataArr['state_tax_inward_supplies'] = isset($_POST['state_tax_inward_supplies']) ? $_POST['state_tax_inward_supplies'] : '';
		$dataArr['cess_tax_inward_supplies'] = isset($_POST['cess_tax_inward_supplies']) ? $_POST['cess_tax_inward_supplies'] : '';
		$dataArr['integrated_tax_allother_itc'] = isset($_POST['integrated_tax_allother_itc']) ? $_POST['integrated_tax_allother_itc'] : '';
		$dataArr['central_tax_allother_itc'] = isset($_POST['central_tax_allother_itc']) ? $_POST['central_tax_allother_itc'] : '';
		$dataArr['state_tax_allother_itc'] = isset($_POST['state_tax_allother_itc']) ? $_POST['state_tax_allother_itc'] : '';
		$dataArr['cess_tax_allother_itc'] = isset($_POST['cess_tax_allother_itc']) ? $_POST['cess_tax_allother_itc'] : '';
		$dataArr['integrated_tax_itc_reversed_b'] = isset($_POST['integrated_tax_itc_reversed_b']) ? $_POST['integrated_tax_itc_reversed_b'] : '';
		$dataArr['central_tax_itc_reversed_b'] = isset($_POST['central_tax_itc_reversed_b']) ? $_POST['central_tax_itc_reversed_b'] : '';
		$dataArr['state_tax_itc_reversed_b'] = isset($_POST['state_tax_itc_reversed_b']) ? $_POST['state_tax_itc_reversed_b'] : '';
		$dataArr['cess_tax_itc_reversed_b'] = isset($_POST['cess_tax_itc_reversed_b']) ? $_POST['cess_tax_itc_reversed_b'] : '';
		$dataArr['integrated_tax_itc_reversed_cgstrules'] = isset($_POST['integrated_tax_itc_reversed_cgstrules']) ? $_POST['integrated_tax_itc_reversed_cgstrules'] : '';
		$dataArr['central_tax_itc_reversed_cgstrules'] = isset($_POST['central_tax_itc_reversed_cgstrules']) ? $_POST['central_tax_itc_reversed_cgstrules'] : '';
		$dataArr['state_tax_itc_reversed_cgstrules'] = isset($_POST['state_tax_itc_reversed_cgstrules']) ? $_POST['state_tax_itc_reversed_cgstrules'] : '';
		$dataArr['cess_tax_itc_reversed_cgstrules'] = isset($_POST['cess_tax_itc_reversed_cgstrules']) ? $_POST['cess_tax_itc_reversed_cgstrules'] : '';
		$dataArr['integrated_tax_itc_reversed_other'] = isset($_POST['integrated_tax_itc_reversed_other']) ? $_POST['integrated_tax_itc_reversed_other'] : '';
		$dataArr['central_tax_itc_reversed_other'] = isset($_POST['central_tax_itc_reversed_other']) ? $_POST['central_tax_itc_reversed_other'] : '';
		$dataArr['state_tax_itc_reversed_other'] = isset($_POST['state_tax_itc_reversed_other']) ? $_POST['state_tax_itc_reversed_other'] : '';
		$dataArr['cess_tax_itc_reversed_other'] = isset($_POST['cess_tax_itc_reversed_other']) ? $_POST['cess_tax_itc_reversed_other'] : '';
		$dataArr['integrated_tax_net_itc_a_b'] = isset($_POST['integrated_tax_net_itc_a_b']) ? $_POST['integrated_tax_net_itc_a_b'] : '';
		$dataArr['central_tax_net_itc_a_b'] = isset($_POST['central_tax_net_itc_a_b']) ? $_POST['central_tax_net_itc_a_b'] : '';
		$dataArr['state_tax_net_itc_a_b'] = isset($_POST['state_tax_net_itc_a_b']) ? $_POST['state_tax_net_itc_a_b'] : '';
		$dataArr['cess_tax_net_itc_a_b'] = isset($_POST['cess_tax_net_itc_a_b']) ? $_POST['cess_tax_net_itc_a_b'] : '';
		$dataArr['integrated_tax_inligible_itc'] = isset($_POST['integrated_tax_inligible_itc']) ? $_POST['integrated_tax_inligible_itc'] : '';
		$dataArr['central_tax_inligible_itc'] = isset($_POST['central_tax_inligible_itc']) ? $_POST['central_tax_inligible_itc'] : '';
		$dataArr['state_tax_inligible_itc'] = isset($_POST['state_tax_inligible_itc']) ? $_POST['state_tax_inligible_itc'] : '';
		$dataArr['cess_tax_inligible_itc'] = isset($_POST['cess_tax_inligible_itc']) ? $_POST['cess_tax_inligible_itc'] : '';
		$dataArr['integrated_tax_inligible_itc_17_5'] = isset($_POST['integrated_tax_inligible_itc_17_5']) ? $_POST['integrated_tax_inligible_itc_17_5'] : '';
		$dataArr['central_tax_inligible_itc_17_5'] = isset($_POST['central_tax_inligible_itc_17_5']) ? $_POST['central_tax_inligible_itc_17_5'] : '';
		$dataArr['state_tax_inligible_itc_17_5'] = isset($_POST['state_tax_inligible_itc_17_5']) ? $_POST['state_tax_inligible_itc_17_5'] : '';
		$dataArr['cess_tax_inligible_itc_17_5'] = isset($_POST['cess_tax_inligible_itc_17_5']) ? $_POST['cess_tax_inligible_itc_17_5'] : '';
		$dataArr['integrated_tax_inligible_itc_others'] = isset($_POST['integrated_tax_inligible_itc_others']) ? $_POST['integrated_tax_inligible_itc_others'] : '';
		$dataArr['central_tax_inligible_itc_others'] = isset($_POST['central_tax_inligible_itc_others']) ? $_POST['central_tax_inligible_itc_others'] : '';
		$dataArr['state_tax_inligible_itc_others'] = isset($_POST['state_tax_inligible_itc_others']) ? $_POST['state_tax_inligible_itc_others'] : '';
		$dataArr['cess_tax_inligible_itc_others'] = isset($_POST['cess_tax_inligible_itc_others']) ? $_POST['cess_tax_inligible_itc_others'] : '';
		$dataArr['inter_state_supplies_composition_scheme'] = isset($_POST['inter_state_supplies_composition_scheme']) ? $_POST['inter_state_supplies_composition_scheme'] : '';
		$dataArr['intra_state_supplies_composition_scheme'] = isset($_POST['intra_state_supplies_composition_scheme']) ? $_POST['intra_state_supplies_composition_scheme'] : '';
		$dataArr['inter_state_supplies_nongst_supply'] = isset($_POST['inter_state_supplies_nongst_supply']) ? $_POST['inter_state_supplies_nongst_supply'] : '';
		$dataArr['intra_state_supplies_nongst_supply'] = isset($_POST['intra_state_supplies_nongst_supply']) ? $_POST['intra_state_supplies_nongst_supply'] : '';
		$dataArr['tax_payable_integrated_tax'] = isset($_POST['tax_payable_integrated_tax']) ? $_POST['tax_payable_integrated_tax'] : '';
		$dataArr['integrated_fee_integrated_tax'] = isset($_POST['integrated_fee_integrated_tax']) ? $_POST['integrated_fee_integrated_tax'] : '';
		$dataArr['central_integrated_tax'] = isset($_POST['central_integrated_tax']) ? $_POST['central_integrated_tax'] : '';
		$dataArr['state_integrated_tax'] = isset($_POST['state_integrated_tax']) ? $_POST['state_integrated_tax'] : '';
		$dataArr['cess_integrated_tax'] = isset($_POST['cess_integrated_tax']) ? $_POST['cess_integrated_tax'] : '';
		$dataArr['taxpaid_tdstcs_integrated_tax'] = isset($_POST['taxpaid_tdstcs_integrated_tax']) ? $_POST['taxpaid_tdstcs_integrated_tax'] : '';
		$dataArr['taxpaid_cess_integrated_tax'] = isset($_POST['taxpaid_cess_integrated_tax']) ? $_POST['taxpaid_cess_integrated_tax'] : '';
		$dataArr['interest_integrated_tax'] = isset($_POST['interest_integrated_tax']) ? $_POST['interest_integrated_tax'] : '';
		$dataArr['latefee_integrated_tax'] = isset($_POST['latefee_integrated_tax']) ? $_POST['latefee_integrated_tax'] : '';
		$dataArr['tax_payable_central_tax'] = isset($_POST['tax_payable_central_tax']) ? $_POST['tax_payable_central_tax'] : '';
		$dataArr['integrated_fee_central_tax'] = isset($_POST['integrated_fee_central_tax']) ? $_POST['integrated_fee_central_tax'] : '';
		$dataArr['central_central_tax'] = isset($_POST['central_central_tax']) ? $_POST['central_central_tax'] : '';
		$dataArr['state_central_tax'] = isset($_POST['state_central_tax']) ? $_POST['state_central_tax'] : '';
		$dataArr['cess_central_tax'] = isset($_POST['cess_central_tax']) ? $_POST['cess_central_tax'] : '';
		$dataArr['taxpaid_tdstcs_central_tax'] = isset($_POST['taxpaid_tdstcs_central_tax']) ? $_POST['taxpaid_tdstcs_central_tax'] : '';
		$dataArr['taxpaid_cess_central_tax'] = isset($_POST['taxpaid_cess_central_tax']) ? $_POST['taxpaid_cess_central_tax'] : '';
		$dataArr['interest_central_tax'] = isset($_POST['interest_central_tax']) ? $_POST['interest_central_tax'] : '';
		$dataArr['latefee_central_tax'] = isset($_POST['latefee_central_tax']) ? $_POST['latefee_central_tax'] : '';
		$dataArr['tax_payable_stateut_tax'] = isset($_POST['tax_payable_stateut_tax']) ? $_POST['tax_payable_stateut_tax'] : '';
		$dataArr['integrated_stateut_tax'] = isset($_POST['integrated_stateut_tax']) ? $_POST['integrated_stateut_tax'] : '';
		$dataArr['central_stateut_tax'] = isset($_POST['central_stateut_tax']) ? $_POST['central_stateut_tax'] : '';
		$dataArr['state_stateut_tax'] = isset($_POST['state_stateut_tax']) ? $_POST['state_stateut_tax'] : '';
		$dataArr['cess_stateut_tax'] = isset($_POST['cess_stateut_tax']) ? $_POST['cess_stateut_tax'] : '';
		$dataArr['taxpaid_tcs_stateut_tax'] = isset($_POST['taxpaid_tcs_stateut_tax']) ? $_POST['taxpaid_tcs_stateut_tax'] : '';
		$dataArr['taxpaid_cess_stateut_tax'] = isset($_POST['taxpaid_cess_stateut_tax']) ? $_POST['taxpaid_cess_stateut_tax'] : '';
		$dataArr['interest_stateut_tax'] = isset($_POST['interest_stateut_tax']) ? $_POST['interest_stateut_tax'] : '';
		$dataArr['latefee_stateut_tax'] = isset($_POST['latefee_stateut_tax']) ? $_POST['latefee_stateut_tax'] : '';
		$dataArr['integrated_tax_tds'] = isset($_POST['integrated_tax_tds']) ? $_POST['integrated_tax_tds'] : '';
		$dataArr['central_tax_tds'] = isset($_POST['central_tax_tds']) ? $_POST['central_tax_tds'] : '';
		$dataArr['state_tax_tds'] = isset($_POST['state_tax_tds']) ? $_POST['state_tax_tds'] : '';
		$dataArr['integrated_tax_tcs'] = isset($_POST['integrated_tax_tcs']) ? $_POST['integrated_tax_tcs'] : '';
		$dataArr['central_tax_tcs'] = isset($_POST['central_tax_tcs']) ? $_POST['central_tax_tcs'] : '';
		$dataArr['state_tax_tcs'] = isset($_POST['state_tax_tcs']) ? $_POST['state_tax_tcs'] : '';
		$dataArr['latefee_cess_tax'] = isset($_POST['latefee_cess_tax']) ? $_POST['latefee_cess_tax'] : '';
		$dataArr['interest_cess_tax'] = isset($_POST['interest_cess_tax']) ? $_POST['interest_cess_tax'] : '';
		$dataArr['taxpaid_cess_cess_tax'] = isset($_POST['taxpaid_cess_cess_tax']) ? $_POST['taxpaid_cess_cess_tax'] : '';
		$dataArr['taxpaid_tcs_cess_tax'] = isset($_POST['taxpaid_tcs_cess_tax']) ? $_POST['taxpaid_tcs_cess_tax'] : '';
		$dataArr['cess_cess_tax'] = isset($_POST['cess_cess_tax']) ? $_POST['cess_cess_tax'] : '';
		$dataArr['state_cess_tax'] = isset($_POST['state_cess_tax']) ? $_POST['state_cess_tax'] : '';
		$dataArr['central_cess_tax'] = isset($_POST['central_cess_tax']) ? $_POST['central_cess_tax'] : '';
		$dataArr['tax_payable_cess_tax'] = isset($_POST['tax_payable_cess_tax']) ? $_POST['tax_payable_cess_tax'] : '';
		$dataArr['integrated_cess_tax'] = isset($_POST['integrated_cess_tax']) ? $_POST['integrated_cess_tax'] : '';
		$dataArr['interest_latefees_integrated_tax'] = isset($_POST['interest_latefees_integrated_tax']) ? $_POST['interest_latefees_integrated_tax'] : '';
	    $dataArr['interest_latefees_central_tax'] = isset($_POST['interest_latefees_central_tax']) ? $_POST['interest_latefees_central_tax'] : '';
	    $dataArr['interest_latefees_state_tax'] = isset($_POST['interest_latefees_state_tax']) ? $_POST['interest_latefees_state_tax'] : '';
	    $dataArr['interest_latefees_cess_tax'] = isset($_POST['interest_latefees_cess_tax']) ? $_POST['interest_latefees_cess_tax'] : '';
		$dataArr['return_filling_date'] = date('Y-m-d H:i:s');
		$dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
		$dataArr['is_deleted'] = 0;
		return $dataArr;
	}
	public function generategstr3bPdf($invid,$returnid,$returnmonth) {
		 
		$sql = "select  *,count(return_id) as totalinvoice from " . TAB_PREFIX . "client_return_gstr3b where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' order by return_id desc limit 0,1";
		     $returndata = $this->get_results($sql);
			
			                
			// array_push($data1, array('Supplies to Unregistered Persons',$place_of_supply_arr,$place_of_supply_total_taxable_value,$place_of_supply_total_amount_of_integrated_tax));

		$sql = "select  * from " . TAB_PREFIX . "client_kyc where added_by='" . $_SESSION['user_detail']['user_id'] . "'";
		 
		       $kycdata = $this->get_results($sql);
			   $mpdfHtml .='<html>';
			   $mpdfHtml .='<body>';
			   
			  $mpdfHtml .='<div style="font-size:12px !important;">';
		      $mpdfHtml .= '<table cellpadding="0" cellspacing="0" width="100%">
		      <tr class="top"><td colspan="2">';
			 $mpdfHtml .=  '<table width="100%"><tr><td width="50%">';
		if (isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
		            $mpdfHtml .= '<img src="' . PROJECT_URL . '/upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="width:100%;max-width:200px;">';
		        } else {
		            $mpdfHtml .= '<img src="' . PROJECT_URL . '/image/gst-k-logo.png" style="width:100%;max-width:200px;">';
		        }

		    $mpdfHtml .='</td><td align="right" width="50%">
		   <b>Company Name #</b>: '.$kycdata[0]->name.'<br>
		   <b>GSTIN #</b>: '.$kycdata[0]->gstin_number.'<br></td></tr></table></td></tr></table>';
		   $mpdfHtml .= ' <div style="position: relative;min-height: 1px;padding-right: 0px;padding-left: 0px;" class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
		   <div style="width: 100%;position: relative;min-height: 1px;padding-right: 15px;padding-left:0px;position: relative;min-height: 1px padding-right: 0px;
		    padding-left: 15px; font-size:12px !important;" class="col-md-12 col-sm-12 col-xs-12">
		    <div style="position: relative;min-height: 1px padding-right: 15px;
		    padding-left: 15px;" class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"></div>
		    <div class="whitebg formboxcontainer"><div style="float: left;width: 100%;font-size: 15px;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;" class="greyheading"  >3.1 Details of Outward Supplies and inward supplies liable to reverse charge</div>
		    <div class="tableresponsive">
			 <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">
									<thead><tr><th align="left">Nature of Supplies</th><th align="left">Total Taxable value</th>
		    <th align="left">Integrated Tax</th><th align="left">Central Tax</th>
		    <th align="left">State/UT Tax</th><th align="left">Cess</th></tr></thead>';
		                                
		    $mpdfHtml .= '<tbody><tr><td class="lftheading" style="font-size: 13px; background: #fdede8;color: #333;
		    border-bottom: 1px solid #f4d4ca;" width="40%">(a) Outward taxable supplies (other than zero rated, nil rated and exempted)</td><td>
			<label>'.$returndata[0]->total_tax_value_supplya.'<span class="starred"></span></label>
			</td><td><label>'.$returndata[0]->integrated_tax_value_supplya.'<span class="starred"></span></label></td><td>
			<label>'.$returndata[0]->central_tax_value_supplya.'<span class="starred"></span></label>
			</td><td><label>'.$returndata[0]->state_tax_value_supplya.'<span class="starred"></span></label>
			</td><td><label>'.$returndata[0]->cess_tax_value_supplya.'<span class="starred"></span></label></td>
		    </tr><tr><td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" width="20%">(b) Outward taxable supplies (zero rated )</td>
			<td><label>'.$returndata[0]->total_tax_value_supplyb.'<span class="starred"></span></label></td>
			<td><label>'.$returndata[0]->integrated_tax_value_supplyb.'<span class="starred"></span></label>
			</td><td><label>'.$returndata[0]->central_tax_value_supplyb.'<span class="starred"></span></label>
		</td><td><label>'.$returndata[0]->state_tax_value_supplyb.'<span class="starred"></span></label></td> 
		 <td><label>'.$returndata[0]->cess_tax_value_supplyb.'<span class="starred"></span></label>
		</td> </tr> <tr><td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" width="20%">(c) Other outward supplies (Nil rated, exempted)</td>
		<td><label>'.$returndata[0]->total_tax_value_supplyc.'<span class="starred"></span></label></td> 
		<td><label>'.$returndata[0]->integrated_tax_value_supplyc.'<span class="starred"></span></label></td> 
		<td><label>'.$returndata[0]->central_tax_value_supplyc.'<span class="starred"></span></label> </td> 
		<td><label>'.$returndata[0]->state_tax_value_supplyc.'<span class="starred"></span></label></td> 
		<td><label>'.$returndata[0]->cess_tax_value_supplyc.'<span class="starred"></span></label> </td></tr>
		<tr><td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" width="20%">(d) Inward supplies (liable to reverse charge)</td>
		<td><label>'.$returndata[0]->total_tax_value_supplyd.'<span class="starred"></span></label> </td> 
		<td><label>'.$returndata[0]->integrated_tax_value_supplyd.'<span class="starred"></span></label></td>
		<td><label>'.$returndata[0]->central_tax_value_supplyd.'<span class="starred"></span></label></td> 
		<td><label>'.$returndata[0]->state_tax_value_supplyd.'<span class="starred"></span></label></td>
		<td><label>'.$returndata[0]->cess_tax_value_supplyd.'<span class="starred"></span></label></td></tr>
		<tr><td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" width="20%">(e) Non-GST outward supplies</td>
		<td><label>'.$returndata[0]->total_tax_value_supplye.'<span class="starred"></span></label></td>
		<td><label>'.$returndata[0]->integrated_tax_value_supplye.'<span class="starred"></span></label></td> 
		<td><label>'.$returndata[0]->central_tax_value_supplye.'<span class="starred"></span></label></td> 
		 <td> <label>'.$returndata[0]->state_tax_value_supplye.'<span class="starred"></span></label></td> 
		 <td><label>'.$returndata[0]->cess_tax_value_supplye.'<span class="starred"></span></label></td></tr></tbody></table></div>';
		$mpdfHtml .= '<div class="greyheading" style="float: left;width: 100%;font-size: 15px;margin: 15px 0 15px 0;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;">3.2 Of the supplies shown in 3.1 (a) above, details of inter-State supplies made to unregistered persons,
		composition taxable persons and UIN holders</div>
		<div class="tableresponsive"><table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">
		<thead><tr><th align="left" style="background:#f0f0f0 !important;"></th>
		<th align="left" style="background:#f0f0f0 !important;">Place of Supply (State/UT)</th>
		<th align="left" style="background:#f0f0f0 !important;">Total Taxable value</th>
		<th align="left" style="background:#f0f0f0 !important;">Amount Of Integrated Tax</th></tr></thead><tbody>';

		    $place_of_supply_arr='';
			$place_of_supply_arr_new='';
			$place_of_supply_total_taxable_value='';
			$place_of_supply_total_amount_of_integrated_tax='';
			$sql="select *,final_submit,count(returnid) as totalinvoice,final_submit from ".TAB_PREFIX."client_return_gstr3b_pos as s INNER join ".TAB_PREFIX."client_return_gstr3b as client3b on client3b.financial_month=s.financial_month and s.added_by='".$_SESSION["user_detail"]["user_id"]."' and s.financial_month like '%".$returnmonth."%' and type='0'";
		     $editflag=0;
		                            $return_a = $this->get_results($sql);
									if($return_a[0]->totalinvoice > 0 )
									{
										 if (isset($return_a[0]->totalinvoice)) {
											 $editflag=1;
											    $str1  = substr($return_a[0]->place_of_supply,0,-1);
												$str1 = (explode(",",$str1));
												$str2  = substr($return_a[0]->totaltaxable_value,0,-1);
												$place_of_supply_total_taxable_value  = $str2;
												$str2 = (explode(",",$str2));
												$str3  = substr($return_a[0]->amount_of_integrated_tax,0,-1);
												$place_of_supply_total_amount_of_integrated_tax=$str3;
												$str3 = (explode(",",$str3));
										
								
										 } 
										$mpdfHtml1='';
										  for($i=0;$i < sizeof($str1); $i++) {
		                                $sql="select state_name from gst_master_state as s where state_id=".$str1[$i]."";
		                                $return_state = $this->get_results($sql);
										if(!empty($return_state))
										{
										//$place_of_supply_arr = $place_of_supply_arr.$return_state[0]->state_name.',';	
										 //array_push($data1, array('Supplies made to Unregistered Persons',$return_state[0]->state_name,$str2[$i],$str3[$i]));
										}
					$mpdfHtml .='<tr>';					  
		            $mpdfHtml .= '<td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" width="25%">Supplies made to Unregistered Persons</td>';
		            $mpdfHtml .= '<td><label>'.$return_state[0]->state_name.'<span class="starred"></span></label></td>';
		            $mpdfHtml .= '<td><label>'.(!empty($str2[$i])?$str2[$i]: '').'<span class="starred"></span></label></td>';
					$mpdfHtml .= '<td><label>'.(!empty($str3[$i])?$str3[$i]: '').'<span class="starred"></span></label></td>';
					$mpdfHtml .= '</tr>';
				
									
										  }
										
									}
									$place_of_supply_arr='';
			$place_of_supply_arr_new='';
			$place_of_supply_total_taxable_value='';
			$place_of_supply_total_amount_of_integrated_tax='';
			$sql="select *,final_submit,count(returnid) as totalinvoice,final_submit from ".TAB_PREFIX."client_return_gstr3b_pos as s INNER join ".TAB_PREFIX."client_return_gstr3b as client3b on client3b.financial_month=s.financial_month and s.added_by='".$_SESSION["user_detail"]["user_id"]."' and s.financial_month like '%".$returnmonth."%' and type='1'";
		     $editflag=0;
		                            $return_a = $this->get_results($sql);
									if($return_a[0]->totalinvoice > 0 )
									{
										 if (isset($return_a[0]->totalinvoice)) {
											 $editflag=1;
											    $str1  = substr($return_a[0]->place_of_supply,0,-1);
												$str1 = (explode(",",$str1));
												$str2  = substr($return_a[0]->totaltaxable_value,0,-1);
												$place_of_supply_total_taxable_value  = $str2;
												$str2 = (explode(",",$str2));
												$str3  = substr($return_a[0]->amount_of_integrated_tax,0,-1);
												$place_of_supply_total_amount_of_integrated_tax=$str3;
												$str3 = (explode(",",$str3));
										
								
										 } 
										$mpdfHtml1='';
										  for($i=0;$i < sizeof($str1); $i++) {
		                                $sql="select state_name from gst_master_state as s where state_id=".$str1[$i]."";
		                                $return_state = $this->get_results($sql);
										if(!empty($return_state))
										{
										//$place_of_supply_arr = $place_of_supply_arr.$return_state[0]->state_name.',';	
										 //array_push($data1, array('Supplies made to Unregistered Persons',$return_state[0]->state_name,$str2[$i],$str3[$i]));
										}
					$mpdfHtml .='<tr>';					  
		            $mpdfHtml .= '<td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" width="25%">Supplies made to Composition Taxable Persons</td>';
		            $mpdfHtml .= '<td><label>'.$return_state[0]->state_name.'<span class="starred"></span></label></td>';
		            $mpdfHtml .= '<td><label>'.(!empty($str2[$i])?$str2[$i]: '').'<span class="starred"></span></label></td>';
					$mpdfHtml .= '<td><label>'.(!empty($str3[$i])?$str3[$i]: '').'<span class="starred"></span></label></td>';
					$mpdfHtml .= '</tr>';
				
									
										  }
										
									}
									$place_of_supply_arr='';
			$place_of_supply_arr_new='';
			$place_of_supply_total_taxable_value='';
			$place_of_supply_total_amount_of_integrated_tax='';
			$sql="select *,final_submit,count(returnid) as totalinvoice,final_submit from ".TAB_PREFIX."client_return_gstr3b_pos as s INNER join ".TAB_PREFIX."client_return_gstr3b as client3b on client3b.financial_month=s.financial_month and s.added_by='".$_SESSION["user_detail"]["user_id"]."' and s.financial_month like '%".$returnmonth."%' and type='2'";
		     $editflag=0;
		                            $return_a = $this->get_results($sql);
									if($return_a[0]->totalinvoice > 0 )
									{
										 if (isset($return_a[0]->totalinvoice)) {
											 $editflag=1;
											    $str1  = substr($return_a[0]->place_of_supply,0,-1);
												$str1 = (explode(",",$str1));
												$str2  = substr($return_a[0]->totaltaxable_value,0,-1);
												$place_of_supply_total_taxable_value  = $str2;
												$str2 = (explode(",",$str2));
												$str3  = substr($return_a[0]->amount_of_integrated_tax,0,-1);
												$place_of_supply_total_amount_of_integrated_tax=$str3;
												$str3 = (explode(",",$str3));
										
								
										 } 
										$mpdfHtml1='';
										  for($i=0;$i < sizeof($str1); $i++) {
		                                $sql="select state_name from gst_master_state as s where state_id=".$str1[$i]."";
		                                $return_state = $this->get_results($sql);
										if(!empty($return_state))
										{
										//$place_of_supply_arr = $place_of_supply_arr.$return_state[0]->state_name.',';	
										 //array_push($data1, array('Supplies made to Unregistered Persons',$return_state[0]->state_name,$str2[$i],$str3[$i]));
										}
					$mpdfHtml .='<tr>';					  
		            $mpdfHtml .= '<td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" width="25%">Supplies made to UIN holders</td>';
		            $mpdfHtml .= '<td><label>'.$return_state[0]->state_name.'<span class="starred"></span></label></td>';
		            $mpdfHtml .= '<td><label>'.(!empty($str2[$i])?$str2[$i]: '').'<span class="starred"></span></label></td>';
					$mpdfHtml .= '<td><label>'.(!empty($str3[$i])?$str3[$i]: '').'<span class="starred"></span></label></td>';
					$mpdfHtml .= '</tr>';
				
									
										  }
										
									}
										
		                      
								 										                       
		$mpdfHtml .='</tbody></table></div>';				
		$mpdfHtml .='<div class="greyheading" style="float: left;width: 100%;font-size: 15px;margin: 15px 0 15px 0;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;">4. Eligible ITC</div><div class="tableresponsive">
		<table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone"><thead>
		<tr><th align="left">Details</th><th align="left">Integrated Tax</th><th align="left">Central Tax</th><th align="left">State/UT Tax</th>
		<th align="left">Cess</th></tr></thead> <tbody><tr><td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;
		border-bottom: 1px solid #f4d4ca;" width="25%"><strong>(A) ITC Available (whether in full or part)</strong></td>
		<td> <label>'.$returndata[0]->integrated_tax_itcavailable_a.'<span class="starred"></span></label></td> 		
		<td> <label>'.$returndata[0]->central_tax_itcavailable_a.'<span class="starred"></span></label> </td>
		<td><label>'.$returndata[0]->state_tax_itcavailable_a.'<span class="starred"></span></label></td> 
		<td><label>'.$returndata[0]->cess_tax_itcavailable_a.'<span class="starred"></span></label></td> </tr>
		<tr><td style="font-size: 13px;background: #fdede8; color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading">(1) Import of goods</td>								 
		 <td><label>'.$returndata[0]->integrated_tax_import_of_goods.'<span class="starred"></span></label> </td> 
		<td> <label>'.$returndata[0]->central_tax_import_of_goods.'<span class="starred"></span></label> </td>
		 <td><label>'.$returndata[0]->state_tax_import_of_goods.'<span class="starred"></span></label>                            </td> 	
		<td> <label>'.$returndata[0]->cess_tax_import_of_goods.'<span class="starred"></span></label> </td></tr>	
		  <tr> <td style="font-size: 13px;background: #fdede8;color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading">(2) Import of services</td>								 
		<td><label>'.$returndata[0]->integrated_tax_import_of_services.'<span class="starred"></span></label> </td>
		 <td> <label>'.$returndata[0]->central_tax_import_of_services.'<span class="starred"></span></label>  </td>
		  <td> <label>'.$returndata[0]->state_tax_import_of_services.'<span class="starred"></span></label> </td>
		<td> <label>'.$returndata[0]->cess_tax_import_of_services.'<span class="starred"></span></label> </td></tr> 
		  <tr>  <td style="font-size: 13px; background: #fdede8;  color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading">(3) Inward supplies liable to reverse charge (other than 1 & 2 above)</td>																                    
		<td> <label>'.$returndata[0]->integrated_tax_inward_supplies_reverse_charge.'<span class="starred"></span></label>  </td> 
		 <td> <label>'.$returndata[0]->central_tax_inward_supplies_reverse_charge.'<span class="starred"></span></label> </td> 
		 <td><label>'.$returndata[0]->state_tax_inward_supplies_reverse_charge.'<span class="starred"></span></label>  </td> 
		 <td> <label>'.$returndata[0]->cess_tax_inward_supplies_reverse_charge.'<span class="starred"></span></label> </td></tr>
		<tr> <td style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;">(4) Inward supplies from ISD</td>            
		<td><label>'.$returndata[0]->integrated_tax_inward_supplies.'<span class="starred"></span></label> </td>
		 <td> <label>'.$returndata[0]->central_tax_inward_supplies.'<span class="starred"></span></label></td>
		<td> <label>'.$returndata[0]->state_tax_inward_supplies.'<span class="starred"></span></label>  </td>
		 <td> <label>'.$returndata[0]->cess_tax_inward_supplies.'<span class="starred"></span></label> </td> </tr>
		 <tr> <td style="font-size: 13px; background: #fdede8;  color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading" >(5) All other ITC</td>
		 <td><label>'.$returndata[0]->integrated_tax_allother_itc.'<span class="starred"></span></label>  </td>
		<td><label>'.$returndata[0]->central_tax_allother_itc.'<span class="starred"></span></label>   </td>
		  <td> <label>'.$returndata[0]->state_tax_allother_itc.'<span class="starred"></span></label> </td>
		 <td> <label>'.$returndata[0]->cess_tax_allother_itc.'<span class="starred"></span></label> </td> </tr>
		  <tr>  <td style="font-size: 13px; background: #fdede8; color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading"><strong>(B) ITC Reversed</strong></td>
		   <td> <label>'.$returndata[0]->integrated_tax_itc_reversed_b.'<span class="starred"></span></label>   </td>
		  <td> <label>'.$returndata[0]->central_tax_itc_reversed_b.'<span class="starred"></span></label>  </td>	
		<td> <label>'.$returndata[0]->state_tax_itc_reversed_b.'<span class="starred"></span></label></td>						 
		<td> <label>'.$returndata[0]->cess_tax_itc_reversed_b.'<span class="starred"></span></label> </td> </tr>   
		  <tr> <td style="font-size: 13px; background: #fdede8; color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading" >(1) As per rules 42 & 43 of CGST Rules</td>
		 	<td> <label>'.$returndata[0]->integrated_tax_itc_reversed_cgstrules.'<span class="starred"></span></label></td> 
		  <td> <label>'.$returndata[0]->central_tax_itc_reversed_cgstrules.'<span class="starred"></span></label> </td>
		 <td><label>'.$returndata[0]->state_tax_itc_reversed_cgstrules.'<span class="starred"></span></label> </td>	
		 <td> <label>'.$returndata[0]->cess_tax_itc_reversed_cgstrules.'<span class="starred"></span></label>  </td>  </tr> 
		 <tr> <td style="font-size: 13px;   background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading" >(2) Others</td>                      
		<td> <label>'.$returndata[0]->integrated_tax_itc_reversed_other.'<span class="starred"></span></label>  </td>	
		<td> <label>'.$returndata[0]->central_tax_itc_reversed_other.'<span class="starred"></span></label>   </td> 
		<td> <label>'.$returndata[0]->state_tax_itc_reversed_other.'<span class="starred"></span></label> </td>	
		<td><label>'.$returndata[0]->cess_tax_itc_reversed_other.'<span class="starred"></span></label> </td>  </tr><tr> <td style="font-size: 13px; background: #fdede8;  color: #333;   border-bottom: 1px solid #f4d4ca;" class="lftheading"><strong>(C) Net ITC Available (A) – (B)</strong></td>
		<td><label>'.$returndata[0]->integrated_tax_net_itc_a_b.'<span class="starred"></span></label>  </td>
		<td> <label>'.$returndata[0]->central_tax_net_itc_a_b.'<span class="starred"></span></label>  </td>
		<td> <label>'.$returndata[0]->state_tax_net_itc_a_b.'<span class="starred"></span></label>  </td>
		<td>	 <label>'.$returndata[0]->cess_tax_net_itc_a_b.'<span class="starred"></span></label> </td> </tr>
		<tr>   <td style="font-size: 13px; background: #fdede8;color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading"><strong>(D) Ineligible ITC</strong></td> <td> <label>'.$returndata[0]->integrated_tax_inligible_itc.'<span class="starred"></span></label>  </td> 
		<td>	 <label>'.$returndata[0]->central_tax_inligible_itc.'<span class="starred"></span></label> </td> 
		<td><label>'.$returndata[0]->state_tax_inligible_itc.'<span class="starred"></span></label>  </td>
		<td> <label>'.$returndata[0]->cess_tax_inligible_itc.'<span class="starred"></span></label>  </td> </tr>
		<tr><td style="font-size: 13px;background: #fdede8; color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading">(1) As per section 17(5)</td>
		<td> <label>'.$returndata[0]->integrated_tax_inligible_itc_17_5.'<span class="starred"></span></label>  </td> 
		 <td> <label>'.$returndata[0]->central_tax_inligible_itc_17_5.'<span class="starred"></span></label>    </td>	
		<td> <label>'.$returndata[0]->state_tax_inligible_itc_17_5.'<span class="starred"></span></label>   </td>	
		 <td> <label>'.$returndata[0]->cess_tax_inligible_itc_17_5.'<span class="starred"></span></label>  </td> </tr>
		 <tr> <td style="font-size: 13px;background: #fdede8;color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading">(2) Others</td>                       
		<td> <label>'.$returndata[0]->integrated_tax_inligible_itc_others.'<span class="starred"></span></label>  </td>   
		 <td> <label>'.$returndata[0]->central_tax_inligible_itc_others.'<span class="starred"></span></label> </td>
		 <td> <label>'.$returndata[0]->state_tax_inligible_itc_others.'<span class="starred"></span></label>  </td>
		  <td> <label>'.$returndata[0]->cess_tax_inligible_itc_others.'<span class="starred"></span></label> </td></tr></tbody>  </table> </div>
		  <div class="greyheading" style="float: left;width: 100%;font-size: 15px;margin: 15px 0 15px 0;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;">5. Values of exempt, nil-rated and non-GST inward supplies</div>
		 <div class="tableresponsive">  <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">
		   <thead><tr><th align="left">Nature of supplies</th> <th align="left">Inter-State supplies</th>  <th align="left">Intra-State supplies</th>   </tr> </thead>
		  <tbody><tr><td style="font-size: 13px;background: #fdede8;color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading" width="25%">From a supplier under composition scheme, Exempt and Nil rated supply</td>
		  <td> <label>'.$returndata[0]->inter_state_supplies_composition_scheme.'<span class="starred"></span></label> </td> 
		 <td> <label>'.$returndata[0]->intra_state_supplies_composition_scheme.'<span class="starred"></span></label> </td>  </tr>
		 <tr>  <td style="font-size: 13px; background: #fdede8; color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading" width="25%">Non GST supply</td>                             	                                   
			<td> <label>'.$returndata[0]->inter_state_supplies_nongst_supply.'<span class="starred"></span></label></td>   
		 <td> <label>'.$returndata[0]->intra_state_supplies_nongst_supply.'<span class="starred"></span></label>  </td></tr>
		 </tbody></table></div><div class="greyheading" style="float: left;width: 100%;font-size: 15px;margin: 15px 0 15px 0;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;">5.1 Interest and late fee payable</div>
		 <div class="tableresponsive">  <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">
		 <thead> <tr> <th align="left">Interest and late fee</th><th align="left">IntegratedTax</th><th align="left">CentralTax</th> <th align="left">State/UT</th> <th>Cess</th> </tr>  	    
		  </thead><tbody><tr> <td class="lftheading" style="font-size: 13px; background: #fdede8; color: #333; border-bottom: 1px solid #f4d4ca;" width="25%">Interest amount</td>        
		 <td> <label>'.$returndata[0]->interest_latefees_integrated_tax.'<span class="starred"></span></label> </td>
		<td> <label>'.$returndata[0]->interest_latefees_central_tax.'<span class="starred"></span></label></td>
		<td><label>'.$returndata[0]->interest_latefees_state_tax.'<span class="starred"></span></label>  </td>
		 <td> <label>'.$returndata[0]->interest_latefees_cess_tax.'<span class="starred"></span></label> </td> </tr></tbody></table>
		 </div>';
		 $mpdfHtml .='<div class="greyheading" style="float: left;width: 100%;font-size: 15px;margin: 15px 0 15px 0;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;">6.1 Payment of tax</div>
		  <div class="tableresponsive"> <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">
		 <tr><th align="left">Description</th><th align="left">Tax payable</th><th colspan="4" align="center">Paid through ITC</th><th align="left">Tax paid <br/>TDS./TCS</th>
		 <th align="left">Tax/Cess <br/>paid in<br/>cash</th>  <th align="left">Interest</th>  <th align="left">Late Fee</th>   </tr>	
		<tr>  <th>&nbsp;</th>  <th>&nbsp;</th><th align="left">Integrated Fee<br> Tax</th> <th align="left">Central<br>Tax</th>  <th align="left">State/UT<br>Tax</th>
		   <th align="left">Cess</th>     <th>&nbsp;</th>  <th>&nbsp;</th>  <th>&nbsp;</th>    <th>&nbsp;</th>     </tr>                               
		<tr><td style="font-size: 13px;background: #fdede8; color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading" width="25%">Integrated Tax</td>                                 
		 <td> <label>'.$returndata[0]->tax_payable_integrated_tax.'<span class="starred"></span></label>  </td>
		<td> <label>'.$returndata[0]->integrated_fee_integrated_tax.'<span class="starred"></span></label>  </td> 
		<td><label>'.$returndata[0]->central_integrated_tax.'<span class="starred"></span></label> </td>
		<td> <label>'.$returndata[0]->state_integrated_tax.'<span class="starred"></span></label></td>
		<td><label>'.$returndata[0]->cess_integrated_tax.'<span class="starred"></span></label> </td>								 
		 <td> <label>'.$returndata[0]->taxpaid_tdstcs_integrated_tax.'<span class="starred"></span></label></td>							
		  <td><label>'.$returndata[0]->taxpaid_cess_integrated_tax.'<span class="starred"></span></label>  </td>								 
		<td> <label>'.$returndata[0]->interest_integrated_tax.'<span class="starred"></span></label></td>
		 <td><label>'.$returndata[0]->latefee_integrated_tax.'<span class="starred"></span></label> </td>  </tr>
		   <tr>    <td style="font-size: 13px;  background: #fdede8; color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading">Central Tax</td>                              									 
		<td>  <label>'.$returndata[0]->tax_payable_central_tax.'<span class="starred"></span></label>    </td>	
		 <td> <label>'.$returndata[0]->integrated_fee_central_tax.'<span class="starred"></span></label>  </td>
		<td> <label>'.$returndata[0]->central_central_tax.'<span class="starred"></span></label>   </td>								 
		 <td style="background:black;"> <label>'.$returndata[0]->state_central_tax.'<span class="starred"></span></label>  </td>						
		 <td> <label>'.$returndata[0]->cess_central_tax.'<span class="starred"></span></label>    </td>								 
		 <td>	 <label>'.$returndata[0]->taxpaid_tdstcs_central_tax.'<span class="starred"></span></label>   </td>									
		 <td> <label>'.$returndata[0]->taxpaid_cess_central_tax.'<span class="starred"></span></label></td>							
		<td>  <label>'.$returndata[0]->interest_central_tax.'<span class="starred"></span></label> </td>							 
		  <td>	 <label>'.$returndata[0]->latefee_central_tax.'<span class="starred"></span></label>   </td>   </tr>
		  <tr>  <td style="font-size: 13px; background: #fdede8; color: #333;  border-bottom: 1px solid #f4d4ca;" class="lftheading">State/UT Tax</td>							                  
		 <td><label>'.$returndata[0]->tax_payable_stateut_tax.'<span class="starred"></span></label> </td>                          
		<td><label>'.$returndata[0]->integrated_stateut_tax.'<span class="starred"></span></label> </td>  
		 <td style="background:black;">	 <label>'.$returndata[0]->central_stateut_tax.'<span class="starred"></span></label> </td>
		 <td> <label>'.$returndata[0]->state_stateut_tax.'<span class="starred"></span></label>  </td>	
		 <td> <label>'.$returndata[0]->cess_stateut_tax.'<span class="starred"></span></label> </td>
		 <td><label>'.$returndata[0]->taxpaid_tcs_stateut_tax.'<span class="starred"></span></label>    </td>
		<td> <label>'.$returndata[0]->taxpaid_cess_stateut_tax.'<span class="starred"></span></label>  </td>							                      
		<td> <label>'.$returndata[0]->interest_stateut_tax.'<span class="starred"></span></label> </td>								 
		<td><label>'.$returndata[0]->latefee_stateut_tax.'<span class="starred"></span></label>  </td></tr> 
		<tr> <td style="font-size: 13px; background: #fdede8;color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading">Cess</td>							
		<td><label>'.$returndata[0]->tax_payable_cess_tax.'<span class="starred"></span></label>  </td>
		 <td style="background:black;"><label>'.$returndata[0]->integrated_cess_tax.'<span class="starred"></span></label> </td>  
		  <td style="background:black;"> <label>'.$returndata[0]->central_cess_tax.'<span class="starred"></span></label>  </td>	
		<td style="background:black;"> <label>'.$returndata[0]->state_cess_tax.'<span class="starred"></span></label>  </td>                              
		<td><label>'.$returndata[0]->cess_cess_tax.'<span class="starred"></span></label>     </td>
		 <td><label>'.$returndata[0]->taxpaid_tcs_cess_tax.'<span class="starred"></span></label>    </td>								 
		 <td>  <label>'.$returndata[0]->taxpaid_cess_cess_tax.'<span class="starred"></span></label>  </td>
		 <td> <label>'.$returndata[0]->interest_cess_tax.'<span class="starred"></span></label> </td>								
		 <td>	 <label>'.$returndata[0]->latefee_cess_tax.'<span class="starred"></span></label></td>	</tr> </table>  </div>	
		  <div class="greyheading" style="float: left;width: 100%;font-size: 15px;margin: 15px 0 15px 0;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;">6.2 TDS/TCS Credit</div>
		 <div class="tableresponsive">  <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">
		 <thead>
		                                
		                                <tr>
		                                 <th align="left">Details</th>
		                                 <th align="left">Integrated Tax</th>
		                                 <th align="left">Central Tax</th> 
		                                  <th align="left">State/UT Tax</th>                                  
		                                   </tr>
		                                </thead>
		                                
		                                <tbody>
		                                    <tr>
		                                    <td class="lftheading" style="font-size: 13px;
		    background: #fdede8;
		    color: #333;
		    border-bottom: 1px solid #f4d4ca;" width="25%">TDS</td>
											 <td> 
									
											 <label>'.$returndata[0]->integrated_tax_tds.'<span class="starred"></span></label>
										
		                                 </td>
										  <td> 
								
											 <label>'.$returndata[0]->central_tax_tds.'<span class="starred"></span></label>
									
		                                 </td>
										 <td> 
								
											 <label>'.$returndata[0]->state_tax_tds.'<span class="starred"></span></label>
										
		                                 </td>
		                                    </tr>
		                                    
		                                     <tr>
		                                    <td style="font-size: 13px;
		    background: #fdede8;
		    color: #333;
		    border-bottom: 1px solid #f4d4ca;" class="lftheading" width="25%">TCS</td>
											 <td> 
									
											 <label>'.$returndata[0]->integrated_tax_tcs.'<span class="starred"></span></label>
									
		                                 </td>
										 <td> 
								
								
											 <label>'.$returndata[0]->central_tax_tcs.'<span class="starred"></span></label>
										
		                                 </td>
										 <td> 
									
											 <label>'.$returndata[0]->state_tax_tcs.'<span class="starred"></span></label>
									
		                                 </td>
		                                    </tr>
		                                    
		                                   
		                                    
		                                </tbody>
		                            </table>
										
		                          								
		                        </div>';
		$mpdfHtml .='</div></div></div>'; 
		$mpdfHtml .='</div>';
		$mpdfHtml .='</body>';
		$mpdfHtml .='</html>';
		        return $mpdfHtml;

    }
    public function saveGstr3b()
    {
		$data = $this->get_results("select * from ".TAB_PREFIX."client_return_gstr3b where added_by='".$_SESSION['user_detail']['user_id']."' and financial_month='".$this->sanitize($_GET['returnmonth'])."'");
		$dataArr = $this->getGSTR3bData();
	
		//$dataPlaceOfSupply = $this->getPlaceOfSupply();
		
	    $sql = "select * from " . TAB_PREFIX . "client_kyc where added_by='" . $_SESSION['user_detail']['user_id'] . "' order by id desc limit 0,1";
       
       $clientdata = $this->get_results($sql);
	   $client_gstin_number;
	   if(count($clientdata) > 0 )
	   {
		   $client_gstin_number = $clientdata[0]->gstin_number;
	   }
	   $dataArr['client_gstin_number'] = $client_gstin_number;
	   

		$returnmonth = $this->sanitize($_GET['returnmonth']);
		if(empty($data))
		{
			$dataArr['financial_month']=$this->sanitize($_GET['returnmonth']);
			
			if ($this->insert(TAB_PREFIX.'client_return_gstr3b', $dataArr)) {
				$this->getPlaceOfSupplyUnregistered();
				$this->getPlaceOfSupplyComposition();
				$this->getPlaceOfSupplyUinHolder();
				$this->setSuccess('GSTR3B Saved Successfully');
				$this->logMsg("GSTR3B Inserted financial month : " . $returnmonth,"gstr_3b");
				return true;
			}
			else
			{
				$this->setError('Failed to save GSTR3B data');
			   return false;    	   
		   }

		}
		else
		{
			if ($this->update(TAB_PREFIX.'client_return_gstr3b', $dataArr,array('added_by'=>$_SESSION['user_detail']['user_id'],'financial_month'=>$this->sanitize($_GET['returnmonth'])))) {
				$this->getPlaceOfSupplyUnregistered();
				$this->getPlaceOfSupplyComposition();
				$this->getPlaceOfSupplyUinHolder();
		                      
				$this->setSuccess('GSTR3B month of return'.$returnmonth."updated Successfully");
				$this->logMsg("GSTR3B updated financial month : " . $returnmonth,"gstr_3b");
				return true;
			}
			else
			{
				$this->setError('Failed to save GSTR3B data');
			   return false;    	   
		   }
		}
	   
   }
    
}