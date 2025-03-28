var l = window.location;
var base_url = l.protocol + "//" + l.host  + l.pathname;
var c_type;
var c_desc;
var c_type_val='';
var c_desc_val='';
var deg_id;
$(document).ajaxStart(function() { Pace.restart(); });
$(function () {
  $('[data-toggle="popover"]').popover({placement: function() { return $(window).width() < 768 ? 'bottom' : 'right'; }})
});


$(document).ready(function() { 
     
    // Configuration Settings
    
    $('.show_dates').hide();   
    $(".dropdown_is_status").hide(); 
    $(".photo_status").hide();  
    
    //Catyegory Settings
    $('.cat_creation').hide();
    $('.cat_type_creation').hide();
    $('.create_btn').hide();
    $('.cat_tbl').hide();
    $('.type_btn').hide();
    $('.new_btn').hide();

    //Degree Settings
    $('.d_name').hide();
    $('.d_type').hide();
    $('.yrs_sem').hide();
    $('.deg_btn').hide();
    $('.deg_back_btn').hide();

    //Programme Settings
    $('.prgm_name').hide();
    $('.prgm_btn').hide();
    $('.prgm_back_btn').hide();

    // Batch & Regulation 
    $('#grade').hide(); 
    $('#BatchSubmit').hide();
    $('#add_content_table').hide();
    $('#exit_table').hide();
    $('#degree_tbl').hide();
    $('#grade_tbl').hide();
    $('#add_grade').hide();
    $('#exit_grade').hide();
    $('#gradepoints').hide();
    $('#gradepoints').hide();
    $('#add').hide();
    $('#reg').hide();
    //$('#main_div').hide();
    $('#button_new_batch').hide();
    $('#button_show_degree').hide();

    //Import Functions load 
    $('#changeColors').hide();

    //Nominal
    $('#CreateNominal').hide();

    // Migrate Subjects
    $('.mig_tbl').hide();
    $('.mig_div').hide();
    
    //Galley
    $('#subjectwise').hide();
    // Student Functions 
    
});

/* 
Import Functions
*/
function bs_input_file() {
    $(".input-file").before(
        function() {
            if ( ! $(this).prev().hasClass('input-ghost') ) {
                var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
                element.attr("name",$(this).attr("name"));
                element.change(function(){
                    element.next(element).find('input').val((element.val()).split('\\').pop());
                });
                $(this).find("button.btn-choose").click(function(){
                    element.click();
                });
                $(this).find("button.btn-reset").click(function(){
                    element.val(null);
                    $(this).parents(".input-file").find('input').val('');
                });
                $(this).find('input').css("cursor","pointer");
                $(this).find('input').mousedown(function() {
                    $(this).parents('.input-file').prev().click();
                    return false;
                });
                return element;
            }
        }
    );
}
$(function() {
    bs_input_file();
});

function changeFile(value)
{
    $('#changeColors').css({ "background-color": "#2173BC",'color':"#FFF !important" });  
    $('#changeColors').show();  
    var href = "";  
    href = $("#download_smple").removeAttr("href");
    $("#download_smple").attr("href", "index.php?r=import/download-sample&value="+value);
}



/* Import Functions Ends here */

/* configuration Starts Her */


