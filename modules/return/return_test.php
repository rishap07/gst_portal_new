<?php
$obj_gstr2 = new gstr2();
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
echo date('Y-m-d H:i:s')."<br>";
$data = 1;
for($x=0;$x<10000;$x++)
{
    for($y=0;$y<10000;$y++)
    {
        $data++;
    }
}
echo $data."<br>".date('Y-m-d H:i:s')."<br>";