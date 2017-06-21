<!--========================sidemenu over=========================--> 
<!--========================admincontainer start=========================-->
<div class="admincontainer greybg">
<div class="formcontainer">
<style>
			.acknowledgetbl{margin:50px auto;width:500px;}
			.acknowledgetbl td{padding:10px;}
			.acknowledgetbl .orange td{background:#fee7df;}
			.acknowledgebx p{line-height:27px;}
			.acknowledgetbl td:nth-child(1){font-weight:700;}

			</style>
<form>
  <div class="adminformbx">
    <div class="kycmainbox">
      <h1>Form GSTR-1</h1>
      <h2>Details of outward supplies of goods or services</h2>
      <div class="clear"></div>
      <div class="formcol">
        <label>Year<span class="starred">*</span></label>
        <input type="text" placeholder="PAN Number" />
      </div>
      <div class="formcol">
        <label>Month<span class="starred">*</span></label>
        <select>
          <option value=''>--Select Month--</option>
          <option selected value='1'>Janaury</option>
          <option value='2'>February</option>
          <option value='3'>March</option>
          <option value='4'>April</option>
          <option value='5'>May</option>
          <option value='6'>June</option>
          <option value='7'>July</option>
          <option value='8'>August</option>
          <option value='9'>September</option>
          <option value='10'>October</option>
          <option value='11'>November</option>
          <option value='12'>December</option>
        </select>
      </div>
      <div class="clear"></div>
      <div class="formcol">
        <label>GSTIN<span class="starred">*</span></label>
        <input type="text" placeholder="" />
      </div>
      <div class="formcol">
        <label>Legal name of the registered person<span class="starred">*</span></label>
        <input type="text" placeholder="" />
      </div>
      <div class="formcol third">
        <label>Trade name, if any<span class="starred">*</span></label>
        <select>
          <option value="SELECT STATE">Center Jurisdiction</option>
        </select>
      </div>
      <div class="formcol">
        <label>Aggregate Turnover in the preceding Financial Year<span class="starred">*</span></label>
        <input type="text" placeholder="" />
      </div>
      <div class="formcol">
        <label>Aggregate Turnover - April to June, 2017<span class="starred">*</span></label>
        <input type="text" placeholder="" />
      </div>
      
      <!---SECTION 4 START HERE-->
      <div class="borderbox">
        <h4>4.Taxable outward supplies made to registered persons (including UIN-holders) other than supplies
          covered by Table 6</h4>
          <div class="clear"></div>
        <div class="tableresponsive">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontent fieldtable">
            <tr>
              <th rowspan="2" valign="top" width="15%">GSTIN/ UIN</th>
              <th colspan="3" valign="top">Invoice details</th>
              <th rowspan="2" valign="top">Rate</th>
              <th rowspan="2" valign="top">Taxable value</th>
              <th colspan="4" valign="top">Amount</th>
              <th rowspan="2" valign="top" width="12%">Place of Supply (Name of State)</th>
            </tr>
            <tr>
              <th>No.</th>
              <th nowrap="nowrap" width="12%">Date</th>
              <th>Value</th>
              <th>Integrated<br>
                Tax</th>
              <th>Central<br>
                Tax</th>
              <th>State / UT<br>
                Tax</th>
              <th>Cess</th>
            </tr>
            <tr>
              <td rowspan="2" valign="middle"><input type="text" class="inputfield" style="margin-top:8px;" /></td>
              <td colspan="3" valign="middle" style="padding-top:0px; padding-bottom:0px;"></td>
              <td rowspan="2" valign="middle"><input type="text" class="inputfield" value="100 Per Item" style="margin-top:8px;" /></td>
              <td rowspan="2" valign="middle"><input type="text" class="inputfield" value="50000" style="margin-top:8px;" /></td>
              <td colspan="4"  valign="middle"></td>
              <td rowspan="2" valign="middle"><input type="text" class="inputfield" value="Rajasthan" style="margin-top:8px;" /></td>
            </tr>
            <tr>
              <td valign="top" style="padding-top:0px;"><input type="text" class="inputfield" value="1" />
                </th>
              <td valign="top" style="padding-top:0px;"><input type="text" class="inputfield" value="21 june 2017" />
                </th>
              <td valign="top" style="padding-top:0px;"><input type="text" class="inputfield" value="100000" />
                </th>
              <td valign="top" style="padding-top:0px;"><input type="text" class="inputfield" value="15%" />
                </th>
              <td valign="top" style="padding-top:0px;"><input type="text" class="inputfield" value="15%"  />
                </th>
              <td valign="top" style="padding-top:0px;"><input type="text" class="inputfield" value="15%"  /></td>
              <td valign="top" style="padding-top:0px;"><input type="text" class="inputfield" value="15%"  /></td>
            </tr>
            <tr>
              <td colspan="11" class="txtheading"> 4A. Supplies other than those (i) attracting reverse charge and (ii) supplies made through e-commerce operator</td>
            </tr>
            <tr>
              <td valign="top"><input type="text" class="inputfield"  />
                </th>
              <td valign="top"><input type="text" class="inputfield"/>
                </th>
              <td valign="top"><input type="text" class="inputfield"  />
                </th>
              <td valign="top"><input type="text" class="inputfield" />
                </th>
              <td valign="top"><input type="text" class="inputfield" />
                </th>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="11" class="txtheading"> 4B. Supplies attracting tax on reverse charge basis</td>
            </tr>
            <tr>
              <td valign="top"><input type="text" class="inputfield"  />
                </th>
              <td valign="top"><input type="text" class="inputfield"/>
                </th>
              <td valign="top"><input type="text" class="inputfield"  />
                </th>
              <td valign="top"><input type="text" class="inputfield" />
                </th>
              <td valign="top"><input type="text" class="inputfield" />
                </th>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="11" class="txtheading"> 4C. Supplies made through e-commerce operator attracting TCS (operator wise, rate wise)</td>
            </tr>
            <tr>
              <td valign="top"><input type="text" class="inputfield"  />
                </th>
              <td valign="top"><input type="text" class="inputfield"/>
                </th>
              <td valign="top"><input type="text" class="inputfield"  />
                </th>
              <td valign="top"><input type="text" class="inputfield" />
                </th>
              <td valign="top"><input type="text" class="inputfield" />
                </th>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="11" class="txtheading"> GSTIN of e-commerce operator</td>
            </tr>
            <tr>
              <td valign="top"><input type="text" class="inputfield"  />
                </th>
              <td valign="top"><input type="text" class="inputfield"/>
                </th>
              <td valign="top"><input type="text" class="inputfield"  />
                </th>
              <td valign="top"><input type="text" class="inputfield" />
                </th>
              <td valign="top"><input type="text" class="inputfield" />
                </th>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
            </tr>
          </table>
        </div>
      </div>
      <!---SECTION 4 END HERE--> 
      
      <!---SECTION 5 START HERE-->
      <div class="borderbox">
        <h4>5.Taxable outward inter-State supplies to un-registered persons where the invoice value is more than Rs
          2.5 lakh</h4>
          <div class="clear"></div>
        <div class="tableresponsive">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontent fieldtable">
            <tr>
              <th rowspan="2" valign="top" width="15%">GSTIN/ UIN</th>
              <th colspan="3" valign="top">Invoice details</th>
              <th rowspan="2" valign="top">Rate</th>
              <th rowspan="2" valign="top">Taxable value</th>
              <th colspan="4" valign="top">Amount</th>
              <th rowspan="2" valign="top" width="12%">Place of Supply (Name of State)</th>
            </tr>
            <tr>
              <th>No.</th>
              <th nowrap="nowrap" width="12%">Date</th>
              <th>Value</th>
              <th>Integrated<br>
                Tax</th>
              <th>Central<br>
                Tax</th>
              <th>State / UT<br>
                Tax</th>
              <th>Cess</th>
            </tr>
            <tr>
              <td valign="middle" align="center" style="border-bottom:1px solid #e0e0e0; border-right:1px solid #e0e0e0; border-left:1px solid #e0e0e0;">1
                </th>
              <td valign="middle" align="center" style="border-bottom:1px solid #e0e0e0; border-right:1px solid #e0e0e0;">2
                </th>
              <td valign="middle" align="center" style="border-bottom:1px solid #e0e0e0; border-right:1px solid #e0e0e0;">3
                </th>
              <td valign="middle" align="center" style="border-bottom:1px solid #e0e0e0; border-right:1px solid #e0e0e0;">4
                </th>
              <td valign="middle" align="center" style="border-bottom:1px solid #e0e0e0; border-right:1px solid #e0e0e0;">5
                </th>
              <td valign="middle" align="center" style="border-bottom:1px solid #e0e0e0; border-right:1px solid #e0e0e0;">6
                </th>
              <td valign="middle" align="center" style="border-bottom:1px solid #e0e0e0; border-right:1px solid #e0e0e0;">7
                </th>
              <td valign="middle" align="center" style="border-bottom:1px solid #e0e0e0; border-right:1px solid #e0e0e0;">8
                </th>
              <td valign="middle" align="center" style="border-bottom:1px solid #e0e0e0; border-right:1px solid #e0e0e0;">9
                </th>
              <td valign="middle" align="center" style="border-bottom:1px solid #e0e0e0; border-right:1px solid #e0e0e0;">10
                </th>
              <td valign="middle" align="center" style="border-bottom:1px solid #e0e0e0; border-right:1px solid #e0e0e0;">11
                </th>
            </tr>
            <tr>
              <td rowspan="2" valign="middle"><input type="text" class="inputfield" style="margin-top:8px;" /></td>
              <td colspan="3" valign="middle" style="padding-top:0px; padding-bottom:0px;"></td>
              <td rowspan="2" valign="middle"><input type="text" class="inputfield" value="100 Per Item" style="margin-top:8px;" /></td>
              <td rowspan="2" valign="middle"><input type="text" class="inputfield" value="50000" style="margin-top:8px;" /></td>
              <td colspan="4"  valign="middle"></td>
              <td rowspan="2" valign="middle"><input type="text" class="inputfield" value="Rajasthan" style="margin-top:8px;" /></td>
            </tr>
            <tr>
              <td valign="top" style="padding-top:0px;"><input type="text" class="inputfield" value="1" />
                </th>
              <td valign="top" style="padding-top:0px;"><input type="text" class="inputfield" value="21 june 2017" />
                </th>
              <td valign="top" style="padding-top:0px;"><input type="text" class="inputfield" value="100000" />
                </th>
              <td valign="top" style="padding-top:0px;"><input type="text" class="inputfield" value="15%" />
                </th>
              <td valign="top" style="padding-top:0px;"><input type="text" class="inputfield" value="15%"  />
                </th>
              <td valign="top" style="padding-top:0px;"><input type="text" class="inputfield" value="15%"  /></td>
              <td valign="top" style="padding-top:0px;"><input type="text" class="inputfield" value="15%"  /></td>
            </tr>
            <tr>
              <td colspan="11" class="txtheading"> 5A. Outward supplies (other than supplies made through e-commerce operator, rate wise)</td>
            </tr>
            <tr>
              <td valign="top"><input type="text" class="inputfield"  />
                </th>
              <td valign="top"><input type="text" class="inputfield"/>
                </th>
              <td valign="top"><input type="text" class="inputfield"  />
                </th>
              <td valign="top"><input type="text" class="inputfield" />
                </th>
              <td valign="top"><input type="text" class="inputfield" />
                </th>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="11" class="txtheading"> 5B. Supplies made through e-commerce operator attracting TCS (operator wise, rate wise)</td>
            </tr>
            <tr>
              <td valign="top"><input type="text" class="inputfield"  />
                </th>
              <td valign="top"><input type="text" class="inputfield"/>
                </th>
              <td valign="top"><input type="text" class="inputfield"  />
                </th>
              <td valign="top"><input type="text" class="inputfield" />
                </th>
              <td valign="top"><input type="text" class="inputfield" />
                </th>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="11" class="txtheading"> GSTIN of e-commerce operator</td>
            </tr>
            <tr>
              <td valign="top"><input type="text" class="inputfield"  />
                </th>
              <td valign="top"><input type="text" class="inputfield"/>
                </th>
              <td valign="top"><input type="text" class="inputfield"  />
                </th>
              <td valign="top"><input type="text" class="inputfield" />
                </th>
              <td valign="top"><input type="text" class="inputfield" />
                </th>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
              <td valign="top"><input type="text" class="inputfield" /></td>
            </tr>
          </table>
        </div>
      </div>
      <!---SECTION 5 END HERE--> 
      
      <!---SECTION 6 START-->
      <div class="borderbox">
        <h4>6. Zero rated supplies and Deemed Exports</h4>
        <div class="clear"></div>
        <div class="tableresponsive">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontent fieldtable">
            <tr>
              <th rowspan="2">GSTIN of recipient</th>
              <th colspan="3">Invoice details</th>
              <th colspan="2">Shipping bill/ Bill of
                export</th>
              <th colspan="3">Integrated Tax
                No. Date Value No. Date Rate Taxable value Amt</th>
            </tr>
            <tr>
              <th>No.</th>
              <th>Date</th>
              <th>Value</th>
              <th>No.</th>
              <th>Date</th>
              <th>Rate</th>
              <th>Taxable value</th>
              <th>Amt.</th>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="11" class="txtheading">6A. Exports</td>
            </tr>
            <tr>
              <td width="15%"><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="11" class="txtheading">6B. Supplies made to SEZ unit or SEZ Developer</td>
            </tr>
            <tr>
              <td width="15%"><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="11" class="txtheading">6C. Deemed exports</td>
            </tr>
            <tr>
              <td width="15%"><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
          </table>
        </div>
      </div>
      
      <!---SECTION 6 END--> 
      
      <!---SECTION 7 START-->
      <div class="borderbox">
        <h4>7. Taxable supplies (Net of debit notes and credit notes) to unregistered persons other than the
          supplies covered in Table 5</h4>
          <div class="clear"></div>
        <div class="tableresponsive">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontent fieldtable">
            <tr>
              <th rowspan="2">Rate of tax</th>
              <th>Total Taxable value</th>
              <th colspan="4">Amount</th>
            </tr>
            <tr>
              <th></th>
              <th>Integrated</th>
              <th>Central Tax</th>
              <th>State Tax/UT Tax</th>
              <th>Cess</th>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="6"  class="fontbold">7A. Intra-State supplies</td>
            </tr>
            <tr>
              <td colspan="6" class="txtheading">7A (1). Consolidated rate wise outward supplies [including supplies made through e-commerce operator attracting TCS]</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="6" class="txtheading">7A (2). Out of supplies mentioned at 7A(1), value of supplies made through e-Commerce Operators attracting TCS (operator
                wise, rate wise)</td>
            </tr>
            <tr>
              <td colspan="6" class="fontbold">GSTIN of e-commerce operator</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="6"  class="txtheading">7B. Inter-State Supplies where invoice value is upto Rs 2.5 Lakh [Rate wise]</td>
            </tr>
            <tr>
              <td colspan="6" class="fontbold" style="font-size:14px;">7B (1). Place of Supply (Name of State)</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="6"  class="txtheading">7B (2). Out of the supplies mentioned in 7B (1), the supplies made through e-Commerce Operators (operator wise,
                rate wise)</td>
            </tr>
            <tr>
              <td colspan="6" class="fontbold" style="font-size:14px;">GSTIN of e-commerce operator</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
          </table>
        </div>
      </div>
      
      <!---SECTION 7 END--> 
      
      <!---SECTION 8 START-->
      <div class="borderbox">
        <h4>8. Nil rated, exempted and non GST outward supplies</h4>
        <div class="clear"></div>
        <div class="tableresponsive">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontent fieldtable">
            <tr>
              <th>Description</th>
              <th>Nil Rated Supplies</th>
              <th>Exempted (Other than Nil rated/non-GST supply)</th>
              <th>Non-GST supplies</th>
            </tr>
            <tr>
              <td align="center">1</td>
              <td align="center">2</td>
              <td align="center">3</td>
              <td align="center">4</td>
            </tr>
            <tr>
              <td>8A. Inter-State supplies to registered persons</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td>8B. Intra- State supplies to registered persons</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td>8C. Inter-State supplies to unregistered persons</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td>8D. Intra-State supplies to unregistered persons</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
          </table>
        </div>
      </div>
      
      <!---SECTION 8 END--> 
      
      <!---SECTION 9 START-->
      <div class="borderbox">
        <h4>9. Amendments to taxable outward supply details furnished in returns for earlier tax periods in Table 4,
          5 and 6 [including debit notes, credit notes, refund vouchers issued during current period and
          amendments thereof]</h4>
          
        <div class="clear"></div>
        <div class="tableresponsive" style="overflow-x:scroll;">
          <table width="2500" border="0" cellspacing="0" cellpadding="0" class="tablecontent fieldtable" style="table-layout:fixed;">
            <tr>
              <th colspan="3">Details of original document</th>
              <th colspan="6">Revised details of document or details of<br/>
                original Debit/Credit Notes or refund
                vouchers</th>
              <th rowspan="3">Rate</th>
              <th rowspan="3">Taxable Value</th>
              <th colspan="4">Amount</th>
              <th rowspan="3">Place of
                supply</th>
            </tr>
            <tr>
              <th rowspan="2">GSTIN</th>
              <th rowspan="2">Inv.<br>
                No.</th>
              <th rowspan="2">Inv.<br>
                Date</th>
              <th rowspan="2">GSTIN</th>
              <th colspan="2">Invoice</th>
              <th colspan="2">Shipping bill</th>
              <th rowspan="2">Value</th>
              <th rowspan="2">Integrated<br>
                Tax</th>
              <th rowspan="2">Central<br>
                Tax</th>
              <th rowspan="2">State / UT<br>
                Tax</th>
              <th rowspan="2">Cess</th>
            </tr>
            <tr>
              <th>No</th>
              <th>Date</th>
              <th>No.</th>
              <th>Date</th>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="16" class="txtheading">9A. If the invoice/Shipping bill details furnished earlier were incorrect</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="16" class="txtheading">9B. Debit Notes/Credit Notes/Refund voucher [original]</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="16" class="txtheading">9C. Debit Notes/Credit Notes/Refund voucher [amendments thereof]</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
          </table>
        </div>
      </div>
      
      <!---SECTION 9 END--> 
      
      <!---SECTION 10 START-->
      <div class="borderbox">
        <h4>10. Amendments to taxable outward supplies to unregistered persons furnished in returns for earlier tax
          periods in Table 7</h4>
          <div class="clear"></div>
        <div class="tableresponsive">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontent fieldtable">
            <tr>
              <th rowspan="2">Rate of tax</th>
              <th>Total Taxable value</th>
              <th colspan="4">Amount</th>
            </tr>
            <tr>
              <th></th>
              <th>Integrated</th>
              <th>Central Tax</th>
              <th>State Tax/UT Tax</th>
              <th>Cess</th>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="6"  class="fontbold">Tax period for which the details are being
                revised</td>
            </tr>
            <tr>
              <td colspan="6" class="txtheading">10A. Intra-State Supplies [including supplies made through e-commerce operator attracting TCS] [Rate wise]</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="6" class="txtheading"> 10A (1). Out of supplies mentioned at 10A, value of supplies made through e-Commerce Operators attracting TCS (operator wise,rate wise)</td>
            </tr>
            <tr>
              <td colspan="6" class="fontbold" style="font-size:14px;">GSTIN of e-commerce operator</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="6"  class="txtheading">10B. Inter-State Supplies [including supplies made through e-commerce operator attracting TCS] [Rate wise]</td>
            </tr>
            <tr>
              <td colspan="6" class="fontbold" style="font-size:14px;">Place of Supply (Name of State)</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="6"  class="txtheading"> 10B (1). Out of supplies mentioned at 10B, value of supplies made through e-Commerce Operators attracting TCS (operator wise, rate wise) </td>
            </tr>
            <tr>
              <td colspan="6" class="fontbold" style="font-size:14px;">GSTIN of e-commerce operator</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
          </table>
        </div>
      </div>
      
      <!---SECTION 10 END--> 
      
      <!---SECTION 11 START-->
      <div class="borderbox">
        <h4>11. Consolidated Statement of Advances Received/Advance adjusted in the current tax period/ Amendments of
          information furnished in earlier tax period</h4>
          <div class="clear"></div>
        <div class="tableresponsive">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontent fieldtable">
            <tr>
              <th rowspan="2">Rate</th>
              <th>Gross Advance Received/adjusted</th>
              <th>Place of supply <span>(Name of State)</span></th>
              <th colspan="4">Amount</th>
            </tr>
            <tr>
              <th></th>
              <th></th>
              <th>Integrated</th>
              <th>Central Tax</th>
              <th>State Tax/UT Tax</th>
              <th>Cess</th>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="7"  class="fontbold">I Information for the current tax period</td>
            </tr>
            <tr>
              <td colspan="7" class="txtheading">11A. Advance amount received in the tax period for which invoice has not been issued (tax amount to be added to
                output tax liability)</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="7" class="txtheading">11A (1). Intra-State supplies (Rate Wise)</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="7"  class="txtheading">11A (2). Inter-State Supplies (Rate Wise)</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="7"  class="txtheading"> 11B. Advance amount received in earlier tax period and adjusted against the supplies being shown in this tax period in Table Nos. 4, 5, 6 and 7 </td>
            </tr>
            <tr>
              <td colspan="7" class="fontbold" style="font-size:13px;">11B (1). Intra-State Supplies (Rate Wise)</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="7" class="fontbold" style="font-size:13px;">11B (2). Inter-State Supplies (Rate Wise)</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td colspan="7"  class="txtheading"> II Amendment of information furnished in Table No. 11[1] in GSTR-1 statement for earlier tax periods [Furnish
                revised information] </td>
            </tr>
            <tr>
              <td>Month</td>
              <td colspan="4" align="center">Amendment relating to information furnished in S.
                No.(select)</td>
              <td colspan="2">11A(1))  11A(2) 11B(1)  11B(2)</td>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td colspan="2"><input type="text" class="inputfield" /></td>
            </tr>
          </table>
        </div>
      </div>
      
      <!---SECTION 11 END--> 
      
      <!---SECTION 12 START-->
      <div class="borderbox">
        <h4>12. HSN-wise summary of outward supplies</h4>
        <div class="clear"></div>
        <div class="tableresponsive" style="overflow-x:scroll;">
          <table width="1500" border="0" cellspacing="0" cellpadding="0" class="tablecontent fieldtable" style="table-layout:fixed;">
            <tr>
              <th width="3%">Sr. No.</th>
              <th>HSN</th>
              <th width="20%">Description<br/>
                <span style="font-size:11px;">(Optional if HSN is provided)</span></th>
              <th>UQC</th>
              <th>UQC</th>
              <th>Total<br />
                Quantity</th>
              <th>Total<br />
                value</th>
              <th>Total<br />
                Taxable Value</th>
              <th colspan="4">Amount</th>
            </tr>
            <tr>
              <th colspan="8"></th>
              <th>Integrated</th>
              <th>Central Tax</th>
              <th>State Tax/UT Tax</th>
              <th>Cess</th>
            </tr>
            <tr>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
          </table>
        </div>
      </div>
      
      <!---SECTION 12 END--> 
      
      <!---SECTION 13 START-->
      <div class="borderbox">
        <h4>13. Documents issued during the tax period</h4>
        <div class="clear"></div>
        <div class="tableresponsive" style="overflow-x:scroll;">
          <table width="1500" border="0" cellspacing="0" cellpadding="0" class="tablecontent fieldtable" style="table-layout:fixed;">
            <tr>
              <th width="3%">Sr. No.</th>
              <th>Nature of document</th>
              <th colspan="2">Sr. No.</th>
              <th>Total number</th>
              <th>Cancelled</th>
              <th>Net issued</th>
            </tr>
            <tr>
              <th></th>
              <th></th>
              <th>From</th>
              <th>To</th>
              <th colspan="3"></th>
            </tr>
            <tr>
              <td class="txtheading">1</td>
              <td class="txtheading">Invoices for outward supply</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td class="txtheading">2</td>
              <td class="txtheading">Invoices for inward supply from<br/>
                unregistered person</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td class="txtheading">3</td>
              <td class="txtheading">Revised Invoice</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td class="txtheading">4</td>
              <td class="txtheading">Debit Note</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td class="txtheading">5</td>
              <td class="txtheading">Credit Note</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td class="txtheading">6</td>
              <td class="txtheading">Receipt voucher</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td class="txtheading">7</td>
              <td class="txtheading">Payment Voucher</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td class="txtheading">8</td>
              <td class="txtheading">Refund voucher</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td class="txtheading">9</td>
              <td class="txtheading">Delivery Challan for job work</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td class="txtheading">10</td>
              <td class="txtheading">Delivery Challan for supply on approval</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td class="txtheading">11</td>
              <td class="txtheading">Delivery Challan in case of liquid gas</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
            <tr>
              <td class="txtheading">11</td>
              <td class="txtheading">Delivery Challan in cases other than by way
                of supply (excluding at S no. 9 to 11)</td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
              <td><input type="text" class="inputfield" /></td>
            </tr>
          </table>
        </div>
      </div>
      
      <!---SECTION 13 END-->    
      <div class="clear height30"></div>
      <div class="adminformbxsubmit" style="width:100%;">
        <div class="tc"><a href="form-2-acknowledgement.php" class="btn orangebg">Submit</a></div>
      </div>
    </div>
  </div>
  </div>
  </div>
  </div>
</form>
<!--========================adminformbox over=========================-->

</div>