$("#config_name_id").on('change',function(){ 
    var config_desc = $("#config_name_id").val();   
    $.ajax({
     url:base_url+"?r=ajaxrequest/getconfigvalue",
     type:"POST",
      data:{
             config_desc: config_desc,
          },
         success: function (data) {   
              
           var jsonFormat = JSON.parse(data);
           var currentYear = (new Date()).getFullYear();

            for(var i = 0; i < jsonFormat.length; i++)
            {   
                var updated_at = parseInt(jsonFormat[i].updated_at);
                var prop_value = updated_at==0?false:true;
                if(prop_value==true)
                {
                    krajeeDialog.dialog(
                        'You have already changed the value of <b>'+jsonFormat[i].config_desc+
                        '</b> <br /> You are <b>Not Allowed</b> to change the value again.<br /> Please contact <b>Admin</b> for more Help!!',
                        function (result) {alert(result);}
                    );
                }
                if(jsonFormat[i].config_name.match("locking.start"))
                {
                  
                  $( "input[name='start_date']" ).val(jsonFormat[i].config_value+"-"+currentYear).prop( "disabled", prop_value );
                  $("input[name='is_status']").val('');
                  $("#config_value_assign").val('');
                  $(".student_photos_path").val('');
                  $(".nominal_status_clear").val('');
                }
                else if(jsonFormat[i].config_name.match("locking.end"))
                {
                  $( "input[name='end_date']" ).val(jsonFormat[i].config_value+"-"+currentYear).prop( "disabled", prop_value );
                  $("input[name='is_status']").val('');
                  $("#config_value_assign").val('');
                  $(".student_photos_path").val('');
                  $(".nominal_status_clear").val('');
                }
                else if(jsonFormat[i].config_name.match("status"))
                {
                    $( "input[name='start_date']" ).val('');
                    $( "input[name='end_date']" ).val('');
                    $("#config_value_assign").val('');
                    $(".student_photos_path").val('');
                    $("#configuration-is_status").val(jsonFormat[i].config_value).trigger('change').prop( "disabled", prop_value );
                }
                else if(jsonFormat[i].config_name.match("student.photo.url"))
                {
                    $( "input[name='start_date']" ).val('');
                    $( "input[name='end_date']" ).val('');
                    $("#config_value_assign").val('');
                    $("input[name='is_status']").val('');
                    $(".student_photos_path").attr("value", jsonFormat[i].config_value).trigger('change').prop( "disabled", prop_value );
                }
                else
                {
                    $( "input[name='start_date']" ).val('');
                    $( "input[name='end_date']" ).val('');
                    $("input[name='is_status']").val('');
                    $(".student_photos_path").val('');
                    $("#config_value_assign").val(jsonFormat[i].config_value).prop( "disabled", prop_value );
                    
                }
            }
          
         }
    });

});
function validateDate(dateValidate)
{   
    var start_date = $("input[name='start_date']" ).val();
    var end_date = $("input[name='end_date']" ).val();
    var config_desc = $("#config_name_id").val();  
    var end_date_diff = Date.parse(end_date);
    var start_date_diff =Date.parse(start_date);
    
    if(end_date_diff>start_date_diff)
    {
        krajeeDialog.dialog(
            'Please Review your Submission for '+config_desc+' <br /><br /> <b>Start Date '+start_date+"</b> <br /><br /> <b>End Date : "+end_date+
            "</b> <br /><br /> You are Allowed to do the operations in the above time period.",
            function (result) {alert(result);}
        );
        return true;
    }
    else
    {
        krajeeDialog.alert("End Date is lesser than the Start Date");
        return false;
    }
}


function changeVal(variable)
{
    if(variable.match("Locking"))
    {    
        $(".hide_value").fadeOut(3000);
        $(".hide_value").removeAttr('value').hide();
        $(".dropdown_is_status").fadeOut(3000).removeAttr('value').hide(); 
        $(".show_dates").fadeIn(2000).show(); 
        $(".photo_status").fadeOut(3000).removeAttr('value').hide();
        $(".student_photos_path").val('');
        $(".nominal_status_clear").val('');
    }
    else if(variable.match("Status"))
    {
    	 $(".show_dates").datepicker('setDate', null);
    	 $(".show_dates").fadeOut(3000).removeAttr('value').hide(); 
    	 $(".hide_value").fadeOut(3000).removeAttr('value').hide();     
    	 $(".dropdown_is_status").fadeIn(2000).show();
         $(".photo_status").fadeOut(3000).removeAttr('value').hide();
         $(".student_photos_path").val('');

    }
    else if(variable.match("Photos Directory"))
    {
         $(".show_dates").datepicker('setDate', null);
         $(".show_dates").fadeOut(3000).removeAttr('value').hide(); 
         $(".hide_value").fadeOut(3000).removeAttr('value').hide();     
         $(".dropdown_is_status").fadeOut(3000).removeAttr('value').hide(); 
         $(".photo_status").fadeIn(2000).show(); 
         $(".nominal_status_clear").val('');

    }
    else
    {
    	 $(".show_dates").fadeOut(3000).removeAttr('value').hide(); 
    	 $(".dropdown_is_status").fadeOut(3000).removeAttr('value').hide(); 
    	 $(".hide_value").fadeIn(2000).show();   
         $(".photo_status").fadeOut(3000).removeAttr('value').hide();   
         $(".student_photos_path").val('');
         $(".nominal_status_clear").val('');
    }
}


/* Configuration Ends Her */

/*
Student Functions 
*/
$("#check_all_stu_vals").on("click",function(){
    
    var values= $("#stu_dob").val();
    var nameOfLable = $('label[for=stu_dob]').text();
    if(values)
    {
         $("#stu_dob").css({ "border": "1px solid #00A65A" }); 
        return true;
    }
    else
    {
        krajeeDialog.alert("Please Enter the value for "+nameOfLable);
        $("#stu_dob").css({ "border": "1px solid #f00" }); 
        $("#stu_dob").focus(); 
        $("#student_submit_form").submit(function(e){
            return false;
        });
    }
});

function isDate(dateOfBirth)
{
  var today = new Date();
  var currentYear = today.getFullYear();
  var dob_year = $("#"+dateOfBirth).val().split("/")[2];
  var difference = currentYear-dob_year;
  if(difference>=16)
  {
     $("#"+dateOfBirth).focus().css("border","1px solid #00A65A");
     return true;
  }
  else
  {
    krajeeDialog.alert($("#"+dateOfBirth).val()+" should be 16 years Back");
    $("#"+dateOfBirth).val("");
    $("#"+dateOfBirth).focus().css("border","1px solid #f00");
    return false;
  }
  
}


