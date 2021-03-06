/* 
 * Created by Rishap Gandhi
 * Dated: 23th Dec 2015
 * Last Updated : 19th Jan 2017
 * Purpose: Validation for All forms
 * Things to do. 
 *  1. Create a Submit button event
 *  2. Use required class on all required elements
 *  3. Use Data-bind for regex to be applicable
 *  4. Pass Custom message for error. Pass array message array in function
 *     message['input_feild_name']='Your Custom Message';
 *     message['select_feild_name']='Your Custom Message';
 *     message['textarea_feild_name']='Your Custom Message';
 *     message['checkbox_feild_name']='Your Custom Message';
 *  5. Pass Form ID as second mandatory parameter
 *  Example How to use it
 *  $('#submit_button_id').click(function () {
        var mesg = {"your_input_feild_name":"My custom message"}; //You put blank bracket it you donot want to put custom message
        if (ilbs.validate(mesg,'form_id')) {
            return true;
        }
        return false;
    });
 */
$(document).on('change', '.required', function (e) {
    var temp = '#' + $(this).attr('id');
    if ($.trim($(this).val()) != '')
    {
        $(this).removeClass("errborder");
        if ($(this).next().attr('class') == 'err')
        {
            $(this).next().remove();
        }
    } else
    {
        if (!$(this).next().hasClass('err'))
        {
            $(this).after("<div class='err'>*Required</div>");
        }
        $(this).addClass("errborder");
    }
});

