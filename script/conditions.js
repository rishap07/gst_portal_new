/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
    $('body').delegate('.checkAll', 'change', function(){
        if($(this).prop('checked') == true){
            var flag=0;
            $('#mainTable').find('.checkRecord').each(function(){
                flag=1;
                $(this).prop('checked', true);
            });
            if(flag==1)
            {
                $('#activeBtn').prop('disabled', false);
                $('#deactiveBtn').prop('disabled', false);
                $('#deleteBtn').prop('disabled', false);
            }
        }else{
            var flag=0;
            $('#mainTable').find('.checkRecord').each(function(){
                flag=1
                $(this).prop('checked', false);
            });
            if(flag==1)
            {
                $('#activeBtn').prop('disabled', true);
                $('#deactiveBtn').prop('disabled', true);
                $('#deleteBtn').prop('disabled', true);
            }
        }
    });
    
    $('body').delegate('.checkRecord', 'change', function(){
        var cheked = $('#mainTable').find('.checkRecord:checked').length;
        var total = $('#mainTable').find('.checkRecord').length;
        if(cheked < total){
            $('.checkAll').prop('checked', false);
        }else if(cheked == total){
            $('.checkAll').prop('checked', true);
        }
        if(cheked){
            $('#activeBtn').prop('disabled', false);
            $('#deactiveBtn').prop('disabled', false);
            $('#deleteBtn').prop('disabled', false);
        }else{
            $('#activeBtn').prop('disabled', true);
            $('#deactiveBtn').prop('disabled', true);
            $('#deleteBtn').prop('disabled', true);
        }
    });
    
    $('body').delegate('.actionBtn', 'click', function(e){
       e.preventDefault();
       $('#actionType').val($(this).val());
       $(this).parents('form').submit();
    });
    $('#deleteBtn').click(function() {
        var res = confirm('Are you sure you want to delete?');
        if(!res){ 
           return false; 
        }else{ 
          return true;
        }
    });
    $('#activeBtn').click(function() {
        var res = confirm('Are you sure you want to activate?');
        if(!res){ 
           return false; 
        }else{ 
          return true;
        }
    });
    $('#deactiveBtn').click(function() {
        var res = confirm('Are you sure you want to deactivate?');
        if(!res){ 
           return false; 
        }else{ 
          return true;
        }
    });
});
    