$("#bulk_edit_stu").on('click',function(){
    var but=$("#bulk_edit_stu").val('Update');
    
});

$("#stu_programme_selected").on('change',function(){
        
        $.ajax({
        url: base_url+"?r=ajaxrequest/getsectionnames",
        type:'POST',
        data:{coe_bat_deg_reg_id:$("#stu_programme_selected").val()},
        success: function(data){
           var jsonFormat = JSON.parse(data);           
           var print_section = 65+jsonFormat.no_of_section;
           var send_to_dropdown = $('#stu_section_select');
           send_to_dropdown.html('');
           send_to_dropdown.append('<option value>---Select---</option>');
           for (var i = 65; i < print_section; i++) {                
                send_to_dropdown.append('<option value='+String.fromCharCode(i)+'>' + String.fromCharCode(i) + '</option>');
            }
        }
    });
}); 


$("#stu_batch_id_selected").on("change",function(){
    var global_batch_id = $("#stu_batch_id_selected").val();
     $.ajax({
            url: base_url+'?r=ajaxrequest/getdegreedetails',
            type:'POST',
            data:{global_batch_id:global_batch_id},
            
            success:function(data)
            {
                var stu_programme_dropdown = $("#stu_programme_selected");
                stu_programme_dropdown.html(data);

            }
        });

});




/*

Student Functions Ends Here 
*/



/* Categories Functions */
$('#category_name').on('change',function(){
    var category=$('#category_name').val();
    if(category==0)
    {
        $('.cat_creation').show();
        $('.cat_type_creation').show();
        $('.create_btn').show();
        $('.cat_tbl').hide();
        $('#type1').hide();
        $('.new_btn').hide();
    }
    else
    {
        $('.cat_creation').hide();
        $('.cat_type_creation').hide();
        $('.create_btn').hide();
        $('.new_btn').show();
        
        $.ajax({
            url: base_url+'?r=ajaxrequest/getcategoryvalue',
            type:'POST',
            data:{category_id:category},
            success:function(data)
            {
                if(data!=0)
                {   
                    //$('#categories-category_name').val(category);
                    $('.cat_tbl').show();
                    $('#stu_tbl').show();
                    $('#stu_tbl').html(data); 
                }
            }
        });
    }
});

$('#type').on('click',function(){
    $('.cat_type_creation').show();
    $('.create_btn').show();
    $('.new_btn').hide();
});

$('.categories_submit_before').on('click',function(){
	var cat_val = $('#category_name').val();

	var cat_name = $('#categories-category_name').val();
	var cat_desc = $('categories-description').val();

    var c_name_label_name = $('label[for=category_name]').text();
    var c_type_label_name = $('label[for=c_type]').text();
    var c_desc_label_name = $('label[for=c_desc]').text();

	var c_type = $('#c_type').val();
	var c_desc = $('#c_desc').val();

	var c_list = $('#c_list').val();
	var c_list1 = $('#c_list1').val();
	if(cat_val==0)
	    {
		if(cat_name!="" && cat_desc!="")
		    {
			if(c_type=="" && c_list=="")
			    {
				alert('Please Enter the value for '+c_type_label_name+' / '+c_desc_label_name);
				$('#c_type').focus();
				return false;
			    }
		    }
		else
		    {
			alert('Please Enter the value for '+c_name_label_name+' / '+c_desc_label_name);
			$('#categories-category_name').focus();
			return false;
		    }
	    }
	else
	    {
		if(c_type=="" && c_list=="")
		    {
			alert('Please Enter the value for '+c_type_label_name+' / '+c_desc_label_name);
			$('#c_type').focus();
			return false;
		    }
	    }
    });

$('#new1').on('click',function(){
	var c_type_label_name = $('label[for=c_type]').text();
	var c_desc_label_name = $('label[for=c_desc]').text();
	var c_type=$('#c_type').val();  
	var c_desc=$('#c_desc').val(); 
	var cat_val = $('#category_name').val();
   
	if(c_type!="" && c_desc!="")     
	    {
		$.ajax({
			url: base_url+'?r=ajaxrequest/getcategorytype',
			    type:'POST',
			    data:{c_type:c_type,c_desc:c_desc,cat_val:cat_val},
			    success:function(data)
			    {
				if(data==1)
				    {
					alert('The value for '+c_type_label_name+' / '+c_desc_label_name+' already created');
					return false;
				    } 
				else
				    {
					c_type_val+=c_type+'#';   
					c_desc_val+=c_desc+'#'; 
					$('#c_type').val('');
					$('#c_desc').val('');
					$('#c_list').val(c_type_val);
					$('#c_list1').val(c_desc_val);
				    }
			    }
		    });
	    }
	else
	    {
		alert('Please Enter the value for '+c_type_label_name+' / '+c_desc_label_name);
		return false;
	    }
    });

