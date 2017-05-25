<?php

class mypdf {

    private $content;
    private $pathtosave;
    private $prefix;
    private $pdf_obj;
    private $display_name;
    private $real_name;
    private $msg;
    private $return_arr;

    public function __construct() {
        $this->content = array();
        $this->pathtosave = "";
        $this->prefix = "";
        $this->display_name = "";
        $this->real_name = "";
        $this->msg = array();
        $this->return_arr = array();
        $this->pdf_obj = new mPDF();
    }

    public function __destruct() {
        // ob_clean();
    }

    public function getPDF($content = '', $prefix, $pathtosave = "", $type = 'F') {
        $this->content = $content;
        $this->pdf_obj->SetAuthor('ILBS Team');
        $this->pdf_obj->SetCreator('ILBS');
        $this->pdf_obj->SetTitle('ILBS');
        $this->pdf_obj->SetSubject('Admit Card');
        $this->pdf_obj->autoScriptToLang = TRUE;
        $this->pdf_obj->baseScript = 1;
        $this->pdf_obj->autoLangToFont = TRUE;
        $this->pathtosave = $pathtosave;
        if (trim($this->pathtosave) != "" && !is_dir($this->pathtosave)) {
            $this->msg[] = "Destination folder does not exist.";
            return false;
        }
        if (trim($this->pathtosave) != "" && !is_writable($this->pathtosave)) {
            $this->msg[] = "Destination folder is not writable.";
            return false;
        }
        $flag = 0;
        $x = 0;
        //$this->pdf_obj->debug = true;
        $this->pdf_obj->WriteHTML($this->content);
        $tme = microtime(true);
        $this->prefix = $prefix;
        if (trim($this->prefix) != "") {
            $display_name = ucfirst($this->prefix) . 'PDF.pdf';
            $real_name = ucfirst($this->prefix) .  $tme . '.pdf';
        } else {
            $display_name = 'PDF.pdf';
            $real_name = 'PDF_' . $tme . '.pdf';
        }
        $this->return_arr['display_name'] = $display_name;
        $this->return_arr['real_name'] = $real_name;
        $real_name = $this->pathtosave . '/' . $real_name;
        $this->pdf_obj->Output($real_name, $type);
    }

    public function getError() {
        return $this->msg;
    }

    public function getResult() {
        return $this->return_arr;
    }

}
