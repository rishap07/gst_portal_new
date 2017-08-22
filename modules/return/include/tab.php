 <ul>
                    <li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2&returnmonth=' . $returnmonth ?>" <?php if(isset($_REQUEST['page']) && $_REQUEST['page']=='return_gstr2') { echo 'class="active"';} ?>>View GSTR2 Summary</a></li>
                    <li><a href="<?php echo PROJECT_URL . '/?page=return_purchase_all&returnmonth=' . $returnmonth ?>"  <?php if(isset($_REQUEST['page']) && $_REQUEST['page']=='return_purchase_all') { echo 'class="active"';} ?>> View My Data</a></li>
                    <li><a href="<?php echo PROJECT_URL . '/?page=return_vendor_invoices&returnmonth=' . $returnmonth ?>" <?php if(isset($_REQUEST['page']) && $_REQUEST['page']=='return_vendor_invoices') { echo 'class="active"';} ?>>Download GSTR-2A</a></li>
                    <li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2_reconcile&returnmonth=' . $returnmonth ?>" <?php if(isset($_REQUEST['page']) && $_REQUEST['page']=='return_gstr2_reconcile') { echo 'class="active"';} ?>>GSTR-2 Reconcile</a></li>
                    <li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2_claim_itc&returnmonth=' . $returnmonth ?>"  <?php if(isset($_REQUEST['page']) && $_REQUEST['page']=='return_gstr2_claim_itc') { echo 'class="active"';} ?>>Claim ITC</a></li>                   
                    <li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2_upload_invoices&returnmonth=' . $returnmonth ?>" <?php if(isset($_REQUEST['page']) && $_REQUEST['page']=='return_gstr2_upload_invoices') { echo 'class="active"';} ?>>Upload To GSTN</a></li>
                    <li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2_file&returnmonth=' . $returnmonth ?>" <?php if(isset($_REQUEST['page']) && $_REQUEST['page']=='return_gstr2_file') { echo 'class="active"';} ?>>GSTR-2 Filing</a></li>


                </ul>