/*//* Categories FGunction */

/* Batch Related Functions */

$('#regulation-grade_point_from').on('click',function(){
    var checked_value = $("input[name='gradee']:checked").val();
    if(checked_value==undefined){
        krajeeDialog.alert("Select Grade Out of 10 or 100");
    }
});

$('#regulation-grade_point_to').on('click',function(){
    var checked_value = $("input[name='gradee']:checked").val();
    var grade_from = parseFloat($('#regulation-grade_point_from').val());
    if(isNaN(grade_from) || grade_from>checked_value){ 
        krajeeDialog.alert("Select Valid Grade Range From");       
        $('#regulation-grade_point_from').val("");
        $('#regulation-grade_point_from').focus();
        
    }
});

$('#regulation-grade_name').on('click',function(){
    var checked_value = $("input[name='gradee']:checked").val();
    var grade_from = parseFloat($('#regulation-grade_point_from').val());
    var grade_to = parseFloat($('#regulation-grade_point_to').val());
    if(isNaN(grade_to) || grade_to>checked_value || grade_from>grade_to){
        krajeeDialog.alert("Select Valid Grade Range To");
        $('#regulation-grade_point_to').val("");
        $('#regulation-grade_point_to').focus();
    }     
});

$('#regulation-grade_point').on('click',function(){
    var grade_name = $('#regulation-grade_name').val();
    if(grade_name=="" || grade_name.match(/[0-9]/i)){
        krajeeDialog.alert("Enter Valid Grade Name");
        $('#regulation-grade_name').val("");
        $('#regulation-grade_name').focus();
    }
});

$('#reset').on('click',function(){
    $('#stu_tbl').hide();
    $('#batch_name').focus();    
});

$('#grade10').click(function() {
    $('#grade100').prop('disabled', true);
});

$('#grade100').click(function() {
    $('#grade10').prop('disabled', true);
});

$('#button_view_batch').on('click',function(){ 
    var batch_label_name = $('label[for=batch_name]').text(); 
    var batch = $('#batch_name').val();
    var regyear = $('#reg_year').val();
    if(batch!=""){       
        $('#batch_name').css({"border": "1px solid #078e4d"});      
        $.ajax({
            url: base_url+"?r=ajaxrequest/getexistbatch",
            type: "POST",
            data:{batch:batch,regyear:regyear},
            success: function(data){    
                if(data==0){
                    $('#reg').show();
                    $('#button_view_batch').hide();
                    $('#button_new_batch').show();
                    $('#reset').hide();                   
                }
                else{
                    $('#stu_tbl').show();
                    $('#stu_tbl').html(data);
                }                           
            }
        });     
    }else{
        krajeeDialog.alert(batch_label_name+" should not be empty");
        $('#batch_name').css({"border": "1px solid #f00"}).focus();
    }   
});


$('#appendgrade').html("<tr></tr>");
$('#add_grade').on('click',function(){

    var grade_from = parseFloat($('#regulation-grade_point_from').val());
    var grade_to = parseFloat($('#regulation-grade_point_to').val());
    var grade_name = $('#regulation-grade_name').val();
    var grade_point = parseInt($('#regulation-grade_point').val());    
    if(!isNaN(grade_point)){                        
        $.ajax({
        url : base_url+"?r=ajaxrequest/getgrade",
        type: "post",       
        success : function(data){
            $('#grade_tbl').css({"visibility": "visible"});
            $('#grade_tbl').show();
            $('#appendgrade').append("<tr><td><input type='hidden' name='from[]'  value='"+grade_from+"' />"+grade_from+"</td><td><input type='hidden' name='to[]' value='"+grade_to+"' />"+grade_to+"</td><td><input type='hidden' id='grade_name' name='name[]' value='"+grade_name.toUpperCase()+"' />"+grade_name.toUpperCase()+"</td><td><input type='hidden' id='grade_point' name='point[]' value='"+grade_point+"' />"+grade_point+"</td></tr>");                     
            $('#regulation-grade_point_from').val("");
            $('#regulation-grade_point_to').val("");
            $('#regulation-grade_name').val("");
            $('#regulation-grade_point').val("");
        }
        });         
    }else{
        krajeeDialog.alert("Select All Required Fields");
        $('#regulation-grade_point').focus();
    }
});