vali = {
    validate: function (message,form_id) {
        var formid='';
        if(form_id!='')
        {
            formid="#"+form_id;
        }
        console.log(formid);
        if (!message)
        {
            message = [];
        }
        var flag = 0;
        var flag1 = 0;
        var reg_match = [];
        reg_match['name'] = /^[a-zA-Z\s]+$/;
		reg_match['namewos'] = /^[a-zA-Z]+$/;
		reg_match['usernamewos'] = /^[a-zA-Z\d]+[(_{1}\-{1}\.{1})|(a-zA-Z\d)][a-zA-Z\d]+$/;
        reg_match['text'] = /^[a-zA-Z0-9\-\s,.\']+$/;
        reg_match['alphanum'] = /^[a-zA-Z0-9]+$/;
        reg_match['content'] = /^[^\\\"<>]+$/;
        reg_match['date'] = /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/;
        reg_match['yearmonth'] = /^[0-9]{4}-(0[1-9]|1[0-2])$/;
        reg_match['year'] = /^[0-9]{4}$/;
        reg_match['datetime'] = /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/;
        reg_match['url'] = /^(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:/~+#-]*[\w@?^=%&amp;/~+#-])?$/;
        reg_match['time'] = /^(2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/;
        reg_match['number'] = /^\d+$/;
        reg_match['numnzero'] = /^[1-9]\d*/;
        reg_match['mobilenumber'] = /^\d{10}$/;
        //reg_match['telephone'] = /^\d{6,30}$/;
        reg_match['pincode'] = /^\d{6}$/;
        reg_match['decimal'] = /^\s*-?[0-9]\d*(\.\d{1,2})?\s*$/;
		reg_match['valtax'] = /^\s*-?[0-9]\d*(\.\d{1,3})?\s*$/;
        //reg_match['avarge']=/^[0-9][0-9]{1,10}(\.[0-9]{1,2})?$/;
        reg_match['dl'] = /^[ A-Za-z0-9\-\/]*$/;
        reg_match['garage'] = /^[ A-Za-z0-9\&\/\-\(\)\.\+]*$/;
        reg_match['remarks'] = /^[ A-Za-z0-9/\n/\r\&\,\/\-\(\)\.]*$/;
        reg_match['itemDesc'] = /^[ A-Za-z0-9/\n/\r\&\,\/\-\(\)\.\+\%_]*$/;
        reg_match['vemodel'] = /^([ A-Za-z0-9/.-])*$/;
        //reg_match['email']=/^.+@.+\..{2,3}$/
        reg_match['email'] = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        reg_match['emailnew'] = /^[a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
        reg_match['pubname'] = /^[ A-Za-z0-9\'\"&().-]*$/
        reg_match['pancard'] = /^(([A-Z]){5}([0-9]){4}([A-Z]){1})*$/
		reg_match['gstin'] = /^(([0-9]){2}([A-Z]){5}([0-9]){4}([A-Z]){1}([A-Z0-9]){1}([Z]){1}([A-Z0-9]){1})+$/
        reg_match['address'] = /^[ a-zA-Z0-9/\n/\r\'.,/()-:]*$/
        reg_match['comname'] = /^[ a-zA-Z0-9!\'\"#$%&()*,-./:;=?@\\_]*$/
        reg_match['cardno'] = /^[ a-zA-Z0-9\/\-\(\)]*$/
        reg_match['pnrno'] = /^([0-9-]{10,20})*$/
        reg_match['int'] = /^[0-9]\d*/
        reg_match['amount'] = /^\d+(\.\d{1,2})?$/

        var err_msg = [];
        err_msg['name'] = "Should be Alphabets";
        err_msg['namewos'] = "Should be Alphabets Without Space";
		err_msg['usernamewos'] = "Should be Without Space";
        err_msg['text'] = "Can contains alphanumberic";
        err_msg['content'] = "Can\'t contains  \\ / :  < > \" ";
        err_msg['date'] = "Should be date format (YYYY-MM-DD)";
        err_msg['datetime'] = "Date Time format (YYYY-MM-DD HH:MM:SS)";
        err_msg['yearmonth'] = "Year Month format (YYYY-MM)";
        err_msg['year'] = "Year format (YYYY)";
        err_msg['time'] = "Time format (HH:MM:SS)";
        err_msg['url'] = "Should be URL with http or https";
        err_msg['number'] = "Only Number Allowed";
        err_msg['numnzero'] = "Number must be greater then 0";
        err_msg['decimal'] = "Allow 2 digit decimal numbers";
		err_msg['valtax'] = "Allow 3 digit decimal numbers";
        err_msg['mobilenumber'] = "Should be number and 10 digit.";
        err_msg['pincode'] = "Allow 6 digit numbers";
        err_msg['email'] = "Not Valid Email";
        err_msg['emailnew'] = "Not Valid Email";
        err_msg['pubname'] = "Can contain alphanumeric and ' \" . () -";
        err_msg['dl'] = "Please Enter Correct Value";
        err_msg['avarge'] = "Only decimal number allowed and should not more then 10 digit";
        err_msg['garage'] = "Can contain alphanumeric and & / - () . +";
        err_msg['remarks'] = "Can contain alphanumeric and ()-.,/ &";
        err_msg['itemDesc'] = "Can contain alphanumeric and ()-.,/ & + @ _%";
        err_msg['address'] = "Can contain alphanumeric and ' . , / () -: ";
        err_msg['pancard'] = "Plese Enter Correct Pan Card Format";
		err_msg['gstin'] = "Plese Enter Correct GSTIN Format";
        err_msg['vemodel'] = "Can contain alphanumeric and / - .";
        err_msg['comname'] = "Can contain alphanumeric and ! \' \" # $ % & ( ) * , - . / : ; = ? @ \\ _";
        err_msg['cardno'] = "Can contain alphanumeric and \/ \- \(\)";
        err_msg['pnrno'] = "Can contain number and \-  minmum length 10";
        err_msg['int'] = "Can contain only number";
        $(".err").each(function (index) {
            $(this).remove();
        });
        $(".errborder").each(function (index) {
            $(this).removeClass("errborder");
        });
		var xfocus='0';
        $(formid+' input, '+formid+' select, '+formid+' textarea, '+formid+' checkbox').each(function (index)
        {
            var tempflag = 0;
            var input = $(this);
			
            if ((input.hasClass('required') && $.trim(input.val()) == '')||(input.hasClass('required') && input.attr('type') == 'checkbox'  && input.prop("checked")===false))
            {
				if(xfocus=='0')
				{
					$(this).focus();
					xfocus='1';
				}
                tempflag = 1;
                flag = 1;
                console.log('Type: ' + input.attr('type') + 'Name: ' + input.attr('name') + 'Value: ' + input.val());
                $(input).after("<div class='err'>*Required</div>");
                $(input).addClass("errborder");
            }
            if (input.attr('data-bind') && tempflag == 0)
            {
				
                var str = input.val();
                var temp1 = new RegExp(reg_match[input.attr('data-bind')]);
                if ($.trim(str) != '')
                {
                    if (!temp1.test(str))
                    {
						if(xfocus=='0')
						{
							$(this).focus();
							xfocus='1';
						}
                        flag1 = 1;
                        name_chk = '';
                        if(input.attr('placeholder') && input.attr('placeholder')!='')
                        {
                            name_chk = input.attr('placeholder');
                        }
                        else
                        {
                            name_chk = input.attr('name');
                        }
                        if (input.attr('name') in message)
                        {
                            //$(input).after("<div class='err'>*" + message[input.attr('name')] + ", " + err_msg[input.attr('data-bind')] + "</div>");
                            $(input).after("<div class='err'>*" + message[input.attr('name')] + "</div>");
                        } else
                        {
                            $(input).after("<div class='err'>*Invalid " + name_chk + ", " + err_msg[input.attr('data-bind')] + "</div>");
                        }
                        $(input).addClass("errborder");
                    }
                }
            }
            if (input.attr('type') == 'file')
            {
                var file_selected = $(input).get(0).files;
                if ($(input).get(0).files && file_selected.length > 5)
                {
                    flag1 = 1;
                    $(input).after("<div class='err'>* You can select max 5 files</div>");
                    $(input).addClass("errborder");
                }
            }
        });
        if (flag == 1 || flag1 == 1)
        {
            return false;
        }
        return true;
    }
}

// THE SCRIPT THAT CHECKS IF THE KEY PRESSED IS A NUMERIC OR DECIMAL VALUE.
function validateInvoiceDiscount(evt, el) {
    
	var charCode = (evt.which) ? evt.which : event.keyCode;
    var number = el.value.split('.');
	if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    
	//just one dot
    if(number.length > 1 && charCode == 46){
         return false;
    }
    
	//get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if( caratPos > dotPos && dotPos > -1 && (number[1].length > 1)){
        return false;
    }

	if(parseFloat(el.value) > 100) {
		el.value = 100;
	}

    return true;
}

// THE SCRIPT THAT CHECKS IF THE KEY PRESSED IS A NUMERIC OR DECIMAL VALUE.
function validateInvoiceAmount(evt, el) {
    
	var charCode = (evt.which) ? evt.which : event.keyCode;
    var number = el.value.split('.');
	if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    
	//just one dot
    if(number.length > 1 && charCode == 46){
         return false;
    }

	//get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if( caratPos > dotPos && dotPos > -1 && (number[1].length > 1)){
        return false;
    }

	return true;
}

// THE SCRIPT THAT CHECKS IF THE KEY PRESSED IS A NUMERIC OR DECIMAL VALUE.
function validateDecimalValue(evt, el) {

	var charCode = (evt.which) ? evt.which : event.keyCode;
    var number = el.value.split('.');
	if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }

	//just one dot
    if(number.length > 1 && charCode == 46){
         return false;
    }

	//get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if( caratPos > dotPos && dotPos > -1 && (number[1].length > 1)){
        return false;
    }

	return true;
}

// THE SCRIPT THAT CHECKS IF THE KEY PRESSED IS A NUMERIC OR DECIMAL VALUE 3 DIGITS ALLOW.
function validateTaxValue(evt, el) {

	var charCode = (evt.which) ? evt.which : event.keyCode;
    var number = el.value.split('.');
	if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }

	//just one dot
    if(number.length > 1 && charCode == 46){
         return false;
    }

	//get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if( caratPos > dotPos && dotPos > -1 && (number[1].length > 2)){
        return false;
    }

	return true;
}

function getSelectionStart(o) {

	if (o.createTextRange) {
		var r = document.selection.createRange().duplicate()
		r.moveEnd('character', o.value.length)
		if (r.text == '') return o.value.length
		return o.value.lastIndexOf(r.text)
	} else return o.selectionStart
}