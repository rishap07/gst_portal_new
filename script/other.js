/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    var y = 2;
    $('body').delegate('.increase', 'click', function(e){
        if(y<4)
        {
            $('div, p, li, h2, h3').each(function(index)
            {
                var x = parseInt($( this).css( "font-size" ));
                x=x+1;
                $( this).css( "font-size",x );
            });
            y=y+1;
        }
    });
    $('body').delegate('.decrease', 'click', function(e){
        if(y>0)
        {
            $('div, p, li, h2, h3').each(function(index)
            {
                var x = parseInt($( this).css( "font-size" ));
                x=x-1;
                $( this).css( "font-size",x );
            });
            y=y-1;
        }
    });
    $('body').delegate('.reset', 'click', function(e){
        $('div, p, li, h2, h3').each(function(index)
        {
            $( this).css( "font-size","");
        });
        y=2;
    });
});