$('#exit_grade').on('click',function(){
    var grade_from = parseFloat($('#regulation-grade_point_from').val());
    var grade_to = parseFloat($('#regulation-grade_point_to').val());
    var grade_name = $('#regulation-grade_name').val();
    var grade_point = parseInt($('#regulation-grade_point').val()); 
    if(!isNaN(grade_point)){   
        $('#appendgrade').append("<tr><td><input type='hidden' name='from[]'  value='"+grade_from+"' />"+grade_from+"</td><td><input type='hidden' name='to[]' value='"+grade_to+"' />"+grade_to+"</td><td><input type='hidden' id='grade_name' name='name[]' value='"+grade_name+"' />"+grade_name+"</td><td><input type='hidden' id='grade_point' name='point[]' value='"+grade_point+"' />"+grade_point+"</td></tr>");                     
        $('#grade').hide();
        $('#gradepoints').hide();
        $('#add_grade').hide();
        $('#exit_grade').hide();
        $('#button_new_batch').hide();
        $('#grade_tbl').hide();
        $('#button_show_degree').show(); 
    }else{
        
        $('#grade').hide();
        $('#gradepoints').hide();
        $('#add_grade').hide();
        $('#exit_grade').hide();
        $('#button_new_batch').hide();
        $('#grade_tbl').hide();
        $('#button_show_degree').show(); 
    }   
});


$('#button_new_batch').on('click',function(){

    $('#batch_name').css({"border": "1px solid #078e4d"});
    var reg_year_label_name = $('label[for=reg_year]').text(); 
    var reg_year = $('#reg_year').val();
    if(reg_year!=""){
    $.ajax({
        url : base_url+"?r=ajaxrequest/getregulationyear",
        type: "post",
        data : {reg_year: $('#reg_year').val()},
        success : function(data)
        {           
            if(data==0 ){
                $('#reg_year').css({"border": "1px solid #078e4d"});
                $('#grade').show();
                $('#gradepoints').show();
                $('#button_view_batch').hide();
                $('#button_new_batch').hide();
                $('#add_grade').show();
                $('#exit_grade').show();

            }else{
                $('#reg_year').css({"border": "1px solid #078e4d"});
                $('#grade').hide();
                $('#gradepoints').hide();
                $('#add_grade').hide();
                $('#exit_grade').hide();
                $('#button_new_batch').hide();
                krajeeDialog.alert("Confirm to follow "+$('#reg_year').val()+" "+ reg_year_label_name +" year");
                $('#button_show_degree').show();
            }           
        }
    }); 
    }else{
        krajeeDialog.alert("Enter "+reg_year_label_name+" Year");
        $('#reg_year').focus();
    }
});


function getShowData(id)
{
    deg_id = id;
    var end_value = deg_id.substr(deg_id.lastIndexOf('_') + 1);    
    var prgm_id = "programme_selected"+end_value; 
    var pg_name = "programmes_"+end_value; 
    if($("input[name='"+pg_name+"']:checked").val()){           
        $("#"+prgm_id).prop('disabled', false); 
    }else{
        $("#"+prgm_id).prop('disabled', true);      
    }    
}

$("#appendrows").html("<tr></tr>");
$('#add_content_table').on('click',function(){
    var degree = $('#degree').val();
    if(degree!=""){ 
    $.ajax({
        url: base_url+"?r=ajaxrequest/getprogramme",
        type: "POST",
        success: function(data){
            var ch_id = deg_id.substr(0,deg_id.lastIndexOf('_'));
            $('#degree_tbl').show();        
            for(var i=1;i<=data;i++){
                if ($('#'+ch_id+'_'+i).is(":checked")){
                    var pgm = $('#'+ch_id+'_'+i).val();
                    var sec = $('#programme_selected'+i).val();                                   
                    $('#appendrows').append("<tr><td><input type='hidden' name='deg[]' value='"+degree+"' />"+degree+"</td><td><input type='hidden' name='pgm[]' value='"+pgm+"' />"+pgm+"</td><td><input type='hidden' name='sec[]' value='"+sec+"' />"+sec+"</td></tr>");
                    $('#programme_selected'+i).val("1");
                    $('#programme_selected'+i).prop("disabled", true);
                    $('#'+ch_id+'_'+i).prop("checked", false);            

                }
            }
            $("#degree option:selected").prop("disabled","disabled");
            $('#degree').val("");
        }   
    });
    }else{
        krajeeDialog.alert("Select All Required Fields");
    }
});


$('#exit_table').on('click',function(){
    var degree = $('#degree').val();
    if(degree!=""){
        $.ajax({
        url: base_url+"?r=ajaxrequest/getprogramme",
        type: "POST",
        success: function(data){
            var ch_id = deg_id.substr(0,deg_id.lastIndexOf('_'));
            $('#degree_tbl').show();
            for(var i=1;i<=data;i++){
                if ($('#'+ch_id+'_'+i).is(":checked")){
                    var pgm = $('#'+ch_id+'_'+i).val();
                    var sec = $('#programme_selected'+i).val();                                   
                    $('#appendrows').append("<tr><td><input type='hidden' name='deg[]' value='"+degree+"' />"+degree+"</td><td><input type='hidden' name='pgm[]' value='"+pgm+"' />"+pgm+"</td><td><input type='hidden' name='sec[]' value='"+sec+"' />"+sec+"</td></tr>");
                    $('#programme_selected'+i).val("1");
                    $('#programme_selected'+i).prop("disabled", true);
                    $('#'+ch_id+'_'+i).prop("checked", false);            
                    $('#stu_tbl').hide();
                    $('#add_content_table').hide();
                    $('#exit_table').hide();
                    $('#BatchSubmit').show();    
                    $('#grade_tbl').show(); 
                }
            }

        }
    });
    }else{
        $('#stu_tbl').hide();
        $('#add_content_table').hide();
        $('#exit_table').hide();
        $('#BatchSubmit').show();    
        $('#grade_tbl').show(); 
    }
    
    
       
});

