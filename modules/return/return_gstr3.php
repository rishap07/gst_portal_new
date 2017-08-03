<?php
$obj_client = new client();
$returnmonth = date('Y-m');
if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_client->redirect(PROJECT_URL . "/?page=return_client&returnmonth=" . $returnmonth);
    exit();
}
$returnmonth = date('Y-m');
if (isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
$time = strtotime($returnmonth . "-01");
$month = date("M", strtotime("+1 month", $time));
?>

<style>

    .tab a {
        width: 17.9%!important;

    }
</style>     
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-3 Filing</h1></div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>  <i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-3 Filing</span> </div>
        <div class="whitebg formboxcontainer">
<!--                     <div class="text-right"><a href="#" class="btngreen"><i class="fa fa-cloud-download" aria-hidden="true"></i> Download GSTR2</a> <a href="#" class="btngreen"><i class="fa fa-upload" aria-hidden="true"></i> Upload GSTR 2A</a></div>-->

            <div class="col-md-12 col-sm-12 col-xs-12 tablistnav padleft0">
                <div class="tab col-md-12 col-sm-12 col-xs-12">
                    <a href="#" class="active">
                        Download GSTR3 Summary
                    </a>
                    <a href="#" >
                        Save My Data
                    </a>
                    <a href="#">
                        Generate GSTR3
                    </a>
                    </a>
                    <a href="#">
                        Submit GSTR3
                    </a>
                    <a href="#">
                        File GSTR3
                    </a>
                </div>
                <!--                   
                <?//php echo PROJECT_URL . '/?page=return_get_summary&returnmonth=' . $returnmonth ?>
                <ul>
                                                <li><a href="#" class="active"></a></li>
                                                 <li><a href="#"> Save GSTR3 Data</a></li>
                                                  <li><a href="#">Generate GSTR3</a></li>
                                                  <li><a href="#">Submit GSTR3</a></li>
                                                 <li><a href="#">File GSTR3</a></li>
                                            </ul>-->
            </div>
            <div class="tableresponsive">
                <h3>3.Turn Over Details</h3>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Taxable</th>
                            <th class="text-right">Zero rated supply on  payment of Tax</th>
                            <th class="text-right">Zero rated supply without payment of Tax</th>
                            <th class="text-right">Deemed Exports</th>
                            <th class="text-right">Nil rated</th>
                            <th class="text-right">Non GST Supply</th>
                            <th class="text-right">Exempted</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
<!--                                    <td colspan="16">No Invoices </td>-->

                            <td class="text-right"> 7000</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">100</td>
                            <td class="text-right">40</td>
                            <td class="text-right">40</td>

                        </tr>
                        <tr>
<!--                                    <td colspan="16">No Invoices </td>-->

                            <td class="text-right"> 7000</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">100</td>
                            <td class="text-right">40</td>
                            <td class="text-right">40</td>

                        </tr>
                        <tr>
<!--                                    <td colspan="16">No Invoices </td>-->

                            <td class="text-right"> 7000</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">100</td>
                            <td class="text-right">40</td>
                            <td class="text-right">40</td>

                        </tr>
                        <tr>
<!--                                    <td colspan="16">No Invoices </td>-->

                            <td class="text-right"> 7000</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">100</td>
                            <td class="text-right">40</td>
                            <td class="text-right">40</td>

                        </tr>
                        <tr>
<!--                                    <td colspan="16">No Invoices </td>-->

                            <td class="text-right"> 7000</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">100</td>
                            <td class="text-right">40</td>
                            <td class="text-right">40</td>

                        </tr>
                    </tbody>
                </table>

                <h3> 4.Outward Supplies</h3>
                <h4> Inter-state supplies </h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Rate of Tax</th>
                            <th class="text-right">taxable value/Net differential value</th>
                            <th class="text-right">IGST Amount as per invoice</th>
                            <th class="text-right">cess</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
<!--                                    <td colspan="16">No Invoices </td>-->

                            <td class="text-right"> 7000</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>
                <h4> Inter Ecomm details  </h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right1">Gstin</th>
                            <th class="text-right">Rate of Tax</th>
                            <th class="text-right">taxable value/Net differential value</th>
                            <th class="text-right">IGST Amount as per invoice</th>
                            <th class="text-right">cess</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
<!--                                    <td colspan="16">No Invoices </td>-->

                            <td class="text-right1">01AABCE5944P1Z9</td>
                            <td class="text-right"> 7000</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>

                <h4> Intra-State supplies </h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Rate of Tax</th>
                            <th class="text-right">taxable value/Net differential value</th>
                            <th class="text-right">CGST Amount as per invoice</th>
                            <th class="text-right">SGST Amount as per invoice</th>
                            <th class="text-right">cess</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
<!--                                    <td colspan="16">No Invoices </td>-->

                            <td class="text-right"> 7000</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>
                <h4> Intra-State Ecomm details  </h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right1">Gstin</th>
                            <th class="text-right">Rate of Tax</th>
                            <th class="text-right">taxable value/Net differential value</th>
                            <th class="text-right">CGST Amount as per invoice</th>
                            <th class="text-right">SGST Amount as per invoice</th>
                            <th class="text-right">cess</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
<!--                                    <td colspan="16">No Invoices </td>-->

                            <td class="text-right1"> 01AABCE5944P1Z9</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table> 

                <h4> Tax effect of amendments made in respect of outward supplies  </h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right1">Gstin</th>
                            <th class="text-right">Rate of Tax</th>
                            <th class="text-right">taxable value/Net differential value</th>
                            <th class="text-right">CGST Amount as per invoice</th>
                            <th class="text-right">SGST Amount as per invoice</th>
                            <th class="text-right">cess</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
<!--                                    <td colspan="16">No Invoices </td>-->

                            <td class="text-right1"> 01AABCE5944P1Z9</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>

                <h4>Inter-State supplies details</h4>
                <h4>Taxable supplies</h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Rate of Tax</th>
                            <th class="text-right">taxable value/Net differential value</th>
                            <th class="text-right">IGST Amount as per invoice</th>
                            <th class="text-right">cess</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
<!--                                    <td colspan="16">No Invoices </td>-->

                            <td class="text-right"> 7000</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>

                <h4>Zero rated supply made with payment of Integrated Tax</h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Rate of Tax</th>
                            <th class="text-right">taxable value/Net differential value</th>
                            <th class="text-right">IGST Amount as per invoice</th>
                            <th class="text-right">cess</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
<!--                                    <td colspan="16">No Invoices </td>-->

                            <td class="text-right"> 7000</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>

                <h4>Out of the Supplies mentioned at A, the value of supplies made though an e-commerce operator attracting TCS</h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>

                            <th class="text-right">Rate of Tax</th>
                            <th class="text-right">taxable value/Net differential value</th>
                            <th class="text-right">CGST Amount as per invoice</th>
                            <th class="text-right">SGST Amount as per invoice</th>
                            <th class="text-right">cess</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
<!--                                    <td colspan="16">No Invoices </td>-->


                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table> 

                <h3> Intra-State supplies </h3>
                <h4>Taxable supplies</h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Rate of Tax</th>
                            <th class="text-right">taxable value/Net differential value</th>
                            <th class="text-right">CGST Amount as per invoice</th>
                            <th class="text-right">SGST Amount as per invoice</th>
                            <th class="text-right">cess</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
<!--                                    <td colspan="16">No Invoices </td>-->

                            <td class="text-right"> 7000</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>
                <h4>Out of the Supplies mentioned at A, the value of supplies made though an e-commerce operator attracting TCS</h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right1">Gstin</th>
                            <th class="text-right">Rate of Tax</th>
                            <th class="text-right">taxable value/Net differential value</th>
                            <th class="text-right">CGST Amount as per invoice</th>
                            <th class="text-right">SGST Amount as per invoice</th>
                            <th class="text-right">cess</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
<!--                                    <td colspan="16">No Invoices </td>-->

                            <td class="text-right1"> 01AABCE5944P1Z9</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table> 

                <h3>5.Inward Supplies</h3>
                <h4>Inward supplies on which tax is payable on reverse charge basis</h4>
                <h5>InterDetails</h5>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Rate of Tax</th>
                            <th class="text-right">taxable value/Net differential value</th>
                            <th class="text-right">IGST Amount as per invoice</th>
                            <th class="text-right">CESS Amount as per invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                        </tr>

                    </tbody>
                </table> 

                <h5>Intra Details</h5>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Rate of Tax</th>
                            <th class="text-right">taxable value/Net differential value</th>
                            <th class="text-right">IGST Amount as per invoice</th>
                            <th class="text-right">SGST Amount as per invoice</th>
                            <th class="text-right">CESS Amount as per invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                        </tr>

                    </tbody>
                </table> 

                <h4>Tax effect of amendments in respect of supplies attracting reverse charge</h4>
                <h5>InterDetails</h5>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Rate of Tax</th>
                            <th class="text-right">taxable value/Net differential value</th>
                            <th class="text-right">IGST Amount as per invoice</th>
                            <th class="text-right">CESS Amount as per invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                        </tr>

                    </tbody>
                </table> 

                <h5>Intra Details</h5>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Rate of Tax</th>
                            <th class="text-right">taxable value/Net differential value</th>
                            <th class="text-right">IGST Amount as per invoice</th>
                            <th class="text-right">SGST Amount as per invoice</th>
                            <th class="text-right">CESS Amount as per invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                        </tr>

                    </tbody>
                </table> 

                <h3>6. ITC Credit</h3>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Taxable Value</th>
                            <th class="text-right">Integrated Tax Amount</th>
                            <th class="text-right">Central Tax Amount</th>
                            <th class="text-right">State / Union Territory Tax Amount</th>
                            <th class="text-right">Cess Amount</th>
                            <th class="text-right">ITC on Integrated Tax</th>
                            <th class="text-right">ITC on Central Tax</th>
                            <th class="text-right">ITC on State/Union territory</th>
                            <th class="text-right">ITC on Cess</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                        </tr>

                    </tbody>
                </table> 

                <h3>7. Additon and Reduction of amount in output tax for Mismatch</h3>
                <h4> ITC claimed on mismatched/duplication of invoices/debit notes</h4>

                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Central Tax Amount</th>
                            <th class="text-right">State /  Union Territory Tax Amount</th>
                            <th class="text-right">Integrated Tax Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table> 

                <h4> Tax Liability claimed on mismatched/duplication of invoices/debit notes</h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Central Tax Amount</th>
                            <th class="text-right">State /  Union Territory Tax Amount</th>
                            <th class="text-right">Integrated Tax Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table> 

                <h4> Reclaim on rectification of mismatched invoices/Debit Notes</h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Central Tax Amount</th>
                            <th class="text-right">State /  Union Territory Tax Amount</th>
                            <th class="text-right">Integrated Tax Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table> 

                <h4> Reclaim on rectification of mismatch credit note</h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Central Tax Amount</th>
                            <th class="text-right">State /  Union Territory Tax Amount</th>
                            <th class="text-right">Integrated Tax Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table> 

                <h4> Negative tax liability from previous tax periods</h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Central Tax Amount</th>
                            <th class="text-right">State /  Union Territory Tax Amount</th>
                            <th class="text-right">Integrated Tax Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table> 

                <h4> Tax paid on advance</h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Central Tax Amount</th>
                            <th class="text-right">State /  Union Territory Tax Amount</th>
                            <th class="text-right">Integrated Tax Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>

                <h4>ITC Reversal / Reclaim</h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Central Tax Amount</th>
                            <th class="text-right">State /  Union Territory Tax Amount</th>
                            <th class="text-right">Integrated Tax Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>
                <h3>8. Total Tax Liability</h3>
                <h4>List of total tax liability on outward supplies</h4>

                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Total Taxable Value</th>
                            <th class="text-right">Tax Rate</th>
                            <th class="text-right">Integrated Tax Amount</th>
                            <th class="text-right">Central Tax Amount</th>
                            <th class="text-right">State / Union Territory Tax Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>

                <h4>List of total tax liability on inward supplies</h4>

                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Total Taxable Value</th>
                            <th class="text-right">Tax Rate</th>
                            <th class="text-right">Integrated Tax Amount</th>
                            <th class="text-right">Central Tax Amount</th>
                            <th class="text-right">State / Union Territory Tax Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>
                <h4>List of total tax liability on ITC Reversal/Reclaim</h4>

                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Total Taxable Value</th>
                            <th class="text-right">Tax Rate</th>
                            <th class="text-right">Integrated Tax Amount</th>
                            <th class="text-right">Central Tax Amount</th>
                            <th class="text-right">State / Union Territory Tax Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>

                <h4>List of total tax liability on mismatch/rectification/other reasons</h4>

                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Total Taxable Value</th>
                            <th class="text-right">Tax Rate</th>
                            <th class="text-right">Integrated Tax Amount</th>
                            <th class="text-right">Central Tax Amount</th>
                            <th class="text-right">State / Union Territory Tax Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>

                <h3> 9.TCS Credit</h3>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Integrated Tax Amount</th>
                            <th class="text-right">Central Tax Amount</th>
                            <th class="text-right">State / Union Territory Tax Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                        </tr>

                    </tbody>
                </table>
                <h4> TDS Credit</h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Integrated Tax Amount</th>
                            <th class="text-right">Central Tax Amount</th>
                            <th class="text-right">State / Union Territory Tax Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                        </tr>

                    </tbody>
                </table>

                <h3> 10.Interest Liability</h3>
                <h4>Output liability on mismatch </h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Integrated Amount</th>
                            <th class="text-right">Central Amount</th>
                            <th class="text-right">State / Union Territory  Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>

                <h4>ITC claimed on mismatched invoice </h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Integrated Amount</th>
                            <th class="text-right">Central Amount</th>
                            <th class="text-right">State / Union Territory  Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>

                <h4>On account of other ITC reversal </h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Integrated Amount</th>
                            <th class="text-right">Central Amount</th>
                            <th class="text-right">State / Union Territory  Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>

                <h4>Undue excess claims or excess reduction
                    [refer sec 50(3)] </h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Integrated Amount</th>
                            <th class="text-right">Central Amount</th>
                            <th class="text-right">State / Union Territory  Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>
                
                <h4>Credit of interest on rectification of mismatch </h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Integrated Amount</th>
                            <th class="text-right">Central Amount</th>
                            <th class="text-right">State / Union Territory  Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>
                
                <h4>Interest liability carry forward </h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Integrated Amount</th>
                            <th class="text-right">Central Amount</th>
                            <th class="text-right">State / Union Territory  Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>
                
                
                <h4>Other Interest</h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Integrated Amount</th>
                            <th class="text-right">Central Amount</th>
                            <th class="text-right">State / Union Territory  Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>
                
                 <h4> Total interest liability </h4>
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Integrated Amount</th>
                            <th class="text-right">Central Amount</th>
                            <th class="text-right">State / Union Territory  Amount</th>
                            <th class="text-right">Cess Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>
                 
                 <h3> 11.Late Fee</h3>
                  <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Central Tax Amount</th>
                            <th class="text-right">State / Union Territory  Tax Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                        </tr>

                    </tbody>
                </table> 
                 <h3>12.Tax Payable and Paid</h3>
                  <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">IGST payable</th>
                            <th class="text-right">CGST payable</th>
                            <th class="text-right">SGST payable</th>
                            <th class="text-right">CESS payable</th>
                            <th class="text-right">Total Igst paid</th>
                            <th class="text-right">Total Cgst paid</th>
                            <th class="text-right">Total Sgst paid</th>
                            <th class="text-right">Total Cess paid</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>

                        </tr>

                    </tbody>
                </table>
                 <h4>Tax Paid A(Paid by Cash)</h4>
                  <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Igst paid</th>
                            <th class="text-right">Cgst paid</th>
                            <th class="text-right">Sgst paid </th>
                            <th class="text-right">Cess paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                        </tr>

                    </tbody>
                </table> 
                 
                 <h4>Tax Paid A(Paid by Credit- ITC)</h4>
                  <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">IGST paid using igst</th>
                            <th class="text-right">IGST paid using Cgst</th>
                            <th class="text-right">IGST paid using Sgst </th>
                            <th class="text-right">CGST paid using igst </th>
                            <th class="text-right">CGST paid using cgst </th>
                            <th class="text-right">SGST paid using igst</th>
                            <th class="text-right">SGST paid using sgst</th>
                            <th class="text-right">Cess paid using cess</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                        </tr>

                    </tbody>
                </table> 
                 <h3>13 .Interest, Late Fee and any other amount (other than tax) payable and paid</h3>
                 <h4>Interest payable</h4>
                  <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Interest payable in IGST head</th>
                            <th class="text-right">Interest payable in CGST head</th>
                            <th class="text-right">Interest payable in SGST head</th>
                            <th class="text-right">Interest payable in CESS head</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                        </tr>

                    </tbody>
                </table> 
                 <h4>Late Fee details</h4>
                  <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Late Fee payable in CGST head</th>
                            <th class="text-right">Late Fee payable in SGST head</th>
                            <th class="text-right">List of Paid through Cash details post utilization of cash in interest</th>
                            <th class="text-right">List of Paid through Cash details post utilization of cash in late fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                        </tr>

                    </tbody>
                </table> 
                 <h4>Interest Paid (Paid By Cash)</h4>
                  <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Igst paid Interest</th>
                            <th class="text-right">Cgst paid Interest</th>
                            <th class="text-right">Sgst paid Interest</th>
                            <th class="text-right">Cess paid Interest</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                        </tr>

                    </tbody>
                </table> 
                 <h4>Late Fee Paid(Paid By Cash)</h4>
                  <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Cgst paid Late fee</th>
                            <th class="text-right">Sgst paid Late fee</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            
                        </tr>

                    </tbody>
                </table> 
                 
                 <h3>14. Refund Claim</h3>
                 <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Gstin of the taxpayer</th>
                            <th class="text-right">Return period</th>
                            <th class="text-right">Bank Account Details</th>
                            <th class="text-right">Debit No.</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            
                        </tr>

                    </tbody>
                </table> 
                 <h4>Minor Heads(Igst,Cgst,Sgst,Cess)</h4>
                 <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Tax</th>
                            <th class="text-right">Penality</th>
                            <th class="text-right">Interest</th>
                            <th class="text-right">Fees</th>
                            <th class="text-right">Others</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            
                        </tr>

                    </tbody>
                </table> 
                  
                 <h3>15.Debit Entries in ledger</h3>
                  <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th class="text-right">Igst paid</th>
                            <th class="text-right">Cgst paid</th>
                            <th class="text-right">Sgst paid </th>
                            <th class="text-right">Cess paid</th>
                            <th class="text-right">Interest Paid in IGST head</th>
                            <th class="text-right">Interest Paid in CGST head</th>
                            <th class="text-right">Interest Paid in SGST head</th>
                            <th class="text-right">Interest Paid in CESS head</th>
                            <th class="text-right">Late Fee payable in SGST head</th>
                            <th class="text-right">Late Fee payable in CGST head</th>
                            <th class="text-right">Igst paid through IGST</th>
                            <th class="text-right">Igst paid through CGST</th>
                            <th class="text-right">Igst paid through SGST</th>
                            <th class="text-right">Cgst paid through IGST</th>
                            <th class="text-right">Cgst paid through CGST</th>
                            <th class="text-right">Sgst paid through IGST</th>
                            <th class="text-right">Sgst paid through SGST</th>
                            <th class="text-right">Cess paid through Cess</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            <td class="text-right">200</td>
                            
                        </tr>

                    </tbody>
                </table> 
            </div>
        </div> 

    </div>
    <div class="clear height40"></div>      
</div>
<div class="clear"></div>

<script>
    if (screen.width < 992) {
        $('[data-toggle=offcanvas]').click(function () {
            $('.row-offcanvas').toggleClass('active');
            $('.collapse').toggleClass('in').toggleClass('visible-xs').toggleClass('visible-xs');
            $("collapsed").hasClass("<i 
        });
    } else {

        $('[data-toggle=offcanvas]').click(function () {
            $('.row-offcanvas').toggleClass('active');
            $('.collapse').toggleClass('in').toggleClass('hidden-xs').toggleClass('visible-xs');
        });
    }

    $(".collapsed").children(".navrgtarrow");

</script>   