$('#button_show_degree').on('click',function(){

    var reg_year_label_name = $('label[for=reg_year]').text();
    var alert_degree_name_label_name = $('label[for=alert_degree_name]').text();
    var alert_programme_name_label_name = $('label[for=alert_programme_name]').text(); 
    var batch_label_name = $('label[for=batch_name]').text(); 
    var batch = $('#batch_name').val();
    var regyear = $('#reg_year').val();
    if(batch!="" ){
        $('#batch_name').css({"border": "1px solid #078e4d"});
        if(regyear!=""){
            $('#reg_year').css({"border": "1px solid #078e4d"});
            $.ajax({
                url: base_url+"?r=ajaxrequest/getdegpgmtable",
                type: "POST",
                data:{batch:batch,regyear:regyear},
                success: function(data){
                    if(data==0){
                        krajeeDialog.alert(alert_degree_name_label_name+" and "+alert_programme_name_label_name+" is not available to create batch");
                    }else{
                        $('#stu_tbl').show();
                        $('#stu_tbl').html(data);           
                        $('#add_content_table').show();
                        $('#exit_table').show();
                        $('#button_show_degree').hide();        
                    }   
                }
            });
        }else{
            krajeeDialog.alert(reg_year_label_name+" should not be empty");
            $('#reg_year').css({"border": "1px solid #f00"}).focus();
        }       
    }else{
        krajeeDialog.alert(batch_label_name+" should not be empty");
        $('#batch_name').css({"border": "1px solid #f00"}).focus();
    }
});
/* Batch Related Fucntions */

/* Welcome Text */

function autoType(elementClass, typingSpeed){
  var thhis = $(elementClass);
  thhis.css({
    "position": "relative",
    "display": "inline-block"
  });
  thhis.prepend('<div class="cursor" style="right: initial; left:0;"></div>');
  thhis = thhis.find(".text-js");
  var text = thhis.text().trim().split('');
  var amntOfChars = text.length;
  var newString = "";
  thhis.text("|");
  setTimeout(function(){
    thhis.css("opacity",1);
    thhis.prev().removeAttr("style");
    thhis.text("");
    for(var i = 0; i < amntOfChars; i++){
      (function(i,char){
        setTimeout(function() {        
          newString += char;
          thhis.text(newString);
        },i*typingSpeed);
      })(i+1,text[i]);
    }
  },1500);
}

$(document).ready(function(){  
  autoType(".type-js",200);
});
/* Welcome Text Ends Here */



/* Degree Start Here */

$( "#deg_sub" ).on('click',function() {
    var degree_label_name = $('label[for=degree_code]').text();
    var degree=$('#degree_code').val();
    if(degree=="")
    {
        krajeeDialog.alert("Please enter the value for "+degree_label_name+" Name");
        //$('#degree_code').focus();
        return false;
    }
    else
    {
        $.ajax({
        url: base_url+'?r=ajaxrequest/getdegreevalue',
            type:'POST',
            data:{deg_name:degree},
            success:function(data)
            {
            if(data!=0)
                {
                $('.deg_back_btn').show();
                $('.deg_tbl').show();
                $('#stu_tbl').show();
                $('#stu_tbl').html(data); 
                $('#deg_sub').hide();
                }   
            else
                {
                $('.d_name').show();
                $('.d_type').show();
                $('.yrs_sem').show();
                $('.deg_btn').show();
                $('#deg_sub').hide();
                }
            }
        });
    }
    
    });

$('#deg_back').on('click',function(){
    $('#degree_code').val('');
    $('.d_name').show();
    $('.d_type').show();
    $('.yrs_sem').show();
    $('.deg_btn').show();
    $('.deg_back_btn').hide();
    $('.deg_tbl').hide();
    });

/* Degree Ends Here */

/* Programme Starts Here */
$( "#prgm_sub" ).on('click',function() {
    var programme_label_name = $('label[for=prgm_code]').text();
	var programme=$('#prgm_code').val();
	if(programme=="")
	    {
		krajeeDialog.alert("Please enter the value for "+programme_label_name+" name");
		return false;
	    }
	else
	    {
	$.ajax({
		url: base_url+'?r=ajaxrequest/getprogrammevalue',
		    type:'POST',
		    data:{prgm_name:programme},
            
		    success:function(data)
		    {
			if(data!=0)
			    {
				$('.prgm_back_btn').show();
				$('.prgm_tbl').show();
				$('#stu_tbl').show();
				$('#stu_tbl').html(data); 
				$('#prgm_sub').hide();
			    }   
			else
			    {
				$('.prgm_name').show();
				$('.prgm_btn').show();
				$('#prgm_sub').hide();
			    }
		    }
	    });
	    }
    });

$('#prgm_back').on('click',function(){
	$('#prgm_code').val('');
	$('#prgm_code').show();
	$('.prgm_name').show();
	$('.prgm_btn').show();
	$('.prgm_back_btn').hide();
	$('.prgm_tbl').hide();
	$('#stu_tbl').hide();
    });
/* Programme Ends Here */



function getThisval(id,value)
{    
    var elective_id = id.substr(id.lastIndexOf('_') + 1); 
    var stu_id = id.substr(0,id.lastIndexOf('_'));
    
    if(elective_id==1){
        var sub_code = $('#'+stu_id+'_'+elective_id).val();             
        $('#'+stu_id+'_2').click(function() {
            $('#'+stu_id+'_2').find("option[value*="+sub_code+"]").prop("disabled", true);           
    });
    }else{
        var sub_code = $('#'+stu_id+'_'+elective_id).val();               
        $('#'+stu_id+'_1').click(function() {
            $('#'+stu_id+'_1').find("option[value*="+sub_code+"]").prop("disabled", true);
    });
    }
}

/* Nominal Ends Here */



/* Migrate Subject starts here */

$("#mig_batch_id_selected").on("change",function(){
    var global_batch_id = $("#mig_batch_id_selected").val();

     $.ajax({
            url: base_url+'?r=ajaxrequest/getdegpgmdetails',
            type:'POST',
            data:{global_batch_id:global_batch_id},
            
            success:function(data)
            {
                var stu_programme_dropdown = $("#mig_programme_selected");
                stu_programme_dropdown.html(data);
            }
        });

     $.ajax({
            url: base_url+'?r=ajaxrequest/getmigbatch',
            type:'POST',
            data:{global_batch_id:global_batch_id},
            
            success:function(data)
            {
                var mig_batch_dropdown = $("#stu_migrate_id_selected");
                mig_batch_dropdown.html(data);
            }
        });

});

$('#mig_btn').on('click',function(){
    var batch = $('#mig_batch_id_selected').val();
    var batch_map_id = $('#mig_programme_selected').val();
    var sem = $('#sem').val();
    var mig_year = $('#stu_migrate_id_selected').val();

    if(batch=="" || batch_map_id=="" || sem=="" || mig_year=="")
    {
        krajeeDialog.alert("Select All The Fields");
        return false;
    }
    else
    {
        $.ajax({
            url: base_url+'?r=ajaxrequest/getmigratedetails',
            type:'POST',
            data:{batch:batch,batch_map_id:batch_map_id,sem:sem},
            
            success:function(data)
            {
                if(data!=0)
                {
                    $('.mig_tbl').show();
                    $('#stu_tbl').html(data);
                    $('.mig_div').show();
                }
                else
                {
                    krajeeDialog.alert("No data found");
                    return false;
                }
            }
        });
    }
});

/* Migrate Subject Ends here*/

/* Nominal Starts Here */
$('#button_view_nominal').on('click',function(){
    var batch = $('#stu_batch_id_selected').val();
    var programme = $('#stu_programme_selected').val();
    var section = $('#stu_section_select').val();
    var semester = $('#nominal-semester').val();

    $.ajax({
        url: base_url+'?r=ajaxrequest/getviewnominal',
        type:'POST',
        data:{batch:batch,programme:programme,section:section,semester:semester},
        success:function(data){   
            if(data)
            {
                var parsed = $.parseJSON(data);           
                if(parsed['result']==0){
                    $('#nominal_tbl').show();
                    $('#nominal_tbl').html(parsed['table']);
                    $('#button_view_nominal').hide();
                    $('#CreateNominal').show();            
                }else{
                    $('#view_nominal_tbl').show();
                    $('#view_nominal_tbl').html(parsed['table']);
                    $('#CreateNominal').show(); 
                    $('#button_view_nominal').hide();
                }    
            }
            else
            {
                alert("No Data Found");
            }
            
        }
    });
});

function getThisval(id,value)
{    
    var elective_id = id.substr(id.lastIndexOf('_') + 1); 
    var stu_id = id.substr(0,id.lastIndexOf('_'));
    
    if(elective_id==1){
        var sub_code = $('#'+stu_id+'_'+elective_id).val();             
        $('#'+stu_id+'_2').click(function() {
            $('#'+stu_id+'_2').find("option[value*="+sub_code+"]").prop("disabled", true);           
    });
    }else{
        var sub_code = $('#'+stu_id+'_'+elective_id).val();               
        $('#'+stu_id+'_1').click(function() {
            $('#'+stu_id+'_1').find("option[value*="+sub_code+"]").prop("disabled", true);
    });
    }
}

/* Nominal Ends Here */
/* Galley Starts Here */
$('#exam_month').on('change',function(){

    var year = $('#hallallocate-year').val();
    var month = $('#exam_month').val();

    $.ajax({
        url: base_url+'?r=ajaxrequest/getexamdate',
        type:'POST',
        data:{year:year,month:month},
        success:function(data){                   
            var parsed = $.parseJSON(data);
            $.each(parsed,function(i,parsed){
                $('#exam_date').append("<option value='"+parsed['exam_date']+"'>"+parsed['exam_date']+"</option>");
     
            });
        }
    });
});

$('#exam_date').on('change',function(){
    var date = $('#exam_date').val();    
    $.ajax({
        url: base_url+'?r=ajaxrequest/getsession',
        type:'POST',
        data:{date:date},
        success:function(data){                    
            var parsed = $.parseJSON(data);
            $.each(parsed,function(i,parsed){
                $('#exam_session').append("<option value='"+parsed['exam_session']+"'>"+parsed['exam_session']+"</option>");
     
            });
        }
    });
});

$('#hall_arrangement').on('change',function(){
    var method = $('#method').val();
    var hall_arrangement = $('#hall_arrangement').val();    
    var date = $('#exam_date').val();
    var session = $('#exam_session').val();
    if(hall_arrangement=="SubjectWise"){
        $('#subjectwise').show();
        $('#subject').html(""); 
        $('#subject').append("<option value=''>-----Select Subject Code-----</option>");
        $.ajax({
            url: base_url+'?r=ajaxrequest/getsubcode',
            type:'POST',
            data:{date:date,hall_arrangement:hall_arrangement,session:session},
            success:function(data){ 
                     
                var parsed = $.parseJSON(data);
                $.each(parsed,function(i,parsed){
                $('#subject').append("<option value='"+parsed['subject_code']+"'>"+parsed['subject_code']+"</option>");
                });           
            }
        });
    }else if(hall_arrangement=="Non-SubjectWise"){        
        $('#subjectwise').hide();
        $.ajax({
            url: base_url+'?r=ajaxrequest/getseatcount',
            type:'POST',
            data:{method:method,date:date,session:session},
            success:function(data){
                alert(data);
                var parsed = $.parseJSON(data);
                //alert(parsed['stu_count']);
                $('#hallallocate-student_count').html("Student Count : "+parsed['stu_count']+"\nNeccessary Halls :"+parsed['available_hall']);
                //$('#hallallocate-student_count').html("Availabe Seats : "+parsed['method_count']);
            }
        });
    }
   
});

$('#subject').on('change',function(){
    var method = $('#method').val();     
    var date = $('#exam_date').val();
    var subject_code = $('#subject').val();
    var session = $('#exam_session').val();    
    $.ajax({
        url: base_url+'?r=ajaxrequest/getsubjectseatcount',
        type:'POST',
        data:{method:method,date:date,subject_code:subject_code,session:session},
        success:function(data){
            var parsed = $.parseJSON(data);
            $('#hallallocate-student_count').html("Student Count : "+parsed['stu_count']+"\nAvailabe Seats :"+parsed['method_count']);
        }
    });    
});

// $('#method').on('change',function(){
//     var method = $('#method').val();
//     alert(method);
// });

/* Galley Ends Here */


/* Exam Starts Here */

$('#stu_programme_selected').on('change',function(){
    //$('#exam_month').html("<option>--- Select Month ---</option>");
    var prgm = $('#stu_programme_selected').val();

    $.ajax({
        url: base_url+'?r=ajaxrequest/getmonth',
        type:'POST',
        data:{batch_map_id:prgm},
            
        success:function(data)
        {
            var exam_month_dropdown = $("#exam_month");
                exam_month_dropdown.html(data);
        }
    });
});

$('#exam_semester').on('change',function(){
    var batch_map_id = $('#stu_programme_selected').val();
    var sem = $('#exam_semester').val();

    $.ajax({
        url: base_url+'?r=ajaxrequest/getsubjectcode',
        type:'POST',
        data:{batch_map_id:batch_map_id,sem:sem},
            
        success:function(data)
        {
            //alert(data);
            var parsed = $.parseJSON(data);
            $.each(parsed,function(i,parsed){
                $("#exam_subject_code").append("<option value="+parsed['coe_subjects_id']+">"+parsed['subject_code']+"</option>");
            });
        }
    });

});

$('#exam_subject_code').on('change',function(){
    var sub_id = $('#exam_subject_code').val();

    $.ajax({
        url: base_url+'?r=ajaxrequest/getsubjectname',
        type:'POST',
        data:{sub_id:sub_id},
            
        success:function(data)
        {
            $("#exam_subject_name").attr("value",data);
        }
    });    

});

/* Exam Ends Here */