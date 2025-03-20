var l = window.location;
var base_url = l.protocol + "//" + l.host  + l.pathname;
var c_type,c_desc,c_type_val='',c_desc_val='',deg_id,grade_display_status=1;
var check_grade_from=[],check_grade_to=[],check_grade_point=[],check_grade_name=[];
var subject_add_clicks = 0;
var total_minimum_pass = 0,ese_min_pass = 0,ese_max_marks = 0,cia_min_pass = 0,cia_max_marks = 0;
var total_dummy_students_count;
var dup_numbers = [];
var count_of_div = 0;
var ab_label_name = $('#get_ab_label_name').text();
$(document).ajaxStart(function() { Pace.restart(); });
$(function () {
  $('[data-toggle="popover"]').popover({placement: function() { return $(window).width() < 768 ? 'bottom' : 'right'; }})
});

function spinner() 
{
    document.getElementById('waiting').style.visibility = 'visible';
}

function spinneroff() 
{
    document.getElementById('waiting').style.visibility = 'hidden';
}

$('#arts_ac_student_list').hide();
$('.arts_additional_submit_btn').hide();
$('#change_style_int').hide();

var $loading = $('#waiting_load').hide();

$(document).ajaxStart(function () {
    $loading.show();
  }).ajaxStop(function () {
    $loading.hide();
});

// Change the selector if needed
var $table = $('table.scroll'),
    $bodyCells = $table.find('tbody tr:first').children(),
    colWidth;

// Adjust the width of thead cells when window resizes
$(window).resize(function() {
    // Get the tbody columns width array
    colWidth = $bodyCells.map(function() {
        return $(this).width();
    }).get();
    
    // Set the width of thead columns
    $table.find('thead tr').children().each(function(i, v) {
        $(v).width(colWidth[i]);
    });    
}).resize(); // Trigger resize handler

// Disable 
window.oncontextmenu = function () {
    //return false;
}
$(document).keydown(function (event) {
    if (event.keyCode == 123) {
        //return false;
    }
    else if ((event.ctrlKey && event.shiftKey && event.keyCode == 73) || (event.ctrlKey && event.shiftKey && event.keyCode == 74)) {
        //return false;
    }
});
$(document).keyup(function(evtobj) {     
                if (!(evtobj.altKey || evtobj.ctrlKey || evtobj.shiftKey)){
    if (evtobj.keyCode == 16) {return false;}
                    if (evtobj.keyCode == 17) {return false;}
                }
});


$(document).ready(function() { 
    $("body").on("contextmenu",function(e){
        //return false;
    });
  // script to show the scroll to top icon
  var scrollTop = $(".scrollTop");
  $(window).scroll(function() {
    var topPos = $(this).scrollTop();
    if (topPos > 100) {
      $(scrollTop).css("opacity", "1");
    } else {
      $(scrollTop).css("opacity", "0");
    }
  }); // scroll END
  
  $(scrollTop).click(function() {
    $('html, body').animate({
      scrollTop: 0
    }, 800);
    return false;

  }); 

  // script Ends Here to show the scroll to top icon
 

    //Pace.start(); 
    
    $('.hide_ab_list_del').hide();
    $('.hide_hall_submit').hide();
    $('.show_hall_result_data').hide();
	$('#pract_show_dummy_numbers').hide();
    $('#hide_batch_section').hide();
    $('#man_sub_credit_btn').hide();
    $('#answer_packets_div').hide();
    $('#answer_packets').hide();
    $('#register_date_print_div').hide();
    $('#register_answer_packets').hide();
    $('#display_or_hiddent').hide();
    $('#get_ab_label_name').hide();
    $('.submit_dummy').hide();
    $('#hide_reval_dum_data').hide();
    $('.hide_ab_list').hide();
    $('#show_details_subs').hide();
    $('#disp_show_details_subs').hide();
    $('.field-mandatorysubjects-updated_at').hide();
    $('#hide_dum_data_send').hide();
    $('#pract_show_dummy_numbers').hide();
    // Student Functions
    $('.detain_status').hide();
    $('#add_guardian_hide_default').hide();
    // Configuration Setting
    $('.show_dates').hide();   
    $(".dropdown_is_status").hide(); 
    $(".call_phot").hide();  
    $(".showBatch").click();
    //Catyegory Settings
    $('.cat_creation').hide();
    $('.cat_type_creation').hide();
    $('.create_btn').hide();
    $('.cat_tbl').hide();
    $('.type_btn').hide();
    $('.new_btn').hide();
    $('.update_txt_box').hide();

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
    $('#hide_dum_data').hide();
    $('#hide_sub_cat_info').hide();
    $('#hide_dum_sub_data').hide();
    // Batch & Regulation 
    $('#grade').hide(); 
    $('#BatchSubmit').hide();
    $('#Batch_reset_page').hide();
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
    $('#add_course').hide();
    $('.degg_id').hide();
    $('.pgm_id').hide();
    $('.sec').hide();
    $('#update_course').hide();

    //$('#main_div').hide();
    $('#button_new_batch').hide();
    $('#button_show_degree').hide();

    //Import Functions load 
    $('#changeColors').hide();
    $('#show_student_text').hide();

    //Nominal
    $('#CreateNominal').hide();

    // Migrate Subjects
    $('.mig_tbl').hide();
    $('.mig_div').hide();
    $('.view_mig').hide();
    $('.view_mig_tbl').hide;
    
    //Galley
    $('#subjectwise').hide();
    $('#hall_stu_tbl').hide();
    $('#qp_tbl').hide();
    $('#qp_tbl_1').hide();
    $('.stu_cnt_text').hide();
    $('.show_hall_vs_stu_print').hide();
    // Student Functions 

    //Internal Mark Setting
    $('.mark_tbl').hide();
    $('#stu_mark_tbl').hide();
    
    //External Mark Setting
    $('.ese_mark_tbl').hide();
    $('.select_model_type').hide();
    $('.mod_type1').hide();
    $('.mod_type2').hide();

    // Absent Entry Functions 
    $(".ab_hide_default").hide();
    
    //Moderation
    $('.mod_done_btn').hide();
    $('#stu_mod_tbl').hide();
    $('.reg_from').hide();
    $('.mrk_frm').hide();
    $('.mrk_to').hide();
    $('.hide_subject').hide();
    $('.hide_dept').hide();

    //Withheld
    $('.tbl_n_submit_withheld').hide();

    //Analysis Reports
    $('.pgm_analysis_print_btn').hide();
    $('.crse_analysis_print_btn').hide();
    $('.mark_percent_print_btn').hide();

    //Revaluation
    $('.revaluationentry').hide();
    $('.revaluationmarkentry').hide();
    $('.tbl_n_submit_revaluation').hide();
    $('.reval_pdf_button').hide();
    //Subject Information
    $('.subject_information_tbl').hide();

    //Hallticket export
    $('.hallticket_export_tbl').hide();
    
    //University report
    $('.university_report_tbl').hide();

    //Additional Credit
    $('#ac_student_list').hide();
    $('.additional_submit_btn').hide();

    //Withdraw
    $('.withdraw').hide();

    //Mark Statement
    $('#imageresource').hide();
    $('#display_results_stu').hide();
    $('#elective_waiver_sub').hide();
    $('#elective_waiver_sub_in').hide();
    $('#electgive_sub_wai').hide();
    //$('.credit_type').hide();
});

/*$('#to_reg').on('change',function(){
    $('.credit_type').show();
    if(($('#exam_semester').val())=="")
    {
        krajeeDialog.alert("Please select all feilds");
    }
});*/

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
  if(value=='student_photos')
  {
      $('#changeColors').css({ "background-color": "#2173BC",'color':"#FFF !important" }); 
      $('#changeColors').hide().fadeOut(2500); 
      $('#show_student_text').show().fadeIn(2500);  
      var href = "";  
      href = $("#download_smple_stu_id").removeAttr("href");
      $("#download_smple_stu_id").attr("href", "index.php?r=import/download-sample&value="+value);
  }
  else
  {
      $('#changeColors').css({ "background-color": "#2173BC",'color':"#FFF !important" });  
      $('#show_student_text').hide().fadeOut(2500);  
      $('#changeColors').show().fadeIn(2500);  
      var href = "";  
      href = $("#download_smple").removeAttr("href");
      $("#download_smple").attr("href", "index.php?r=import/download-sample&value="+value);
  }
  
    
}


/* Import Functions Ends here */

/* configuration Starts Her */
function validateThisForm() 
{
   if($('#org_tagline').val()=='' || $('#org_address').val()=='' || $('#org_web').val()=='' || $('#org_name').val()=='')
   {
         krajeeDialog.alert('Kindly Enter The Name,Address,Title && Website');
         return false;
   }
   return true;

}

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
                      $("#config_value_assign_photo").val('');
                      $(".nominal_status_clear").val('');
                }
                else if(jsonFormat[i].config_name.match("locking.end"))
                {
                      $( "input[name='end_date']" ).val(jsonFormat[i].config_value+"-"+currentYear).prop( "disabled", prop_value );
                      $("input[name='is_status']").val('');
                      $("#config_value_assign").val('');
                      $("#config_value_assign_photo").val('');
                      $(".nominal_status_clear").val('');
                }
                else if(jsonFormat[i].config_name.match("enable.status"))
                {

                    $( "input[name='start_date']" ).val('');
                    $( "input[name='end_date']" ).val('');
                    $("#config_value_assign").val('');
                    $("#config_value_assign_photo").val('');
                    $("#configuration-is_status").val(jsonFormat[i].config_value).trigger('change').prop( "disabled", prop_value );
                }
                else
                {
                    $( "input[name='start_date']" ).val('');
                    $( "input[name='end_date']" ).val('');
                    $("input[name='is_status']").val('');
                    $("#config_value_assign_photo").val('');
                    $("#config_value_assign").val(jsonFormat[i].config_value).prop( "disabled", prop_value );
                    
                }
            }
          
         }
    });

});
function requiredVal()
{
    if($("#config_value_assign_photo").val()=="" || $("#config_value_assign_photo").val()=="undefined")
    {
        krajeeDialog.alert("Please Enter the required values");
        $("form").submit(function(e){
            e.preventDefault();
        });
        return false;
    }
    else
    {
        return true;
    }
}
$("#config_value_assign_photo").on('keypress',function(e){
    var keyArray = [46, 8, 9, 27, 13, 110, 190];
    
    if ( // Allow: Ctrl+A,Ctrl+C,Ctrl+V, Command+A
          ((e.keyCode == 65 || e.keyCode == 86 || e.keyCode == 67) || e.ctrlKey ) ||
          // Allow: home, end, left, right, down, up
          (e.keyCode >= 35 && e.keyCode <= 40)) {
          // let it happen, don't do anything
          return true;
        } 
        else if($.inArray(e.keyCode,keyArray)!==-1)
        {
            return true;
        }
        else
        {   
            $("#errmsg").css("color","1px solid #F00");
            $("#errmsg").html("CTRL+V Only Allow").show().fadeOut(2500);
            e.preventDefault();
            return false;
        }

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
   
    if(variable.indexOf("Locking")!==-1)
    {    
        $(".hide_value").fadeOut(3000);
        $(".hide_value").removeAttr('value').hide();
        $(".dropdown_is_status").fadeOut(3000).removeAttr('value').hide(); 
        $(".show_dates").fadeIn(2000).show(); 
        $(".nominal_status_clear").val('');
    }
    else if(variable.indexOf("Status")!==-1)
    {
    	 $(".show_dates").datepicker('setDate', null);       
    	 $(".show_dates").fadeOut(3000).removeAttr('value').hide(); 
    	 $(".hide_value").fadeOut(3000).removeAttr('value').hide();     
    	 $(".dropdown_is_status").fadeIn(2000).show();
    }
    else if(variable.indexOf("Enable")!==-1)
    {    
        $(".hide_value").fadeOut(3000);
        $(".hide_value").removeAttr('value').hide();
        $(".dropdown_is_status").fadeIn(3000).show(); 
        $(".show_dates").fadeOut(2000).hide(); 
        $(".show_dates").fadeOut(3000).removeAttr('value').hide();
        
    }
    else
    {
      	 $(".show_dates").fadeOut(3000).removeAttr('value').hide(); 
      	 $(".dropdown_is_status").fadeOut(3000).removeAttr('value').hide(); 
      	 $(".hide_value").fadeIn(2000).show();   
         $(".nominal_status_clear").val('');
    }
}


/* Configuration Ends Her */

/* Dummy Numbers */
function get_reval_info()
{
    $.ajax({
    url: base_url+'?r=ajaxrequest/getrevaldetails',
    type:'POST',
    data:{year:$('#year').val(),month:$('#exam_month').val()},
        success:function(data)
        {
            var jsonFormat = JSON.parse(data);
            if(jsonFormat==0)
            {
                krajeeDialog.alert('No data Found');
                $('#hide_reval_dum_data').hide().html('');
            }
            else
            {
                $('#hide_reval_dum_data').show().html(jsonFormat);
            }
            
        }

    });
}
function get_dummy_num_info()
{
    var year=$('#hallallocate-year').val();
    var month=$('#exam_month').val();
    var exam_date=$('#exam_date').val();
    $.ajax({
    url: base_url+'?r=ajaxrequest/getdumnuminfo',
    type:'POST',
    data:{year:year,month:month,exam_date:exam_date},
        success:function(data)
        {
            var jsonFormat = JSON.parse(data);
            if(jsonFormat==0)
            {
                krajeeDialog.alert('No data Found');
                $('#hide_dum_repo_data').hide().html('');
            }
            else
            {
                $('#hide_dum_repo_data').show().html(jsonFormat);
            }
            
        }

    });
}
function get_dummy_num_reg_info()
{
    var year=$('#year').val();
    var month=$('#dum_exam_month').val();
    $.ajax({
    url: base_url+'?r=ajaxrequest/getdumnumyregnumbers',
    type:'POST',
    data:{year:year,month:month,sub_id:$('#dummy_exam_subject_code').val()},
        success:function(data)
        {
            var jsonFormat = JSON.parse(data);
            if(jsonFormat==0)
            {
                krajeeDialog.alert('No data Found');
                $('#hide_dum_repo_data').hide().html('');
            }
            else
            {
                $('#hide_dum_repo_data').show().html(jsonFormat);
            }
            
        }

    });
}
function get_sub_status(subject_map_id,exam_year,exam_month)
{
    $('#hide_dum_sub_data').hide();                    
    $('#show_dummy_sub_info').html('');
    if(subject_map_id!='')
    {
        $.ajax({
        url: base_url+'?r=ajaxrequest/getdummysubjectinfo',
        type:'POST',
        data:{subject_map_id:subject_map_id,exam_year:exam_year,exam_month:exam_month},
            success:function(data)
            {
                var jsonFormat = JSON.parse(data);
                var body='';           
                
                var tr='<tr>';
                var td='<td>1</td><td>'+jsonFormat['subject_code']+' </td><td>'+jsonFormat['subject_name']+' </td><td>'+jsonFormat['galley_arranged']+'</td><td>'+jsonFormat['student_absent']+'</td><td>'+jsonFormat["dummy_arranged"]+'</td><td>'+((jsonFormat['galley_arranged']-jsonFormat['student_absent'])-jsonFormat["dummy_arranged"])+'</td>';
                var tr_dum_close ='</tr>'; 
                body = tr+td+tr_dum_close;
                $('#hide_dum_sub_data').show();                    
                $('#show_dummy_sub_info').html(body);
                $.ajax({
                url: base_url+'?r=ajaxrequest/getstoreddata',
                type:'POST',
                data:{subject_map_id:subject_map_id,exam_year:exam_year,exam_month:exam_month},
                    success:function(data)
                    {
                        var jsonFormat = JSON.parse(data);
                        if(jsonFormat==0)
                        {
                            //krajeeDialog.alert('No Numbers Found');
                            $('.submit_dummy').hide();
                            $('#hide_dum_data').hide();                    
                            $('#show_dummy_numbers').html('');
                            $('#start_number').val('').attr('readonly',false);
                            $('#end_number').val('').attr('readonly',false);;
                            $('#change_text_button').text('Save');

                        }
                        else
                        {
                            $('#start_number').val(jsonFormat['dummy_from']).attr('readonly',true);
                            $('#end_number').val(jsonFormat['dummy_to']).attr('readonly',true);
                            $('#change_text_button').text('Generate');
                        }
                    }

                });

                
            }

        });

        
    }
    else
    {
        $('#hide_dum_sub_data').hide();    
    }
    
}
// Below function is for dummy mark entry 

function getExaminerName(subject_map_id,exam_year,exam_month)
{
    $('#hide_dum_data_send').hide();
    $('#pract_show_dummy_numbers').hide();
    if(subject_map_id!='')
    {
        $.ajax({
        url: base_url+'?r=ajaxrequest/getexaminername',
        type:'POST',
        data:{subject_map_id:subject_map_id,exam_year:exam_year,exam_month:exam_month},
            success:function(data)
            {
                var jsonFormat = JSON.parse(data);
                if(jsonFormat==0 || jsonFormat['chief_examiner_name']=='')
                {
                    $('#chief_examiner_name').val('').attr('readonly',false);
                }
                else
                {
                    $('#chief_examiner_name').val(jsonFormat['chief_examiner_name']).attr('readonly',true);
                }
                
            }

        });
    }
    else
    {
        $('#hide_dum_sub_data').hide();  

    }
}



function get_numbers_info(subject_map_id,exam_year,exam_month)
{
    if(subject_map_id!='')
    {
        $.ajax({
        url: base_url+'?r=ajaxrequest/getdummysubjectinfo',
        type:'POST',
        data:{subject_map_id:subject_map_id,exam_year:exam_year,exam_month:exam_month},
            success:function(data)
            {
                var jsonFormat = JSON.parse(data);
                var body='';           
                
                var tr='<tr>';
                var td='<td>1</td><td>'+jsonFormat['subject_code']+' </td><td>'+jsonFormat['subject_name']+' </td><td>'+jsonFormat['min']+'</td><td>'+jsonFormat['max']+'</td><td>'+jsonFormat['pass']+'</td>';
                var tr_dum_close ='</tr>'; 
                body = tr+td+tr_dum_close;
                
                $('#hide_dum_sub_data').show();                    
                $('#show_dummy_entry').html(body);
            }

        });
    }
    else
    {
        $('#hide_dum_sub_data').hide();  

    }
    
}
function getLastNumber(start_number)
{
    var value_assign = parseInt(start_number+29);
    $('#end_number').val(value_assign);
}
function getMinmaxNumber(subject_map_id)
{
    if(subject_map_id!='')
    {
        $.ajax({
        url: base_url+'?r=ajaxrequest/getnumbersinfo',
        type:'POST',
        data:{subject_map_id:subject_map_id},
            success:function(data)
            {
                var jsonFormat = JSON.parse(data);
                var body='';           
                
                var tr='<tr>';
                var td='<td>1</td><td>'+jsonFormat['subject_code']+' </td><td>'+jsonFormat['subject_name']+' </td><td>'+jsonFormat['min']+'</td><td>'+jsonFormat['max']+'</td><td>'+jsonFormat['pass']+'</td>';
                var tr_dum_close ='</tr>'; 
                body = tr+td+tr_dum_close;
                
                $('#hide_dum_sub_data').show();                    
                $('#show_dummy_entry').html(body);
            }

        });
    }
    else
    {
        $('#hide_dum_sub_data').hide();  

    }
}
function checktheLastvalue(id,val)
{
    var last_dummy_number = $('#last_dummy_number').val();
    if(last_dummy_number!='')
    {
        if(parseInt(val) <= parseInt(last_dummy_number))
        {
            $('#'+id).val('');
            krajeeDialog.alert('Kindly use the Higher number than the last used number');
            return false;
        }
    }
    
}
function getAllnumbers(start_number,exam_year,exam_month) 
{
    var subject_map_id = $('#dummy_exam_subject_code').val();
    if(subject_map_id!='')
    {
        $.ajax({
        url: base_url+'?r=ajaxrequest/getdummysubjectinfo',
        type:'POST',
        data:{subject_map_id:subject_map_id,exam_year:exam_year,exam_month:exam_month},
            success:function(data)
            {
                var jsonFormat = JSON.parse(data);
                var totalCount = parseInt(jsonFormat['galley_arranged']-jsonFormat['student_absent']);   
                if(parseInt(jsonFormat['dummy_arranged'])!=0)
                {
                    totalCount = parseInt(totalCount)-parseInt(jsonFormat['dummy_arranged']);
                }
                if(totalCount==0)
                {
                    var generate= parseInt(parseInt(start_number)+totalCount);
                }
                else
                {
                    var generate= parseInt(parseInt(start_number)+parseInt(totalCount))-1; 
                    
                }
                
                $('#end_number').val(generate);             
            }

        });
    }
}
function getAllnumbersLimit(start_number,exam_year,exam_month) 
{
    var subject_map_id = $('#dummy_exam_subject_code').val();
    var limit = $('#limit').val();
    var end_number = parseInt(start_number)+parseInt(limit);
    if(subject_map_id!='')
    {
        $.ajax({
        url: base_url+'?r=ajaxrequest/getdummynumbersarranged',
        type:'POST',
        data:{subject_map_id:subject_map_id,limit:limit,start_number:start_number,exam_year:exam_year,exam_month:exam_month},
            success:function(data)
            {
                var jsonFormat = JSON.parse(data);
                if(jsonFormat==0)
                {
                    krajeeDialog.alert('No Data Found');
                    $('#hide_dum_data').hide();
                    $('#end_number').val(''); 
                    $('#limit').val(30).attr('readonly',false);
                }
                else
                {
                    var limit_diff = parseInt(jsonFormat)-parseInt(start_number);
                    var diff = limit_diff+1 // Ignore the starting number

                    if(diff<30)
                    {
                        //$('#limit').val(diff).attr('readonly',true);
                    }
                    else
                    {
                        $('#limit').val(30).attr('readonly',false);
                    }
                    
                    $('#end_number').val(jsonFormat);  
                }
                        
            }

        });
    }
}
function compareNumbers(end_number_val)
{
    var start_num = $('#start_number').val();
    if(start_num == end_number_val)
    {
        $('#end_number').val('');
        krajeeDialog.alert('Both numbers should not be same');
        return false;
    }
    else if(parseInt(start_num) > parseInt(end_number_val))
    {
        $('#end_number').val('');
        krajeeDialog.alert('Start Number Should Be Greater than the Ending Number');
        return false;
    }
    else
    {
        return true;
    }
} 
function verify_marks()
{
    var sub_map_id = $('#dummy_exam_subject_code').val();
    var end_number = $('#end_number').val();
    var exam_year = $('#exam_year').val();
    var exam_month = $('#exam_month').val();
    var start_number = $('#start_number').val();
    var exam_type = $('#examtimetable-exam_type').val();
    var exam_term = $('#examtimetable-exam_term').val();
    var examiner_name = $('#examiner_name').val();
    if(sub_map_id=='' || end_number=='' || start_number=='')
    {
        krajeeDialog.alert('Must select All Required Fields');
        return false;
    }  
    if(examiner_name=='')
    {
        krajeeDialog.alert('Kindly Enter the Examiner Name');
        return false;
    }   
    else
    {
        $.ajax({
        url: base_url+'?r=ajaxrequest/verifymarks',
        type:'POST',
        data:{sub_map_id:sub_map_id,start_number:start_number,end_number:end_number,exam_type:exam_type,exam_term:exam_term,examiner_name:examiner_name,exam_year:exam_year,exam_month:exam_month},
            success:function(data)
            {
                var jsonFormat = JSON.parse(data);                
                if(jsonFormat==0)
                {
                    $('.submit_dummy').hide();
                    $('#hide_dum_data').hide();                    
                    krajeeDialog.alert('No Data Found');
                }
                else{
                    
                    $('.submit_dummy').show();
                    $('#hide_dum_data').show();                    
                    $('#show_dummy_numbers').html(jsonFormat);
                }                
                
            }
        });
    }
}
$('#limit').on('change',function(){
    $('#start_number').val('');
    $('#end_number').val('');
    $('#hide_dum_data').hide();
    krajeeDialog.alert('Changing the Limit Will Resets the Values <br /> Kindly Enter the Start Number Again');
        return false;
});
function writeText(id,number)
{
    
    var string_text = '';
    var numberArray = {'-1':'ABSENT','-':'ABSENT','0':'ZERO','1':'ONE','2':'TWO','3':'THREE','4':'FOUR','5':'FIVE','6':'SIX','7':'SEVEN','8':'EIGHT','9':'NINE'};
    if(number<0)
    {
        string_text +=numberArray['-1']+" "; 
    }
    else
    {
        var digits = number.split("");    
        for (var i = 0; i < digits.length; i++) 
        {
            if(digits[i]=='-' && number=='-1')
            {
                string_text = 'ABSENT';
            }
            else if(digits[i]=='1' && number=='-1')
            {
                string_text = 'ABSENT';
            }
            else
            {
                if(digits[i]<0)
                {
                    string_text +=numberArray['-1']+" "; 
                }
                else
                {
                    string_text +=numberArray[digits[i]]+" "; 
                }

                   
            }        
        }
    }    
    if(number<50)
    {
        $('#'+id+'_1').addClass('print_red_color');  
        $('#'+id+'_1').val(string_text).css({"border": "1px solid #BE3F48", "color": "#BE3F48"});  
    }
    else
    {
        $('#'+id+'_1').addClass('print_green_color'); 
        $('#'+id+'_1').val(string_text).css({"border": "1px solid #00a65a", "color": "#00a65a"});
    }   
    
}
function get_students_info(exam_year,exam_month)
{
    var sub_map_id = $('#dummy_exam_subject_code').val();
    var limit = parseInt($('#limit').val());
    var end_number = $('#end_number').val();
    var examiner_name = $('#examiner_name').val();
    var chief_examiner_name = $('#chief_examiner_name').val();
    var start_number = $('#start_number').val();
    
    if(sub_map_id=='' || limit=='' || examiner_name=='' || chief_examiner_name=='')
    {
        krajeeDialog.alert('Must select All Required Fields');
        return false;
    }
    if(limit > 33)
    {
        krajeeDialog.alert('Limit Must be 33 or lower');
        return false;
    }
    else
    {
        $.ajax({
        url: base_url+'?r=ajaxrequest/getdummystudents',
        type:'POST',
        data:{sub_map_id:sub_map_id,limit:limit,start_number:start_number,end_number:end_number,exam_year:exam_year,exam_month:exam_month},
            success:function(data)
            {
                var jsonFormat = JSON.parse(data);

                if(jsonFormat==0)
                {
                    $('.submit_dummy').hide();
                    $('#hide_dum_data').hide();                    
                    krajeeDialog.alert('No Data Found');
                }
                else{
                    var body='';                    
                    var parsed = $.parseJSON(data);
                    var k=0;
                    $.each(parsed,function(i,parsed){
                        var tr='<tr>';
                        var td='<td>'+(k+1)+'</td><td> <input type ="hidden" value="'+parsed['subject_map_id']+'" name="sub_map_id_dumm[] " /><input type ="hidden" value="'+parsed['student_map_id']+'" name="dummy_numbers[] " />'+parsed['dummy_number']+' </td><td><input type="text" id=dum_num_marks'+k+' autocomplete="off"  required=required onkeypress="numbersOnly(event);allowEntr(event,this.id);" onchange="check_max_number(this.id,this.value); writeText(this.id,this.value);" name="ese_marks[]" /><td><input type="text" style="border: none;" readonly id=dum_num_marks'+k+'_1 required /></td>';
                        var tr_dum_close ='</tr>'; 
                        body += tr+td+tr_dum_close; 
                        k++;
                    });
                    
                    total_dummy_students_count = k;
                    $('.submit_dummy').show();
                    $('#hide_dum_data').show();                    
                    $('#show_dummy_numbers').html(body);
                }                
                
            }
        });
    }
}
function generate_numbers()
{
    var sub_map_id = $('#dummy_exam_subject_code').val();
    var start_number = parseInt($('#start_number').val());
    var end_number = parseInt($('#end_number').val());
    var exam_year = $("#exam_year").val();
    var semester_val = $("#semester_val").val();
    var batch_mapping_id = $("#stu_programme_selected").val();
    var exam_month = $("#exam_month").val();
    var button_name = $('#change_text_button').text();

    if(sub_map_id=="" && !isNaN(start_number) &&  !isNaN(end_number) )
    {
        krajeeDialog.alert('Must select All Required Fields');
        return false;
    }
    else if(end_number < start_number)
    {
        krajeeDialog.alert('Kindly check the input');
        return false;
    }
    else
    {
        if(button_name=='Generate')
        {
            $.ajax({
            url: base_url+'?r=ajaxrequest/generatedummynumbers',
            type:'POST',
            data:{sub_map_id:sub_map_id,exam_year:exam_year,exam_month:exam_month,semester_val:semester_val,batch_mapping_id:batch_mapping_id},
                success:function(data)
                {
                    var jsonFormat = JSON.parse(data);

                    if(jsonFormat==0)
                    {
                        $('.submit_dummy').hide();
                        $('#hide_dum_data').hide();                    
                        krajeeDialog.alert('No Data Found');
                    }
                    else
                    {
                        var body='';
                        var diff_numbers = end_number-start_number;
                        var drop_down_dummy='<option value="" >--Select--</option>';
                        for (var repeat = 0; repeat <= diff_numbers; repeat++) 
                        {
                            drop_down_dummy += "<option value='"+(parseInt(start_number)+parseInt(repeat))+"' >"+(parseInt(start_number)+parseInt(repeat))+"</option>";
                        }
                        for(var i = 0; i <= diff_numbers; i++)
                        {
                            if(jsonFormat[i])
                            {
                                var tr='<tr>';
                                var td='<td>'+(i+1)+'</td><td> <input type ="hidden" value="'+jsonFormat[i].coe_student_mapping_id+'" name="register_number[] " /><input type="hidden" id="register_number_duplicate_'+i+'" name="register_number_duplicate_'+i+'" value='+jsonFormat[i].register_number+' />'+jsonFormat[i].register_number+' </td><td>'+jsonFormat[i].name+' </td><td><input type="text" id=reg_num_'+i+' required onkeypress="numbersOnly(event); allowEntr(event,this.id);" autocomplete="off" onchange="check_dummy_number(this.id,this.value,'+start_number+','+end_number+')" name="dummy_numbers[]" /></td>';
                                //var td='<td>'+(i+1)+'</td><td> <input type ="hidden" value="'+jsonFormat[i].coe_student_mapping_id+'" name="register_number[] " />'+jsonFormat[i].register_number+' </td><td>'+jsonFormat[i].name+' </td><td><select id=reg_num_'+i+' onchange="updateNumber(this.id,this.value,'+diff_numbers+')" required name="dummy_numbers[]" >'+drop_down_dummy+'</select></td>';
                                var tr_dum_close ='</tr>'; 
                                body += tr+td+tr_dum_close;                       
                            }                           
                        } 
                        total_dummy_students_count = i;
                        $('.submit_dummy').show();
                        $('#hide_dum_data').show();                    
                        $('#show_dummy_numbers').html(body);
                    }                
                    
                }
            });
        }      
        else // If Button Name is Store
        {
                $('.submit_dummy').hide();
                $('#hide_dum_data').hide();                    
                $('#show_dummy_numbers').html('');
                $.ajax({
                url: base_url+'?r=ajaxrequest/storedummynumbers',
                type:'POST',
                data:{sub_map_id:sub_map_id,start_number:start_number,end_number:end_number,exam_year:exam_year,exam_month:exam_month},
                success:function(data)
                {
                    var jsonFormat = JSON.parse(data);
                    if(jsonFormat==1)
                    {
                        krajeeDialog.alert('Data Saved Successfully!');
                    }
                    else if(jsonFormat==0)
                    {
                        krajeeDialog.alert('Something Wrong!');
                    }
                    else if(jsonFormat=='Duplicate')
                    {
                       krajeeDialog.alert('Sequesnce is Missing / Duplicate Found');
                       return false;
                    }
                    else
                    {
                        krajeeDialog.alert('Unable to Save Data');
                        return false;
                    } 
                
                }
            });
        }
        
    }
    
}
function allowEntr(event,id)
{
    if(event.keyCode==13)
    {
        var id_num = id.substr(id.length-1);
        var number = id.match(/\d+/g).map(Number);
        var next_focus = parseInt(number)+parseInt(1);

        var number_length = number.toString().length;
        var number_splide = id.slice(0,id.length-number_length);
        var id_name = id.slice(0,id.length-1);       
        $('#'+number_splide+next_focus).focus();
        event.preventDefault();
    }
}
// Allow Alphabets only
function onlyAlphabets(e, t) {
try {
    if (window.event) {
        var charCode = window.event.keyCode;
    }
    else if (e) {
        var charCode = e.which;
    }
    else { return true; }
    if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123))
        return true;
    else
        return false;

}

catch (err) {

    alert(err.Description);

}

}
// Allow Alphabets only
function check_max_number(id,dum_value) 
{
    if(dum_value>100)
    {
        $('#'+id).val('').focus();
        krajeeDialog.alert('Wrong Entry');
        return false;
    }
}
function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(typeof haystack[i] == 'object') {
            if(arrayCompare(haystack[i], needle)) return true;
        } else {
            if(haystack[i] == needle) return true;
        }
    }
    return false;
}
function arrayCompare(a1, a2) {
    if (a1.length != a2.length) return false;
    var length = a2.length;
    for (var i = 0; i < length; i++) {
        if (a1[i] !== a2[i]) return false;
    }
    return true;
}
function check_dummy_number(id,dum_value,start_number,end_number) {
    
    var duppnumber = id.match(/\d+/g).map(Number);
    var reg_num_dup;
    
    if(dum_value<start_number || dum_value>end_number)
    {
        $('#'+id).val('').focus();
        krajeeDialog.alert('Wrong Number');
    }
    else {
        var repeat_count =0;
        for (var i = 0; i < total_dummy_students_count; i++) 
        {
            if(dum_value==$('#reg_num_'+i).val())
            {   
                repeat_count++;                
            }
        }
        if(repeat_count>1)
        {
            $('#'+id).val('').focus();
            krajeeDialog.alert('Duplicate Number Already used');
        }
        
    }
}
function checkEn(evt)
{
    if (!evt)
    evt = event;


    if (evt.ctrlKey && (evt.keyCode==86 || evt.keyCode==118))
    {
        return true;
    }
    else
    {
        return false;
    }
}
function numbersOnly(event) 
{
    var regex = new RegExp("^[0-9\-]+$");
    var key = String.fromCharCode(event.charCode ? event.which : event.charCode);
    if(!regex.test(key) && event.which != 8 && event.which != 0 && event.which != 13) 
    {
        event.preventDefault();
        return false;
    }
    
}
/* Dummy Numbers */


/*
Student Functions 
*/

$("#add_sub_row").on('click',function()
{
    var html = $("#create_div_element").append($("#add_sub_row_div").clone()).html();
});

$("#add_guardian").on('click',function(){
    $("#add_guardian_hide_default").show();
});
$("#reset_guardian").on('click',function()
{
    $('#guardian-guardian_name_1').val('')
    $('#guardian-guardian_relation_1').val('')
    $('#guardian-guardian_mobile_no_1').val('');
    $("#add_guardian_hide_default").hide();
});
function addFields(admission_status_id)
{
    $.ajax({
        url: base_url+'?r=ajaxrequest/showrequirefields',
        type:'POST',
        data:{admission_status_id:$("#"+admission_status_id).val()},
        success:function(data)
        {
            if(data==$("#"+admission_status_id).val())
            {
                $('.detain_status').show();
                krajeeDialog.alert('You have selected <b>Rejoin</b> Kindly enter the <b>Previous Registration</b> Number');
                $('#studentmapping-previous_reg_number').css("border", "1px solid #f00");
                $('#studentmapping-previous_reg_number').attr('required','required');
                
            }
            else
            {
                $('.detain_status').hide();
                $('#studentmapping-previous_reg_number').css("border", "1px solid ##00a65a");
                $('#studentmapping-previous_reg_number').val('');
            }
        }
    });
}
$("#sendEmail").on('click',function(){

    $.ajax({
        url: base_url+'?r=ajaxrequest/sendemail',
        type:'POST',
        data:{email_to:$("#email_to").val(),subj_info:$("#subject_info").val(),text_info:$("#message_details").val()},
        success:function(data)
        {
            jsonFormat = JSON.parse(data);
            krajeeDialog.alert('Under Development');
        }
    });
});
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();
    return [month, day, year];
}
function convertTime(time, format) {
    var t = new Date(time);
    var tf = function (i) { return (i < 10 ? '0' : '') + i };
    return format.replace(/yyyy|MM|dd|HH|mm|ss/g, function (a) {
        switch (a) {
            case 'yyyy':
                return tf(t.getFullYear());
                break;
            case 'MM':
                return tf(t.getMonth() + 1);
                break;
            case 'mm':
                return tf(t.getMinutes());
                break;
            case 'dd':
                return tf(t.getDate());
                break;
            case 'HH':
                return tf(t.getHours());
                break;
            case 'ss':
                return tf(t.getSeconds());
                break;
        }
    });
}

function parseDate(str) {
    var mdy = str.split('/');
    return new Date(mdy[2], mdy[0]-1, mdy[1]);
}

function daydiff(first, second) {
    return Math.round((second-first)/(1000*60*60*24));
}

function lockdaydiff(first, second) {
    var timeDiff = Math.abs(second.getTime() - first.getTime());
    return Math.ceil(timeDiff / (1000 * 3600 * 24));
}


function CheckThisDate(examDate)
{

  var today = new Date();
  var converted_date = formatDate(new Date());
  
  var display_date = convertTime(today.setDate(today.getDate()),'MM/dd/yyyy');
  var selected_date = $("#"+examDate).val();  
  var exam_date = selected_date.split("-"); //05-25-2018

  var converted_exam_date=exam_date[1]+"/"+exam_date[0]+"/"+exam_date[2]; 
  var lock_date_1 = convertTime(today.setDate(today.getDate()+31),'MM/dd/yyyy');

  var current_time = parseDate(display_date).getTime();
  var exam_time = parseDate(converted_exam_date).getTime();
  var last_exam_time = parseDate(lock_date_1).getTime();

  if(exam_time>=current_time && exam_time<=last_exam_time)
  {
      $("#"+examDate).focus().css("border","1px solid #00A65A");
      return true;
  }
  else
  {
       krajeeDialog.alert("Date Range Should be Between Month / Date / Year Of "+display_date+" To "+lock_date_1);
      $("#"+examDate).val("");
      $("#"+examDate).focus().css("border","1px solid #f00");
      return false;
  }
}

function getInternalModeSubs(sem_id)
{
    $.ajax({
            url: base_url+"?r=ajaxrequest/getintsubjects",
            type:'POST',
            data:{bat_map_val:$('#stu_programme_selected').val(),sem_id:sem_id,exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='MarkEntry[term]']:checked").val(),mark_type:$("input[name='MarkEntry[mark_type]']:checked").val()},
            success:function(data)
            {
                if(data==0)
                {
                    $('#mark_subject_code').html('');
                    $('#show_details_subs').hide();
                    $('#disp_show_details_subs').hide();
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;

                }
                else
                {                       
                    var parsed = $.parseJSON(data);   
                    $('#mark_subject_code').html('');
                    $('#mark_subject_code').append('<option value="" >---SELECT----</option>');                 
                    $.each(parsed,function(i,parsed)
                    {
                        $('#mark_subject_code').append("<option value='"+parsed['coe_subjects_mapping_id']+"'>"+parsed['subject_code']+"</option>");
                    });
                }
            }

        });
}
function getExternalModeSubs(sem_id)
{
    $.ajax({
            url: base_url+"?r=ajaxrequest/getexternalarsubjects",
            type:'POST',
            data:{bat_map_val:$('#stu_programme_selected').val(),sem_id:sem_id,exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='MarkEntryMaster[term]']:checked").val(),mark_type:$("input[name='MarkEntryMaster[mark_type]']:checked").val()},
            success:function(data)
            {

                if(data==0)
                {
                    $('#mark_subject_code').html('');
                    $('#show_details_subs').hide();
                    $('#disp_show_details_subs').hide();
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;

                }
                else
                {                       
                    var parsed = $.parseJSON(data); 
                    $('#mark_subject_code').html('');
                    $('#mark_subject_code').append("<option value='' >----SELECT----</option>");                   
                    $.each(parsed,function(i,parsed)
                    {
                        $('#mark_subject_code').append("<option value='"+parsed['coe_subjects_mapping_id']+"'>"+parsed['subject_code']+"</option>");
                    });
                }
            }

        });
} 

function getValueAdd(sem_id)
{
    $.ajax({
            url: base_url+"?r=ajaxrequest/getexternalvalsub",
            type:'POST',
            data:{bat_map_val:$('#stu_programme_selected').val(),sem_id:sem_id,exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='MarkEntryMaster[term]']:checked").val(),mark_type:$("input[name='MarkEntryMaster[mark_type]']:checked").val()},
            success:function(data)
            {

                if(data==0)
                {
                    $('#mark_subject_code').html('');
                    $('#show_details_subs').hide();
                    $('#disp_show_details_subs').hide();
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;

                }
                else
                {                       
                    var parsed = $.parseJSON(data); 
                    $('#mark_subject_code').html('');
                    $('#mark_subject_code').append("<option value='' >----SELECT----</option>");                   
                    $.each(parsed,function(i,parsed)
                    {
                        $('#mark_subject_code').append("<option value='"+parsed['coe_sub_mapping_id']+"'>"+parsed['subject_code']+"</option>");
                    });
                }
            }

        });
} 
function getIntStuList()
{

    if($('#mark_subject_code').val()=='')
    {
        krajeeDialog.alert('Select the Required Fields');     
        return false;
    }
    $.ajax({
            url: base_url+"?r=ajaxrequest/getintsubjectdetails",
            type:'POST',
            data:{sub_type:$('#subject_type').val(),sub_map_id:$('#mark_subject_code').val(), bat_map_val:$('#stu_programme_selected').val(),sem_id:$('#markentry-semester').val(),exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='MarkEntry[term]']:checked").val(),mark_type:$("input[name='MarkEntry[mark_type]']:checked").val()},
            success:function(data)
            {                
                $('#show_details_subs').html('');
                $('#show_details_subs').hide();
                $('#disp_show_details_subs').hide();
                if(data==0)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;
                }
                else if(data==1)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('Mark Entry Completed');     
                    return false;
                }
                else
                {                       
                    var body='';  
                    var full_body='';                    
                    var parsed = $.parseJSON(data);
                    var k=0;
                    var table_open ='<table class="table table-responsive table-stripped table-bordered table-hover"><tr class="info"><th>S.No.</th><th>Register Number</th><th>Name</th><th>Status</th></tr>';
                    if($('#subject_type').val()==1)
                    {
                        $.each(parsed,function(i,parsed)
                        {
                            var tr='<tr>';
                            var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td> <input class="flat-red" checked type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="YES" /> Completed <input class="flat-red"  type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="NO" />  Not Completed <input class="flat-red"  type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="AB" />  Absent </td>';
                            var tr_dum_close ='</tr>'; 
                            body += tr+td+tr_dum_close; 
                            k++;
                        });
                       full_body = table_open+body+"</table>";
                       $('#show_details_subs').show();
                       $('#disp_show_details_subs').show();
                       $('#show_details_subs').html(full_body);
                    }
                    else if($('#subject_type').val()==2)
                    {
                      
                        $.each(parsed,function(i,parsed)
                        {
                            var tr='<tr>';
                            var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td> <input class="flat-red" type="radio" checked  name="status_'+parsed['coe_student_mapping_id']+'" value="A" /> EXEMPLARY <input class="flat-red" type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="B" /> VERY GOOD <input class="flat-red" type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="C" /> GOOD <input class="flat-red"  type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="D" /> FAIR <input class="flat-red"  type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="E" /> SATISFACTORY </td>';
                            var tr_dum_close ='</tr>'; 
                            body += tr+td+tr_dum_close; 
                            k++;
                        });
                       full_body = table_open+body+"</table>";
                       $('#show_details_subs').show();
                       $('#disp_show_details_subs').show();
                       $('#show_details_subs').html(full_body);
                    }
                    else if($('#subject_type').val()==0)
                    {
                        $.each(parsed,function(i,parsed)
                        {
                            var tr='<tr>';
                            var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td> <input class="flat-red" checked type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="YES" /> Pass <input class="flat-red"  type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="NO" />  Fail <input class="flat-red"  type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="AB" />  Absent </td>';
                            var tr_dum_close ='</tr>'; 
                            body += tr+td+tr_dum_close; 
                            k++;
                        });
                       full_body = table_open+body+"</table>";
                       $('#show_details_subs').show();
                       $('#disp_show_details_subs').show();
                       $('#show_details_subs').html(full_body);
                    }
                    else
                    {
                        $('#mark_subject_code').val('');
                        krajeeDialog.alert('Subject Type is Mandatory');     
                        return false;
                    }

                    
                }
            }

        });
}
function getViewIntStuList()
{

    if($('#mark_subject_code').val()=='')
    {
        krajeeDialog.alert('Select the Required Fields');     
        return false;
    }
    $.ajax({
            url: base_url+"?r=ajaxrequest/getviewintsubjectdetails",
            type:'POST',
            data:{sub_map_id:$('#mark_subject_code').val(), bat_map_val:$('#stu_programme_selected').val(),sem_id:$('#markentry-semester').val(),exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='MarkEntry[term]']:checked").val(),mark_type:$("input[name='MarkEntry[mark_type]']:checked").val()},
            success:function(data)
            {
                $('#show_details_subs').hide();
                $('#disp_show_details_subs').hide();
                if(data==0)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;
                }
                else
                {                       
                    var body='';  
                    var full_body='';                    
                    var parsed = $.parseJSON(data);
                    var k=0;
                    var table_open ='<table class="table table-responsive table-stripped table-bordered table-hover"><tr class="info"><th>S.No.</th><th>Register Number</th><th>Name</th><th>Status</th></tr>';
                    $.each(parsed,function(i,parsed)
                    {
                        var tr='<tr>';
                        if(parsed['result']=='Fail')
                        {
                            var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td> <b><input disabled class="flat-red" type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="YES" /> Completed <input class="flat-red" checked disabled  type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="NO" />  Not Completed </b> <input class="flat-red" checked disabled  type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="AB" />  Absent </b></td>';
                        }
                        else if(parsed['result']=='Absent')
                        {
                            var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td> <b><input disabled class="flat-red" type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="YES" /> Completed <input class="flat-red" disabled  type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="NO" />  Not Completed </b> <input class="flat-red" checked  type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="AB" />  Absent </b></td>';
                        }
                        else
                        {
                            var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td> <b><input class="flat-red" checked disabled type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="YES" /> Completed <input class="flat-red" disabled type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="NO" />  Not Completed </b><input class="flat-red" checked disabled  type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="AB" />  Absent </b></td>';
                        }
                        
                        
                        var tr_dum_close ='</tr>'; 
                        body += tr+td+tr_dum_close; 
                        k++;
                    });
                   full_body = table_open+body+"</table>";
                   $('#show_details_subs').show();
                   $('#disp_show_details_subs').show();
                   $('#show_details_subs').html(full_body);
                }
            }

        });
}
function getArtsExternalStuList()
{

    if($('#mark_subject_code').val()=='')
    {
        krajeeDialog.alert('Select the Required Fields');     
        return false;
    }
    $.ajax({
            url: base_url+"?r=ajaxrequest/getexternalartssubjectdetails",
            type:'POST',
            data:{sub_map_id:$('#mark_subject_code').val(), bat_map_val:$('#stu_programme_selected').val(),sem_id:$('#markentry-semester').val(),exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='MarkEntry[term]']:checked").val(),mark_type:$("input[name='MarkEntry[mark_type]']:checked").val()},
            success:function(data)
            {
                if(data==0)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;
                }
                else if(data==1)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('Mark Entry Completed');     
                    return false;
                }
                else
                {                       
                    var body='';  
                    var full_body='';                    
                    var parsed = $.parseJSON(data);
                    var k=0;
                    var table_open ='<table class="table table-responsive table-stripped table-bordered table-hover"><tr class="info"><th>S.No.</th><th>Register Number</th><th>Name</th><th>Status</th></tr>';
                    $.each(parsed,function(i,parsed)
                    {
                        var tr='<tr>';
                        var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td> <input class="flat-red" checked type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="YES" /> Completed <input class="flat-red"  type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="NO" />  Not Completed </td>';
                        var tr_dum_close ='</tr>'; 
                        body += tr+td+tr_dum_close; 
                        k++;
                    });
                   full_body = table_open+body+"</table>";
                   $('#show_details_subs').show();
                   $('#disp_show_details_subs').show();
                   $('#show_details_subs').html(full_body);
                }
            }

        });
} 
function getVerifyMarks(exam_year,exam_month)
{
    if($('#dummy_exam_subject_code').val() && $('#exam_type').val())
    {
        $.ajax({
            url: base_url+"?r=mark-entry-master/verification",
            type:'POST',
            data:{sub_code:$('#dummy_exam_subject_code').val(),mark_type:$('#exam_type').val(),exam_year:exam_year,exam_month:exam_month},
            success:function(data)
            {
                if(data!=0)
                {
                    $('#display_or_hiddent').show();
                    $('#display_or_hiddent').html(data);
                }
                else
                {
                     krajeeDialog.alert('NO DATA FOUND');                    
                    $('#display_or_hiddent').html('');
                    $('#display_or_hiddent').hide();
                    return false;
                }
            }

        });
    }
    else
    {
        krajeeDialog.alert('Select All Fields');
        return false;
    }

}
function getVerifyMarksArts()
{
    if($('#markentry-subject_map_id').val() && $('#exam_type').val())
    {
        $.ajax({
            url: base_url+"?r=mark-entry-master/verificationarts",
            type:'POST',
            data:{batch_mapping_id:$('#stu_programme_selected').val(),year:$('#mark_year').val(),month:$('#exam_month').val(),sub_code:$('#markentry-subject_map_id').val(),mark_type:$('#exam_type').val()},
            success:function(data)
            {
                if(data!=0)
                {
                    $('#display_or_hiddent').show();
                    $('#display_or_hiddent').html(data);
                }
                else
                {
                     krajeeDialog.alert('NO DATA FOUND');                    
                    $('#display_or_hiddent').html('');
                    $('#display_or_hiddent').hide();
                    return false;
                }
            }

        });
    }
    else
    {
        krajeeDialog.alert('Select All Fields');
        return false;
    }

}     
function isDateBulk(dateOfBirth)
{

  var today = new Date();
  var currentYear = today.getFullYear();
  var dob_year = $("#"+dateOfBirth).val().split("/")[0];
  var difference = currentYear-dob_year;
  if(difference>=16)
  {
     $("#"+dateOfBirth).focus().css("border","1px solid #00A65A");
     return true;
  }
  else
  {
    krajeeDialog.alert($("#"+dateOfBirth).val()+" should be 16 years Back from the "+today);
    $("#"+dateOfBirth).val("");
    $("#"+dateOfBirth).focus().css("border","1px solid #f00");
    return false;
  }
  
}


function isDate(dateOfBirth)
{

  var today = new Date();
  var currentYear = today.getFullYear();
  var dob_year = $("#"+dateOfBirth).val().split("/")[2];
  var difference = currentYear-dob_year;
  if(difference>=15)
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
           send_to_dropdown.append('<option value="All" >All (View Only)</option>');
           for (var i = 65; i < print_section; i++) {                
                send_to_dropdown.append('<option value='+String.fromCharCode(i)+'>' + String.fromCharCode(i) + '</option>');
            }
        }
    });
    $.ajax({
        url: base_url+"?r=ajaxrequest/semesternames",
        type:'POST',
        data:{coe_bat_deg_reg_id:$("#stu_programme_selected").val()},
        success: function(data){
           var jsonFormat = JSON.parse(data);
           if(jsonFormat=="PG")
           {
                $("label[for*=semester]").html('Trimester');
           }
           else
           {
                $("label[for*=semester]").html('Semester');
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
                var stu_programme_dropdown_1 = $("#mandatory_stu_selected");
                stu_programme_dropdown_1.html(data);

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
        $('.update_txt_box').hide();
    }
    else
    {
        $('.cat_creation').hide();
        $('.cat_type_creation').hide();
        $('.create_btn').hide();
        $('.new_btn').show();
        $('.update_txt_box').hide();

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
				krajeeDialog.alert('Please Enter the value for '+c_type_label_name+' / '+c_desc_label_name);
				$('#c_type').focus();
				return false;
			    }
		    }
		else
		    {
			krajeeDialog.alert('Please Enter the value for '+c_name_label_name+' / '+c_desc_label_name);
			$('#categories-category_name').focus();
			return false;
		    }
	    }
	else
	    {
		if(c_type=="" && c_list=="")
		    {
			krajeeDialog.alert('Please Enter the value for '+c_type_label_name+' / '+c_desc_label_name);
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
					krajeeDialog.alert('The value for '+c_type_label_name+' / '+c_desc_label_name+' already created');
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
      		krajeeDialog.alert('Please Enter the value for '+c_type_label_name+' / '+c_desc_label_name);
      		return false;
	    }
    });

/*//* Categories FGunction */

/* Batch Related Functions */
$('#add_course').on('click',function(){
    $('.degg_id').show();
});

function deleteFunction(variable)
{
    var c_type_label_name = $('label[for=c_type]').text();
    var c_desc_label_name = $('label[for=c_desc]').text();

    if(confirm("Are you sure you want to delete"))
    {
        $.ajax({
            url: base_url+'?r=ajaxrequest/getcategorydelete',
            type:'POST',
            data:{c_type_id:variable},
            success:function(data)
            {
                if(data==0)
                {
                    alert(c_type_label_name+' & '+ c_desc_label_name +' Deleted successfully') ? "" : location.reload();

                    /*krajeeDialog.alert(c_type_label_name+' & '+ c_desc_label_name +' '+'Deleted successfully');
                    setTimeout(function(){
                        window.location.reload();
                    },2500);  */
                }
                else
                {
                    krajeeDialog.alert(c_type_label_name+' & '+ c_desc_label_name +' '+data);
                }
            }
        });
    }
}
$('#revaluation_entry_btn').click(function()
{
    setTimeout(function(){ location.reload(); }, 4000);
});
function updateFunction(variable)
{
    var c_type_label_name = $('label[for=c_type]').text();
    var c_desc_label_name = $('label[for=c_desc]').text();
    $.ajax({
        url: base_url+'?r=ajaxrequest/getcategoryupdate',
        type:'POST',
        data:{c_type_id:variable},
        success:function(data)
        {
            if(data==0)
            {
                krajeeDialog.alert("You can't update " +c_type_label_name+' & '+ c_desc_label_name +".Because already assigned");
                $('.update_txt_box').hide();
            }
            else
            {
                if(confirm("Are you sure you want to edit"))
                {
                    $('.update_txt_box').show();

                    var parsed = $.parseJSON(data);
                    $.each(parsed,function(i,parsed){
                        $("#update_type").attr("value",parsed['category_type']);
                        $('#update_desc').attr("value",parsed['description']);
                        $('#update_cat_id').attr("value",parsed['coe_category_type_id']);
                    });
                }

                /*krajeeDialog.alert('Are you sure you want to edit');
                $('.update_txt_box').show();

                var parsed = $.parseJSON(data);
                $.each(parsed,function(i,parsed){
                   $("#update_type").attr("value",parsed['category_type']);
                   $('#update_desc').attr("value",parsed['description']);
                   $('#update_cat_id').attr("value",parsed['coe_category_type_id']);
                });*/
            }
        }
    });
}

$('.update_cat_type').on('click',function(){
    var c_type_label_name = $('label[for=c_type]').text();
    var c_desc_label_name = $('label[for=c_desc]').text();

    var cat_id = $('#update_cat_id').val();
    var cat_type = $("#update_type").val();
    var cat_desc = $('#update_desc').val();

    $.ajax({
        url: base_url+'?r=ajaxrequest/getupdatecategory',
        type:'POST',
        data:{cat_id:cat_id,cat_type:cat_type,cat_desc:cat_desc},
        success:function(data)
        {
           if(data==0)
           {
                krajeeDialog.alert("Please enter "+c_type_label_name+' & '+c_desc_label_name+'.');
           }
           else
           {
                krajeeDialog.alert(c_type_label_name+' & '+ c_desc_label_name +' Updated successfully');
                $('#update_cat_id').hide();

                setTimeout(function(){
                    window.location.reload();
                }, 1000);

                //alert(c_type_label_name+' & '+ c_desc_label_name +' Updated successfully') ? "" : location.reload();
           }
           
        }
    });
});

$('#degree_id').on('change',function(){
    var programme_label_name = $('label[for=programme_id]').text();
    $('#programme_id').html("<option>----Select "+programme_label_name+"----</option>");    
        var batch = $('#batch_name').val();
        var degree_id = $('#degree_id').val();
    $.ajax({
        url: base_url+"?r=ajaxrequest/getexistprogramme",
        type:"POST",
        data:{degree_id:degree_id,batch:batch},
        success: function(data){
            if(data==0){
                krajeeDialog.alert("Already "+programme_label_name+" are Assigned");
                $('#degree_id').val(""); 
            }else{
                $('.pgm_id').show();
                var parsed = $.parseJSON(data);
                $.each(parsed,function(i,parsed){
                    $('#programme_id').append("<option value='"+parsed['programme_code']+"'>"+parsed['programme_code']+"</option>");
                });
            }
        }
    });
});

$('#programme_id').on('change',function(){
    $('.sec').show();   
    $('#section').val("").focus().attr("required", "true");
    $('#update_course').show();
});

$('#update_course').on('click',function(){

    var section_label_name = $('label[for=section]').text();
    var batch = $('#batch_name').val();
    var degree = $('#degree_id').val();
    var programme = $('#programme_id').val();
    var section = $('#section').val();
    if(section!=""){
        $.ajax({
            url: base_url+"?r=batch/updated",
            type:"POST",
            data:{batch:batch,degree:degree,programme:programme,section:section},
            success: function(data){            
                if(data=="Updated"){               
                    $('#programme_id').removeAttr('value');
                    $('#section').val("");
                    krajeeDialog.alert("Record Updated Successfully");
                    setTimeout(function(){
                        window.location.reload();
                    },2000); 
                }else if(data==0){
                    krajeeDialog.alert("Record Already Assigned");
                }
            }
        });
    }else{
       krajeeDialog.alert(section_label_name+" should not be empty"); 
    }
});


$('#regulation-grade_point_from').on('click',function(){
    var checked_value = $("input[name='gradee']:checked").val();    
    if(checked_value==10){
        $('#grade100').prop('disabled', true);
    }else{
        $('#grade10').prop('disabled', true);
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

$('#regulation-grade_point_to').on('blur',function(){
    var checked_value = $("input[name='gradee']:checked").val();
    var grade_from = parseFloat($('#regulation-grade_point_from').val());
    var grade_to = parseFloat($('#regulation-grade_point_to').val());
    if(isNaN(grade_from) || grade_from>grade_to){ 
        krajeeDialog.alert("Enter Valid Grade Range");       
        $('#regulation-grade_to').val("").focus();
        
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
    $('#exist_degree_tbl').hide();
    $('#deg_grade_tbl').hide();
    $('#add_course').hide();
    $('.update_course_div').hide();    
    $('#batch_name').focus();    
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
                var parsed = $.parseJSON(data);
                   
                if(data==0){
                    $('#reg').show();
                    $('#button_view_batch').hide();
                    $('#button_new_batch').show();
                    $('#reset').hide(); 
                    $('#deg_grade_tbl').hide();
                    $('#exist_degree_tbl').hide();
                    $('#add_course').hide();                   
                }
                else{                    
                    $('#deg_grade_tbl').show();
                    $('#deg_grade_tbl').html(parsed['grade_table']); 
                    //$('#deg_grade_tbl').html(data);
                    $('#exist_degree_tbl').show();
                    $('#exist_degree_tbl').html(parsed['batch_table']);                   
                    $('#add_course').show();                    
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
    var grade_point = parseInt($('#regulation-grade_point').val()); var grade_point = parseInt($('#regulation-grade_point').val());    
    
    if(!isNaN(grade_point)){

        if($.inArray(grade_point,check_grade_point) !==-1 || $.inArray(grade_point,check_grade_point) !==0 
          && $.inArray(grade_from,check_grade_from)!==-1 || $.inArray(grade_from,check_grade_from)!==0
          && $.inArray(grade_to,check_grade_from)!==-1 || $.inArray(grade_to,check_grade_from)!==0
          && $.inArray(grade_to,check_grade_to)!==-1 || $.inArray(grade_to,check_grade_to)!==0
          && $.inArray(grade_from,check_grade_to)!==-1 || $.inArray(grade_from,check_grade_to)!==0
          && $.inArray(grade_name,check_grade_name)!==-1 || $.inArray(grade_name,check_grade_name)!==-1)
        {
            krajeeDialog.alert("You are trying to add duplicate grades <br /> Please re-check your submission");
            $('#regulation-grade_point_from').val("");
            $('#regulation-grade_point_to').val("");
            $('#regulation-grade_name').val("");
            $('#regulation-grade_point').val("");             
        }
        else
        {
            $.ajax({
            url : base_url+"?r=ajaxrequest/getgrade",
            type: "post",       
            success : function(data){

                check_grade_from.push(grade_from);
                check_grade_to.push(grade_to);
                check_grade_name.push(grade_name);
                check_grade_point.push(grade_point);

                $('#grade_tbl').css({"visibility": "visible"});
                $('#grade_tbl').show();
                $('#appendgrade').append("<tr><td><input type='hidden' name='from[]'  value='"+grade_from+"' />"+grade_from+"</td><td><input type='hidden' name='to[]' value='"+grade_to+"' />"+grade_to+"</td><td><input type='hidden' id='grade_name' name='name[]' value='"+grade_name.toUpperCase()+"' />"+grade_name.toUpperCase()+"</td><td><input type='hidden' id='grade_point' name='point[]' value='"+grade_point+"' />"+grade_point+"</td></tr>");                     
                $('#regulation-grade_point_from').val("");
                $('#regulation-grade_point_to').val("");
                $('#regulation-grade_name').val("");
                $('#regulation-grade_point').val("");
              }
            });
        }                     
                
    }
    else{
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
    $.ajax({
        url : base_url+"?r=ajaxrequest/getcheckedbatch",
        type: "post",
        data : {batch: $('#batch_name').val()},
        success : function(data){
            if(data == 0){
                $('#reg').show();
                $('#button_new_batch').show();

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
                            krajeeDialog.alert("Confirm to follow "+$('#reg_year').val()+" "+ reg_year_label_name);
                            $('#reg_year').prop('readonly', true); 
                            $('#button_show_degree').show();
                        }           
                    }
                }); 
                }else{
                    krajeeDialog.alert("Enter "+reg_year_label_name);
                    $('#reg_year').focus();
                }
            }else{

                $('#deg_grade_tbl').show();
                $('#exist_degree_tbl').show();
                $('#reg').hide();
                $('#button_new_batch').hide();
                $('#button_view_batch').show();
                $('#reset').show();

            }
        }
    }); 
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
    var reg_year = $('#reg_year').val();
    if(degree!=""){ 
    $.ajax({
        url: base_url+"?r=ajaxrequest/getprogramme",
        type: "POST",
        data:{reg_year:reg_year},
        success: function(data){
            
            var parsed = $.parseJSON(data);
            var ch_id = deg_id.substr(0,deg_id.lastIndexOf('_'));
            $('#degree_tbl').show();        
            for(var i=1;i<=parsed['pgm_count'];i++){
                if ($('#'+ch_id+'_'+i).is(":checked")){
                    grade_display_status=2;
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
    var reg_year = $('#reg_year').val();        
    $.ajax({
        url: base_url+"?r=ajaxrequest/getprogramme",
        type: "POST",
        data:{reg_year:reg_year},
        success: function(data){            
            var parsed = $.parseJSON(data);
            
        if(degree!=""){
            $('#degree_tbl').show();
            for(var i=1;i<=parsed['pgm_count'];i++){
                if($('#check_'+i).is(":checked")){
                    var pgm_status = $('#check_'+i).is(":checked");                
                    //var pgm_status = $('#check_'+i).val();
                    var sec = $('#programme_selected'+i).val();
                    if(pgm_status!="" && sec!=""){ 
                        grade_display_status=1;
                        var pgm = $('#check_'+i).val();
                            $('#appendrows').append("<tr><td><input class='check_status' type='hidden' name='deg[]' value='"+degree+"' />"+degree+"</td><td><input type='hidden' name='pgm[]' value='"+pgm+"' />"+pgm+"</td><td><input type='hidden' name='sec[]' value='"+sec+"' />"+sec+"</td></tr>");
                            $('#programme_selected'+i).val("1");
                            $('#programme_selected'+i).prop("disabled", true);
                            $('#check_'+i).prop("checked", false);            
                            $('#stu_tbl').hide();
                            $('#add_content_table').hide();
                            $('#exit_table').hide();
                            $('#BatchSubmit').show();     
                            $('#Batch_reset_page').show();                    
                    }else{
                        
                        krajeeDialog.alert("Select All Required Fields");
                        return false;
                    }
                }
            }         
        }
        else{            
        $('#stu_tbl').hide();
        $('#add_content_table').hide();
        $('#exit_table').hide();
        $('#BatchSubmit').show();
        $('#Batch_reset_page').show();    
        
        }
        if(grade_display_status==2 || grade_display_status==1){
            $('#grade_tbl').css({"visibility": "visible"});
            if(parsed['grade_table']){
                $('.all_btndiv_hide').hide();
                $('#grade_tbl').html(parsed['grade_table']);    
            }else{
                $('.all_btndiv_hide').hide();
                $('#grade_tbl').show();
            }
            $('#grade_tbl').show();
        }
    }
    });

});

$('#button_show_degree').on('click',function()
{

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
        krajeeDialog.alert("Please enter the value for "+degree_label_name);
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

$('#deg_back_degree').on('click',function(){
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
        		krajeeDialog.alert("Please enter the value for "+programme_label_name+" ");
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
$('#prgm_back_update').on('click',function(){
  $('#prgm_code').show();
  $('.prgm_name').show();
  $('.prgm_btn').show();
  $('.prgm_back_btn').hide();
  $('.prgm_tbl').hide();
  $('#stu_tbl').hide();
    });
/* Programme Ends Here */






/* Migrate Subject starts here */

$("#stu_batch_id_selected").on("change",function(){
    var global_batch_id = $("#stu_batch_id_selected").val();

     $.ajax({
            url: base_url+'?r=ajaxrequest/getdegpgmdetails',
            type:'POST',
            data:{global_batch_id:global_batch_id},
            
            success:function(data)
            {
                var stu_programme_dropdown = $("#stu_programme_selected");
                stu_programme_dropdown.html(data);

                var stu_programme_dropdown_1 = $("#mandatory_stu_selected");
                stu_programme_dropdown_1.html(data);
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
    var batch = $('#stu_batch_id_selected').val();
    var batch_map_id = $('#stu_programme_selected').val();
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
    //var section = $('#stu_section_select').val();
    var semester = $('#nominal-semester').val();
    if(batch=='' || programme=='' || semester=='')
    {
        krajeeDialog.alert("Kindly Select the required fields");
    }
    $.ajax({
        url: base_url+'?r=ajaxrequest/getviewnominal',
        type:'POST',
        data:{batch:batch,programme:programme,semester:semester},
        success:function(data){         
            if(data)
            {
                var parsed = $.parseJSON(data);           
                if(parsed['result']==0)
                {
                    $('#student_bulk_edit').show();
                    $('#student_bulk_edit').html(parsed['table']);
                    //$('#button_view_nominal').show();
                    //$('.nominal_submit').hide();
                    $('#CreateNominal').show();            
                }
                else
                {
                    $('#student_bulk_edit_1').show();
                    $('#student_bulk_edit_1').html('');
                    $('#student_bulk_edit_1').html(parsed['table']);
                    $('#CreateNominal').show(); 
                    //$('#button_view_nominal').show();
                    //$('.nominal_submit').hide();
                }    
            }
            else
            {
                krajeeDialog.alert("We coudn't found any data on your Submission");
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

        if(sub_code)
        {
           $('#'+stu_id+'_2').focus().attr("required", "true");
           $('#'+stu_id+'_2').click(function() { 
            $('#'+stu_id+'_2').find('option').prop('disabled', false);
            $('#'+stu_id+'_2').find("option[value*="+sub_code+"]").prop("disabled", true);           
          });
        }          
        
    }else{
        var sub_code = $('#'+stu_id+'_'+elective_id).val();
        $('#'+stu_id+'_1').focus().attr("required", "true"); 
        $('#'+stu_id+'_1').click(function() {
          $('#'+stu_id+'_1').find('option').prop('disabled', false);
          $('#'+stu_id+'_1').find("option[value*="+sub_code+"]").prop("disabled", true);
      });
    }
}

/* Nominal Ends Here */

/* Galley Starts Here */
function resetHalls(){
  if(confirm("Are you sure to reset hall arrangement")){
    var year = $('#hallallocate-year').val();
    var month = $('#exam_month').val();
    var date = $('#exam_date').val();
    var session = $('#exam_session').val();
    $.ajax({
      
      url : base_url+'?r=hall-allocate/deletehallarrangement',
      type:'POST',
      data:{year:year,month:month,date:date,session:session},
      success:function(data){
        if(data==1){
          krajeeDialog.alert("Halls reset successfully!!!");
        }else if(data==0){
          krajeeDialog.alert("Mark Entry already done!! You can't reset Halls!!");
        }
      }
    });

  }
}


$('#exam_month').on('change',function(){

    var year = $('#hallallocate-year').val();
    var month = $('#exam_month').val();

    $.ajax({
        url: base_url+'?r=ajaxrequest/getqpexamdate',
        type:'POST',
        data:{year:year,month:month},
        success:function(data){       
            $('#exam_date').html('');                    
            var parsed = $.parseJSON(data);
            var old_ex_date = '';
            $('#exam_date').html('<option value="" >----Select----</option>');
            $.each(parsed,function(i,parsed)
            {
              
              if(old_ex_date!=parsed['exam_date'])
              {
                old_ex_date=parsed['exam_date'];
                $('#exam_date').append("<option value='"+parsed['exam_date']+"' >"+parsed['exam_date']+"</option>");
              }
     
            });
        }
    });
});

$('#exam_date').on('change',function(){

    $('#exam_session').html("<option value=''>-----Select----</option>");

    var date = $('#exam_date').val();    
    $.ajax({
        url: base_url+'?r=ajaxrequest/getqpsession',
        type:'POST',
        data:{date:date},
        success:function(data){               
            var parsed = $.parseJSON(data);
            $('#exam_session').html('');
            $.each(parsed,function(i,parsed){
                $('#exam_session').append("<option value='"+parsed['coe_category_type_id']+"'>"+parsed['description']+"</option>");
            });
        }
    });
});

$('#hall_arrangement').on('change',function(){

    $('#from').html('');
    $('#to').html('');
    $('#countFrom').val('');
    $('#countTo').val('');
    $('#method').html("<option value=''>-----Select----</option>");
    $('#hallallocate-student_count').html('');

    var hall_arrangement = $('#hall_arrangement').val(); 
    var date = $('#exam_date').val();
    var exam_month = $('#exam_month').val();
    var session = $('#exam_session').val();

    if(hall_arrangement=="Subject Wise"){
        $('#subjectwise').show();
        $('#subject').html(""); 
        $('#subject').append("<option value=''>-----Select----</option>");
        $.ajax({
            url: base_url+'?r=ajaxrequest/getsubcode',
            type:'POST',
            data:{date:date,hall_arrangement:hall_arrangement,session:session,exam_month:exam_month},
            success:function(data){                                     
                var parsed = $.parseJSON(data);
                $.each(parsed,function(i,parsed){
                $('#subject').append("<option value='"+parsed['subject_code']+"'>"+parsed['subject_code']+"</option>");
                });           
            }
        });

    }else if(hall_arrangement=="Non-Subject Wise"){
        $('#subjectwise').hide();
        $.ajax({
            url: base_url+'?r=ajaxrequest/getmethod',
            type:'POST',
            data:{date:date,hall_arrangement:hall_arrangement,session:session,exam_month:exam_month},
            success:function(data){            
                var parsed = $.parseJSON(data);
                $('#method').html('');
                $('#method').html("<option value=''>-----Select----</option>");
                $.each(parsed,function(i,parsed){
                $('#method').append("<option value='"+parsed['coe_category_type_id']+"'>"+parsed['category_type']+"</option>");
                });   
            }
        });
    }

});

$('#method').on('change',function(){
    
    var method = $('#method').val();
    var exam_year = $('#hallallocate-year').val();
    var exam_month = $('#exam_month').val();
    var hall_arrangement = $('#hall_arrangement').val();
    var date = $('#exam_date').val();
    var session = $('#exam_session').val();
    var student_count = $('label[for=hallallocate-student_count]').text();
    var sub_code_galley='';

    $('#from').html("");
    $.ajax({
        url: base_url+'?r=ajaxrequest/gethall',
        type:'POST',
        data:{method:method},
        success:function(data)
        {
          var parsed = $.parseJSON(data);
          if(parsed==1)
          {
              krajeeDialog.alert("Kindly Import the halls for selected method.");
          }
          else
          {
              $('#from').html('');
              $('#to').html('');
              $.each(parsed,function(i,parsed){
                  $('#from').append("<option value='"+parsed['hall_name']+"'>"+parsed['hall_name']+"</option>");
                  
              });
          }
            
        }
    });

    if(hall_arrangement=="Non-Subject Wise"){               
        $('#subjectwise').hide();
        $.ajax({
            url: base_url+'?r=ajaxrequest/getseatcount',
            type:'POST',
            data:{method:method,date:date,session:session,exam_month:exam_month,exam_year:exam_year},
            success:function(data){                 
                var parsed = $.parseJSON(data); 
                $('#hall_cnt').attr("value",parsed['available_hall']);                                     
                $('#hallallocate-student_count').html(parsed['message']);                
            }
        });
    }else if(hall_arrangement=="Subject Wise"){
             
            $("#subject").each(function(){
                var subject_code = $('#subject').val();        
                sub_code_galley+=subject_code;               
            });
            
            $.ajax({
                url: base_url+'?r=ajaxrequest/getsubjectseatcount',
                type:'POST',
                data:{method:method,date:date,sub_code_galley:sub_code_galley,session:session,exam_month:exam_month,exam_year:exam_year},
                success:function(data){                     
                    var parsed = $.parseJSON(data);
                    $('#hall_cnt').attr("value",parsed['available_hall']);
                    $('#hallallocate-student_count').html(parsed['message']);
                }
            });     
            
    }

});

$('#subject').on('change',function(){
    $('#method').html("<option value=''>-----Select----</option>");
    $('#hallallocate-student_count').html('');

    var hall_arrangement = $('#hall_arrangement').val(); 
    var date = $('#exam_date').val();
    var session = $('#exam_session').val();
    $.ajax({
            url: base_url+'?r=ajaxrequest/getmethod',
            type:'POST',
            data:{date:date,hall_arrangement:hall_arrangement,session:session},
            success:function(data){            
                var parsed = $.parseJSON(data);
                $.each(parsed,function(i,parsed){
                $('#method').append("<option value='"+parsed['coe_category_type_id']+"'>"+parsed['category_type']+"</option>");
                });   
            }
    });

});

$('#qp_month').on('change',function(){
    var year = $('#hallallocate-year').val();
    var month = $('#qp_month').val();
    $.ajax({
        url: base_url+'?r=ajaxrequest/getqpexamdate',
        type:'POST',
        data:{year:year,month:month},
        success:function(data){
            var parsed = $.parseJSON(data);
            $('#qp_exam_date').html('<option value="" >----Select----</option>');
            $.each(parsed,function(i,parsed){
                $('#qp_exam_date').append("<option value='"+parsed['exam_date']+"'>"+parsed['exam_date']+"</option>");
            });
        }
    });
});

$('#hallstu').on('click',function(){

    var date = $('#qp_exam_date').val();
    var session = $('#qp_exam_session').val();
    $.ajax({
        url: base_url+'?r=hall-allocate/hallvsstudentreport',
        type:'POST',
        data:{date:date,session:session,exam_year:$("#hallallocate-year").val(),exam_month:$("#qp_month").val()},
        success:function(data)
        {    
           $('#hall_stu_tbl').show();
           $('.show_hall_vs_stu_print').show();
           $('#hall_stu_tbl').html(data);
        }
    });
});
$('#hallstuabsent').on('click',function(){

    var date = $('#qp_exam_date').val();
    var session = $('#qp_exam_session').val();
    $.ajax({
        url: base_url+'?r=hall-allocate/hallvsabsentstudentreport',
        type:'POST',
        data:{date:date,session:session,exam_year:$("#hallallocate-year").val(),exam_month:$("#qp_month").val()},
        success:function(data)
        {    
           $('#hall_stu_tbl').show();
           $('.show_hall_vs_stu_print').show();
           $('#hall_stu_tbl').html(data);
        }
    });
});

function getValues()
{
  var hallName="";
  var tex = $( "#to" ).text();
  var count3 =  $('#to option').length;
  var x=document.getElementById("to");
  for (var i = 0; i < x.options.length; i++) 
  {
    hallName += x.options[i].value+"&";      
  }
  document.getElementById("hallName").value=hallName;

}
function moveSelected(from, to)
{
    $('#'+from+' option:selected').remove().appendTo('#'+to);
    var count =  $('#from option').length;
    document.getElementById("countFrom").value=count;
    var count2 =  $('#to option').length;
    document.getElementById("countTo").value=count2;
    $("#from option:first").attr('selected','selected');
}

function moveAll(from, to)
{
    $('#'+from+' option').remove().appendTo('#'+to);
    var count =  $('#from option').length;
    document.getElementById("countFrom").value=count;
    var count2 =  $('#to option').length;
    document.getElementById("countTo").value=count2;
}

function shuffle()
{
  var toto=[];
  var emp="";
  var xx=document.getElementById("to");
  for (var i = 0; i < xx.options.length; i++) {
    toto[i]= xx.options[i].value;
  }
  var selectBox = document.getElementById("to");
  selectBox.innerHTML = "";
  options = shuffle2(toto);
  for (var i = 0; i < options.length; i++) {
    var opt = options[i];
    var el = document.createElement("option");
    el.textContent = opt;
    el.value = opt;
    selectBox.appendChild(el);
  }
}
function shuffle2(array) {
  var currentIndex = array.length, temporaryValue, randomIndex;
  // While there remain elements to shuffle...                                                                                                                         
  while (0 !== currentIndex) {
    // Pick a remaining element...                                                                                                                                     
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;
    // And swap it with the current element.                                                                                                                           
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }
  return array;
}


$('#qp_exam_date').on('change',function(){
    var date = $('#qp_exam_date').val();
    $.ajax({
        url: base_url+'?r=ajaxrequest/getqpsession',
        type:'POST',
        data:{date:date},
        success:function(data)
        {            
            $('#qp_exam_session').html('');
            var parsed = $.parseJSON(data);
            $.each(parsed,function(i,parsed)
            {
                $('#qp_exam_session').append("<option value='"+parsed['coe_category_type_id']+"'>"+parsed['description']+"</option>");
     
            });
        }
    });
});


$('#qpsubmit').on('click',function(){
   var date = $('#qp_exam_date').val();
   var session = $('#qp_exam_session').val();
   var exam_year = $('#hallallocate-year').val();
   var exam_month = $('#qp_month').val();
   if(date=='' || session=='')
   {
        krajeeDialog.alert("Select the Required Fields");
        return false;
   }
   $.ajax({
        url: base_url+'?r=hall-allocate/qpcnt',
        type:'POST',
        data:{date:date,session:session,exam_year:exam_year,exam_month:exam_month},
        success:function(data){
          if(data==0)
          {
            krajeeDialog.alert("No Data Found");
            $('#qp_tbl').hide();
            $('#qp_tbl_1').hide();
            return false;
          }else{
            //var a='<div class="panel  box box-info"><div class="box-header  with-border" role="tab" ><div class="row"><div class="col-md-10"><h4 class="padding box-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Question Paper Distribution</a></h4></div></div></div><div id="collapseOne" class="panel-collapse collapse in"><div class="box-body">'+data;
            
            $('#qp_tbl').show();
            $('#qp_tbl_1').show();
            $('#qp_tbl').html(data); 
          }
           
        }
    });
});

/*$('#hallstu').on('click',function(){

    var date = $('#qp_exam_date').val();
    var session = $('#qp_exam_session').val();
    $.ajax({
        url: base_url+'?r=hall-allocate/hallvsstudentreport',
        type:'POST',
        data:{date:date,session:session},
        success:function(data){
           $('#hall_stu_tbl').show();
           $('#hall_stu_tbl').html(data);
        }
    });
});*/

/* Galley Ends Here */

/* Migrate Functions */
$('#view_mig').on('click',function(){
    $('.view_mig').show();
});

$('#mig_rep').on('click',function(){
    var year = $('#mig_rep_year').val();
    //alert(year);
    if(year!="")
    {
        $.ajax({
            url: base_url+'?r=ajaxrequest/getmigratedvalue',
            type:'POST',
            data:{year:year},
            
            success:function(data)
            {
                if(data!=0)
                {
                    $('.view_mig_tbl').show();
                    $('#mig_sub_tbl').html(data);
                }
                else
                {
                    krajeeDialog.alert("No data found");
                    return false;
                }
            }
        });
    }
    else
    {
        krajeeDialog.alert("Please enter year");
        return false;
    }
});
/* Migrate Functions Ends Here */
/* Exam Starts Here */
$("#exam_del_butt").click(function(){

    var id=$("input[type=checkbox]:checked").serializeArray();
    var jsonStr = JSON.stringify(id);
    var objVal = JSON.parse(jsonStr);
    var lengthVal = Object.keys(objVal).length;
    if(lengthVal.length<=0 || lengthVal=='')
    {
        krajeeDialog.alert('Select At Least 1 Exam');
        return false;
    }
    var output="";
    for (var i = 0; i < Object.keys(objVal).length; i++) {
      output=output+objVal[i].value+"^";
    }
    document.getElementById("finalString").value=output;
   
  });

$("#stu_exam_del_butt").click(function(){

    var id=$("input[type=checkbox]:checked").serializeArray();
    var jsonStr = JSON.stringify(id);
    var objVal = JSON.parse(jsonStr);
    var lengthVal = Object.keys(objVal).length;
    if(lengthVal.length<=0 || lengthVal=='')
    {
        krajeeDialog.alert('Select At Least 1 Student');
        return false;
    }
    var output="";
    for (var i = 0; i < Object.keys(objVal).length; i++) {
      output=output+objVal[i].value+"^";
    }
    document.getElementById("finalString").value=output;
   
  });
function submitFormStu(id)
{
    var stu_delForm = $('#delete-student').submit();
}
$("#subjects_mapping_delid").click(function(){

    var id=$("input[type=checkbox]:checked").serializeArray();
    var jsonStr = JSON.stringify(id);
    var objVal = JSON.parse(jsonStr);
    var lengthVal = Object.keys(objVal).length;
    var output="";
    if(lengthVal.length<=0 || lengthVal=='')
    {
        krajeeDialog.alert('Select At Least 1 Subject');
        return false;
    }
    for (var i = 0; i < Object.keys(objVal).length; i++) {
      output=output+objVal[i].value+"^";
    }
    document.getElementById("finalString").value=output;
   
  });
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
                exam_month_dropdown.html("<option>--- Select---</option>");
                exam_month_dropdown.html(data);
        }
    });
});

$('#exam_semester').on('change',function(){
    $('#exam_subject_code').html('');
    var batch_map_id = $('#stu_programme_selected').val();
    var sem = $('#exam_semester').val();
    var type = $('#exam_type').val();

    $.ajax({
        url: base_url+'?r=ajaxrequest/getsubjectcode',
        type:'POST',
        data:{batch_map_id:batch_map_id,sem:sem,type:type},
            
        success:function(data)
        {
          //alert(data);
          $("#exam_subject_code").html('');
          $("#exam_subject_code").append("<option value='' > ---- Select --- </option>");
          var parsed = $.parseJSON(data);
          $.each(parsed,function(i,parsed){
              $("#exam_subject_code").append("<option value="+parsed['sub_id']+">"+parsed['subject_code']+"</option>");
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
            var parsed = $.parseJSON(data);
            $("#exam_subject_name").attr("value",parsed['subject_name']);
        }
    });    

});

/* Exam Ends Here */

/* Internal mark entry starts here */
/*$('#stu_list').on('click',function(){

    var year = $('#mark_year').val();
    var batch = $('#stu_batch_id_selected').val(); 
    var batch_map_id = $('#stu_programme_selected').val();
    var section = $('#sec').val();
    var sem = $('#exam_semester').val();
    var sub_code = $('#exam_subject_code').val();
    var internal = $('#mark_type_selected').val();
    if(year=="" || batch=="" || batch_map_id=="" || section=="" || sem=="" || sub_code=="" || internal=="")
    {
        krajeeDialog.alert("Please enter all fields");
        return false;
    }
    else
    {
        $.ajax({
            url: base_url+'?r=ajaxrequest/getstudentlist',
            type:'POST',
            data:{batch:batch,batch_map_id:batch_map_id,section:section},
                
            success:function(data)
            {
                if(data!=0)
                {
                    //alert(data);
                    $('.mark_tbl').show();
                    $('#stu_mark_tbl').show();
                    $('#stu_mark_tbl').html(data);
                }
                else
                {
                    krajeeDialog.alert("No data found");
                    return false;
                }
            }
        });
    }

});*/
/* Internal mark entry ends here */

// View Absentees

$( document ).ready(function() {
     var ab_type = 1;
   $.ajax({
        type:'POST',
        url: base_url+"?r=ajaxrequest/examdatesviewab",
        data: {
            ab_type: ab_type,
        },
        success: function(data)
        {
            var jsonFormat = JSON.parse(data);        
            $('#absententry-exam_date').html('');
             var drop_items= "<option value=''> ---- Select ---- </option>";
              for (var i = 0; i < jsonFormat.length; i++){
                drop_items+= "<option value='" + jsonFormat[i].exam_date+ "'>" + jsonFormat[i].exam_date+ "</option>";
              }
            $("#absententry-exam_date").html(drop_items);            
        }
    });
});

function showAbdata(exam_year,exam_month)
{
    $.ajax({
        type:'POST',
        url: base_url+"?r=ajaxrequest/getablist",
        data: {
            exam_year: exam_year,
            exam_month: exam_month,
            exam_date: $('#exam_date').val(),
            exam_session:$('#absententry-exam_session').val()
        },
        success: function(data)
        {
           var parsed = JSON.parse(data);
           
           if(parsed==0)
            {        
                $('.hide_ab_list').hide();
                krajeeDialog.alert("No Data Found");
            }
            else
            {
                $('.show_ab_data').html('');
                   var append_data;
                   var increment=1;
                    $.each(parsed,function(i,parsed)
                    {
                     // type_drop+= "<option obj='" + value.coe_category_type_id+ "'>" + value.category_type+ "</option>";                   
                       var tr_open,td_1,td_2,td_3,td_4,td_5,td_6,td_7,td_8,td_9,td_10,tr_close;
                       tr_open ="<tr>";
                       td_3 = "<td>" + increment + "</td>";
                       td_1 = "<td>" + parsed['batch_name'] + "</td>";
                       td_2 = "<td>" + parsed['programme_degree_name'] + "</td>";
                       td_4 = "<td>" + parsed['exam_type'] + "</td>";
                       td_6 = "<td>" + parsed['register_number'] + "</td>";
                       td_7 = "<td>" + parsed['name'] + "</td>";
                       td_8 = "<td>" + parsed['subject_code'] + "</td>";
                       td_9 = "<td>" + parsed['subject_name'] + "</td>";
                       td_10 = "<td>" + parsed['semester'] + "</td>";   
                       
                       tr_close = "</tr>";
                       append_data +=tr_open+td_3+td_1+td_2+td_4+td_6+td_7+td_8+td_9+td_10+tr_close;
                       increment++;
                  });

                  $('.hide_ab_list').show();
                  $('.show_ab_data').html(append_data);
            }
        }
    });
}
function showAbdatadelete(exam_year,exam_month)
{
    $.ajax({
        type:'POST',
        url: base_url+"?r=ajaxrequest/getablistdelete",
        data: {
            exam_year: exam_year,
            exam_month: exam_month,
            exam_date: $('#exam_date').val(),
            exam_session:$('#absententry-exam_session').val()
        },
        success: function(data)
        {
           var parsed = JSON.parse(data);
           if(parsed==0)
            {        
                $('.hide_ab_list_del').hide();
                krajeeDialog.alert("No Data Found");
            }
            else if(parsed==1)
            {        
                $('.hide_ab_list_del').hide();
                krajeeDialog.alert("Sorry Mark Entry Completed Contact SKIT");
            }
            else
            {
                $('.show_ab_data_del').html('');
                   var append_data;
                   var increment=1;
                    $.each(parsed,function(i,parsed)
                    {
                     // type_drop+= "<option obj='" + value.coe_category_type_id+ "'>" + value.category_type+ "</option>";                   
                       var tr_open,td_1,td_2,td_3,td_4,td_5,td_6,td_7,td_8,td_9,td_10,tr_close;
                       tr_open ="<tr>";
                       td_3 = "<td>" + increment + "</td>";
                       td_1 = "<td>" + parsed['batch_name'] + "</td>";
                       td_2 = "<td>" + parsed['programme_degree_name'] + "</td>";
                       
                       td_6 = "<td>" + parsed['register_number'] + "</td>";
                       td_7 = "<td>" + parsed['name'] + "</td>";
                       td_8 = "<td>" + parsed['subject_code'] + "</td>";
                       td_9 = "<td>" + parsed['subject_name'] + "</td>";
                       td_10 = "<td>" + parsed['semester'] + "</td>";   
                       td_4 = "<td><button type'button' class='btn btn-block btn-danger' id='del_ab_"+increment+"' value='"+parsed['coe_absent_entry_id']+"' onclick='deleteAbsent(this.id,this.value)' >Delete</button></td>";              
                       tr_close = "</tr>";
                       append_data +=tr_open+td_3+td_1+td_2+td_6+td_7+td_8+td_9+td_10+td_4+tr_close;
                       increment++;
                  });
                  $('.hide_ab_list_del').show();
                  $('.show_ab_data_del').html(append_data);
            }
        }
    });
}
function deleteAbsent(id,val)
{
    $.ajax({
        type:'POST',
        url: base_url+"?r=ajaxrequest/getdeleteabsentrecord",
        data: {
            ab_id: val,
        },
        success: function(data)
        {
            if(data=='NO')
            {   
                krajeeDialog.alert("MARK ENTRY COMPLETED CONTACT SKIT");
            }
            else if(data==0)
            {   
                krajeeDialog.alert("SOMETHING WRONG CONTACT SKIT");
            }
            else if(data=='NOT_FOUND')
            {   
                krajeeDialog.alert("ABSENT ENTRY ALREADY DELETED!!");
            }
            else
            {
                setTimeout(function(){
                    window.location.reload();
                }, 500);
                krajeeDialog.alert("ABSENT ENTRY SUCCESSFULLY DELETED!!!");
            }
        }
    });
}
function showConsolidateAbdata(exam_year,exam_month,exam_date,exam_session)
{
    $.ajax({
        type:'POST',
        url: base_url+"?r=ajaxrequest/getconsolidateablist",
        data: {
            exam_year: exam_year,exam_month: exam_month,exam_date: exam_date,exam_session: exam_session,
            
        },
        success: function(data)
        {
           var jsonFormat = JSON.parse(data);
           
           if(jsonFormat==0)
            {        
                $('.hide_ab_list').hide();
                krajeeDialog.alert("No Data Found");
            }
            else
            {
                 $('.show_ab_data').html('');                  
                  $('.hide_ab_list').show();
                  $('.show_ab_data').html(jsonFormat);
            }
        }
    });
}

/* Absent Entry Functions Starts Here */ 



function changeFields(catId)
{
    var programme_id_val = $('#stu_programme_selected').val();
    var absent_type = $("#absententry-absent_type");
    var exam_date = $("#exam_date");
    var exam_session = $("#absententry-exam_session");
    var halls = $("#absententry-halls");
    var exam_type = $("#absententry-exam_type");
    var exam_term = $("#absententry-absent_term");
    var exam_subject_id = $("#absententry-exam_subject_id");
    var section = $("#stu_section_select");
    var exam_semester_id = $("#absententry-exam_semester_id");

    $.ajax({
         url: base_url+'?r=ajaxrequest/showrequired',
         type:'POST',
         data:{catId:catId},
         success:function(data)
         {
            var jsonFormat = JSON.parse(data);
            if(jsonFormat=="Exam")
            {
                halls.html('');
                exam_semester_id.html('');
                halls.prop('required',false);
                $('.exam_wise').fadeIn(1500).show();
                $('.ab_common_all').fadeIn(1500).show();
                $('.ab_hall_wise').fadeOut(1500).hide();
                $('.hide_semester').fadeOut(1500).hide();                
                $(".removecommon").removeAttr('value').hide(); // Remove the Previous Selected Vals
            }
            else if(jsonFormat=="Practical")
            {   
                
                exam_session.html('');
                halls.html('');  
                halls.prop('required',false);
                $('.ab_common_all').fadeIn(1500).show();
                $('.exam_wise').fadeOut(1500).hide();
                $('.ab_hall_wise').fadeOut(1500).hide();
                $('.remove_section_for_hall').fadeIn(1500).show();
            }
            else if(jsonFormat=="Hall")
            {
                section.val('');
                exam_session.html('');
                exam_semester_id.html('');
                // exam_type.html('');
                // exam_term.html('');
                exam_subject_id.html('');
                halls.prop('required',true);
                $('.exam_wise').fadeIn(1500).show();
                $('.ab_common_all').fadeIn(1500).show();
                $('.ab_hall_wise').fadeIn(1500).show();
                $('.remove_section_for_hall').fadeOut(1500);
            }
            else
            {
                halls.html('');
                section.html('');
                exam_semester_id.html('');
                exam_date.html('');
                exam_session.html('');
                exam_type.html('');
                exam_term.html('');
                exam_subject_id.html('');
                $('.ab_hide_default').hide();
            }
         }
    });
}
function get_semester(programme_id_val)
{
    $.ajax({
        type: "post",
        url: base_url+'?r=ajaxrequest/absemeters',
        data: {programme_id_val:programme_id_val},
        success: function (data) {
            var jsonFormat = JSON.parse(data);
            if(data)
            {        
                $('#absententry-exam_semester_id').html('');
                 var drop_items= "<option value=''> ---- Select ---- </option>";;
                  for (var i = 1; i <=jsonFormat; i++){
                    drop_items+= "<option value='" + i+ "'>" + i + "</option>";
                  }
                  $("#absententry-exam_semester_id").html(drop_items);
            }
            
        }
    });
}
function getReg()
{
    var total = "";
  var counts=document.getElementById("countvalue").value;
// alert(counts);
for(i=0; i<counts; i++) 
   {   
     if (document.getElementById("checkall"+i).checked == false)
      {
             var reg=document.getElementById("register_number"+i).value;
              var name=document.getElementById("student_name"+i).value;                                    
        
              total = total+reg+"^"+name+"@"; //or total += reg+"^"+name;
      }
    }
}
function changeLable(id)
{
  //var check = document.getElementById(id).checked;
  var stu_id_array = id.split("[");
  var stu_id = stu_id_array[1].split("]");
  var check=$("input[name='"+id+"']:checked").val();
  var reg_num = $('#reg_nu_sem_ab_'+stu_id[0]).val()+", ";
  var str = $('.display_stu_res').html(); 
  var count
  $('.display_stu_res').css({"background-color": "#b3003b",'padding':'5px', "visibility": "visible",'color':'#FFF','font-weight':'bold'});
  $('.display_stu_res_count').css({"background-color": "#0000b3",'padding':'5px','width':'150px', "visibility": "visible",'color':'#FFF','font-weight':'bold'});
  
  if(check=='on' && check!='undefined' )
  {    
       count = str.split(', ').length;
      $('.display_stu_res').append(reg_num);
      $('label[for=absent-name_'+stu_id[0]+']').html("<b style='color: #f00;' >"+ab_label_name+'</b>');
  }
  else
  {    
      var res = str.replace(reg_num, "");
       count = res.split(', ').length;
      var final_ss = $('.display_stu_res').html(res);
      $('label[for=absent-name_'+stu_id[0]+']').html('Present');
  } 
  $('.display_stu_res_count').html(" Total Absent="+count)
    
}

function get_exam_dates(programme_id)
{
    var batch_id=$("#stu_batch_id_selected").val();
    $.ajax({
        type:'POST',
        url: base_url+"?r=ajaxrequest/examdates",
        data: {
            programme_id: programme_id,batch_id:batch_id,
        },
        success: function(data)
        {
            var jsonFormat = JSON.parse(data);        
            $('#absententry-exam_date').html('');
             var drop_items= "<option value=''> ---- Select ---- </option>";
              for (var i = 0; i < jsonFormat.length; i++){
                drop_items+= "<option value='" + jsonFormat[i].exam_date+ "'>" + jsonFormat[i].exam_date+ "</option>";
              }
            $("#absententry-exam_date").html(drop_items);            
        }
    });
}
function getHalls(exam_date,year,month)
{
     $.ajax({
        type:'POST',
        url: base_url+"?r=ajaxrequest/gethalls",
        data: {
            exam_date: exam_date,year:year,month:month
        },
        success: function(data)
        {
            if(data){
                var jsonFormat = JSON.parse(data);        
                $('#absententry-halls').html('');
                var drop_items= "<option value=''> ---- Select ---- </option>";
                  for (var i = 0; i < jsonFormat.length; i++){
                    drop_items += "<option value='" + jsonFormat[i].coe_hall_master_id+ "'>" + jsonFormat[i].hall_name+ "</option>";
                  }
                $("#absententry-halls").html(drop_items); 
            }
        }
    });
}
function showSessions(exam_date,exam_year,exam_month)
{
    var batch_id=$("#stu_batch_id_selected").val();
    var programme_id=$("#stu_programme_selected").val();
    $(".show_hall_result_data").hide(); 
     $(".show_hall_result_data").html(''); 
     $('.hide_hall_submit').hide();
     $('#hall_names').html('');
    $.ajax({
        type:'POST',
        url: base_url+"?r=ajaxrequest/examsession",
        data: {
            exam_date: exam_date,exam_year:exam_year,exam_month:exam_month
        },
        success: function(data)
        {
            if(data){
               
                var jsonFormat = JSON.parse(data);  
                $('#absententry-exam_session').html('');
                var drop_items="<option value='' >------SELECT----</option>";                      ;
                $.each(jsonFormat,function(i,parsed){
                   //$.each(parsed, function(key, value) {
                     drop_items+= "<option value='" + parsed.coe_category_type_id+ "'>" + parsed.category_type+ "</option>";                      
                    //});
                });
                 
                $("#absententry-exam_session").html(drop_items);  
                $.ajax({
                    type:"POST",
                    url: base_url+"?r=ajaxrequest/examtype",
                   data: {
                        exam_date: exam_date,exam_year:exam_year,exam_month:exam_month
                    },
                    success: function(data)
                    {
                        if(data)
                        {
                            var jsonFormat = JSON.parse(data);
                            $('#absententry-exam_type').html('');
                            var type_drop;

                             $.each(jsonFormat,function(i,parsed){
                               
                                  type_drop+= "<option value='" + parsed.coe_category_type_id+ "'>" + parsed.category_type+ "</option>";                   
                            
                            });
                            
                            $("#absententry-exam_type").html(type_drop);

                            $.ajax({
                                type:"POST",
                                url: base_url+"?r=ajaxrequest/examterm",
                               data: {
                                    exam_date: exam_date,exam_year:exam_year,exam_month:exam_month
                                },
                                success: function(data)
                                {
                                    if(data)
                                    {
                                        var jsonFormat = JSON.parse(data);
                                        $('#absententry-absent_term').html('');
                                        var term_drop;
                                        $.each(jsonFormat,function(i,parsed){ 
                                              term_drop+= "<option value='" + parsed.coe_category_type_id+ "'>" + parsed.category_type+ "</option>";
                                             
                                        });
                                        
                                        $("#absententry-absent_term").html(term_drop);
                                        $.ajax({
                                            type:"POST",
                                            url: base_url+"?r=ajaxrequest/examsubcode",
                                           data: {
                                                exam_date: exam_date,exam_year:exam_year,exam_month:exam_month
                                            },
                                            success: function(data)
                                            {
                                                if(data)
                                                {
                                                    var jsonFormat = JSON.parse(data);
                                                    $('#absententry-exam_subject_id').html('');
                                                    var sub_drop;
                                                    for (var i = 0; i < jsonFormat.length; i++){
                                                        sub_drop += "<option value='" + jsonFormat[i].coe_subjects_id+ "'>" + jsonFormat[i].subject_code+ "</option>";
                                                      }
                                                    $("#absententry-exam_subject_id").html(sub_drop);
                                                }
                                            }
                                        });



                                    }
                                }
                            });

                        }
                    }
                });
            }
            
        }
    });
}
function showSubjectCodes(term)
{
    var ab_prgm_id_1 = $("#stu_programme_selected").val();
    var absent_type_1 = $("#absententry-absent_type").val();
    var exam_date_1 = $("#exam_date").val();
    var exam_session_1 = $("#absententry-exam_session").val();
    var halls_1 = $("#absententry-halls").val();
    var exam_type_1 = $("#absententry-exam_type").val();
    var section_1 = $("#stu_section_select").val();
    var exam_semester_id_1 = $("#absententry-exam_semester_id").val();
    $.ajax({
         type:'post',
         url: base_url+'?r=ajaxrequest/showsubjectcodes',
         data:{
            exam_type_1:exam_type_1,ab_prgm_id_1:ab_prgm_id_1,
            section_1:section_1,exam_date_1:exam_date_1,halls_1:halls_1,
            exam_session_1:exam_session_1,absent_type_1:absent_type_1,
            exam_semester_id_1:exam_semester_id_1
        },
         success: function(data)
         {
            var jsonFormat = JSON.parse(data);
            $('#absententry-exam_subject_id').html('');
            if(jsonFormat['result_type']=='Practical')
            {
                 var drop_items= "<option value=''> ---- Select ---- </option>";;
                  for (var i = 0; i < jsonFormat.send_result.length; i++){
                    drop_items+= "<option value='" + jsonFormat.send_result[i].sub_id + "'>" + jsonFormat.send_result[i].sub_code + "</option>";
                  }
                  $("#absententry-exam_subject_id").html(drop_items);
            }
            else if(jsonFormat['result_type']=="Exam")
            {
                $('#absententry-exam_semester_id').html(data);
            }
            else if(jsonFormat['result_type']=="Hall")
            {
                
            }
            else 
            {
                krajeeDialog.alert("No Data Found");
            }
         }
     });
}

function ExternalSubjectCodes(exam_term)
{
    var programme_id_val = $('#stu_programme_selected').val();
    var exam_semester_id_1 = $('#absententry-exam_semester_id').val();
    var exam_type = $('#absententry-exam_type').val();

    $.ajax({
         type:'post',
         url: base_url+'?r=ajaxrequest/externalsubjectcodes',
         data:{
            programme_id_val:programme_id_val,
            exam_semester_id_1:exam_semester_id_1,
            exam_type:exam_type,
            exam_term:exam_term,
        },
         success: function(data)
         {
            var jsonFormat = JSON.parse(data);
            if(data)
            {        
                $('#absententry-exam_subject_id').html('');
                 var drop_items= "<option value=''> ---- Select ---- </option>";;
                  for (var i = 0; i < jsonFormat.length; i++){
                    drop_items+= "<option value='" + jsonFormat[i].sub_id + "'>" + jsonFormat[i].sub_code + "</option>";
                  }
                  $("#absententry-exam_subject_id").html(drop_items);
            }
            
         }
     });

    
}

function getSemesters(programme_id)
{
    var programme_id_val = programme_id;
     $.ajax({
        type: "post",
        url: base_url+'?r=ajaxrequest/absemeters',
        data: {programme_id_val:programme_id_val},
        success: function (data) {
            var jsonFormat = JSON.parse(data);
            if(data)
            {        
                $('#absententry-exam_semester_id').html('');
                 var drop_items= "<option value=''> ---- Select ---- </option>";;
                  for (var i = 1; i <=jsonFormat; i++){
                    drop_items+= "<option value='" + i+ "'>" + i + "</option>";
                  }
                  $("#absententry-exam_semester_id").html(drop_items);
            }
        }
    });
}

/* Absent Entry Functions Starts Here */ 

/* Internal mark entry starts here */

$('#internal_semester').on('change',function(){
    $('#internal_subject_code').html('');
    var batch_map_id = $('#stu_programme_selected').val();
    var sem = $('#internal_semester').val();

    $.ajax({
        url: base_url+'?r=ajaxrequest/getinternalsubjectcode',
        type:'POST',
        data:{batch_map_id:batch_map_id,sem:sem},
            
        success:function(data)
        {
            $("#internal_subject_code").html('');
            $("#internal_subject_code").append("<option value='' > ---- Select --- </option>");
            var parsed = $.parseJSON(data);
            $.each(parsed,function(i,parsed){
                $("#internal_subject_code").append("<option value="+parsed['coe_subjects_id']+">"+parsed['subject_code']+"</option>");
            });
        }
    });

});

$('#cia_btn').on('click',function(){

  var year = $('#mark_year').val();
  var batch = $('#stu_batch_id_selected').val(); 
  var batch_map_id = $('#stu_programme_selected').val();
  var sem = $('#internal_semester').val();
  var sub_code = $('#internal_subject_code').val();
  var internal = $('#mark_type_selected').val();
  var year_label_name = $('label[for=mark_year]').text();
  var batch_label_name = $('label[for=stu_batch_id_selected]').text();

  if(year=="" || batch=="" || batch_map_id=="" || sem=="" || sub_code=="" || internal=="")
  {
    krajeeDialog.alert("Please select all fields");
    return false;
  }

  $.ajax({
    url: base_url+'?r=ajaxrequest/getcheckexamyearbatch',
    type:'POST',
    data:{global_batch_id:batch},
            
    success:function(data)
    {
      if(year>=data)
      {
        $.ajax({
          url: base_url+'?r=ajaxrequest/getstudentlist',
          type:'POST',
          data:{batch_map_id:batch_map_id,sem:sem,sub_code:sub_code,internal:internal,year:year},
              
          success:function(data)
          {
            //alert(data);
            var parsed = $.parseJSON(data);

            if(parsed['table']=="No")
            {
                krajeeDialog.alert("Please Complete the nominal For the above selected fileds");
                $('.mark_tbl').hide();
                $('#stu_mark_tbl').hide();
                return false;
            }

            if(data!=0)
            {
                $('.mark_tbl').show();
                $('#stu_mark_tbl').show();
                $('#stu_mark_tbl').html(parsed['table']);
                $("#stu_count").attr("value",parsed['sn1']);
                $("#cia_max_mark").attr("value",parsed['cia_max']);
                $("#attendance_percent").attr("value",parsed['attendance_per']);
            }
            else
            {
                $('.mark_tbl').hide();
                $('#stu_mark_tbl').hide();
                krajeeDialog.alert("No data found");
                return false;
            }

            if($(".mark_txt").attr('disabled') || $(".mark_txt").prop('disabled'))
            {
              $('.cia_mark_done_btn').hide();
            }
            else
            {
              $('.cia_mark_done_btn').show();
            }
          }
        });
      }
      else
      {
        krajeeDialog.alert("Please select correct "+year_label_name+" or "+batch_label_name+".");
        return false;
      }
    }
  });
});

function checkMax(stu_id)
{
  var cia_max_mark = parseInt($('#cia_max_mark').val());
  var stu_marks = parseInt($("#"+stu_id).val());
  var stu_id_splitted =  stu_id.substr(stu_id.lastIndexOf('_') + 1); 

  if(stu_marks>cia_max_mark)
  {
    krajeeDialog.alert("Marks entered wrongly... Please make sure to enter the correct marks");
    $("#"+stu_id).css("border","1px solid #F00");
    $("#"+stu_id).focus().val("");
    return false;
  }
  else
  {
    $("#"+stu_id).css("border","1px solid #008D4C");
    
  }

  if(stu_marks!="" || stu_marks!='undefined' || stu_marks!=null)
  {
    $("#attendance_percentage_"+stu_id_splitted).attr('required','true');
  }
  else
  {
    $("#attendance_percentage_"+stu_id_splitted).css("border","1px solid red");
  }
  return true;
}

function AttendancePercentage(id)
{
  var attendance = parseInt($("#attendance_percent").val());
  var att_per = parseInt($("#"+id).val());
  var reg_number = $("#reg_"+id).val();

  if(att_per>100)
  {
    krajeeDialog.alert("Please enter attendance percentage as below 100 %");
    $("#"+id).val('');
    $("#"+id).css("border","1px solid #F00");
    return false;
  }
  else
  {
    $("#"+id).css("border","1px solid #008D4C");
    if(attendance>att_per)
    {
      $("#"+id).css('color','#F00');
      $("#"+id).css("border","1px solid #F00");
      $("#remark_"+id).val('Not Allowed').css("color","#F00");
    }
    else
    {
      $("#"+id).css('color','black');
      $("#"+id).css("border","1px solid #008D4C");
      $("#remark_"+id).val('Allowed').css("color","#008D4C");
    }
  }
}

/* Internal mark entry ends here */

/* External mark entry starts here */
function checkTotal(model_val)
{
    if($('#mod_1').val() && $('#mod_2').val())
    {
        var sub_id = $('#mark_subject_code').val();
        var map_id = $('#stu_programme_selected').val();
        $.ajax({
            url: base_url+'?r=ajaxrequest/getesesubjectinformation',
            type:'POST',
            data:{batch_map_id:map_id,sub_id:sub_id,mod_1:$('#mod_1').val(),mod_2:$('#mod_2').val()},
            success:function(data)
            {
                if(data==0)
                {
                    $('#mod_1').val('');
                    $('#mod_2').val('');
                    krajeeDialog.alert('Model 1 & Model 2 not matching with ESE Maximum');
                    return false;
                }
            }
        });
    }
}

function validateMarkStatement()
{
  var batch = $('#stu_batch_id_selected').val();
  var degree = $('#stu_programme_selected').val();
  var month = $('#exam_month').val();
  var section = $('#stu_section_select').val();
  var semester = $('#markentry-semester').val();

  if(batch=='' && degree=='' && month=="" && section=="" && semester=="")
  {
    spinneroff();
    krajeeDialog.alert("Please Select the All Fields to generate the Mark Statement");

    return false;
  }
  else
  {
    krajeeDialog.alert("Please Wait....");
    return true;
  }
}
// Mark Statement Alignments


$('#deg_credit_type').on('change',function(){
    
    var img_src = "";  
    var cred_val = $('#deg_credit_type').val();
     var path = window.location.pathname;
      var pathName = path.substring(0, path.lastIndexOf('/') + 1);
      var source_url = window.location.protocol + "//" + window.location.host + pathName;
      
      if(cred_val=="CBCS")
      {
        img_src = $("#imagepreview").attr("src", pathName+"images/cbcs-pg.jpg");
        $('#imagemodal').modal('show');
      }
      else
      {
       
        img_src = $("#imagepreview").attr("src", pathName+"images/non-cbcs.jpg");
        $('#imagemodal').modal('show');
      }
     
    
});

$('#mark_semester').on('change',function(){

  var batch_map_id = $('#stu_programme_selected').val();
  var sem = $('#mark_semester').val();
  var type = $('#exam_type').val();
  

  $.ajax({
    url: base_url+'?r=ajaxrequest/getmarksubjectcode',
    type:'POST',
    data:{batch_map_id:batch_map_id,sem:sem,type:type},
        
    success:function(data)
    {
      var parsed = $.parseJSON(data);
      if(parsed!="")
      {
        $("#mark_subject_code").html('<option>---Select---</option>');
        $.each(parsed,function(i,parsed){
          $("#mark_subject_code").append("<option value="+parsed['coe_subjects_id']+">"+parsed['subject_code']+"</option>");
        });
      }
      else
      {
        krajeeDialog.alert("Please select the correct data");
        return false;
      } 
    }
  });
});

function getExternalEnggStudeList()
{
      var mark_sub_code = $('#mark_subject_code').val();
      var bat_map_id = $('#stu_programme_selected').val();
      //alert(mark_sub_code);
      $.ajax({
        url: base_url+'?r=ajaxrequest/getmarksubjecttype',
        type:'POST',
        data:{mark_sub_code:mark_sub_code,bat_map_id:bat_map_id},
            
        success:function(data)
        {
          if(data==0)
          {
            $('#hide_dum_sub_data').hide();
            $('#show_dummy_entry').hide();
            $('.select_model_type').hide();
          }
          else
          {
            $('.select_model_type').show();
          }
          $.ajax({
            url: base_url+'?r=ajaxrequest/getsubjectname',
            type:'POST',
              data:{sub_id:mark_sub_code},
              success:function(data)
              {
                $('#hide_dum_sub_data').show();
                $('#show_dummy_entry').show();
                    var jsonFormat = JSON.parse(data);
                    var body='';           
                    
                    var tr='<tr>';
                    var td='<td>1</td><td>'+jsonFormat['subject_code']+' </td><td>'+jsonFormat['subject_name']+' </td><td>'+jsonFormat['total_minimum_pass']+'</td><td>'+(jsonFormat['CIA_max']+jsonFormat['ESE_max'])+'</td>';
                    var tr_dum_close ='</tr>'; 
                    body = tr+td+tr_dum_close;
                    
                    $('#hide_dum_sub_data').show();                    
                    $('#show_dummy_entry').html(body);
              }
            }); 
        }
      });
}

/*$('#mark_subject_code').on('change',function(){

  var mark_sub_code = $('#mark_subject_code').val();
  var bat_map_id = $('#stu_programme_selected').val();
  //alert(mark_sub_code);
  $.ajax({
    url: base_url+'?r=ajaxrequest/getmarksubjecttype',
    type:'POST',
    data:{mark_sub_code:mark_sub_code,bat_map_id:bat_map_id},
        
    success:function(data)
    {
      if(data==0)
      {
        $('#hide_dum_sub_data').hide();
        $('#show_dummy_entry').hide();
        $('.select_model_type').hide();
      }
      else
      {
        $('.select_model_type').show();
      }
      $.ajax({
        url: base_url+'?r=ajaxrequest/getsubjectname',
        type:'POST',
          data:{sub_id:mark_sub_code},
          success:function(data)
          {
            alert('sai2');
            $('#hide_dum_sub_data').show();
            $('#show_dummy_entry').show();
                var jsonFormat = JSON.parse(data);
                var body='';           
                
                var tr='<tr>';
                var td='<td>1</td><td>'+jsonFormat['subject_code']+' </td><td>'+jsonFormat['subject_name']+' </td><td>'+jsonFormat['total_minimum_pass']+'</td><td>'+(jsonFormat['CIA_max']+jsonFormat['ESE_max'])+'</td>';
                var tr_dum_close ='</tr>'; 
                body = tr+td+tr_dum_close;
                
                $('#hide_dum_sub_data').show();                    
                $('#show_dummy_entry').html(body);
          }
        }); 
    }
  });
});*/

$('#select_mod_type').on('change',function(){
    var year = $('#mark_year').val();
    var batch_map_id = $('#stu_programme_selected').val();
    var month = $('#exam_month').val();
    var term = $('#exam_term').val();
    var type = $('#exam_type').val();
    var sub_id = $('#mark_subject_code').val();
    if($('#select_mod_type').val()=="With Model")
    {
        $.ajax({
            url: base_url+'?r=ajaxrequest/getmodtype',
            type:'POST',
            data:{year:year,batch_map_id:batch_map_id,month:month,type:type,term:term,sub_id:sub_id},
            success:function(data)
            {
                if(data==0)
                {
                    $('.mod_type1').show();
                    $('.mod_type2').show();
                    /*$('.mod_type1').attr('required','required');
                    $('.mod_type2').attr('required','required');*/
                }
                else
                {
                    $('.mod_type1').hide();
                    $('.mod_type2').hide();
                }
            } 
        });
    }
    else
    {
        $('.mod_type1').hide();
        $('.mod_type2').hide();
    }
});

$('#ese_btn').on('click',function(){
  var year = $('#mark_year').val();
  var batch = $('#stu_batch_id_selected').val(); 
  var batch_map_id = $('#stu_programme_selected').val();
  var month = $('#exam_month').val();
  var term = $('#exam_term').val();
  var type = $('#exam_type').val();
  var section = $('#stu_section_select').val();
  var sem = $('#mark_semester').val();
  var sub_code = $('#mark_subject_code').val();
  var year_label_name = $('label[for=mark_year]').text();
  var batch_label_name = $('label[for=stu_batch_id_selected]').text();

  var model_type = $('#select_mod_type').val();
  
  var mod_1 = $('#mod_1').val();
  var mod_2 = $('#mod_2').val();

    if(year=="" || batch=="" || batch_map_id=="" || month=="" || term=="" || type=="" || section=="" || sem=="" || sub_code=="")
    {
        krajeeDialog.alert("Please enter all fields");
        return false;
    }
    //if($('#select_mod_type').val()=="With Model")
    //{
       /* if(($('#mod_1').val())!="" || ($('#mod_2').val())!="")
        {
            krajeeDialog.alert("Please enter all fields");
            return false;
        }*/
    //}
    
  $.ajax({
    url: base_url+'?r=ajaxrequest/getcheckexamyearbatch',
    type:'POST',
    data:{global_batch_id:batch},
            
    success:function(data)
    {
      if(year>=data)
      {
        $.ajax({
          url: base_url+'?r=ajaxrequest/getesestudentlist',
          type:'POST',
          data:{year:year,batch:batch,batch_map_id:batch_map_id,month:month,term:term,type:type,section:section,sem:sem,sub_code:sub_code,model_type:model_type,mod_1:mod_1,mod_2:mod_2},
          success:function(data)
          {
            //alert(data);
            var parsed = $.parseJSON(data);

            if(parsed['table']=='No')
            {
                krajeeDialog.alert("Please Complete the Internal Mark Entry For the above selected fileds");
                $('.ese_mark_tbl').hide();
                return false;
            }
            else if(data!=0)
            {
              $('.ese_mark_tbl').show();
              $('#stu_mark_tbl').show();
              $('#stu_mark_tbl').html(parsed['table']);
              $("#stu_count1").attr("value",parsed['sn1']);
              $("#ese_max_mark").attr("value",parsed['ese_max']);
              $("#ese_min_mark").attr("value",parsed['ese_min']);
              $("#ese_total_mark").attr("value",parsed['ese_total']);

              $("#cat_model1_ese_val").attr("value",parsed['cat_model1_ese_val']);
              $("#cat_model2_ese_val").attr("value",parsed['cat_model2_ese_val']);
            }
            else
            {
              $('.ese_mark_tbl').hide();
              $('#stu_mark_tbl').hide();
              krajeeDialog.alert("No data found");
              return false;
            }
            var mark_txt1=$('.mark_txt1').val();

            if(mark_txt1!="")
            {
              $('.cia_mark_done_btn').hide();
              //$(".mark_txt2").prop('disabled', false);
            }
            else
            {
              $('.cia_mark_done_btn').show();
              //$(".mark_txt2").prop('disabled', true);
            }
            if(parsed['ese_max']==0 && parsed['cia_max']==0)
            {
                $('.cia_mark_done_btn').show();
            }
          }
        });
      }
      else
      {
        krajeeDialog.alert("Please select correct "+year_label_name+" or "+batch_label_name+".");
        return false;
      }
    }
  });
});

  function checkeseMax(stu_id)
  {
    var converted_mark;
    var ese_max_mark = parseInt($('#ese_max_mark').val());
    
    var ese_min_mark = parseInt($('#ese_min_mark').val());
    var ese_total_mark = parseInt($('#ese_total_mark').val());

    var stu_marks = parseInt($("#"+stu_id).val());
    var stu_id_splitted = stu_id.split("_");
    
    if(!isNaN(stu_marks))
    {

      if(ese_max_mark==0 && stu_marks>ese_max_mark)
      {
        krajeeDialog.alert("Marks entered wrongly... Please make sure to enter the correct marks");
        $("#"+stu_id).css("border","1px solid #F00");
        $("#"+stu_id).focus().val("");
        $('#result_'+stu_id_splitted[1]).val('');
        $('#total_'+stu_id_splitted[1]).val('');
        $('#converted_marks_'+stu_id_splitted[1]).val('');
        return false;
      }

      converted_mark = (stu_marks / 100) * ese_max_mark;
      
      if(converted_mark > ese_max_mark)
      {
        krajeeDialog.alert("Marks entered wrongly... Please make sure to enter the correct marks");
        $("#"+stu_id).css("border","1px solid #F00");
        $("#"+stu_id).focus().val("");
        return false;
      }
      else
      {
        //converted_mark = parseInt(converted_mark);
        converted_mark = Math.round(converted_mark);
        $("#"+stu_id).css("border","1px solid #139952");
        $('#converted_marks_'+stu_id_splitted[1]).val(converted_mark);
        var cia = parseInt($('#cia_'+stu_id_splitted[1]).val());
        var total = cia + converted_mark ;
        parseInt($('#total_'+stu_id_splitted[1]).val(total));
        if((converted_mark  >= ese_min_mark) && (total >= ese_total_mark))
        {
            $('#result_'+stu_id_splitted[1]).val("Pass");
        }
        else
        {   
            $('#result_'+stu_id_splitted[1]).val("Fail");
        }

        if($('#result_'+stu_id_splitted[1]).val()=="Pass")
        {
          $('#result_'+stu_id_splitted[1]).css('color','#000');
        }
        else
        {
          $('#result_'+stu_id_splitted[1]).css('color','#F00');
        }

      }
    }
  }

function convertfor100(stu_id)
{

  var stu_marks = 0;
  var ese_min = parseInt($('#ese_min_mark').val());
  var ese_max = parseInt($('#ese_max_mark').val());
  var total_min = $("#ese_total_mark").val();
  var cat_model1_ese_val = parseInt($('#'+stu_id).val());
  var ese_con = (cat_model1_ese_val*ese_max)/100;
  var convert_mark = Math.round(ese_con);
  var stu_id_split = stu_id.split("_");
  if(convert_mark > cat_model1_ese_val || cat_model1_ese_val>100)
  {
    krajeeDialog.alert("Marks entered wrongly... Please make sure to enter the correct marks");
    $("#"+stu_id).css("border","1px solid #F00");
    $("#"+stu_id).focus().val("");
    return false;
  }
  else
  {
    $("#"+stu_id).css("border","1px solid #139952");
    parseInt($("#"+stu_id).val(cat_model1_ese_val));
    $('#converted_marks_'+stu_id_split[1]).val(convert_mark);
    $("#total_"+stu_id_split[1]).val(convert_mark);
    if((convert_mark  >= ese_min))
    {
        $('#result_'+stu_id_split[1]).val("Pass");
    }
    else
    {   
        $('#result_'+stu_id_split[1]).val('Fail');
    }
  }
}

function checkesemax1(stu_id)
{
  var stu_marks = parseInt($("#"+stu_id).val());
  
  var cat_model1_ese_val = parseInt($('#cat_model1_ese_val').val());

  var convert_mark = (stu_marks / 100) * cat_model1_ese_val;
  var stu_id_split = stu_id.split("_");
  if(convert_mark > cat_model1_ese_val)
  {
    krajeeDialog.alert("Marks entered wrongly... Please make sure to enter the correct marks");
    $("#"+stu_id).css("border","1px solid #F00");
    $("#"+stu_id).focus().val("");
    return false;
  }
  else
  {
    $("#"+stu_id).css("border","1px solid #139952");
    parseInt($("#"+stu_id).val(stu_marks));
    $('#mark15_'+stu_id_split[1]).val(Math.round(convert_mark));
  }
}

function checkesemax2(stu_id)
{
  var stu_marks = parseInt($("#"+stu_id).val());
  var cat_model2_ese_val = parseInt($('#cat_model2_ese_val').val());
  var convert_mark = (stu_marks / 100) * cat_model2_ese_val;
  var stu_id_splitted = stu_id.split("_");
  if(convert_mark > cat_model2_ese_val)
  {
    krajeeDialog.alert("Marks entered wrongly... Please make sure to enter the correct marks");
    $("#"+stu_id).css("border","1px solid #F00");
    $("#"+stu_id).focus().val("");
    return false;
  }
  else
  {
    $("#"+stu_id).css("border","1px solid #139952");
    if($('#mark_'+stu_id_splitted[1]).val()!="" && $('#mark1_'+stu_id_splitted[1]).val()!="")
    {
      parseInt($("#"+stu_id).val(stu_marks));
      $('#mark25_'+stu_id_splitted[1]).val(Math.round(convert_mark));

      var ese_15 = parseInt($('#mark15_'+stu_id_splitted[1]).val());

      var ese_25 = parseInt($('#mark25_'+stu_id_splitted[1]).val());

      var ese = parseInt(ese_15)+parseInt(ese_25);
      
      $('#converted_mark_'+stu_id_splitted[1]).val(ese);

      var cia = $('#cia_'+stu_id_splitted[1]).val();
      var total = parseInt(cia + ese);
      $('#total_'+stu_id_splitted[1]).val(total);

      var ese_min = $("#ese_min_mark").val();
      var total_min = $("#ese_total_mark").val();

      if((ese  >= ese_min) && (total >= total_min))
      {
          $('#result_'+stu_id_splitted[1]).val("Pass");
      }
      else
      {   
          $('#result_'+stu_id_splitted[1]).val("Fail");
      }
    }
    else
    {
      krajeeDialog.alert("Please enter the mark");
      return false;
    }
  }
}     

/* External mark entry ends here */

/*Moderation starts here */
$('#mod_exam_type').on('change',function(){
    var bat_map_id = $('#stu_programme_selected').val();
    var year = $('#mark_year').val();
    var month = $('#exam_month').val();
    var type = $('#mod_exam_type').val();
    
    $.ajax({
        url: base_url+'?r=ajaxrequest/getmodsubjectcode',
        type:'POST',
        data:{year:year,bat_map_id:bat_map_id,month:month,type:type},
        success:function(data){
            var parsed = $.parseJSON(data);
            $.each(parsed,function(i,parsed){
                $("#mark_subject_code").append("<option value="+parsed['coe_subjects_id']+">"+parsed['subject_code']+"</option>");
            });
        } 
    });
});

function getListSubjects(batch_mapping_id,year,month,subject_mapp_drop)
{
    $.ajax({
        url: base_url+'?r=ajaxrequest/yearexamsubjectsdata',
        type:'POST',
        data:{year:year,bat_map_id:batch_mapping_id,month:month},
        success:function(data){
            var parsed = $.parseJSON(data);
            if(parsed=='NO')
            {
                krajeeDialog.alert('NO DATA FOUND');
                return false;
            }
            else
            {
                $("#"+subject_mapp_drop).html('');
                $("#"+subject_mapp_drop).append("<option value='' >------SELECT----</option>");
                $.each(parsed,function(i,parsed)
                {
                    $("#"+subject_mapp_drop).append("<option value="+parsed['coe_subjects_id']+">"+parsed['subject_code']+"</option>");
                });
            }
            
        } 
    });
}

$('#filter_type').on('change',function()
{
  var filter_name = $('#filter_type').val();
  
  if(filter_name == 'Register Number')
  {
    
    $('#mark_from').val("");
    $('#mark_to').val("");
    $('.reg_from').show();
    $('.mrk_frm').hide();
    $('.mrk_to').hide();
    $('#stu_mod_tbl').hide();
    $('.mod_done_btn').hide();
    $("#reg_range_from").show();
    $("#reg_range_to").show();  
    
  }
  else if(filter_name=='Mark')
  {
    $("#reg_range_from").html('');
    $("#reg_range_to").html('');    
    $('.mrk_frm').show();
    $('.mrk_to').show();
    $('.reg_from').hide();
    $('#stu_mod_tbl').hide();
    $('.mod_done_btn').hide();

  }
  else
  {
    $("#reg_range_from").html('');
    $("#reg_range_to").html('');    
    $('.mrk_frm').hide();
    $('.mrk_frm').html();
    $('.mrk_to').hide();
    $('.mrk_to').hide();
    $('.reg_from').hide();
    $('#stu_mod_tbl').hide();
    $('.mod_done_btn').hide();
       
  }  
  
});


$('#moderation_btn').on('click',function()
{
  var exam_year = $('#exam_year').val();   
  var exam_month = $('#exam_month').val();
  var batch_id = $('#stu_batch_id_selected').val();
  var dept_id = $('#stu_programme_selected').val();
  var moderation_marks = $('#max_moderation_marks').val();
 
  if(batch_id=='' || dept_id=='')
  {
    krajeeDialog.alert("Select Required Fields");  
    return false;
  }
   $.ajax({
    url: base_url+'?r=ajaxrequest/getmoderationlist',
    type:'POST',
    data:{exam_year:exam_year,exam_month:exam_month,batch_map_id:dept_id,moderation_marks:moderation_marks},
    success:function(data)
    {
      if(data==0)
      {
        $('#stu_mod_tbl').hide();
        $('.mod_done_btn').hide();
        krajeeDialog.alert("No Data Found / Fields Not Selection Wrong");        
      }
      else if(data==1)
      {
        $('#stu_mod_tbl').hide();
        $('.mod_done_btn').hide();
        krajeeDialog.alert("Result Published");
      }
      else
      {
        $('#stu_mod_tbl').show();
        $('#stu_mod_tbl').html(data);
        $('.mod_done_btn').show();   
      }             
      
    }
  });
  
});


$('#mod_type').on('change',function(){
  var mod_type_value = $('#mod_type').val();
  if (mod_type_value == "Department Wise") {
    $('.hide_subject').hide();
    $('.hide_dept').show();
  }else{
    $('.hide_subject').show();
    $('.hide_dept').hide();
  }
});

$('#view_mod_mark_year').on('blur',function(){
  
  var view_mod_year = $('#view_mod_mark_year').val();

  $.ajax({
    url: base_url+'?r=ajaxrequest/viewmodmonth',
    type:'POST',
    data:{view_mod_year:view_mod_year},
    success:function(data){
      $('#view_mod_month').html('<option>---Select---</option>');
      var parsed = $.parseJSON(data);
      $.each(parsed,function(i,parsed){
        $('#view_mod_month').append("<option value="+parsed['month']+">"+parsed['description']+"</option>");
      });
    }
  });
});

$('#view_mod_month').on('change',function(){

  var view_mod_year = $('#view_mod_mark_year').val();
  var view_mod_month = $('#view_mod_month').val();

  $.ajax({
    url: base_url+'?r=ajaxrequest/viewmodtype',
    type:'POST',
    data:{view_mod_year:view_mod_year,view_mod_month:view_mod_month},
    success:function(data){
      $('#view_mod_exam_type').html('<option>---Select---</option>');
      var parsed = $.parseJSON(data);
      $.each(parsed,function(i,parsed){
        $('#view_mod_exam_type').append("<option value="+parsed['mark_type']+">"+parsed['description']+"</option>");
      });
    }
  });
});

$('#mod_type').on('change',function(){

  var view_mod_year = $('#view_mod_mark_year').val();
  var view_mod_month = $('#view_mod_month').val();
  var view_mod_type = $('#view_mod_exam_type').val();
  var filter_value = $('#mod_type').val();

  $.ajax({
    url: base_url+'?r=ajaxrequest/viewmodsubjectcode',
    type:'POST',
    data:{view_mod_year:view_mod_year,view_mod_month:view_mod_month,view_mod_type:view_mod_type,filter_value:filter_value},
    success:function(data){
       
      if(filter_value == "Department Wise"){
        $('#view_mod_mark_dept').html('<option>---Select---</option>');
        var parsed = $.parseJSON(data);
         $.each(parsed,function(i,parsed){
          $('#view_mod_mark_dept').append("<option value="+parsed['batch_mapping_id']+">"+parsed['programme']+"</option>");
         });
      }else{
        $('#view_mod_mark_subject_code').html('<option>---Select---</option>');
        var parsed = $.parseJSON(data);
        $.each(parsed,function(i,parsed){
        $('#view_mod_mark_subject_code').append("<option value="+parsed['subject_map_id']+">"+parsed['subject_code']+"</option>");
      });
      }
      
    }
  });
});

function changeLableModeration(id)
{
  //var check = document.getElementById(id).checked;
  var stu_id_array = id.split("_");
  var check=$("input[name=mod"+stu_id_array[1]+"]:checked").val();
  if(check=='YES'  && check!='undefined' )
  {
      $('#result_'+stu_id_array[1]).html("<b style='color: #00a65a;' >Pass</b>");
  }
  else
  {
      $('#result_'+stu_id_array[1]).html("<b style='color: #000;' >Fail</b>");
  } 
    
}
/*Moderation ends here */

/*With Held Starts Here */

$('#exam_month').on('change',function(){
    $('#withheld_stu_reg_num').html('<option>---Select Register Number---</option>');
    var batch_map_id = $('#stu_programme_selected').val();

    $.ajax({
    url: base_url+'?r=ajaxrequest/withheldregnum',
    type:'POST',
    data:{batch_map_id:batch_map_id},
    success:function(data){    
      var parsed = $.parseJSON(data);
      $.each(parsed,function(i,parsed){
        $('#withheld_stu_reg_num').append("<option value="+parsed['coe_student_mapping_id']+">"+parsed['register_number']+"</option>");
      });
    }
  });
});

$('#withheld_btn').on('click',function(){
  var year = $('#mark_year').val();
  var month = $('#exam_month').val();
  var stu_map_id = $('#withheld_stu_reg_num').val();
  var bat_map_id = $('#stu_programme_selected').val();

    if(year=='' || month=='' || stu_map_id=='' || bat_map_id=='')
    {
        krajeeDialog.alert("Please Select Required feilds");
        return false;
    }

  $.ajax({
    url: base_url+'?r=ajaxrequest/withheldstumarks',
    type:'POST',
    data:{year:year,month:month,stu_map_id:stu_map_id,bat_map_id:bat_map_id},
    success:function(data)
    {     
       /**if(data==1)
        {
            krajeeDialog.alert("Result Published");
        }
        else if(data==0)**/
        if(data==0)
        {
            $('.tbl_n_submit_withheld').hide();
            krajeeDialog.alert("No data found");
        }
        else      
        {
            $('.tbl_n_submit_withheld').show();
            $('#stu_withheld_tbl').html('');
            $('#stu_withheld_tbl').html(data);
            //alert(data);
        } 
    }
  });
});

function withheld_check(id){
  var no = id.substr(id.lastIndexOf('_') + 1);
  var check = "withheld"+no;
  //alert(check);
  if($("input[name='"+check+"']:checked").val())
  {
    $('#remarks'+no).attr("required",'true');
    $('#remarks'+no).attr("disabled",false);
  }else{
    $('#remarks'+no).val('');
    $('#remarks'+no).attr("required",'false');
    $('#remarks'+no).attr("disabled",true);
  }  
}

/*Withheld Ends Here */


/* Reports Section Starts Here */

function validateHallTicket()
{
  var batch = $('#stu_batch_id_selected').val();
  var degree = $('#stu_programme_selected').val();
  var month = $('#exam_month').val();
  var section = $('#stu_section_select').val();

  if(batch=='' && degree=='' && month=="" && section=="")
  {
    spinneroff();
    krajeeDialog.alert("Please Select the All Fields to proceed.....");

    return false;
  }
  else
  {
    krajeeDialog.alert("Please Wait....");
    return true;
  }
}

function getSubjects(exam_type_value)
{
    var exam_year = $('#mark_year').val();
    var bat_map_id = $('#stu_programme_selected').val();
    var month = $('#exam_month').val();

    $.ajax({
    url: base_url+'?r=ajaxrequest/getlistofsubjects',
    type:'POST',
    data:{exam_year:exam_year,month:month,bat_map_id:bat_map_id},
    success:function(data){  
      if(data!=0)
      {
        $('#subject_code').html('');
        $('#subject_code').html(data);
      } 
      else
      {
        alert("No data found");
      }     
    }
  });
}
/* Reports Section Ends Here */

/*Revaluation Starts Here */

$('#revaluationentry_btn').on('click',function(){
    var year = $('#reval_entry_year').val();
    var month = $('#reval_entry_month').val();
    var stu_reg_num = $('#stu_reg_num').val();
    //alert(stu_reg_num);
    if(year=="" || month=="" || stu_reg_num=="")
    {
        krajeeDialog.alert("Please select all feilds");
        return false;
    }
     else
    {
        $.ajax({
            url: base_url+'?r=mark-entry/revaluationentrysub',
            type:'POST',
            data:{year:year,month:month,stu_reg_num:stu_reg_num},
            success:function(data)
            {     
                //alert(data);
                if(data=="status")
                {
                    krajeeDialog.alert("Revaluation Date Already Passed");
                    $('.revaluationentry').hide();
                    $('.reval_pdf_button').hide();
                }
                else if(data==1)
                {
                    krajeeDialog.alert("Internet copy should publish before the Entry!!");
                    $('.revaluationentry').hide();
                    return false;
                }
                else if(data==0)
                {
                    krajeeDialog.alert("No data found");
                    $('.revaluationentry').hide();
                } 
                else
                {
                    var parsed = $.parseJSON(data);
                    if(parsed['result']==50)
                    {
                        //alert("fhdfhgfh");
                        $('.reval_pdf_button').show();
                        $('.revaluationentry').show();
                        $('#stu_revaluation_entry_tbl').html(parsed['table']);
                        $('.revaluation_entry_done_btn').hide();
                    }
                    else
                    {
                        $('.reval_pdf_button').hide();
                        $('.revaluationentry').show();
                        $('#stu_revaluation_entry_tbl').html(parsed['table']);
                        $('.revaluation_entry_done_btn').show();
                    }            
                }       
            }
        });
    }
});

function getstudentresults() {
    var year = $('#markentry-year').val();
    var month = $('#exam_month').val();
    if(year=='' || month=='')
    {
        krajeeDialog.alert("Select Required Fields");
    }
    else
    {
        $.ajax({
          url: base_url+'?r=ajaxrequest/getstudentresultexport',
          type:'POST',
          data:{year:year,month:month},
          success:function(data){     
            if(data==0)
            {
                krajeeDialog.alert("No data found");
                   $('#display_results_stu').hide();
                   $('#assign_stu_res').html('');
                  return false;
              
            } 
            else
            {
               $('#display_results_stu').show();
               $('#assign_stu_res').html(data);
            }     
          }
        });
    }
}
function getstudentgraderesults() {
    var year = $('#markentry-year').val();
    var month = $('#exam_month').val();
    if(year=='' || month=='')
    {
        krajeeDialog.alert("Select Required Fields");
    }
    else
    {
        $.ajax({
          url: base_url+'?r=ajaxrequest/getstudentgraderesults',
          type:'POST',
          data:{year:year,month:month},
          success:function(data){     
            if(data==0)
            {
                krajeeDialog.alert("No data found");
                   $('#display_results_stu').hide();
                   $('#assign_stu_res').html('');
                  return false;
              
            } 
            else
            {
               $('#display_results_stu').show();
               $('#assign_stu_res').html(data);
            }     
          }
        });
    }
    // body...
}

function revaluationentry_check(id)
{
  var stu_id_array = id.split("_");
  var check_count = $('.transparency:checked').length;
  var sn = $('#sn_'+stu_id_array[1]).val();
  var max_reval = $('#max_reval_papers').val();
  if(check_count > max_reval)
  {
    krajeeDialog.alert("Selection Reached Maximum");
    $('#transparency_'+sn).prop('checked', false);
    return false;
  }
    var amount = 0;
    var check_box = document.getElementsByClassName("transparency");
    var amt = document.getElementsByClassName("fee");
    for (var i = 0; amt[i]; ++i) 
    {
        if (check_box[i].checked) 
        {
            amount += Number(amt[i].value);
        }
    }
    $('#total').val(amount);
}

function revaluationentry_checked(id)
{
  var stu_id_array = id.split("_");
  var check_count = $('.revaluation_column:checked').length;
  var sn = $('#sn_'+stu_id_array[1]).val();
    var amount1 = 0;
    var check_box1 = document.getElementsByClassName("revaluation_column");
    var amt1 = document.getElementsByClassName("reval_fee");
    for (var i = 0; amt1[i]; ++i) 
    {
        if (check_box1[i].checked) 
        {
            amount1 += Number(amt1[i].value);
        }
    }
    $('#total').val(amount1);
}


$('#revaluation_btn').on('click',function(){
  var year = $('#reval_entry_year').val();
  var month = $('#reval_entry_month').val();
  var stu_map_id = $('#stu_reg_num').val();
  var mark_out_of = $('#mark_out_of').val();
  
  if(year=="" || month=="" || stu_map_id=="")
  {
    krajeeDialog.alert("Please select all feilds");
    return false;
  }
  else if(mark_out_of=="")
  {
    krajeeDialog.alert("Must Select Marks Out Of");
    return false;
  }
  else
  {
    $.ajax({
      url: base_url+'?r=mark-entry/revaluationstumarks',
      type:'POST',
      data:{year:year,month:month,stu_map_id:stu_map_id,mark_out_of:mark_out_of},
      success:function(data){     

        if(data!=0)
        {
          $('.tbl_n_submit_revaluation').show();
          $('#stu_revaluation_tbl').html(data);
        } 
        else
        {
          krajeeDialog.alert("No data found");
          $('.tbl_n_submit_revaluation').hide();
          return false;
        }     
      }
    });
  }
});

function revaluation_check(id)
{
  var stu_id_array = id.split("_");
  var check=$("input[name=revaluation"+stu_id_array[1]+"]:checked").val();
  var check_count = $('.revaluation:checked').length;
  var sn = $('#sn_'+stu_id_array[1]).val();
  
  if(check_count > 5)
  {
    krajeeDialog.alert("Revaluation not allowed for above 5 subjects");
    $('#revaluation_'+sn).prop('checked', false);
    return false;
  }
  
  if(check)
  {
    $('#newese100_'+stu_id_array[1]).attr('required');
    $('#newese100_'+stu_id_array[1]).removeAttr("disabled");
    return false;
  }
  else
  {
      $('#newese100_'+stu_id_array[1]).val('');
      $('#newese_'+stu_id_array[1]).val('');
      $('#newtotal_'+stu_id_array[1]).val('');
      $('#newresult_'+stu_id_array[1]).val('');
      $('#newese100_'+stu_id_array[1]).attr("disabled","true");
  } 
}

function revaluation_check1(id)
{
  var stu_id_array = id.split("_");
  var check=$("input[name=revaluation1"+stu_id_array[1]+"]:checked").val();
  if(!check)
  {
    if(confirm("Please confirm to delete revaluation mark?"))
    {
      $('#newese100_'+stu_id_array[1]).val('');
      $('#newese_'+stu_id_array[1]).val('');
      $('#newtotal_'+stu_id_array[1]).val('');
      $('#newresult_'+stu_id_array[1]).val('');
    }
    else
    {
      $('#revaluation1_'+stu_id_array[1]).prop('checked', true);
      return false;
    }
  }
}
function revaluation_esereg(id)
{
  var stu_id_array = id.split("_");

  var ese_min = parseInt($('#esemin_'+stu_id_array[1]).val());
  var ese_max = parseInt($('#esemax_'+stu_id_array[1]).val());
  var total_min = parseInt($('#totalmin_'+stu_id_array[1]).val());
  var newese100 = parseInt($('#newese100_'+stu_id_array[1]).val());

  var newese_val = ((newese100/100)*ese_max);
  if(newese100 > 100)
  {
    krajeeDialog.alert("Marks entered wrongly... Please make sure to enter the correct marks");
    $('#newese100_'+stu_id_array[1]).css("border","1px solid #F00");
    $('#newese100_'+stu_id_array[1]).val('');
    $('#newese_'+stu_id_array[1]).val('');
    $('#newtotal_'+stu_id_array[1]).val('');
    $('#newresult_'+stu_id_array[1]).val('');
    return false;
  }
  else
  {
    $('#newese100_'+stu_id_array[1]).css("border","1px solid #139952");
    $('#newese_'+stu_id_array[1]).val(Math.round(newese_val));

  }
  
  var newese = parseInt(Math.round(newese_val));
  var cia = parseInt($('#cia_'+stu_id_array[1]).val());
  var oldese = parseInt($('#oldese_'+stu_id_array[1]).val());
  var oldtotal = parseInt($('#oldtotal_'+stu_id_array[1]).val());
  var oldresult = $('#oldresult_'+stu_id_array[1]).val();
  var newtotal = parseInt(parseInt(newese) + parseInt(cia));
  $('#newtotal_'+stu_id_array[1]).val(newtotal);
  
    if(newese>=ese_min && newtotal>=total_min)
    {
      $('#newresult_'+stu_id_array[1]).val('Pass');
      
    }
    else
    {
      $('#newresult_'+stu_id_array[1]).val('Fail');
    }
}
function revaluation_eseregMax(id)
{
  var stu_id_array = id.split("_");

  var ese_min = parseInt($('#esemin_'+stu_id_array[1]).val());
  var ese_max = parseInt($('#esemax_'+stu_id_array[1]).val());
  var total_min = parseInt($('#totalmin_'+stu_id_array[1]).val());
  var newese100 = parseInt($('#newese100_'+stu_id_array[1]).val());

  var newese_val = newese100;
  if(newese100 > ese_max)
  {
    krajeeDialog.alert("Marks entered wrongly... Please make sure to enter the correct marks and below "+ese_max);
    $('#newese100_'+stu_id_array[1]).css("border","1px solid #F00");
    $('#newese100_'+stu_id_array[1]).val('');
    $('#newese_'+stu_id_array[1]).val('');
    $('#newtotal_'+stu_id_array[1]).val('');
    $('#newresult_'+stu_id_array[1]).val('');
    return false;
  }
  else
  {
    $('#newese100_'+stu_id_array[1]).css("border","1px solid #139952");
    $('#newese_'+stu_id_array[1]).val(Math.round(newese_val));
  }
  
  var newese = parseInt(Math.round(newese_val));
  var cia = parseInt($('#cia_'+stu_id_array[1]).val());
  var oldese = parseInt($('#oldese_'+stu_id_array[1]).val());
  var oldtotal = parseInt($('#oldtotal_'+stu_id_array[1]).val());
  var oldresult = $('#oldresult_'+stu_id_array[1]).val();
  var newtotal = parseInt(parseInt(newese) + parseInt(cia));
  $('#newtotal_'+stu_id_array[1]).val(newtotal);
  
    if(newese>=ese_min && newtotal>=total_min)
    {
      $('#newresult_'+stu_id_array[1]).val('Pass');
      
    }
    else
    {
      $('#newresult_'+stu_id_array[1]).val('Fail');
    }
}
function revaluation_ese(id)
{
  var stu_id_array = id.split("_");

  var ese_min = parseInt($('#esemin_'+stu_id_array[1]).val());
  var ese_max = parseInt($('#esemax_'+stu_id_array[1]).val());
  var total_min = parseInt($('#totalmin_'+stu_id_array[1]).val());
  var newese100 = parseInt($('#newese100_'+stu_id_array[1]).val());

  var newese_val = ((newese100/100)*ese_max);
  if(newese_val > ese_max)
  {
    krajeeDialog.alert("Marks entered wrongly... Please make sure to enter the correct marks");
    $('#newese100_'+stu_id_array[1]).css("border","1px solid #F00");
    $('#newese100_'+stu_id_array[1]).val('');
    return false;
  }
  else
  {
    $('#newese100_'+stu_id_array[1]).css("border","1px solid #139952");
    $('#newese_'+stu_id_array[1]).val(Math.round(newese_val));
  }
  
  var newese = parseInt($('#newese_'+stu_id_array[1]).val());

  var cia = parseInt($('#cia_'+stu_id_array[1]).val());
  var oldese = parseInt($('#oldese_'+stu_id_array[1]).val());
  var oldtotal = parseInt($('#oldtotal_'+stu_id_array[1]).val());
  var oldresult = $('#oldresult_'+stu_id_array[1]).val();

  if(oldese >= newese)
  {
    $('#newtotal_'+stu_id_array[1]).val(oldtotal);
    $('#newresult_'+stu_id_array[1]).val(oldresult);
  }
  else
  {
    var total = parseInt(newese + cia);
    var newtotal = parseInt($('#newtotal_'+stu_id_array[1]).val(total));
    if(newese>=ese_min && newtotal>=total_min)
    {
      $('#newresult_'+stu_id_array[1]).val('Pass');
    }
    else
    {
      $('#newresult_'+stu_id_array[1]).val('Fail');
    }
  }
}

$('#reval_markentry_btn').on('click',function(){
    var year = $('#reval_entry_year').val();
    var month = $('#reval_entry_month').val();
    var dummy_number = $('#dummy_num').val();

    if(year=="" || month=="" || dummy_number=="")
    {
        krajeeDialog.alert("Please select all feilds");
        return false;
    }
    else
    {
        $.ajax({
            url: base_url+'?r=mark-entry/revaluationmarkentryview',
            type:'POST',
            data:{year:year,month:month,dummy_number:dummy_number},
            success:function(data)
            {     
                
                if(data=="status")
                {
                    krajeeDialog.alert("Result Published");
                    $('.revaluationmarkentry').hide();
                    return false;
                }
                else if(data!=0)
                {
                    $('.revaluationmarkentry').show();
                    $('#revaluation_mark_entry_tbl').html(data);
                } 
                else
                {
                    krajeeDialog.alert("No data found");
                    $('.revaluationmarkentry').hide();
                    return false;
                }     
            }
        });
    }
    var check = $("input[name=revaluation]:checked").val();
    if(check!="")
    {
        $('#revaluation_entry_btn').show();
    }
});


function reval_mark_check(id)
{
  var check = $("input[name=revaluation]:checked").val();

  if(check)
  {
    $('#newese100').attr('required');
    $('#newese100').removeAttr("disabled");
    return false;
  }
  else
  {
      $('#newese100').val('');
      $('#newese').val('');
      $('#newtotal').val('');
      $('#newresult').val('');
      $('#newgrade').val('');
      $('#newese100').attr("disabled","true");
  } 
}


function reval_mark_ese(id)
{
  var ese_min = parseInt($('#esemin').val());
  var ese_max = parseInt($('#esemax').val());
  var total_min = parseInt($('#totalmin').val());
  var sub_map_id = $('#sub_map_id_val').val();

  var newese100 = parseInt($('#newese100').val());
  var newese_val = ((newese100/100)*ese_max);

  var newese_val = ((newese100/100)*ese_max);
  if(newese_val > ese_max)
  {
    krajeeDialog.alert("Marks entered wrongly... Please make sure to enter the correct marks");
    $('#newese100').css("border","1px solid #F00");
    $('#newese100').val('');
    return false;
  }
  else
  {
    $('#newese100').css("border","1px solid #139952");
    $('#newese').val(Math.round(newese_val));
  }
  
  var newese = parseInt($('#newese').val());

  var cia = parseInt($('#cia').val());
  var oldese = parseInt($('#oldese').val());
  var oldtotal = parseInt($('#oldtotal').val());
  var oldresult = $('#oldresult').val();

    var total = parseInt(newese + cia);    
    var newtotal = parseInt($('#newtotal').val(total));

    if(newese>=ese_min || newtotal>=total_min)
    {
      $('#newresult').val('Pass');
    }
    else
    {
      $('#newresult').val('Fail');
    }
    //alert(total+"--"+oldtotal);
    var year = $('#reval_entry_year').val();
    var month = $('#reval_entry_month').val();
    var dummy_number = $('#dummy_num').val();

    $.ajax({
        url: base_url+'?r=mark-entry/revaluationmarkentrygrade',
        type:'POST',
        data:{year:year,month:month,cia:cia,newese:newese100,oldese:oldese,sub_map_id:sub_map_id,dummy_number:dummy_number,total:total,oldtotal:oldtotal},
        success:function(data)
        {
            $('#newgrade').val('');
            $('#newgrade').val(data);
        }
    });  


}
/*Revaluation Ends Here */
/* Course Result Analysis Starts Here*/
$('#pgmanalysisbutton').on('click',function(){

  var batch = $('#stu_batch_id_selected').val();
  var batch_map_id = $('#stu_programme_selected').val();
  var section = $('#stu_section_select').val();
  var year = $('#course_year').val();
  var month = $('#exam_month').val();
  var mark_type = $('#exam_type').val();
  //alert(batch+"--"+batch_map_id+"--"+year+"--"+month+"--"+mark_type);
  $.ajax({
    url: base_url+'?r=mark-entry/programmeresultanalysis',
    type:'POST',
    data:{year:year,month:month,batch_map_id:batch_map_id,batch:batch,mark_type:mark_type,section:section},
    success:function(data){
      if(data==0){
        $('.pgm_analysis_print_btn').hide();
        $('#programme_result').hide();        
        krajeeDialog.alert("No Data available");        
      }else{
         $('.pgm_analysis_print_btn').show();
        $('#programme_result').show();
        $('#programme_result').html(data);
      }      
    } 
  }); 
});
$('#pgmanalysisbutton_marks').on('click',function(){

  var batch = $('#stu_batch_id_selected').val();
  var batch_map_id = $('#stu_programme_selected').val();
  var year = $('#course_year').val();
  var month = $('#exam_month').val();
  var mark_type = $('#exam_type').val();
  //alert(batch+"--"+batch_map_id+"--"+year+"--"+month+"--"+mark_type);
  $.ajax({
    url: base_url+'?r=mark-entry-master/programmeresultanalysis',
    type:'POST',
    data:{year:year,month:month,batch_map_id:batch_map_id,batch:batch,mark_type:mark_type},
    success:function(data){
        $('#waiting').hide(); 
      if(data==0){
        $('.pgm_analysis_print_btn').hide();
        $('#programme_result').hide();        
        krajeeDialog.alert("No Data available");        
      }else{
         $('.pgm_analysis_print_btn').show();
        $('#programme_result').show();
        $('#programme_result').html(data);
      }      
    } 
  }); 
});




/* Course Result Analysis Ends Here*/


$('#pgmanalysisbutton_marks_value').on('click',function(){

  var batch = $('#stu_batch_id_selected').val();
  var batch_map_id = $('#stu_programme_selected').val();
  var year = $('#course_year').val();
  var month = $('#exam_month').val();
  var mark_type = $('#exam_type').val();
  //alert(batch+"--"+batch_map_id+"--"+year+"--"+month+"--"+mark_type);
  $.ajax({
    url: base_url+'?r=coe-value-mark-entry/valueadded-programmeresultanalysis',
    type:'POST',
    data:{year:year,month:month,batch_map_id:batch_map_id,batch:batch,mark_type:mark_type},
    success:function(data){
        $('#waiting').hide(); 
      if(data==0){
        $('.pgm_analysis_print_btn').hide();
        $('#programme_result').hide();        
        krajeeDialog.alert("No Data available");        
      }else{
         $('.pgm_analysis_print_btn').show();
        $('#programme_result').show();
        $('#programme_result').html(data);
      }      
    } 
  }); 
});



/* Subject Information start */

$('#sub_info').on('click',function(){
  
  var batch = $('#stu_batch_id_selected').val();
  

  if( batch=="")
  {
    krajeeDialog.alert("Please select all feilds");
    return false;
  }
  else
  {
    $.ajax({
      url: base_url+'?r=mark-entry/subjectinformationdata',
      type:'POST',
      data:{batch:batch},
      success:function(data)
      {
        if(data!=0)
        {
          //alert(data);
          $('.subject_information_tbl').show();
          $('#sub_info_tbl').html(data);
        }
        else
        {
          krajeeDialog.alert('No data found..');
          $('.subject_information_tbl').hide();
          return false;
        }
        
      } 
    });
  }
});

/* Subject Information end */
$('#subjectsmapping-course_type_id').on('change',(function(){
    var batch_mapping_id = $('#stu_programme_selected').val();
    $.ajax({
        url: base_url+'?r=ajaxrequest/getpapernumber',
        type:'POST',
          data:{batch_mapping_id:batch_mapping_id},
          success:function(data){
            $('#subjectsmapping-paper_no').val(parseInt(data)+1)
          }
    });
}));
/* Subject Information end */

/*Subject result analysis Starts Here */
$('#exam_type').on('change',function(){
    $('#mark_subject_code').html("<option>---Select---</option>");
    var batch_id = $('#stu_batch_id_selected').val();
    var year = $('#year').val();
    var month = $('#exam_month').val();
    var mark_type = $('#exam_type').val();
    //alert(batch_id+"---"+year+"---"+month+"---"+mark_type);
    $.ajax({
      url: base_url+'?r=mark-entry/crseanalysissubcode',
      type:'POST',
      data:{year:year,month:month,batch_id:batch_id,mark_type:mark_type},
      success:function(data){
        var parsed = $.parseJSON(data);
        $.each(parsed,function(i,parsed){
          $('#mark_subject_code').append("<option value="+parsed['coe_subjects_id']+">"+parsed['subject_code']+"</option>");
        });
      }
    });
});

$('#crseanalysisbutton').on('click',function(){
  var year = $('#year').val();
  var month = $('#exam_month').val();
  var batch = $('#stu_batch_id_selected').val(); 
  var mark_type = $('#exam_type').val();
  var sub_id = $('#mark_subject_code').val();
  //alert(year+"--"+month+"---"+mark_type+"--"+sub_id);
  $.ajax({
    url: base_url+'?r=mark-entry/courseresultanalysis',
    type:'POST',
    data:{batch:batch,year:year,month:month,sub_id:sub_id,mark_type:mark_type},
    success:function(data){
      if(data==0){
      $('.crse_analysis_print_btn').hide();
      $('#course_result').hide();        
      krajeeDialog.alert("No Data available");        
      }else{
       $('.crse_analysis_print_btn').show();
      $('#course_result').show();
      $('#course_result').html(data);
      }      
    } 
  }); 
});

$('#crseanalysisbutton_marks').on('click',function(){
  var year = $('#year').val();
  var month = $('#exam_month').val();
  var batch = $('#stu_batch_id_selected').val(); 
  var mark_type = $('#exam_type').val();
  var sub_id = $('#mark_subject_code').val();
  //alert(year+"--"+month+"---"+mark_type+"--"+sub_id);
  $.ajax({
    url: base_url+'?r=mark-entry-master/courseresultanalysis',
    type:'POST',
    data:{batch:batch,year:year,month:month,sub_id:sub_id,mark_type:mark_type},
    success:function(data){
      if(data==0){
      $('.crse_analysis_print_btn').hide();
      $('#course_result').hide();        
      krajeeDialog.alert("No Data available");        
      }else{
       $('.crse_analysis_print_btn').show();
      $('#course_result').show();
      $('#course_result').html(data);
      }      
    } 
  }); 
});

/*Subject result analysis Ends Here */


//Hallticket Export starts here

$('#hallticket_export').on('click',function(){
  var year = $('#mark_year').val();
  var batch_id = $('#stu_batch_id_selected').val();
  var month = $('#mark_month').val();
  var semester_val = $('#semester_val').val();
  var check_val = $("input[name='MarkEntry[mark_type]']:checked").val();
  if(year=="" || month=="")
  {
    krajeeDialog.alert("Please select all feilds");
    return false;
  }
  else
  {
    $.ajax({
      url: base_url+'?r=mark-entry/hallticketexportdata',
      type:'POST',
      data:{year:year,month:month,check_val:check_val,batch_id:batch_id,semester:semester_val},
      success:function(data)
      {
        if(data!=0)
        {
          //alert(data);
          $('.hallticket_export_tbl').show();
          $('#hall_ticket_export_tbl').html(data);
        }
        else
        {
          krajeeDialog.alert('No data found..');
          $('.hallticket_export_tbl').hide();
          return false;
        }
      } 
    });
  }
});
function getOmrPrint(year,month)
{
  var batch_id = $('#stu_batch_id_selected').val();
  var batch_map_id = $('#stu_programme_selected').val();
  var mark_type = $("input[name='MarkEntry[mark_type]']:checked").val();
  var fees_status = $("input[name='MarkEntry[is_updated]']:checked").val();
  if(year=="" || month=="")
  {
    krajeeDialog.alert("Please select all feilds");
    return false;
  }
  else
  {
    $.ajax({
      url: base_url+'?r=mark-entry-master/omrexportdata',
      type:'POST',
      data:{year:year,month:month,batch_id:batch_id,batch_map_id:batch_map_id,mark_type:mark_type,fees_status:fees_status},
      success:function(data)
      {
        if(data!=0)
        {
          //alert(data);
          $('.hallticket_export_tbl').show();
          $('#hall_ticket_export_tbl').html(data);
        }
        else
        {
          krajeeDialog.alert('No data found..');
          $('.hallticket_export_tbl').hide();
          return false;
        }
      } 
    });
  }
}
//Hallticket Export ends here


//University report starts here

/*$('#university_btn').on('click',function(){
  var batch = $('#stu_batch_id_selected').val();
  var bat_map_id = $('#stu_programme_selected').val();
  var exam_term= $('#exam_term').val();
  

  if(batch=="" || bat_map_id=="")
  {
    krajeeDialog.alert("Please select all feilds");
    return false;
  }
  else
  {
    $.ajax({
      url: base_url+'?r=mark-entry/universityreportdata',
      type:'POST',
      data:{batch:batch,bat_map_id:bat_map_id,exam_term:exam_term},
      success:function(data)
      {
        if(data!=0)
        {
        //alert(data);
          $('.university_report_tbl').show();
          $('#uni_rep_tbl').html(data);
        }
        else
        {
          krajeeDialog.alert('No data found..');
          $('.university_report_tbl').hide();
          return false;
        }
      }
    });
  }  
});*/

$('#university_btn').on('click',function(){
  var batch = $('#stu_batch_id_selected').val();
  var bat_map_id = $('#stu_programme_selected').val();
  var exam_term= $('#exam_term').val();
  

  if(batch=="" || bat_map_id=="")
  {
    krajeeDialog.alert("Please select all feilds");
    return false;
  }
  else
  {
    $.ajax({
      url: base_url+'?r=mark-entry/universityreportdata',
      type:'POST',
      data:{batch:batch,bat_map_id:bat_map_id,exam_term:exam_term},
      success:function(data)
      {
        if(data!=0)
        {
          //alert(data);
          $('.university_report_tbl').show();
          $('#uni_rep_tbl').html(data);
        }
        else
        {
          krajeeDialog.alert('No data found..');
          $('.university_report_tbl').hide();
          return false;
        }
      }
    });
  }  
});

$('#university_btn_comp').on('click',function(){
  var batch = $('#stu_batch_id_selected').val();
  var bat_map_id = $('#stu_programme_selected').val();
  var year = $('#mark_year').val();
  var month = $('#exam_month').val();

  if(batch=="" || bat_map_id=="" || year=="" || month=="")
  {
    krajeeDialog.alert("Please select all feilds");
    return false;
  }
  else
  {
    $.ajax({
      url: base_url+'?r=mark-entry/universityreportdatacomp',
      type:'POST',
      data:{batch:batch,bat_map_id:bat_map_id,year:year,month:month},
      success:function(data)
      {
        if(data!=0)
        {
          //alert(data);
          $('.university_report_tbl').show();
          $('#uni_rep_tbl').html(data);
        }
        else
        {
          krajeeDialog.alert('No data found..');
          $('.university_report_tbl').hide();
          return false;
        }
      }
    });
  }  
});
$('#myclassroom_btn').on('click',function(){
  var year = $('#class_year').val();
  var month = $('#class_month').val();
  var degree ='UG';// $('#degree_name').val();
  var batch_id = $('#stu_batch_id_selected').val();
  var batch_map_id = $('#stu_programme_selected').val();

  if(batch_id =="" || year=="" || month=="" || degree=='')
  {
    krajeeDialog.alert("Please select all feilds");
    return false;
  }
  else
  {
    $.ajax({
      url: base_url+'?r=mark-entry/myclassroomdata',
      type:'POST',
      data:{year:year,month:month,degree:degree,batch_id:batch_id,batch_map_id:batch_map_id},
      success:function(data)
      {
        if(data!=0)
        {
          $('.university_report_tbl').show();
          $('#uni_rep_tbl').html(data);
        }
        else
        {
          krajeeDialog.alert('No data found..');
          $('.university_report_tbl').hide();
          return false;
        }
      }
    });
  }  
});
//University report ends here



//Mark Percent Starts Here
$('#markpercentbutton').on('click',function(){
  var batch = $('#stu_batch_id_selected').val();
  var year = $('#year').val();
  var month = $('#exam_month').val();
  var mark_type = $('#exam_type').val();
  //alert(batch+"---"+bat_map_id+"---"+year+"---"+month+"---"+mark_type);
  $.ajax({
      url: base_url+'?r=mark-entry/markpercentreport',
      type:'POST',
      data:{batch:batch,year:year,month:month,mark_type:mark_type},
      success:function(data){
        if(data==0){
          krajeeDialog.alert('No data found..');
          $('#mark_percent').hide();
        }else{
          $('.mark_percent_print_btn').show();
          $('#mark_percent').show();
          $('#mark_percent').html(data);
        }
      }
  });
});
//Mark Percent Ends Here

//Additional Credits Starts Here

$('#add_sub_code').on('change',function(){
    var subject_code = $('#add_sub_code').val();
    var semester=$('#semester').val();
    
    $.ajax({
        url: base_url+'?r=ajaxrequest/acsubjectname',
        type:'POST',
        data:{subject_code:subject_code,semester:semester},
        success:function(data){
            //alert(data);
            if(data==0){                
                $('#add_sub_name').val('');
                $('#add_credits').val('');
                $('#add_sub_name').prop('readonly', false);
                $('#add_credits').prop('readonly', false);
                
            }else{
                var parsed = $.parseJSON(data);
                $.each(parsed,function(i,parsed){   
                    $('#add_sub_name').val(parsed['subject_name']);
                    $('#add_credits').val(parsed['credits']);            
                    
                    $('#add_sub_name').prop('readonly', true);
                    $('#add_credits').prop('readonly', true);
                });
            }
            
        }
    });
    
});

$('#add_credit_btn').on('click',function(){
    var batch = $('#stu_batch_id_selected').val();
    var batch_map_id = $('#stu_programme_selected').val();
    var subject_code = $('#add_sub_code').val();
    var subject_name = $('#add_sub_name').val();
    var credit = $('#add_credits').val();
    $.ajax({
        url: base_url+'?r=ajaxrequest/additionalcreditstulist',
        type:'POST',
        data:{batch:batch,batch_map_id:batch_map_id,subject_code:subject_code,subject_name:subject_name,credit:credit},
        success:function(data){
            if(data==0){
                krajeeDialog.alert("Select All required fields");
            }else{
                //alert(data);
                $('#ac_student_list').show();
                $('#ac_student_list').html(data);
                $('.additional_submit_btn').show(); 
            }
           
        }
    });
});

function additional_check(id){
    var end_value = id.substr(id.lastIndexOf('_') + 1);
    var grtxt = "actxt_"+end_value;
    var resulttxt = "acresult_"+end_value;
    var checkbox_name = "add"+end_value;
    var grade_name = "grade_"+end_value;
    var grade_point = "grade_point_"+end_value;

    if($("input[name='"+checkbox_name+"']:checked").val()){
        $("#"+grtxt).prop('disabled', false);
        $("#"+grtxt).prop('required', true);
        $("#"+resulttxt).prop('disabled', false);
        $("#"+grade_name).prop('readonly', true);
        $("#"+grade_point).prop('readonly', true);
    }else{
        $("#"+grtxt).prop('disabled', true);
        $("#"+resulttxt).prop('disabled', true);
        $("#"+grtxt).val("");
        $("#"+resulttxt).val("");
        $("#"+grade_name).val("");
        $("#"+grade_point).val("");
    }
}
function getAddResult(id,id_value)
{
    var end_value = id.substr(id.lastIndexOf('_') + 1);
    var marks = "actxt_"+end_value;
    var resulttxt = "acresult_"+end_value;
    var grade_name = "grade_"+end_value;
    var grade_point = "grade_point_"+end_value;
    var checkbox_name = "add"+end_value;
    var batch_map_id = $('#stu_batch_id_selected').val();
    var stu_marks = $("#"+marks).val();
    var year= $('#mark_year').val();
    var month= $('#exam_month').val();
    if(stu_marks>100)
    {
        krajeeDialog.alert("Wrong entry Morethan 100");
        $("#"+marks).val("");
        $("#"+resulttxt).prop('readonly', true).val("");
        $("#"+grade_name).prop('readonly', true).val("");
        $("#"+grade_point).prop('readonly', true).val("");
        return false;
    }

    if($("input[name='"+checkbox_name+"']:checked").val()){
        $("#"+marks).prop('disabled', false);
        $("#"+marks).prop('required', true);
        
        $.ajax({
            url: base_url+'?r=ajaxrequest/getgradepoint',
            type:'POST',
            data:{marks:stu_marks,batch_map_id:batch_map_id,year:year,month:month},
            success:function(data)
            {
                var jsonFormat = JSON.parse(data);
                if(data==0)
                {
                    krajeeDialog.alert("Sorry Unknow Error");
                    return false;
                }
                else
                {
                    //var grade_point = jsonFormat['grade_point']==0 || jsonFormat['grade_point']=='' ? 0 : jsonFormat['grade_point'];
                    $("#"+resulttxt).prop('readonly', true).val(jsonFormat['result']);
                    $("#"+grade_name).prop('readonly', true).val(jsonFormat['grade_name']);
                    $("#"+grade_point).prop('readonly', true).val(jsonFormat['grade_point']);
                }
            }    

        });
    }else{
        $("#"+marks).prop('disabled', true);
        $("#"+resulttxt).prop('disabled', true);
        $("#"+resulttxt).val("");
        $("#"+grade_name).prop('readonly', true).val("");
        $("#"+grade_point).prop('readonly', true).val("");
    }
}
function getMandatoryResult(id,id_value)
{
    var end_value = id.substr(id.lastIndexOf('_') + 1);
    var marks = "actxt_"+end_value;
    var resulttxt = "acresult_"+end_value;
    var grade_name = "grade_"+end_value;
    var grade_point = "grade_point_"+end_value;
    var checkbox_name = "add"+end_value;
    var batch_map_id = $('#stu_batch_id_selected').val();
    var stu_marks = $("#"+marks).val();
    var year= $('#mark_year').val();
    var month= $('#exam_month').val();
    var manSubId= $('#manSubId').val();

    if(stu_marks>100)
    {
        krajeeDialog.alert("Wrong entry Morethan 100");
        $("#"+marks).val("");
        $("#"+resulttxt).prop('readonly', true).val("");
        $("#"+grade_name).prop('readonly', true).val("");
        $("#"+grade_point).prop('readonly', true).val("");
        return false;
    }

    if($("input[name='"+checkbox_name+"']:checked").val()){
        $("#"+marks).prop('disabled', false);
        $("#"+marks).prop('required', true);
        
        $.ajax({
            url: base_url+'?r=ajaxrequest/getmandatorygradepoint',
            type:'POST',
            data:{marks:stu_marks,batch_map_id:batch_map_id,year:year,month:month,manSubId:manSubId},
            success:function(data)
            {
                var jsonFormat = JSON.parse(data);
                if(data==0)
                {
                    krajeeDialog.alert("Sorry Unknow Error");
                    return false;
                }
                else
                {

                    $("#"+resulttxt).prop('readonly', true).val(jsonFormat['result']);
                    $("#"+grade_name).prop('readonly', true).val(jsonFormat['grade_name']);
                    $("#"+grade_point).prop('readonly', true).val(jsonFormat['grade_point']);
                }
            }    

        });
    }else{
        $("#"+marks).prop('disabled', true);
        $("#"+resulttxt).prop('disabled', true);
        $("#"+resulttxt).val("");
        $("#"+grade_name).prop('readonly', true).val("");
        $("#"+grade_point).prop('readonly', true).val("");
    }
}
//Additional Credits Ends Here

//Withdraw starts here
$('#withdraw_btn').on('click',function(){
    var year = $('#withdraw_year').val();
    var month = $('#withdraw_month').val();
    var sem = $('#sem').val();
    var reg = $('#stu_reg_num').val();
    if(year=="" && month=="" && sem=="" && reg=="")
    {
        krajeeDialog.alert("Please select all fields");
        return false;
    }
    else
    {
        $.ajax({
            url: base_url+'?r=mark-entry/withdrawsublist',
            type:'POST',
            data:{year:year,month:month,sem:sem,reg:reg},
            success:function(data){
                if(data==0)
                {
                    krajeeDialog.alert("No Data Found");
                    return false;
                }
                else
                {
                   $('.withdraw').show();
                   $('#withdraw_entry_tbl').html(data);
                }
            }
        });
    }
});
//Withdraw ends here

// Border Line Marks Starts Here 
function getArrearStudetails(anchor_id) 
{
    var anchor_split = anchor_id.split('_');
    var count_degree_id = $("#get_count_degree_"+anchor_split[1]).val();
    var count_mark_year = $("#count_mark_year").val();
    var exam_month = $("#count_exam_month").val();
    var cLine = $("#count_boderLine").val();
    var eseMin = $("#count_eseMin_"+anchor_split[1]).val();
    var count_subject_id = $("#get_count_subject_"+anchor_split[1]).val();

    $.ajax({

        url: base_url+'?r=ajaxrequest/getarrearcountstudents',
            type:'POST',
            data:{subject_map_id:count_subject_id,batch_mapping_id:count_degree_id,
                month:exam_month,year:count_mark_year,Min:eseMin,border:cLine},
            success:function(data){
                if(data==0)
                {
                    krajeeDialog.alert("Sorry Unknow Error");
                    return false;
                }
                else
                {
                    var body='';                    
                    var parsed = $.parseJSON(data);
                    var k=0;
                    body +='<table style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive bulk_edit_table table-hover" id = "student_data_esults"><thead><tr><th>SNO</th><th>CODE</th><th>Register Number</th><th>CIA</th><th>ESE</th><th>TOTAL</th><th>ESE MIN</th></tr>'; 
                    $.each(parsed,function(i,parsed){
                        var tr='<tr>';
                        var td='<td>'+(k+1)+'</td><td> '+parsed['subject_code']+' </td><td> '+parsed['register_number']+' </td><td> '+parsed['CIA']+' </td><td> '+parsed['ESE']+' </td><td> '+parsed['total']+' </td><td> '+parsed['ESE_min']+' </td>';
                        var tr_dum_close ='</tr>'; 
                        body += tr+td+tr_dum_close; 
                        k++;
                    });
                    body +='</table>';
                   krajeeDialog.alert(body);
                }
            }
     

    });
}

function ArrearList(id)
{
    var pgm_code = $("#pgm_"+id).val();
    var sub_code = $("#sub_"+id).val();
    var year = $("#mark_year").val();

    $.ajax({
        url: base_url+'?r=mark-entry/getarrearstudent',
        type:'POST',
        data:{pgm_code:pgm_code,sub_code:sub_code,year:year},
        success:function(data){  
            var body='';                    
            var parsed = $.parseJSON(data);
            var k=0;
            var table_open ='<table class="table table-responsive table-hover table-bordered table-stripped" border=1><tr><td>S.No.</td><td>Register Number</td><td>Name</td></tr>';
            $.each(parsed,function(i,parsed){
                var tr='<tr>';
                var td='<td>'+(k+1)+'</td><td> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td>';
                var tr_dum_close ='</tr>'; 
                body += tr+td+tr_dum_close; 
                k++;
            });
           krajeeDialog.alert(table_open+body+"</table>");
        }
    });
}

//Arrear report ends here

// Student Mark View

function studentMarkView(id)
{
    var reg_num = $('#mark_view_reg_no').val();
    $.ajax({
        url: base_url+'?r=ajaxrequest/studentarkview',
        type: 'POST',
        data:{reg_no: reg_num},
        success: function(data){

        }
    });
}

//Mandatory Changes

function getSubjectsList()
{
    var man_sub_code = $('#mandatorysubcatsubjects-man_subject_id').val();
    var batch_mapping_id = $('#stu_programme_selected').val();
    $.ajax({
            url: base_url+'?r=ajaxrequest/getmansubname',
            type:'POST',
            data:{sub_code:man_sub_code,batch_mapping_id:batch_mapping_id},
            success:function(data)
            {            
                var parsed = $.parseJSON(data); 
                var disp_val;
                if(parsed['count']==0)
                {
                    disp_val = "001";
                }
                else if(parsed['lenth']<=1)
                {
                    disp_val = "00"+parseInt(parsed['count']+1);
                }
                else if(parsed['lenth']>1 && parsed['lenth']<=2)
                {
                    disp_val = "0"+parseInt(parsed['count']+1);
                }
                
                $('#mandatorysubcatsubjects-sub_cat_code').prop('readonly', true).val(disp_val);
                   
            }
        });
}

function getManSubjectsList()
{
    var man_sub_code = $('#manSubId').val();
    var year = $('#mark_year').val();
    var month = $('#exam_month').val();
    var exam_type = $('#exam_type').val();
    var exam_term = $('#exam_term').val();
    var manSubcatId = $('#manSubcatId').val();
    var batch_map_id = $('#stu_programme_selected').val();
     $('#hide_sub_cat_info').html('');
    $('#hide_sub_cat_info').hide();
    $('#man_sub_credit_btn').hide();
    $.ajax({
            url: base_url+'?r=ajaxrequest/getsubcatlist',
            type:'POST',
            data:{sub_code:man_sub_code,batch_map_id:batch_map_id,year:year,month:month,mark_type:exam_type,exam_term:exam_term,manSubcatId:manSubcatId},
            success:function(data)
            {            
                var parsed = $.parseJSON(data); 
                var disp_val;
                if(parsed=='NO')
                {
                    krajeeDialog.alert('NO DATA FOUND');
                    $('#manSubcatId').html('');
                    $('#hide_sub_cat_info').html('');
                    $('#hide_sub_cat_info').hide();
                    $('#man_sub_credit_btn').hide();
                }
                else
                {
                    var send_to_dropdown = $('#manSubcatId');
                        send_to_dropdown.html('');
                    send_to_dropdown.append('<option value>---Select---</option>');
                   $.each(parsed,function(i,parsed)
                    {
                        send_to_dropdown.append("<option value='"+parsed['sub_cat_id']+"' >"+parsed['sub_cat_code']+"</option>");     
                    });
                }
            }
        });
}
function getMandatorySubjectsList()
{
    var year = $('#mark_year').val();
    var semester = $('#mandatorystumarks-semester').val();
    var month = $('#exam_month').val();
    var exam_type = $('#exam_type').val();
    var exam_term = $('#exam_term').val();
    var batch_map_id = $('#stu_programme_selected').val();
     $('#hide_sub_cat_info').html('');
    $('#hide_sub_cat_info').hide();
    $('#man_sub_credit_btn').hide();
    $.ajax({
            url: base_url+'?r=ajaxrequest/getmandatosubcatlist',
            type:'POST',
            data:{batch_map_id:batch_map_id,year:year,month:month,mark_type:exam_type,exam_term:exam_term,semester:semester},
            success:function(data)
            {            
                var parsed = $.parseJSON(data); 
                var disp_val;
                if(parsed=='0')
                {
                    krajeeDialog.alert('NO DATA FOUND');
                    $('#manSubId').html('');
                }
                else
                {
                    var send_to_dropdown = $('#manSubId');
                        send_to_dropdown.html('');
                    send_to_dropdown.append('<option value>---Select---</option>');
                   $.each(parsed,function(i,parsed)
                    {
                        send_to_dropdown.append("<option value='"+parsed['sub_id']+"' >"+parsed['subj_code']+"</option>");     
                    });
                }
            }
        });
}
function getManSubjectDetails()
{
    var man_sub_code = $('#manSubId').val();
    var year = $('#mark_year').val();
    var month = $('#exam_month').val();
    var exam_type = $('#exam_type').val();
    var semester = $('#mandatorystumarks-semester').val();
    var exam_term = $('#exam_term').val();
    var manSubcatId = $('#manSubcatId').val();
    var batch_map_id = $('#stu_programme_selected').val();

    $.ajax({
        url: base_url+'?r=ajaxrequest/getsubcatinfo',
        type:'POST',
        data:{sub_code:man_sub_code,batch_map_id:batch_map_id,year:year,month:month,mark_type:exam_type,exam_term:exam_term,manSubcatId:manSubcatId,semester:semester},
        success:function(data)
        {            
            var parsed = $.parseJSON(data); 
            var disp_val;
            if(parsed=='NO'){
                krajeeDialog.alert('NO DATA FOUND');
                $('#hide_sub_cat_info').html('');
                $('#hide_sub_cat_info').hide();
                $('#man_sub_credit_btn').hide();
            }
            else
            {
                $('#hide_sub_cat_info').html(parsed);
                $('#hide_sub_cat_info').show();
                $('#man_sub_credit_btn').show();
            }
        }
    });              
}
$('#mandatorysubjects-created_at').on('click',function()
{
    var check_val = $("input[name='MandatorySubjects[created_at]']:checked").val();
    if(check_val==1)
    {
        var batch_id = $('#stu_batch_id_selected').val();
        var programme_id = $('#stu_programme_selected').val();
        var semester = $('#mandatorysubjects-semester').val();
        
        $.ajax({
            url: base_url+'?r=ajaxrequest/getsubcatsubect',
            type:'POST',
            data:{batch_id:batch_id,programme_id:programme_id,semester:semester},
            success:function(data)
            {            
                var parsed = $.parseJSON(data)
                if(parsed['no_data']=='NO')
                {
                    krajeeDialog.alert('NO CATEGORIES FOUND');
                    $('#mandatorysubjects-updated_at').attr('value','');
                    $('.field-mandatorysubjects-updated_at').hide();
                    $("#mandatorysubjects-updated_at").prop('required', false);
                    $("input[name='MandatorySubjects[created_at]']").prop("checked", false);
                }
                else
                {
                    $('.field-mandatorysubjects-updated_at').show();
                    $("#mandatorysubjects-updated_at").prop('required', true); 
                    $("#mandatorysubjects-updated_at").html('');   
                    $("#mandatorysubjects-updated_at").html(parsed);   
                   
                    return true;
                }
            }
        });

        
        
    }
    else
    {
        $("#mandatorysubjects-updated_at").prop('required', false);
        $('#mandatorysubjects-updated_at').attr('value','');
        $('.field-mandatorysubjects-updated_at').hide();   
    }
});

$('#mandatorysubjects-updated_at').on('change',function()
{
    $.ajax({
            url: base_url+'?r=ajaxrequest/checksubcats',
            type:'POST',
            data:{sub_code:$('#mandatorysubjects-updated_at').val(),coe_batch_id:$('#stu_batch_id_selected').val()},
            success:function(data)
            {            
                var parsed = $.parseJSON(data);
                if(parsed['no_data']=='NO')
                {
                    krajeeDialog.alert('NO CATEGORIES FOUND');
                    $('#mandatorysubjects-updated_at').attr('value','');
                    $('.field-mandatorysubjects-updated_at').hide();
                    $("#mandatorysubjects-updated_at").prop('required', false);
                    $("input[name='MandatorySubjects[created_at]']").prop("checked", false);
                }
                else
                {
                    return true;
                }
            }
        });
});

$('#examtimetable-coe_batch_id').on('click',function()
{
    var check_val = $("input[name='ExamTimetable[coe_batch_id]']:checked").val();
    if(check_val==1)
    {
        $('#hide_batch_section').show();
        $("#stu_batch_id_selected").prop('required', true);
        $("#stu_programme_selected").prop('required', true);
        $("#stu_section_select").prop('required', true);
    }
    else
    {
        $('#hide_batch_section').hide();
        $('#stu_batch_id_selected').attr('value','');
        $('#stu_programme_selected').attr('value','');
        $('#stu_section_select').attr('value','');
        $("#stu_batch_id_selected").prop('required', false);
        $("#stu_programme_selected").prop('required', false);
        $("#stu_section_select").prop('required', false);
    }
});
$('#answer_packets_btn').on('click',function()
{
    $.ajax({
            url: base_url+'?r=ajaxrequest/getanswerpacketsinfo',
            type:'POST',
            data:{exam_date:$('#exam_date').val(),exam_year:$("#hallallocate-year").val(),exam_month:$("#exam_month").val(),exam_session:$('#absententry-exam_session').val(),packet_count:$('#total_print_reg').val()},
            success:function(data)
            {            
                var parsed = $.parseJSON(data);
                if(parsed==0)
                {
                    krajeeDialog.alert('NO DATA FOUND');
                    $('#answer_packets_div').hide();
                    $('#answer_packets').hide();
                    $('#answer_packets').html('');
                }
                else
                {
                    $('#answer_packets_div').show();
                    $('#answer_packets').show();
                    $('#answer_packets').html(parsed);
                    return true;
                }
            }
        });
});

$('#print_packets_btn').on('click',function()
{
    $.ajax({
            url: base_url+'?r=ajaxrequest/getprintregisternumber',
            type:'POST',
            data:{exam_date:$('#exam_date').val(),exam_session:$('#absententry-exam_session').val(),exam_year:$("#hallallocate-year").val(),exam_month:$("#exam_month").val(),print_count:$("#total_print_reg").val()},
            success:function(data)
            {            
                var parsed = $.parseJSON(data);
                if(parsed==0)
                {
                    krajeeDialog.alert('NO DATA FOUND');
                    $('#register_date_print_div').hide();
                    $('#register_answer_packets').hide();
                    $('#register_answer_packets').html('');
                    return false;
                }
                else
                {
                    $('#register_date_print_div').show();
                    $('#register_answer_packets').show();
                    $('#register_answer_packets').html(parsed);
                    return true;
                }
            }
        });
});

function getPracticalSubs(sem_id)
{

    $.ajax({
            url: base_url+"?r=ajaxrequest/getpracticalsubjects",
            type:'POST',
            data:{bat_map_val:$('#stu_programme_selected').val(),section:$('#stu_section_select').val(),sem_id:sem_id,exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='PracticalEntry[term]']:checked").val(),mark_type:$("input[name='PracticalEntry[mark_type]']:checked").val()},
            success:function(data)
            {
                if(data==0)
                {
                    $('#mark_subject_code').html('');
                    $('#show_details_subs').hide();
                    $('#disp_show_details_subs').hide();
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;

                }
                else
                {                       
                    var parsed = $.parseJSON(data);                    
                    $('#mark_subject_code').html('');
                    $('#mark_subject_code').append("<option value=''>------SELECT-----</option>");
                    $.each(parsed,function(i,parsed)
                    {
                        $('#mark_subject_code').append("<option value='"+parsed['coe_subjects_mapping_id']+"'>"+parsed['subject_code']+"</option>");
                    });
                }
            }

        });
}
function getPracticalStuList()
{

    if($('#mark_subject_code').val()=='' || $('#examiner_name').val()=='' || $('#chief_exam_name').val()=='')
    {
        krajeeDialog.alert('Select the Required Fields');     
        return false;
    }
    $.ajax({
            url: base_url+"?r=ajaxrequest/getpracticalsubjectdetails",
            type:'POST',
            data:{sub_map_id:$('#mark_subject_code').val(), bat_map_val:$('#stu_programme_selected').val(),sem_id:$('#markentry-semester').val(),exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='PracticalEntry[term]']:checked").val(),mark_type:$("input[name='PracticalEntry[mark_type]']:checked").val(),section:$('#stu_section_select').val(),reg_from:$('#student-register_number_from').val(),reg_to:$('#to_reg').val()},
            success:function(data)
            {
                $('#show_details_subs').hide();
                $('#disp_show_details_subs').hide();
                if(data==0)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;
                }
                else if(data==1)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('Mark Entry Completed');     
                    return false;
                }
                else if(data==3)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('Wrong Entry Kindly Check Your Submission');     
                    return false;
                }
                else
                {                       
                    var body='';  
                    var full_body='';                    
                    var parsed = $.parseJSON(data);
                    var k=0;
                    var table_open ='<table class="table table-responsive table-stripped table-bordered table-hover"><tr class="info"><th>S.No.</th><th>Register Number</th><th>Name</th><th>Marks Out Of 100</th><th>Marks in Words</th></tr>';
                    $.each(parsed,function(i,parsed)
                    {
                        var tr='<tr>';
                        var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td><input type="text" id=dum_num_marks'+k+' autocomplete="off"  required=required onkeypress="numbersOnly(event);allowEntr(event,this.id);" onchange="check_max_number(this.id,this.value); writeText(this.id,this.value);" name="ese_marks[]" /><td><input type="text" style="border: none;" readonly id=dum_num_marks'+k+'_1 required /></td>';
                        var tr_dum_close ='</tr>'; 
                        body += tr+td+tr_dum_close; 
                        k++;
                    });
                   full_body = table_open+body+"</table>";
                   $('#show_details_subs').show();
                   $('#disp_show_details_subs').show();
                   $('#show_details_subs').html(full_body);
                }
            }

        });
}

function getExternalStudeList()
{
    var subject_ese_min='';
    var subject_ese_max='';
    var subject_total_min='';
    if($('#mark_subject_code').val()=='')
    {
        $('#hide_dum_sub_data').hide();
        $('#show_dummy_entry').hide();
        $('#show_details_subs').hide();
        $('#disp_show_details_subs').hide();
        krajeeDialog.alert('Select the Required Fields');     
        return false;
    }
    $.ajax({
        url: base_url+'?r=ajaxrequest/getsubjectname',
        type:'POST',
          data:{sub_id:$('#mark_subject_code').val()},
          success:function(data)
          {
            $('#hide_dum_sub_data').show();
            $('#show_dummy_entry').show();
                var jsonFormat = JSON.parse(data);
                var body='';           
                if(jsonFormat=='NO')
                {
                    $('#hide_dum_sub_data').hide();
                    $('#show_dummy_entry').hide();
                }
                else
                {
                    var tr='<tr>';
                    var td='<td>1</td><td>'+jsonFormat['subject_code']+' </td><td>'+jsonFormat['subject_name']+' </td><td>'+jsonFormat['ESE_min']+'</td><td>'+jsonFormat['ESE_max']+'</td><td>'+jsonFormat['total_minimum_pass']+'</td>';
                    var tr_dum_close ='</tr>'; 
                    body = tr+td+tr_dum_close;

                    $('#hide_dum_sub_data').show();                    
                    $('#show_dummy_entry').html(body);
                }
                
          }
        });   
            
    $.ajax({
            
            url: base_url+"?r=ajaxrequest/getexternalstusubjectdetails",
            type:'POST',
            data:{sub_map_id:$('#mark_subject_code').val(), bat_map_val:$('#stu_programme_selected').val(),sem_id:$('#markentry-semester').val(),exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='MarkEntryMaster[term]']:checked").val(),mark_type:$("input[name='MarkEntryMaster[mark_type]']:checked").val()},
            success:function(data)
            {
                $('#show_details_subs').hide();
                $('#disp_show_details_subs').hide();
                if(data==0)
                {
                    $('#markentry-semester').val('');
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;
                }
                else if(data==1)
                {
                    $('#markentry-semester').val('');
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('Mark Entry Completed View Marks');     
                    return false;
                }
                else if(data==2)
                {
                    $('#markentry-semester').val('');
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('No Internal Marks Found');     
                    return false;
                }
                else if(data==3)
                {
                    $('#exam_month').val('');
                    $('#stu_programme_selected').val('')
                    $('#markentry-semester').val('');
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('Wrong Entry Kindly Check Your Submission');     
                    return false;
                }
                else
                {                
                    var body='';  
                    var full_body='';                    
                    var parsed = $.parseJSON(data);
                    var k=0;
                    var table_open ='<table class="table table-responsive table-stripped table-bordered table-hover"><tr class="info"><th>S.No.</th><th>Register Number</th><th>Name</th><th>CIA</th><th>PRESENT/ABSENT</th><th>ESE</th><th>RESULT</th><th>Marks in Words</th></tr>';
                    $.each(parsed,function(i,parsed)
                    {
                        var tr='<tr>';
                        var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td><input type="hidden" readonly=readonly width=30 id=cia_marks'+k+' name="cia_marks[]" value="'+parsed['category_type_id_marks']+'" /> '+parsed['category_type_id_marks']+'</td><td><input type="hidden" width=30 id=ab_status'+k+' name="present_status[]" value="Present" /> Present </td><td><input type="text" id=ese_marks'+k+' autocomplete="off"  required=required onkeypress="numbersOnly(event);allowEntr(event,this.id);" onchange="getArtstuResult(this.id,this.value,'+parsed['category_type_id_marks']+','+parsed['ESE_min']+','+parsed['ESE_max']+','+parsed['total_minimum_pass']+'); writeText(this.id,this.value);" name="ese_marks[]" /><td><input type="text" readonly=readonly name="ext_result[]"  id=ext_result'+k+'_1 required /></td><td><input type="text" style="border: none;" readonly id=ese_marks'+k+'_1 required /></td>';                              
                        var tr_dum_close ='</tr>'; 
                        body += tr+td+tr_dum_close; 
                        k++;
                    });
                   full_body = table_open+body+"</table>";
                   $('#show_details_subs').show();
                   $('#disp_show_details_subs').show();
                   $('#show_details_subs').html(full_body);
                }
            }

        });
}

function getExternalMarksStudeList()
{
    var subject_ese_min='';
    var subject_ese_max='';
    var subject_total_min='';
    if($('#mark_subject_code').val()=='')
    {
        $('#hide_dum_sub_data').hide();
        $('#show_dummy_entry').hide();
        $('#show_details_subs').hide();
        $('#disp_show_details_subs').hide();
        krajeeDialog.alert('Select the Required Fields');     
        return false;
    }
    $.ajax({
        url: base_url+'?r=ajaxrequest/getsubjectname',
        type:'POST',
          data:{sub_id:$('#mark_subject_code').val()},
          success:function(data)
          {
            $('#hide_dum_sub_data').show();
            $('#show_dummy_entry').show();
                var jsonFormat = JSON.parse(data);
                var body='';           
                if(jsonFormat=='NO')
                {
                    $('#hide_dum_sub_data').hide();
                    $('#show_dummy_entry').hide();
                }
                else
                {
                    var tr='<tr>';
                    var td='<td>1</td><td>'+jsonFormat['subject_code']+' </td><td>'+jsonFormat['subject_name']+' </td><td>'+jsonFormat['CIA_min']+'</td><td> '+jsonFormat['CIA_max']+'</td><td>'+jsonFormat['ESE_min']+'</td><td> '+jsonFormat['ESE_max']+'</td><td> '+jsonFormat['total_minimum_pass']+'</td>';
                    var tr_dum_close ='</tr>'; 
                    body = tr+td+tr_dum_close;

                    $('#hide_dum_sub_data').show();                    
                    $('#show_dummy_entry').html(body);
                }
                
          }
        });   
            
    $.ajax({
            
            url: base_url+"?r=ajaxrequest/getexternalstumarkdetails",
            type:'POST',
            data:{sub_map_id:$('#mark_subject_code').val(), bat_map_val:$('#stu_programme_selected').val(),sem_id:$('#markentry-semester').val(),exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='MarkEntryMaster[term]']:checked").val(),mark_type:$("input[name='MarkEntryMaster[mark_type]']:checked").val()},
            success:function(data)
            {
                $('#show_details_subs').hide();
                $('#disp_show_details_subs').hide();
                if(data==0)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;
                }
                else if(data==1)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('Mark Entry Completed');     
                    return false;
                }
                else
                {                
                    var body='';  
                    var full_body='';                    
                    var parsed = $.parseJSON(data);
                    var k=0;
                    var table_open ='<table class="table table-responsive table-stripped table-bordered table-hover"><tr class="info"><th>S.No.</th><th>Register Number</th><th>Name</th><th>CIA</th><th>ESE</th><th>TOTAL</th><th>RESULT</th><th>GRADE NAME</th><th>GRADE POINT</th></tr>';
                    $.each(parsed,function(i,parsed)
                    {
                        if(parsed['result']=='Absent')
                        {
                            var tr='<tr style="background: #F00; border: 1px solid #F00; color: #FFF;">';                       
                            var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td>'+parsed['CIA']+'</td><td>'+parsed['ESE']+'</td><td>'+parsed['total']+'<td>'+parsed['result']+'</td><td>'+parsed['grade_name']+'</td><td>'+parsed['grade_point']+'</td>';
                            var tr_dum_close ='</tr>'; 
                        }
                        else
                        {
                            var tr='<tr>';                       
                            var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td>'+parsed['CIA']+'</td><td>'+parsed['ESE']+'</td><td>'+parsed['total']+'<td>'+parsed['result']+'</td><td>'+parsed['grade_name']+'</td><td>'+parsed['grade_point']+'</td>';
                            var tr_dum_close ='</tr>'; 
                        } 
                        body += tr+td+tr_dum_close; 
                        k++;
                    });
                   full_body = table_open+body+"</table>";
                   $('#show_details_subs').show();
                   $('#disp_show_details_subs').show();
                   $('#show_details_subs').html(full_body);
                }
            }

        });
} 


function getExternalMarksStudeListAdd()
{
    var subject_ese_min='';
    var subject_ese_max='';
    var subject_total_min='';
    if($('#mark_subject_code').val()=='')
    {
        $('#hide_dum_sub_data').hide();
        $('#show_dummy_entry').hide();
        $('#show_details_subs').hide();
        $('#disp_show_details_subs').hide();
        krajeeDialog.alert('Select the Required Fields');     
        return false;
    }
    $.ajax({
        url: base_url+'?r=ajaxrequest/getsubjectnameadd',
        type:'POST',
          data:{sub_id:$('#mark_subject_code').val()},
          success:function(data)
          {
            $('#hide_dum_sub_data').show();
            $('#show_dummy_entry').show();
                var jsonFormat = JSON.parse(data);
                var body='';           
                if(jsonFormat=='NO')
                {
                    $('#hide_dum_sub_data').hide();
                    $('#show_dummy_entry').hide();
                }
                else
                {
                    var tr='<tr>';
                    var td='<td>1</td><td>'+jsonFormat['subject_code']+' </td><td>'+jsonFormat['subject_name']+' </td><td>'+jsonFormat['CIA_min']+'</td><td> '+jsonFormat['CIA_max']+'</td><td>'+jsonFormat['ESE_min']+'</td><td> '+jsonFormat['ESE_max']+'</td><td> '+jsonFormat['total_minimum_pass']+'</td>';
                    var tr_dum_close ='</tr>'; 
                    body = tr+td+tr_dum_close;

                    $('#hide_dum_sub_data').show();                    
                    $('#show_dummy_entry').html(body);
                }
                
          }
        });   
            
    $.ajax({
            
            url: base_url+"?r=ajaxrequest/getexternalstumarkdetailsvaladd",
            type:'POST',
            data:{sub_map_id:$('#mark_subject_code').val(), bat_map_val:$('#stu_programme_selected').val(),sem_id:$('#markentry-semester').val(),exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='MarkEntryMaster[term]']:checked").val(),mark_type:$("input[name='MarkEntryMaster[mark_type]']:checked").val()},
            success:function(data)
            {
                $('#show_details_subs').hide();
                $('#disp_show_details_subs').hide();
                if(data==0)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;
                }
                else if(data==1)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('Mark Entry Completed');     
                    return false;
                }
                else
                {                
                    var body='';  
                    var full_body=''; 
                    var parsed = JSON.parse(data);                   
                 
                    var k=0;
                    var table_open ='<table class="table table-responsive table-stripped table-bordered table-hover"><tr class="info"><th>S.No.</th><th>Register Number</th><th>Name</th><th>CIA</th><th>ESE</th><th>TOTAL</th><th>RESULT</th><th>GRADE NAME</th><th>GRADE POINT</th></tr>';
                    $.each(parsed,function(i,parsed)
                    {
                        if(parsed['result']=='Absent')
                        {
                            var tr='<tr style="background: #F00; border: 1px solid #F00; color: #FFF;">';                       
                            var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td>'+parsed['CIA']+'</td><td>'+parsed['ESE']+'</td><td>'+parsed['total']+'<td>'+parsed['result']+'</td><td>'+parsed['grade_name']+'</td><td>'+parsed['grade_point']+'</td>';
                            var tr_dum_close ='</tr>'; 
                        }
                        else
                        {
                            var tr='<tr>';                       
                            var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td>'+parsed['CIA']+'</td><td>'+parsed['ESE']+'</td><td>'+parsed['total']+'<td>'+parsed['result']+'</td><td>'+parsed['grade_name']+'</td><td>'+parsed['grade_point']+'</td>';
                            var tr_dum_close ='</tr>'; 
                        } 
                        body += tr+td+tr_dum_close; 
                        k++;
                    });
                   full_body = table_open+body+"</table>";
                   $('#show_details_subs').show();
                   $('#disp_show_details_subs').show();
                   $('#show_details_subs').html(full_body);
                }
            }

        });
} 
function getArtstuResult(student_map_id,ESE_marks,student_cia,sub_ese_min,subject_ese_max,subject_total_min)
{

    var stu_total_marks = parseInt(ESE_marks)+parseInt(student_cia);

    var id_num = student_map_id.substr(student_map_id.length-1);
    var number = student_map_id.match(/\d+/g).map(Number);
    var next_focus = parseInt(number)+parseInt(1);
    var result_focus = parseInt(number);

    var number_length = number.toString().length;
    var number_splide = student_map_id.slice(0,student_map_id.length-number_length);
    var id_name = student_map_id.slice(0,student_map_id.length-1);
    if(ESE_marks>subject_ese_max)
    {
        $('#ext_result'+result_focus+'_1').val('');
        $('#'+student_map_id).val('');
        krajeeDialog.alert('Marks Crossing Maximum Marks');
        return false;
    }
    else if(ESE_marks=='-1' || ESE_marks<0 )
    {
        $('#ext_result'+result_focus+'_1').val('Absent');
        $('#ext_result'+result_focus+'_1').css("border","1px solid #F00");
    }
    else if(ESE_marks<sub_ese_min || stu_total_marks<subject_total_min )
    {
        $('#ext_result'+result_focus+'_1').val('Fail');
        $('#ext_result'+result_focus+'_1').css("border","1px solid #e60000");
    }
    else{
        $('#ext_result'+result_focus+'_1').val('Pass');
        $('#ext_result'+result_focus+'_1').css("border","1px solid #00b33c");
    }
    return true;

}

function requireFields()
{
    var year=$('#mark_year').val();
    var month=$('#exam_month').val();
    if(year=='' || month=='')
    {
        krajeeDialog.alert('Select All Fields');
        return false;
    }
   
}
function ExportStuList(id,value)
{    
   var che = id.split("_");
   var sn_id = che[1];
   var batch_mapping_id=$('#batch_map_id_'+sn_id).val();
   var semester=$('#semester_'+sn_id).val();
   var subject_id=$('#sub_id_'+sn_id).val();
   $.ajax({
		url: base_url+"?r=ajaxrequest/getecportstudentlistelc",
		type:'POST',
		data:{sub_id:subject_id, bat_map_val:batch_mapping_id,sem_id:semester},
		success:function(data)
		{
			var jsonFormat = JSON.parse(data);
			$('.hide_elec_div').show();
			$('.hide_elec_div_con').html(jsonFormat);
		}

	});
}
function getMaSubjectsList(batch_map_id,batch_map_i_val)
{    
    
    $.ajax({
            url: base_url+'?r=ajaxrequest/getmansubslist',
            type:'POST',
            data:{batch_map_id:batch_map_i_val},
            success:function(data)
            {         
                 var send_to_dropdown = $('#mandatorysubcatsubjects-man_subject_id');
                        send_to_dropdown.html('');   
                var parsed = $.parseJSON(data); 
                var disp_val;
                if(parsed=='NO')
                {
                    krajeeDialog.alert('NO DATA FOUND');                    
                }
                else
                {                   
                    send_to_dropdown.append('<option value>---Select---</option>');
                   $.each(parsed,function(i,parsed)
                    {
                        send_to_dropdown.append("<option value='"+parsed['man_subject_id']+"' >"+parsed['subject_code']+"</option>");     
                    });
                }
            }
        });
}
$('#mandatorysubcatsubjects-man_subject_id').on('change',(function(){
    var batch_mapping_id = $('#stu_programme_selected').val();
    $.ajax({
        url: base_url+'?r=ajaxrequest/getpapernumber',
        type:'POST',
          data:{batch_mapping_id:batch_mapping_id},
          success:function(data){
            $('#man_pap_no').val(parseInt(data)+1)
          }
    });
}));
function showSubjectsOfEle()
{
    var stu_reg_num = $('#stu_reg_num').val();
    var year = $('#elective_wa_year').val();
    var month = $('#elective_wai_month').val();
    if(stu_reg_num=='' || year=='' || month=='')
    {
        krajeeDialog.alert('Enter Required Fields'); 
        return false;
    }
    else
    {
        $.ajax({
            url: base_url+'?r=ajaxrequest/getelectivewaiversubs',
            type:'POST',
              data:{year:year,month:month,stu_reg_num:stu_reg_num},
              success:function(data)
              {
                var jsonFormat = JSON.parse(data);
                if(jsonFormat==0)
                {
                    $('#electgive_sub_wai').hide();
                    $('#elective_waiver_sub').hide();
                    $('#elective_waiver_sub_in').hide();
                    krajeeDialog.alert('No Data Found'); 
                    return false;
                }
                else if(jsonFormat==1)
                {
                    $('#electgive_sub_wai').hide();
                    $('#elective_waiver_sub').hide();
                    $('#elective_waiver_sub_in').hide();
                    krajeeDialog.alert('Exam Already Created'); 
                    return false;
                }
                else if(jsonFormat==2)
                {
                    $('#electgive_sub_wai').hide();
                    $('#elective_waiver_sub').hide();
                    $('#elective_waiver_sub_in').hide();
                    krajeeDialog.alert('Mark Already Entered'); 
                    return false;
                }
                else if(jsonFormat==3)
                {
                    $('#electgive_sub_wai').hide();
                    $('#elective_waiver_sub').hide();
                    $('#elective_waiver_sub_in').hide();
                    krajeeDialog.alert('<b style="color: #0000ff;" >'+stu_reg_num+'</b> NOT ELIGIBLE ALREADY OPTED FOR WAIVER <br /> CONTACT SUPPORT TEAM FOR HELP'); 
                    return false;
                }
                else
                {
                    $('#electgive_sub_wai').show();
                    $('#elective_waiver_sub').show();
                    $('#elective_waiver_sub_in').show();
                    $('#elective_waiver_sub_in').html('');
                    $('#elective_waiver_sub_in').html(jsonFormat);
                }
              }
        });
    }
}

function additional_check_waiver(id){
    var end_value = id.substr(id.lastIndexOf('_') + 1);
    var grtxt = "elect_textare_"+end_value;
    var resulttxt = "elect_text_"+end_value;
    var checkbox_name = "elect_wwa_"+end_value;

    if($("input[name='"+checkbox_name+"']:checked").val()){
        $("#"+grtxt).prop('disabled', false);
        $("#"+grtxt).prop('required', true);
        $("#"+resulttxt).prop('disabled', false);
        $("#"+resulttxt).prop('required', true);
      
    }else{
        $("#"+grtxt).prop('disabled', true);
        $("#"+resulttxt).prop('disabled', true);
        $("#"+grtxt).val("");
        $("#"+resulttxt).val("");
       
    }
}
function getSubjectInfoPracGET(batch_val,programme_val,month_id,month_val)
{ 
    var mark_type = $("#mark_type").val();  
    var send_to_dropdown = $('#mark_subject_code');  
    $.ajax({
        url: base_url+'?r=ajaxrequest/getverifypractsubsdata',
        type:'POST',
          data:{year:$('#mark_year').val(),batch_id:batch_val,dept_id:programme_val,month:$('#exam_month').val(),mark_type:mark_type},
          success:function(data)
          {
            var parsed = JSON.parse(data);
            send_to_dropdown.html('');
            if(parsed=='NO')
            {
                krajeeDialog.alert('No Data Found');
                $("#examiner_name").prop('required', true);
                $("#chief_exam_name").prop('required', true);
                $('#examiner_name').html('');
                $('#chief_exam_name').html('');
                $('#examiner_name').val('');
                $('#chief_exam_name').val('');
                $('#examiner_name').prop('disabled', false);
                $('#chief_exam_name').prop('disabled', false);
                return false;
            }
            else
            {
               send_to_dropdown.append('<option value>---Select---</option>');
               $.each(parsed,function(i,parsed)
               {
                  send_to_dropdown.append("<option value='"+parsed['subject_map_id']+"' >"+parsed['subject_code']+"</option>");     
               });
            }
            
          }
    });
}
function getSubjectInfoPrac(month_id,month_val)
{
    var mark_type = $("#mark_type").val();  
    var send_to_dropdown = $('#mark_subject_code');  
    $.ajax({
        url: base_url+'?r=ajaxrequest/getverifypractsubs',
        type:'POST',
          data:{year:$('#mark_year').val(),month:$('#exam_month').val(),mark_type:mark_type},
          success:function(data)
          {
            var parsed = JSON.parse(data);
            send_to_dropdown.html('');
            if(parsed=='NO')
            {
                krajeeDialog.alert('No Data Found');
                $("#examiner_name").prop('required', true);
                $("#chief_exam_name").prop('required', true);
                $('#examiner_name').html('');
                $('#chief_exam_name').html('');
                $('#examiner_name').val('');
                $('#chief_exam_name').val('');
                $('#examiner_name').prop('disabled', false);
                $('#chief_exam_name').prop('disabled', false);
                return false;
            }
            else
            {
               send_to_dropdown.append('<option value>---Select---</option>');
               $.each(parsed,function(i,parsed)
               {
                  send_to_dropdown.append("<option value='"+parsed['subject_map_id']+"' >"+parsed['subject_code']+"</option>");     
               });
            }
            
          }
    });
}

function getSubjectInfoPracVerify(month_id,month_val)
{
    var mark_type = $("#mark_type").val();  
    var send_to_dropdown = $('#mark_subject_code');  
    $.ajax({
        url: base_url+'?r=ajaxrequest/getverifypractsubsmigrate',
        type:'POST',
          data:{year:$('#mark_year').val(),month:$('#exam_month').val(),mark_type:mark_type},
          success:function(data)
          {
            var parsed = JSON.parse(data);
            send_to_dropdown.html('');
            if(parsed=='NO')
            {
                krajeeDialog.alert('No Data Found');
                $("#examiner_name").prop('required', true);
                $("#chief_exam_name").prop('required', true);
                $('#examiner_name').html('');
                $('#chief_exam_name').html('');
                $('#examiner_name').val('');
                $('#chief_exam_name').val('');
                $('#examiner_name').prop('disabled', false);
                $('#chief_exam_name').prop('disabled', false);
                return false;
            }
            else
            {
               send_to_dropdown.append('<option value>---Select---</option>');
               $.each(parsed,function(i,parsed)
               {
                  send_to_dropdown.append("<option value='"+parsed['subject_map_id']+"' >"+parsed['subject_code']+"</option>");     
               });
            }
            
          }
    });
}

function getExaminerNames(sub_map_id_val)
{
    $('#pract_show_dummy_numbers').html('');   
    $('#pract_show_dummy_numbers').show(); 
    var mark_type = $("#mark_type").val();
    $.ajax({
        url: base_url+'?r=ajaxrequest/getverifypractsubsdeta',
        type:'POST',
          data:{year:$('#mark_year').val(),month:$('#exam_month').val(),mark_type:mark_type,sub_map_id:sub_map_id_val},
          success:function(data)
          {
            var parsed = JSON.parse(data);
            if(parsed=='NO')
            {
                $('#hide_dum_data').hide();
                $('#hide_dum_sub_data').hide();
                $('#pract_show_dummy_numbers').hide();
                krajeeDialog.alert('No Data Found'); 
                $("#examiner_name").prop('required', true);
                $("#chief_exam_name").prop('required', true);
                $('#examiner_name').html('');
                $('#chief_exam_name').html('');
                $('#examiner_name').val('');
                $('#chief_exam_name').val('');
                $('#examiner_name').prop('disabled', false);
                $('#chief_exam_name').prop('disabled', false);
                return false;
            }
            else if(parsed=='NO_CIA')
            {
                $('#hide_dum_data').hide();
                $('#hide_dum_sub_data').hide();
                $('#pract_show_dummy_numbers').hide();
                
                $("#examiner_name").prop('required', true);
                $("#chief_exam_name").prop('required', true);
                $('#examiner_name').html('');
                $('#chief_exam_name').html('');
                $('#examiner_name').val('');
                $('#chief_exam_name').val('');
                $('#examiner_name').prop('disabled', false);
                $('#chief_exam_name').prop('disabled', false);
                krajeeDialog.alert('No Internal Marks Found!!'); 
                return false;
            }
            else
            {   
                $('#hide_dum_sub_data').show();        
                $('#hide_dum_data').show();     
                var abc=1;   
                var full_body='';   
                var ince = 1;         
                $.each(parsed,function(i,parsed)
                {

                 if(abc==1)
                 {                    
                    $('#examiner_name').val(parsed['examiner_name']).prop('disabled', true);
                    $('#chief_exam_name').val(parsed['chief_exam_name']).prop('disabled', true);
                    
                 }
                 var body='';
                 var tr='<tr>';
                 var td='<td>'+ince+'</td><td><input type="hidden" name=stu_map_ids[] value="'+parsed['student_map_id']+'" /> '+parsed['register_number']+' </td><td><input type="hidden" name=stu_marks[] value="'+parsed['out_of_100']+'" /> '+parsed['out_of_100']+' </td><td>'+parsed['approve_status']+' </td>';
                 var tr_dum_close ='</tr>'; 
                 body = tr+td+tr_dum_close;
                 full_body  +=body;     
                 ince++;          
                 abc = 2;
               });
               
                $('#pract_show_dummy_numbers').html(full_body);

                $.ajax({
                url: base_url+'?r=ajaxrequest/getsubjectname',
                type:'POST',
                  data:{sub_id:sub_map_id_val},
                  success:function(data)
                  {
                    $('#hide_dum_sub_data').show();
                    $('#show_dummy_entry').show();
                        var jsonFormat = JSON.parse(data);
                        var body='';           
                        
                        var tr='<tr>';
                        var td='<td>1</td><td>'+jsonFormat['subject_code']+' </td><td>'+jsonFormat['subject_name']+' </td><td>'+jsonFormat['total_minimum_pass']+'</td><td>'+(jsonFormat['CIA_max']+jsonFormat['ESE_max'])+'</td>';
                        var tr_dum_close ='</tr>'; 
                        body = tr+td+tr_dum_close;
                        
                        $('#hide_dum_sub_data').show();                    
                        $('#show_dummy_entry').html(body);
                  }
                });

            }
            
          }
    });
}

function getExaminerNamesRePrint(sub_map_id_val)
{
    $('#pract_show_dummy_numbers').html('');   
    $('#pract_show_dummy_numbers').show(); 
    $.ajax({
        url: base_url+'?r=ajaxrequest/getverifypractsubsdeta',
        type:'POST',
          data:{year:$('#mark_year').val(),month:$('#exam_month').val(),sub_map_id:sub_map_id_val},
          success:function(data)
          {
            var parsed = JSON.parse(data);
            if(parsed=='NO')
            {
                $('#hide_dum_data').hide();
                $('#hide_dum_sub_data').hide();
                $('#pract_show_dummy_numbers').hide();
                krajeeDialog.alert('No Data Found'); 
                $("#examiner_name").prop('required', true);
                $("#chief_exam_name").prop('required', true);
                $('#examiner_name').html('');
                $('#chief_exam_name').html('');
                $('#examiner_name').val('');
                $('#chief_exam_name').val('');
                $('#examiner_name').prop('disabled', false);
                $('#chief_exam_name').prop('disabled', false);
                return false;
            }
            else
            {   
                $('#hide_dum_sub_data').show();        
                $('#hide_dum_data').show();     
                var abc=1;   
                var full_body='';   
                var ince = 1;         
                $.each(parsed,function(i,parsed)
                {

                 if(abc==1)
                 {                    
                    $('#examiner_name').val(parsed['examiner_name']).prop('disabled', true);
                    $('#chief_exam_name').val(parsed['chief_exam_name']).prop('disabled', true);
                    
                 }
                 var body='';
                 var tr='<tr>';
                 var td='<td>'+ince+'</td><td><input type="hidden" name=stu_map_ids[] value="'+parsed['student_map_id']+'" /> '+parsed['register_number']+' </td><td><input type="hidden" name=stu_marks[] value="'+parsed['out_of_100']+'" /> '+parsed['out_of_100']+' </td><td>'+parsed['approve_status']+' </td>';
                 var tr_dum_close ='</tr>'; 
                 body = tr+td+tr_dum_close;
                 full_body  +=body;     
                 ince++;          
                 abc = 2;
               });
               
                $('#pract_show_dummy_numbers').html(full_body);

                $.ajax({
                url: base_url+'?r=ajaxrequest/getsubjectname',
                type:'POST',
                  data:{sub_id:sub_map_id_val},
                  success:function(data)
                  {
                    $('#hide_dum_sub_data').show();
                    $('#show_dummy_entry').show();
                        var jsonFormat = JSON.parse(data);
                        var body='';           
                        
                        var tr='<tr>';
                        var td='<td>1</td><td>'+jsonFormat['subject_code']+' </td><td>'+jsonFormat['subject_name']+' </td><td>'+jsonFormat['total_minimum_pass']+'</td><td>'+(jsonFormat['CIA_max']+jsonFormat['ESE_max'])+'</td>';
                        var tr_dum_close ='</tr>'; 
                        body = tr+td+tr_dum_close;
                        
                        $('#hide_dum_sub_data').show();                    
                        $('#show_dummy_entry').html(body);
                  }
                });

            }
            
          }
    });
}

function bringYearMonthSubs(exam_month,exam_year)
{
    var batch_map_id = $('#stu_programme_selected').val();
    var send_to_dropdown = $('#dummy_exam_subject_code');  
    $.ajax({
        url: base_url+'?r=ajaxrequest/getdummyentsubje',
        type:'POST',
          data:{exam_year:exam_year,exam_month:exam_month,batch_map_id:batch_map_id},
          success:function(data)
          {
            var parsed = JSON.parse(data);
            send_to_dropdown.html('');
            if(parsed==0)
            {
                krajeeDialog.alert('No Data Found');
            }
            else
            {
               send_to_dropdown.append('<option value>---Select---</option>');
               $.each(parsed,function(i,parsed)
               {
                  send_to_dropdown.append("<option value='"+parsed['coe_subjects_id']+"' >"+parsed['subject_code']+"</option>");     
               });
            }
                
          }
        });
}

function ShowStudentArrears(register_number)
{
    $('#stu_arrear_subjects').val(register_number);    
}

$('#sub_info_internet').on('click',function(){
  var year = $('#mark_year').val();  
  var month = $('#mark_month').val();

  if(year=="" || month=="")
  {
    krajeeDialog.alert("Please select all feilds");
    return false;
  }
  else
  {
    $.ajax({
      url: base_url+'?r=mark-entry-master/subjectinformationdata',
      type:'POST',
      data:{year:year,month:month},
      success:function(data)
      {
        if(data!=0)
        {
          //alert(data);
          $('.subject_information_tbl').show();
          $('#sub_info_tbl').html(data);
        }
        else
        {
          krajeeDialog.alert('No data found..');
          $('.subject_information_tbl').hide();
          return false;
        }
        
      } 
    });
  }
});
$('#sub_info_internet_engg').on('click',function(){
  var year = $('#mark_year').val();  
  var month = $('#mark_month').val();

  if(year=="" || month=="")
  {
    krajeeDialog.alert("Please select all feilds");
    return false;
  }
  else
  {
    $.ajax({
      url: base_url+'?r=mark-entry-master/subjectinformationdataengg',
      type:'POST',
      data:{year:year,month:month},
      success:function(data)
      {
        if(data!=0)
        {
          //alert(data);
          $('.subject_information_tbl').show();
          $('#sub_info_tbl').html(data);
        }
        else
        {
          krajeeDialog.alert('No data found..');
          $('.subject_information_tbl').hide();
          return false;
        }
        
      } 
    });
  }
});



$('#sub_value').on('click',function(){
  var year = $('#mark_year').val();  
  var month = $('#mark_month').val();

  if(year=="" || month=="")
  {
    krajeeDialog.alert("Please select all feilds");
    return false;
  }
  else
  {
    $.ajax({
      url: base_url+'?r=coe-value-mark-entry/subadded',
      type:'POST',
      data:{year:year,month:month},
      success:function(data)
      {
        if(data!=0)
        {
          //alert(data);
          $('.subject_information_tbl').show();
          $('#sub_info_tbl').html(data);
        }
        else
        {
          krajeeDialog.alert('No data found..');
          $('.subject_information_tbl').hide();
          return false;
        }
        
      } 
    });
  }
});



function getStudentInfoPracts(register_number)
{
    if(register_number=='')
    {
        krajeeDialog.alert('Kindly Enter the From and To Register Numbers');
        return false;
    }
    var mark_type = $('#mark_type').val();
    var sub_map_id_val = $('#mark_subject_code').val();
    var examiner_name = $('#examiner_name').val();
    var chief_exam_name = $('#chief_exam_name').val();
    var register_num_from = $('#register_num_from').val();
    $.ajax({
        url: base_url+'?r=ajaxrequest/getverifypractsubsdetareprint',
        type:'POST',
          data:{year:$('#mark_year').val(),register_num_from:register_num_from,month:$('#exam_month').val(),mark_type:mark_type,sub_map_id:sub_map_id_val,register_number:register_number,examiner_name:examiner_name,chief_exam_name:chief_exam_name},
          success:function(data)
          {
                
                var jsonFormat = JSON.parse(data);
                if(jsonFormat=='NO')
                {
                    $('#hide_dum_data_send').hide();
                    $('#pract_show_dummy_numbers').hide();
                    krajeeDialog.alert('Arrear NO Data Found');
                }
                else if(jsonFormat=='NO_reg' || jsonFormat=='NO_DATA')
                {
                    $('#hide_dum_data_send').hide();
                    $('#pract_show_dummy_numbers').hide();
                    krajeeDialog.alert('No Data Found');
                }
                else
                {   
                    $('#hide_dum_data_send').show();
                    $('#pract_show_dummy_numbers').show();              
                    $('#pract_show_dummy_numbers').html('');   
                    $('#pract_show_dummy_numbers').html(jsonFormat);
                }
          }
    });
}
function getInternalArrearSubs(sem_id)
{
    $.ajax({
            url: base_url+"?r=ajaxrequest/getintarrearsubjects",
            type:'POST',
            data:{bat_map_val:$('#stu_programme_selected').val(),sem_id:sem_id,exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='MarkEntry[term]']:checked").val(),mark_type:$("input[name='MarkEntry[mark_type]']:checked").val()},
            success:function(data)
            {
                var parsed = $.parseJSON(data);     
                if(data==0)
                {
                    $('#mark_subject_code').html('');
                    $('#show_details_subs').hide();
                    $('#disp_show_details_subs').hide();
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;

                }
                else
                {  
                    $('#mark_subject_code').html('');
                    $('#mark_subject_code').append("<option value=''>------SELECT------</option>")               
                    $.each(parsed,function(i,parsed)
                    {
                        $('#mark_subject_code').append("<option value='"+parsed['coe_subjects_mapping_id']+"'>"+parsed['subject_code']+"</option>");
                    });
                }
            }

        });
}
function getIntArrearStuList()
{
    $('#show_details_subs').hide();
    $('#disp_show_details_subs').hide();
    if($('#mark_subject_code').val()=='')
    {
        krajeeDialog.alert('Select the Required Fields');     
        return false;
    }
    $.ajax({
        url: base_url+'?r=ajaxrequest/getsubjectname',
        type:'POST',
          data:{sub_id:$('#mark_subject_code').val()},
          success:function(data)
          {
            $('#hide_dum_sub_data').show();
            $('#show_dummy_entry').show();
                var jsonFormat = JSON.parse(data);
                var body='';           
                if(jsonFormat=='NO')
                {
                    $('#hide_dum_sub_data').hide();
                    $('#show_dummy_entry').hide();
                }
                else
                {
                    var tr='<tr>';
                    var td='<td>1</td><td>'+jsonFormat['subject_code']+' </td><td>'+jsonFormat['subject_name']+' </td><td>'+jsonFormat['CIA_min']+'</td><td>'+jsonFormat['CIA_max']+'</td><td>'+jsonFormat['total_minimum_pass']+'</td>';
                    var tr_dum_close ='</tr>'; 
                    body = tr+td+tr_dum_close;

                    $('#hide_dum_sub_data').show();                    
                    $('#show_dummy_entry').html(body);
                }
                
          }
        });
    $.ajax({
            url: base_url+"?r=ajaxrequest/getintarrearsubjectdetails",
            type:'POST',
            data:{sub_map_id:$('#mark_subject_code').val(), bat_map_val:$('#stu_programme_selected').val(),sem_id:$('#markentry-semester').val(),exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='MarkEntry[term]']:checked").val(),mark_type:$("input[name='MarkEntry[mark_type]']:checked").val()},
            success:function(data)
            {
                
                if(data==0)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;
                }
                else if(data==1)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('Mark Entry Completed');     
                    return false;
                }
                else if(data==2)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('Fields Selected Wrongly');     
                    return false;
                }
                else
                {                       
                    var body='';  
                    var full_body='';                    
                    var parsed = $.parseJSON(data);
                    var k=0;
                    var table_open ='<table class="table table-responsive table-stripped table-bordered table-hover"><tr class="info"><th>S.No.</th><th>Register Number</th><th>Name</th><th>Marks</th><th>Status</th></tr>';
                    $.each(parsed,function(i,parsed)
                    {
                        var tr='<tr>';
                        var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td><input type="text" id=dum_num_marks'+k+' autocomplete="off"  required=required onkeypress="numbersOnly(event);allowEntr(event,this.id);checkMaximum(this.id,this.value,'+parsed['CIA_max']+')" onchange="check_max_number(this.id,this.value); writeText(this.id,this.value);" name="ese_marks[]" /><td><input type="text" style="border: none;" readonly id=dum_num_marks'+k+'_1 required /></td>';
                        var tr_dum_close ='</tr>'; 
                        body += tr+td+tr_dum_close; 
                        k++;
                    });
                   full_body = table_open+body+"</table>";
                   $('#show_details_subs').show();
                   $('#disp_show_details_subs').show();
                   $('#show_details_subs').html(full_body);
                }
            }

        });
}
function getChangeHalls(exam_session,exam_date,year,month)
{
     $.ajax({
        type:'POST',
        url: base_url+"?r=ajaxrequest/getchangehalls",
        data: {
            exam_date: exam_date,year:year,month:month,exam_session:exam_session,
        },
        success: function(data)
        {
            if(data){
                var jsonFormat = JSON.parse(data);        
                $('#hall_names').html('');
                var drop_items= "<option value=''> ---- Select ---- </option>";
                  for (var i = 0; i < jsonFormat.length; i++){
                    drop_items += "<option value='" + jsonFormat[i].coe_hall_master_id+ "'>" + jsonFormat[i].hall_name+ "</option>";
                  }
                $("#hall_names").html(drop_items); 
            }
        }
    });
}
function getExamHallStudents(hall_id,exam_date,exam_session,year,month)
{
     $.ajax({
        type:'POST',
        url: base_url+"?r=ajaxrequest/getchangehallstudents",
        data: {
            exam_date: exam_date,year:year,hall_id:hall_id,month:month,exam_session:exam_session,
        },
        success: function(data)
        {    
             var jsonFormat = JSON.parse(data); 
             $(".show_hall_result_data").hide(); 
             $(".show_hall_result_data").html(''); 
             $('.hide_hall_submit').hide();
             if(jsonFormat==0)
             {                
                krajeeDialog.alert('No Data Found / Dummy Number Arranged');
             }
             else
             {
                $('.hide_hall_submit').show();
                $(".show_hall_result_data").show(); 
                $(".show_hall_result_data").html(jsonFormat);     
             }
            
        }
    });
}

function getExternalEnggStudentsList()
{
    var subject_ese_min='';
    var subject_ese_max='';
    var subject_total_min='';
    if($('#mark_subject_code').val()=='')
    {
        $('#hide_dum_sub_data').hide();
        $('#show_dummy_entry').hide();
        $('#show_details_subs').hide();
        $('#disp_show_details_subs').hide();
        krajeeDialog.alert('Select the Required Fields');     
        return false;
    }
    $.ajax({
        url: base_url+'?r=ajaxrequest/getsubjectname',
        type:'POST',
          data:{sub_id:$('#mark_subject_code').val()},
          success:function(data)
          {
            $('#hide_dum_sub_data').show();
            $('#show_dummy_entry').show();
                var jsonFormat = JSON.parse(data);
                var body='';           
                if(jsonFormat=='NO')
                {
                    $('#hide_dum_sub_data').hide();
                    $('#show_dummy_entry').hide();
                }
                else
                {
                    var tr='<tr>';
                    var td='<td>1</td><td>'+jsonFormat['subject_code']+' </td><td>'+jsonFormat['subject_name']+' </td><td>'+jsonFormat['ESE_min']+'</td><td>'+jsonFormat['ESE_max']+'</td><td>'+jsonFormat['total_minimum_pass']+'</td>';
                    var tr_dum_close ='</tr>'; 
                    body = tr+td+tr_dum_close;

                    $('#hide_dum_sub_data').show();                    
                    $('#show_dummy_entry').html(body);
                }
                
          }
        });   
            
    $.ajax({
            
            url: base_url+"?r=ajaxrequest/getexternalstusubjectdetails",
            type:'POST',
            data:{sub_map_id:$('#mark_subject_code').val(), bat_map_val:$('#stu_programme_selected').val(),sem_id:$('#markentry-semester').val(),exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='MarkEntryMaster[term]']:checked").val(),mark_type:$("input[name='MarkEntryMaster[mark_type]']:checked").val()},
            success:function(data)
            {
                $('#show_details_subs').hide();
                $('#disp_show_details_subs').hide();
                if(data==0)
                {
                    $('#markentry-semester').val('');
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;
                }
                else if(data==1)
                {
                    $('#markentry-semester').val('');
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('Mark Entry Completed View Marks');     
                    return false;
                }
                else if(data==2)
                {
                    $('#markentry-semester').val('');
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('No Internal Marks Found');     
                    return false;
                }
                else if(data==3)
                {
                    $('#exam_month').val('');
                    $('#stu_programme_selected').val('')
                    $('#markentry-semester').val('');
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('Wrong Entry Kindly Check Your Submission');     
                    return false;
                }
                else
                {                
                    var body='';  
                    var full_body='';                    
                    var parsed = $.parseJSON(data);
                    var k=0;
                    var table_open ='<table class="table table-responsive table-stripped table-bordered table-hover"><tr class="info"><th>S.No.</th><th>Register Number</th><th>Name</th><th>CIA</th><th>PRESENT/ABSENT</th><th>OUT OF 100</th><th>ESE</th><th>RESULT</th><th>Marks in Words</th></tr>';
                    $.each(parsed,function(i,parsed)
                    {
                        var tr='<tr>';
                       
                                /*if(parsed_ab=='YES')
                                {
                                    var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td><input type="hidden" readonly=readonly width=30 id=cia_marks'+k+' name="cia_marks[]" value="'+parsed['category_type_id_marks']+'" /> '+parsed['category_type_id_marks']+'</td><td><input type="hidden" width=30 id=ab_status'+k+' name="present_status[]" value="Absent"  /> Absent </td><td><input type="text" id=ese_marks'+k+' autocomplete="off"  required=required onkeypress="numbersOnly(event);allowEntr(event,this.id);" onchange="getArtstuResult(this.id,this.value,'+parsed['category_type_id_marks']+','+parsed['ESE_min']+','+parsed['ESE_max']+','+parsed['total_minimum_pass']+'); writeText(this.id,this.value);" value="0" readonly="readonly" name="ese_marks[]" /><td><input type="text" readonly=readonly name="ext_result[]"  id=ext_result'+k+'_1 value="Absent" required /></td><td><input type="text" style="border: none;" readonly id=ese_marks'+k+'_1 required /></td>';
                                }
                                else
                                {*/
                                    var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td><input type="hidden" readonly=readonly width=30 id=cia_marks'+k+' name="cia_marks[]" value="'+parsed['category_type_id_marks']+'" /> '+parsed['category_type_id_marks']+'</td><td><input type="hidden" width=30 id=ab_status'+k+' name="present_status[]" value="Present" /> Present </td><td><input type="text" id=ese_marks'+k+' autocomplete="off"  required=required onkeypress="numbersOnly(event);allowEntr(event,this.id);" onchange="getEnggstuResult(this.id,this.value,'+parsed['category_type_id_marks']+','+parsed['ESE_min']+','+parsed['ESE_max']+','+parsed['CIA_max']+','+parsed['total_minimum_pass']+'); writeText(this.id,this.value);" name="ese_marks[]" /><td><input type="text" readonly=readonly name="ext_converted[]"  id=ext_conv_marks'+k+'_1 required /></td><td><input type="text" readonly=readonly name="ext_result[]"  id=ext_result'+k+'_1 required /></td><td><input type="text" style="border: none;" readonly id=ese_marks'+k+'_1 required /></td>';
                                //}
                              
                        var tr_dum_close ='</tr>'; 
                        body += tr+td+tr_dum_close; 
                        k++;
                    });
                   full_body = table_open+body+"</table>";
                   $('#show_details_subs').show();
                   $('#disp_show_details_subs').show();
                   $('#show_details_subs').html(full_body);
                }
            }

        });
}

function getEnggstuResult(student_map_id,ESE_marks,student_cia,sub_ese_min,subject_ese_max,subject_cia_max,subject_total_min)
{
    var stu_total_marks = parseInt(ESE_marks)+parseInt(student_cia);

    var id_num = student_map_id.substr(student_map_id.length-1);
    var number = student_map_id.match(/\d+/g).map(Number);
    var next_focus = parseInt(number)+parseInt(1);
    var result_focus = parseInt(number);

    var number_length = number.toString().length;
    var number_splide = student_map_id.slice(0,student_map_id.length-number_length);
    var id_name = student_map_id.slice(0,student_map_id.length-1);
    var total_max_marks = parseInt(subject_cia_max)+parseInt(subject_ese_max);
    var converted_marks = Math.round(parseInt((ESE_marks*subject_ese_max)/100));
    if(ESE_marks>total_max_marks)
    {
        $('#ext_conv_marks'+result_focus+'_1').val('');
        $('#ext_result'+result_focus+'_1').val('');
        $('#'+student_map_id).val('');
        krajeeDialog.alert('Marks Crossing Maximum Marks');
        return false;
    }
    else if(converted_marks<sub_ese_min || stu_total_marks<subject_total_min )
    {
        $('#ext_conv_marks'+result_focus+'_1').val(converted_marks);
        $('#ext_result'+result_focus+'_1').val('Fail');
        $('#ext_result'+result_focus+'_1').css("border","1px solid #e60000");
    }
    else{
        $('#ext_conv_marks'+result_focus+'_1').val(converted_marks);
        $('#ext_result'+result_focus+'_1').val('Pass');
        $('#ext_result'+result_focus+'_1').css("border","1px solid #00b33c");
    }
    return true;
}
function getSubjectNameSubCode(subject_code)
{
    $.ajax({
        url: base_url+'?r=ajaxrequest/getsubjectinfonamedetails',
        type:'POST',
          data:{subject_code:subject_code},
          success:function(data)
          {
           
            var jsonFormat = JSON.parse(data);
            if(jsonFormat=='NO')
            {
                $('#subjects-subject_name').val('');
                $('#subjects-subject_name').val('').attr('readonly',false);
            }
            else
            {
              $('#subjects-subject_name').val(jsonFormat['subject_name']).attr('readonly',true);
            }
                
          }
        });
}
function getViewIntArrearStuList()
{
    $('#show_details_subs').hide();
    $('#disp_show_details_subs').hide();
    if($('#mark_subject_code').val()=='')
    {
        krajeeDialog.alert('Select the Required Fields');     
        return false;
    }
    $.ajax({
        url: base_url+'?r=ajaxrequest/getsubjectname',
        type:'POST',
          data:{sub_id:$('#mark_subject_code').val()},
          success:function(data)
          {
            $('#hide_dum_sub_data').show();
            $('#show_dummy_entry').show();
                var jsonFormat = JSON.parse(data);
                var body='';           
                if(jsonFormat=='NO')
                {
                    $('#hide_dum_sub_data').hide();
                    $('#show_dummy_entry').hide();
                }
                else
                {
                    var tr='<tr>';
                    var td='<td>1</td><td>'+jsonFormat['subject_code']+' </td><td>'+jsonFormat['subject_name']+' </td><td>'+jsonFormat['CIA_min']+'</td><td>'+jsonFormat['CIA_max']+'</td><td>'+jsonFormat['total_minimum_pass']+'</td>';
                    var tr_dum_close ='</tr>'; 
                    body = tr+td+tr_dum_close;

                    $('#hide_dum_sub_data').show();                    
                    $('#show_dummy_entry').html(body);
                }
                
          }
        });
    $.ajax({
            url: base_url+"?r=ajaxrequest/getviewintarrearsubjectdetails",
            type:'POST',
            data:{sub_map_id:$('#mark_subject_code').val(), bat_map_val:$('#stu_programme_selected').val(),sem_id:$('#markentry-semester').val(),exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='MarkEntry[term]']:checked").val(),mark_type:$("input[name='MarkEntry[mark_type]']:checked").val()},
            success:function(data)
            {
                
                if(data==0)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;
                }
                else if(data==1)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('Mark Entry Completed');     
                    return false;
                }
                else if(data==2)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('Fields Selected Wrongly');     
                    return false;
                }
                else
                {                       
                    var body='';  
                    var full_body='';                    
                    var parsed = $.parseJSON(data);
                    var k=0;
                    var mark_avail = 0;
                    var table_open ='<table class="table table-responsive table-stripped table-bordered table-hover"><tr class="info"><th>S.No.</th><th>Register Number</th><th>Name</th><th>Marks</th></tr>';
                    $.each(parsed,function(i,parsed)
                    {
                        var tr='<tr>';
                        if(parsed['category_type_id_marks']!='')
                        {
                            mark_avail = 1;
                            var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td>'+parsed['category_type_id_marks']+'</td>';
                        }
                        else
                        {
                            var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td><input type="text" id=dum_num_marks'+k+' autocomplete="off"  required=required onkeypress="numbersOnly(event);allowEntr(event,this.id);" onchange="check_max_number(this.id,this.value); writeText(this.id,this.value);" name="ese_marks[]" value="'+parsed['category_type_id_marks']+'" /></td>';
                        }
                        
                        var tr_dum_close ='</tr>'; 
                        body += tr+td+tr_dum_close; 
                        k++;
                    });
                   full_body = table_open+body+"</table>";
                   if(mark_avail==0)
                   {
                        $('#change_style_int').show();
                   }
                   $('#show_details_subs').show();
                   $('#disp_show_details_subs').show();
                   $('#show_details_subs').html(full_body);
                }
            }

        });
}
function getViewInternalArrearSubs(sem_id)
{
    $.ajax({
            url: base_url+"?r=ajaxrequest/getviewintarrearsubjects",
            type:'POST',
            data:{bat_map_val:$('#stu_programme_selected').val(),sem_id:sem_id,exam_year:$('#mark_year').val(),month:$('#exam_month').val(),term:$("input[name='MarkEntry[term]']:checked").val(),mark_type:$("input[name='MarkEntry[mark_type]']:checked").val()},
            success:function(data)
            {
                var parsed = $.parseJSON(data);     
                if(data==0)
                {
                    $('#mark_subject_code').html('');
                    $('#show_details_subs').hide();
                    $('#disp_show_details_subs').hide();
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;

                }
                else
                {  
                    $('#mark_subject_code').html('');
                    $('#mark_subject_code').append("<option value=''>------SELECT------</option>")               
                    $.each(parsed,function(i,parsed)
                    {
                        $('#mark_subject_code').append("<option value='"+parsed['coe_subjects_mapping_id']+"'>"+parsed['subject_code']+"</option>");
                    });
                }
            }

        });
}
function PrintTextInto(id,number)
{
    
    var numberArray = {'0':'ZERO','1':'ONE','2':'TWO','3':'THREE','4':'FOUR','5':'FIVE','6':'SIX','7':'SEVEN','8':'EIGHT','9':'NINE'};
    var digits = number.split("");
    var string_text = '';
    for (var i = 0; i < digits.length; i++) 
    {
        string_text +=numberArray[digits[i]]+" ";
    }
    if(number<50)
    {
        $('#'+id).addClass('print_red_color');  
        $('#'+id).val(string_text).css({"border": "1px solid #BE3F48", "color": "#BE3F48"});  
    }
    else
    {
        $('#'+id).addClass('print_green_color'); 
        $('#'+id).val(string_text).css({"border": "1px solid #00a65a", "color": "#00a65a"});
    }    
    
}
//Withdraw starts here
$('#withdraw_btn_MARKS').on('click',function(){
    var year = $('#withdraw_year').val();
    var month = $('#withdraw_month').val();
    var sem = $('#sem').val();
    var reg = $('#stu_reg_num').val();
    if(year=="" && month=="" && sem=="" && reg=="")
    {
        krajeeDialog.alert("Please select all fields");
        return false;
    }
    else
    {
        $.ajax({
            url: base_url+'?r=mark-entry-master/withdrawsublist',
            type:'POST',
            data:{year:year,month:month,sem:sem,reg:reg},
            success:function(data){
                if(data==0)
                {
                    krajeeDialog.alert("No Data Found");
                    return false;
                }
                else
                {
                   $('.withdraw').show();
                   $('#withdraw_entry_tbl').html(data);
                }
            }
        });
    }
});
//Withdraw ends here
function getChangeExamSubjects(exam_session,exam_date,year,month)
{
     $.ajax({
        type:'POST',
        url: base_url+"?r=ajaxrequest/getchangeexasubjects",
        data: {
            exam_date: exam_date,year:year,month:month,exam_session:exam_session,
        },
        success: function(data)
        {
            if(data)
            {
                $('#exam_subject_id').html('');
                var jsonFormat = JSON.parse(data);    
                if(jsonFormat==0)
                {
                    krajeeDialog.alert('NO DATA FOUND');
                    return false;
                }
                else{
                    var drop_items= "<option value=''> ---- Select ---- </option>";
                      for (var i = 0; i < jsonFormat.length; i++){
                        drop_items += "<option value='" + jsonFormat[i].coe_subjects_id+ "'>" + jsonFormat[i].subject_code+ "</option>";
                      }
                    $("#exam_subject_id").html(drop_items);
                }    
                 
            }
        }
    });
}

function getExamSubStudents(sub_id,exam_date,exam_session,year,month)
{
     $.ajax({
        type:'POST',
        url: base_url+"?r=ajaxrequest/getchangeexamsubstudents",
        data: {
            exam_date: exam_date,year:year,sub_id:sub_id,month:month,exam_session:exam_session,
        },
        success: function(data)
        {    
             var jsonFormat = JSON.parse(data); 
             $(".show_hall_result_data").hide(); 
             $(".show_hall_result_data").html(''); 
             $('.hide_hall_submit').hide();
             if(jsonFormat==0)
             {                
                krajeeDialog.alert('No Data Found / Dummy Number Arranged');
             }
             else
             {
                $('.hide_hall_submit').show();
                $(".show_hall_result_data").show(); 
                $(".show_hall_result_data").html(jsonFormat);   
                  
             }
            
        }
    });
}

$('#singleAttempt').on('click',function()
{
  var year = $('#year').val();
  var batch_id = $('#BATCH_id').val();
  var month = $('#exam_month').val();
  $.ajax({
      url: base_url+'?r=ajaxrequest/markpercentreport',
      type:'POST',
      data:{year:year,month:month,batch_id:batch_id},
      success:function(data){
        if(data==0){
          krajeeDialog.alert('No data found..');
          $('#mark_percent').hide();
        }else{
          $('.mark_percent_print_btn').show();
          $('#mark_percent').show();
          $('#mark_percent').html(data);
        }
      }
  });
});

function check_sub_max_number(id,dum_value,sub_max_val) 
{
    if(dum_value=='-1')
    {
        $('#'+id).val('').focus();
        krajeeDialog.alert('Use Absent Import to Mark Absent');
        return false;
    }
    if(dum_value>sub_max_val)
    {
        $('#'+id).val('').focus();
        krajeeDialog.alert('Wrong Entry');
        return false;
    }
    else if(dum_value>100)
    {
        $('#'+id).val('').focus();
        krajeeDialog.alert('Wrong Entry');
        return false;
    }
}
$('#toppersList').on('click',function()
{
  var batch_id = $('#stu_batch_id_selected').val();
  var programme = $('#stu_programme_selected').val();
  $.ajax({
      url: base_url+'?r=ajaxrequest/topperslistrepo',
      type:'POST',
      data:{programme:programme,batch_id:batch_id},
      success:function(data){
        if(data==0){
          krajeeDialog.alert('No data found..');
          $('#mark_percent').hide();
        }else{
          $('.mark_percent_print_btn').show();
          $('#mark_percent').show();
          $('#mark_percent').html(data);
        }
      }
  });
});



$('#view_withdraw_btn').on('click',function(){
    var year = $('#withdraw_year').val();
    var month = $('#withdraw_month').val();
    var reg = $('#stu_reg_num').val();
    if(year=="" && month=="" && reg=="")
    {
        krajeeDialog.alert("Please select all fields");
        return false;
    }
    else
    {
        $.ajax({
            url: base_url+'?r=ajaxrequest/viewwithdrawsublist',
            type:'POST',
            data:{year:year,month:month,reg:reg},
            success:function(data){
                if(data==0)
                {
                    krajeeDialog.alert("No Data Found");
                    return false;
                }
                else
                {
                   $('.withdraw').show();
                   $('#withdraw_entry_tbl').html(data);
                }
            }
        });
    }
});

function getMarkSubInfoma(min_max_info,batch_id)
{
    if(batch_id='' && min_max_info=='')
    {
        krajeeDialog.alert("Please select all fields");
        return false;
    }
    else
    {
        $.ajax({
            url: base_url+'?r=ajaxrequest/getsubminmaxinfo',
            type:'POST',
            data:{batchhh_id:$('#stu_cs_batch_id').val(),min_max_info:min_max_info},
            success:function(data)
            {
                $('.withdraw').hide();
                $('#withdraw_entry_tbl').html('');
                if(data==0)
                {
                    krajeeDialog.alert("No Data Found");
                    return false;
                }
                else
                {
                   $('.withdraw').show();
                   $('#withdraw_entry_tbl').html(data);
                }
            }
        });
    }
}

function getCiaNotZeroStuList(batch_id) 
{
    $.ajax({
            url: base_url+'?r=ajaxrequest/getcianotzero',
            type:'POST',
            data:{batchhh_id:batch_id},
            success:function(data)
            {
                $('.withdraw').hide();
                $('#withdraw_entry_tbl').html('');
                if(data==0)
                {
                    krajeeDialog.alert("No Data Found");
                    return false;
                }
                else
                {
                   $('.withdraw').show();
                   $('#withdraw_entry_tbl').html(data);
                }
            }
        });
}

$('#consolidate_pass_repo').on('click',function(){
    var year = $('#withdraw_year').val();
    var batch_id = $('#stu_cs_batch_id').val();
    var degree_name = $('#degree_name').val();
    var month = $('#consolidate_month').val();
    if(year=="" && month=="" && batch_id=='' && degree_name=='')
    {
        krajeeDialog.alert("Please select all fields");
        return false;
    }
    else
    {
        $.ajax({
            url: base_url+'?r=ajaxrequest/viewconolidatereport',
            type:'POST',
            data:{year:year,month:month,batch_id:batch_id,degree_name:degree_name},
            success:function(data){
                if(data==0)
                {
                    krajeeDialog.alert("No Data Found");
                    return false;
                }
                else
                {
                   $('.withdraw').show();
                   $('#withdraw_entry_tbl').html(data);
                }
            }
        });
    }
});
function getStuRgNumber(reg_num)
{
    $.ajax({
            url: base_url+'?r=ajaxrequest/getstudentinfo',
            type:'POST',
            data:{reg_num:reg_num},
            success:function(data)
            {
                $('#disp_name_of_stu').hide();
                $('#disp_name_of_stu').html('');
                if(data==0)
                {
                    krajeeDialog.alert("No Data Found");
                    return false;
                }
                else
                {
                   $('#disp_name_of_stu').show();
                   $('#disp_name_of_stu').html(data);
                }
            }
        });
}
$('#rangebutton_marks').on('click',function(){

  var batch = $('#stu_batch_id_selected').val();
  var batch_map_id = $('#stu_programme_selected').val();
  var year = $('#course_year').val();
  var month = $('#exam_month').val();
  var val_from = $('#val_from').val();
  var val_to = $('#val_to').val();
  var exam_subject_code = $('#exam_subject_code').val();
  var exam_semester = $('#exam_semester').val();
  var mark_type = $('#exam_type').val();
  //alert(batch+"--"+batch_map_id+"--"+year+"--"+month+"--"+mark_type);
  $.ajax({
    url: base_url+'?r=mark-entry-master/rangemarksanalysis',
    type:'POST',
    data:{year:year,month:month,batch_map_id:batch_map_id,batch:batch,mark_type:mark_type,val_from:val_from,val_to:val_to,exam_semester:exam_semester,exam_subject_code:exam_subject_code},
    success:function(data){
      if(data==0){
        $('.pgm_analysis_print_btn').hide();
        $('#programme_result').hide();        
        krajeeDialog.alert("No Data available");        
      }else{
         $('.pgm_analysis_print_btn').show();
        $('#programme_result').show();
        $('#programme_result').html(data);
      }      
    } 
  }); 
});

function getSujectsCode(semester)
{
    $('#exam_subject_code').html('');
    var batch_map_id = $('#stu_programme_selected').val();
    var sem = $('#exam_semester').val();
    var type = $('#mark_type').val();

    $.ajax({
        url: base_url+'?r=ajaxrequest/getsubjectcode',
        type:'POST',
        data:{batch_map_id:batch_map_id,sem:sem,type:type},
            
        success:function(data)
        {
          //alert(data);
          $("#exam_subject_code").html('');
          $("#exam_subject_code").append("<option value='' > ---- Select --- </option>");
          var parsed = $.parseJSON(data);
          $.each(parsed,function(i,parsed){
              $("#exam_subject_code").append("<option value="+parsed['sub_id']+">"+parsed['subject_code']+"</option>");
          });
        }
    });
}
function changeThisCheckBboxVal(id,val)
{
   if($('#'+id).prop('checked')==true)
   {
      $('#'+id).val('YES');
      $('#'+id).prop('checked',true);
   }
   else
   {
    $('#'+id).prop('checked',false);
      $('#'+id).val('NO');
   }
}
function changeThisCheckBboxValRev(id,val)
{
   if($('#'+id).prop('checked')==true)
   {
      $('#'+id).val('YES');
      $('#'+id).prop('checked',true);
   }
   else
   {
    $('#'+id).prop('checked',false);
      $('#'+id).val('NO');
   }
}

function getRevalResult(year,month)
{
    $.ajax({
        url: base_url+'?r=ajaxrequest/getrevalrepo',
        type:'POST',
        data:{year:year,month:month},
        success:function(data)
        {
          $("#reval_batch_report").hide();
          $("#reval_batch_report_ex").hide();
          $("#reval_batch_report_ex").html('');
          if(data==0)
          {
            krajeeDialog.alert('NO DATA FOUND');
          }
          else
          {
            $("#reval_batch_report").show();
            $("#reval_batch_report_ex").show();
            $("#reval_batch_report_ex").html(data);
          }
        }
    });
}
function getListOfRevalSubs(year,month)
{
    $.ajax({
        url: base_url+'?r=ajaxrequest/getrevalsubjecs',
        type:'POST',
        data:{year:year,month:month},
        success:function(data)
        {

          $("#exam_subject_code").html('');
          $("#exam_subject_code").append("<option value='' > ---- Select --- </option>");
          var parsed = $.parseJSON(data);          
          if(parsed==0)
          {
            krajeeDialog.alert('NO DATA FOUND');
          }
          else
          {
            $.each(parsed,function(i,parsed){
                  $("#exam_subject_code").append("<option value="+parsed['sub_id']+">"+parsed['subject_code']+"</option>");
              });
          }
        }
    });
}
function getlistofRevaStu(year,month,subject_id,mark_out_of)
{
    $.ajax({
        url: base_url+'?r=ajaxrequest/getrevalstulistapp',
        type:'POST',
        data:{year:year,month:month,subject_id:subject_id,mark_out_of},
        success:function(data)
        {

            if(data!=0)
            {
              $('.tbl_n_submit_revaluation').show();
              $('#stu_revaluation_tbl').html(data);
            } 
            else
            {
              krajeeDialog.alert("No data found");
              $('.tbl_n_submit_revaluation').hide();
              return false;
            } 
        }
    });

    $.ajax({
        url: base_url+'?r=ajaxrequest/getsubjectnamewithsubid',
        type:'POST',
          data:{sub_id:$('#exam_subject_code').val()},
          success:function(data)
          {
            $('#hide_dum_sub_data').show();
            $('#show_dummy_entry').show();
                var jsonFormat = JSON.parse(data);
                var body='';           
                if(jsonFormat=='NO')
                {
                    $('#hide_dum_sub_data').hide();
                    $('#show_dummy_entry').hide();
                }
                else
                {
                    var tr='<tr>';
                    var td='<td>1</td><td>'+jsonFormat['subject_code']+' </td><td>'+jsonFormat['subject_name']+' </td><td>'+jsonFormat['ESE_min']+'</td><td>'+jsonFormat['ESE_max']+'</td><td>'+jsonFormat['total_minimum_pass']+'</td>';
                    var tr_dum_close ='</tr>'; 
                    body = tr+td+tr_dum_close;

                    $('#hide_dum_sub_data').show();                    
                    $('#show_dummy_entry').html(body);
                }
                
          }
        });
}

function getPracticalExamDa(exam_year,month) 
{
    $.ajax({
        url: base_url+'?r=ajaxrequest/getpractqpexamdate',
        type:'POST',
        data:{exam_year:exam_year,month:month},
        success:function(data){       
            $('#exam_date').html('');                    
            var parsed = $.parseJSON(data);
            var old_ex_date = '';
            
            $('#exam_date').html('<option value="" >----Select----</option>');
            if(parsed=='')
            {   
                krajeeDialog.alert('NO DATA FOUND');
                return false;
            }
            else
            {
                $.each(parsed,function(i,parsed)
                {
                  if(old_ex_date!=parsed['exam_date'])
                  {
                    old_ex_date=parsed['exam_date'];
                    $('#exam_date').append("<option value='"+parsed['exam_date']+"' >"+parsed['exam_date']+"</option>");
                  }
         
                });    
            }
        }
    });
}

function getStuSubGradeDetail(exam_year,month,reg_num,sub_code) 
{
    $.ajax({
        url: base_url+'?r=ajaxrequest/getsubjectnamewithsubid',
        type:'POST',
          data:{sub_id:sub_code},
          success:function(data)
          {
            $('#hide_dum_sub_data').show();
            $('#show_dummy_entry').show();
                var jsonFormat = JSON.parse(data);
                var body='';           
                if(jsonFormat=='NO')
                {
                    $('#hide_dum_sub_data').hide();
                    $('#show_dummy_entry').hide();
                }
                else
                {
                    var tr='<tr>';
                    var td='<td>1</td><td>'+jsonFormat['subject_code']+' </td><td>'+jsonFormat['subject_name']+' </td><td>'+jsonFormat['ESE_min']+'</td><td>'+jsonFormat['ESE_max']+'</td><td>'+jsonFormat['total_minimum_pass']+'</td>';
                    var tr_dum_close ='</tr>'; 
                    body = tr+td+tr_dum_close;

                    $('#hide_dum_sub_data').show();                    
                    $('#show_dummy_entry').html(body);
                }
                
          }
        });

    $.ajax({
        url: base_url+'?r=ajaxrequest/getstusubgradeifo',
        type:'POST',
        data:{exam_year:exam_year,month:month,reg_num:reg_num,sub_code:sub_code},
        success:function(data){                       
            var parsed = $.parseJSON(data);   
            $('#show_details_subs').hide();
            $('#disp_show_details_subs').hide();      
            if(parsed==0)
            {   
                krajeeDialog.alert('NO DATA FOUND');
                return false;
            }
            else
            {
                var body='';  
                var full_body='';   
                var k=0;
                var table_open ='<table class="table table-responsive table-stripped table-bordered table-hover"><tr class="info"><th>S.No.</th><th>Register Number</th><th>Name</th><th>Subject Code</th><th>CIA</th><th>ESE</th><th>TOTAL</th><th>RESULT</th><th>GRADE POINT</th><th>GRADE NAME</th><th>NEW GRADE</th></tr>';
                $.each(parsed,function(i,parsed)
                {
                    var tr='<tr>';
                    var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['student_map_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+' </td><td> <input type="hidden" name=sub_map_id[] value='+parsed['subject_map_id']+' /> '+parsed['subject_code']+' </td><td> <input type="hidden" name=year[] value='+parsed['year']+' /><input type="hidden" name=mark_type[] value='+parsed['mark_type']+' /><input type="hidden" name=term[] value='+parsed['term']+' /><input type="hidden" name=month[] value='+parsed['month']+' /> '+parsed['CIA']+' </td><td>'+parsed['ESE']+'</td><td>'+parsed['total']+'</td><td>'+parsed['result']+'</td><td>'+parsed['grade_point']+'</td><td>'+parsed['grade_name']+'</td><td><input type="text" required name=grade_change[] /> </td>';
                    var tr_dum_close ='</tr>'; 
                    body += tr+td+tr_dum_close; 
                    k++;
                });
               full_body = table_open+body+"</table>";
               $('#show_details_subs').show();
               $('#disp_show_details_subs').show();
               $('#show_details_subs').html(full_body);  
            }
        }
    });
}

$('#studentArrear').on('click',function()
{
  var year = $('#year').val();
  var batch_id = $('#BATCH_id').val();
  var month = $('#exam_month').val();
  $.ajax({
      url: base_url+'?r=ajaxrequest/getstudentwisearreacount',
      type:'POST',
      data:{batch_id:batch_id},
      success:function(data){
        if(data==0){
          krajeeDialog.alert('No data found..');
          $('#mark_percent').hide();
        }else{
          $('.mark_percent_print_btn').show();
          $('#mark_percent').show();
          $('#mark_percent').html(data);
        }
      }
  });
});
function getSubCodesPrac(semester,batch_map_id)
{
  $.ajax({
      url: base_url+'?r=ajaxrequest/getpractsubscodes',
      type:'POST',
      data:{semester:semester,batch_map_id:batch_map_id},
      success:function(data)
      {
        var parsed = $.parseJSON(data);
        $('#subject_map_id').html('');
        if(parsed==0)
        {
          krajeeDialog.alert('No data found..');
        }
        else
        {   
          $('#subject_map_id').html(parsed);
        }
      }
  });
}

function getManSubCodes(batch_id,batch_mapping_id,man_sub_id)
{
    $.ajax({
          url: base_url+'?r=ajaxrequest/getmandatorypapers',
          type:'POST',
          data:{batch_id:batch_id,batch_mapping_id:batch_mapping_id,man_sub_id:man_sub_id},
          success:function(data)
          {
            var parsed = $.parseJSON(data);
            $('#display_data').html('');
            if(parsed==0)
            {
              krajeeDialog.alert('No data found..');
              return false;
            }
            else
            {   
                $('#display_data').html(parsed);
            }
          }
      });
}

function getSubjectsListPaper(batch_id,batch_mapping_id)
{
    $.ajax({
        url: base_url+'?r=ajaxrequest/getmansubnamedetails',
        type:'POST',
        data:{batch_mapping_id:batch_mapping_id,batch_id:batch_id},
        success:function(data)
        {     
            var parsed = $.parseJSON(data);
            $('#man_subject_id').html('');
            if(parsed==0)
            {
                krajeeDialog.alert('No data found..');
                return false;
            }
            else
            {   
                $('#man_subject_id').html(parsed);
            }
        }
    });
}
function checkMaximum(ele_id,enter_val,maximum_marks)
{

    if(enter_val>maximum_marks)
    {
        krajeeDialog.alert('Wrong Entry Maximum Marks Crossed..');
        $('#'+ele_id).val('');
        return false;
    }
}

function getArtsSubInfo(sub_code,year,month)
{
    $.ajax({
        url: base_url+'?r=ajaxrequest/additionalcreditsubinfo',
        type:'POST',
        data:{sub_code:sub_code,year:year,month:month},
        success:function(data)
        {     
            var parsed = $.parseJSON(data);
            $('#add_sub_name').val('').prop('readonly', false);
            $('#add_cia_min').val('').prop('readonly', false);
            $('#add_credits').val('').prop('readonly', false);
            $('#add_cia_max').val('').prop('readonly', false);
            $('#add_ese_min').val('').prop('readonly', false);
            $('#add_ese_max').val('').prop('readonly', false);
            $('#min_pass').val('').prop('readonly', false);
            if(parsed==0 || parsed==1)
            {                
                
            }
            else
            {   
                $('#add_sub_name').val(parsed['sub_name']).prop('readonly', true);
                $('#add_credits').val(parsed['credits']).prop('readonly', true);
                if( (parsed['ese_max']!==0 && parsed['ese_max']!='' ) && (parsed['ese_min']!==0 && parsed['ese_min']!='') )
                {
                    $('#add_ese_max').val(parsed['ese_max']).prop('readonly', true);
                    $('#add_ese_min').val(parsed['ese_min']).prop('readonly', true);
                    $('#add_cia_max').val(0).prop('readonly', true);
                    $('#add_cia_min').val(0).prop('readonly', true);
                    $('#min_pass').val(parsed['min_pass']).prop('readonly', true);
                }
                else if( (parsed['cia_max']!==0 && parsed['cia_max']!='') && (parsed['cia_min']!==0  && parsed['cia_min']!='' ))
                {
                    $('#add_cia_max').val(parsed['cia_max']).prop('readonly', true);
                    $('#add_cia_min').val(parsed['cia_min']).prop('readonly', true);
                    $('#add_ese_max').val(0).prop('readonly', true);
                    $('#add_ese_min').val(0).prop('readonly', true);
                    $('#min_pass').val(parsed['min_pass']).prop('readonly', true);
                }

            }
        }
    });
}
function getArtsSubInfoUpdate(sub_code)
{
    $.ajax({
        url: base_url+'?r=ajaxrequest/additionalcreditsubinfo',
        type:'POST',
        data:{sub_code:sub_code},
        success:function(data)
        {     
            var parsed = $.parseJSON(data);            
            if(parsed==0 || parsed==1)
            {                
                $('#add_sub_name_update').val('');
            }
            else
            {   
                $('#add_sub_name_update').val(parsed['sub_name']);                
            }
        }
    });
}
function updateSubjectName(sub_code)
{
    $.ajax({
        url: base_url+'?r=ajaxrequest/additionalcreditsubinfoupdate',
        type:'POST',
        data:{sub_code:sub_code,sub_name:$('#add_sub_name_update').val()},
        success:function(data)
        {     
            var parsed = $.parseJSON(data);  
            $('#arts_ac_student_list').hide();
            $('#arts_ac_student_list').html('');
            $('.arts_additional_submit_btn').hide();
            if(parsed==1)
            {                
               krajeeDialog.alert('<b>'+sub_code+' NAME UPDATED SUCCESSFULLY!! </b>');
               setTimeout(function(){
                    window.location.reload();
                }, 500);
               return true;
            }
            else if(parsed==2)
            {
                krajeeDialog.alert('<b>UNABLE TO UPDATE SUBJECT CODE '+sub_code+' NAME </b>');
               return false;
            }
            else
            {   

                krajeeDialog.alert('Enter All Fields');
                return false;           
            }          
            
        }
    });
}
function getArtsList(batch_map_id,exam_year,exam_month,semester,sub_code,cia_min,cia_max,ese_min,ese_max,total_min)
{
   
    if(batch_map_id=='' || exam_year=='' || exam_month=='' || sub_code=="" ||semester==""|| total_min=='' || $('#add_sub_name').val()=='')
    {
        krajeeDialog.alert('Enter All Fields');
        return false;
    }
    $.ajax({
        url: base_url+'?r=ajaxrequest/additionalcreditartsstulist',
        type:'POST',
        data:{batch_map_id:batch_map_id,exam_year:exam_year,exam_month:exam_month,sub_code:sub_code,semester:semester,cia_min:cia_min,ese_min:ese_min,cia_max:cia_max,ese_max:ese_max,total_min:total_min},
        success:function(data)
        {    
            var parsed = $.parseJSON(data);
            $('#arts_ac_student_list').hide();
            $('#arts_ac_student_list').html('');
            $('.arts_additional_submit_btn').hide();
            if(parsed==0)
            {
                krajeeDialog.alert('No data found..');
                return false;
            }
            else
            {   
                $('#arts_ac_student_list').show();
                $('#arts_ac_student_list').html(parsed);
                $('.arts_additional_submit_btn').show();
            }
        }
    });
}
function getArtsAddResult(id,id_value,cia_min,ese_min,total_min,ese_max,cia_max)
{
    var end_value = id.substr(id.lastIndexOf('_') + 1);
    var stu_result;
    var marks = "actxt_"+end_value;
    var stu_marks = $("#"+marks).val();
    var box_100 = "actxttotal_"+end_value;
    var resulttxt = "acresult_"+end_value;
    var checkbox_name = "add"+end_value;

    if(ese_max!==0 && ese_max!='')
    {
        var convert_100 = Math.round((stu_marks*100)/ese_max);
        if(stu_marks>=ese_min && stu_marks>=total_min)
        {
            stu_result = 'Pass';
        }
        else
        {
            stu_result = 'Fail';   
        }
        if(stu_marks>ese_max)
        {
            krajeeDialog.alert("Wrong entry Morethan ESE Maximum");
            $("#"+marks).val("");
            $("#"+resulttxt).val("");
            $("#"+box_100).val('');
            return false;
        }
    }
    else if(cia_max!==0 && cia_max!='')
    {
        var convert_100 = Math.round((stu_marks*100)/cia_max);
        if(stu_marks>=ese_min && stu_marks>=total_min)
        {
            stu_result = 'Pass';
        }
        else
        {
            stu_result = 'Fail';   
        }
        if(stu_marks>cia_max)
        {
            krajeeDialog.alert("Wrong entry Morethan CIA Maximum");
            $("#"+marks).val("");
            $("#"+resulttxt).val("");
            $("#"+box_100).val('');
            return false;
        }
    }
    else
    {
        krajeeDialog.alert("No Data Found");
        $("#"+marks).val("");
        $("#"+resulttxt).val("");
        $("#"+box_100).val('');
        return false;
    }
    
    
    if($("input[name='"+checkbox_name+"']:checked").val())
    {
        $("#"+marks).prop('disabled', false);
        $("#"+marks).prop('required', true); 
        $("#"+box_100).val(convert_100); 
        $("#"+resulttxt).val(stu_result);       
        
    }else{
        $("#"+marks).prop('disabled', true);
        $("#"+marks).prop('required', false);
        $("#"+resulttxt).val("");
        $("#"+box_100).val(""); 
        $("#"+resulttxt).val("");
    }
}

function additional_arts_check(id){
    var end_value = id.substr(id.lastIndexOf('_') + 1);
    var grtxt = "actxt_"+end_value;
    var box_100_change = "actxttotal_"+end_value;
    var resulttxt = "acresult_"+end_value;
    var checkbox_name = "add"+end_value;

    if($("input[name='"+checkbox_name+"']:checked").val()){
        $("#"+grtxt).prop('disabled', false);
        $("#"+grtxt).prop('required', true);
    }else{
        $("#"+grtxt).prop('disabled', true);
        $("#"+grtxt).prop('required', false);
        $("#"+grtxt).val("");
        $("#"+box_100_change).val("");
        $("#"+resulttxt).val("");
    }
}
function getfeesarrear()
{
var batch=$('#stu_batch_id_selected').val();
    $.ajax({
            url: base_url+"?r=ajaxrequest/getfeessubjects",
            type:'POST',
            data:{batch:batch},

            success:function(data)
            {
                if(data==0)
                {
                    $('#mark_subject_code').html('');
                    $('#show_details_subs').hide();
                    $('#disp_show_details_subs').hide();
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;

                }
                else
                {                       
                    var parsed = $.parseJSON(data);                    
                    $('#mark_subject_code').html('');
                    $('#mark_subject_code').append("<option value=''>------SELECT-----</option>");
                    $.each(parsed,function(i,parsed)
                    {
                        $('#mark_subject_code').append("<option value='"+parsed['subject_code']+"'>"+parsed['subject_code']+"</option>");
                    });
                }
            }

        });
}

function getfeesstulist()
{

var sub_id = $('#mark_subject_code').val();
var batch=$('#stu_batch_id_selected').val();
var year=$('#year').val();
var month=$('#exam_month_change').val();

 $.ajax({
            
            url: base_url+"?r=ajaxrequest/getfeesarrearstu",
            type:'POST',
            data:{sub_id:sub_id,batch:batch,year:year,month:month},
            success:function(data)
            {
                $('#show_details_subs').hide();
                $('#disp_show_details_subs').hide();
                if(data==0)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('NO DATA FOUND');     
                    return false;
                }
                else if(data==1)
                {
                    $('#mark_subject_code').val('');
                    krajeeDialog.alert('Fees Paid Already Updated');     
                    return false;
                }
                else
                {                
                    var body='';  
                    var full_body='';                    
                    var parsed = $.parseJSON(data);
                    var k=0;
                    var table_open ='<table class="table table-responsive table-stripped table-bordered table-hover"><tr class="info"><th>S.No.</th><th>Register Number</th><th>Name</th><th>Feespaid</th></tr>';
                    $.each(parsed,function(i,parsed)
                    {
                        var tr='<tr>';                       
                        var td='<td>'+(k+1)+'</td><td> <input type="hidden" name=reg_number[] value='+parsed['coe_student_mapping_id']+' /><input type="hidden" name=sub_map_id[] value='+parsed['sub_map_id']+' /> '+parsed['register_number']+' </td><td>'+parsed['name']+'</td><td> <input class="flat-red" checked type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="YES" /> YES <input class="flat-red"  type="radio" name="status_'+parsed['coe_student_mapping_id']+'" value="NO" />  No </td>';;
                        var tr_dum_close ='</tr>'; 
                        body += tr+td+tr_dum_close; 
                        k++;
                    });
                   full_body = table_open+body+"</table>";
                   $('#show_details_subs').show();
                   $('#disp_show_details_subs').show();
                   $('#show_details_subs').html(full_body);
                }
            }

        });
} 
function getfeespaidstulist()
{
    var sub_id = $('#mark_subject_code').val();
    var batch=$('#stu_batch_id_selected').val();
    var year=$('#year').val();
    var month=$('#exam_month_change').val();

     $.ajax({
        url: base_url+"?r=ajaxrequest/getfeespaidstudents",
        type:'POST',
        data:{sub_id:sub_id,batch:batch,year:year,month:month},
        success:function(data)
        {
            $('#show_details_subs').hide();
            $('#disp_show_details_subs').hide();
            if(data==0)
            {
                $('#mark_subject_code').val('');
                krajeeDialog.alert('NO DATA FOUND');     
                return false;
            }
            else
            {                
                var body='';  
                var full_body='';                    
                var parsed = $.parseJSON(data);
                var k=0;
                var count = 0;
                var table_open ='<table class="table table-responsive table-stripped table-bordered table-hover"><tr class="info"><th>S.No.</th><th>Register Number</th><th>Name</th><th>Status</th><th>Created</th></tr>';
                $.each(parsed,function(i,parsed)
                {
                    if(parsed['status']==='YES')
                    {
                        count++;
                    }
                    var tr='<tr>';                       
                    var td='<td>'+(k+1)+'</td><td> '+parsed['register_number']+' </td><td>'+parsed['name']+'</td><td> '+parsed['status']+'</td><td> '+parsed['username']+'</td>';;
                    var tr_dum_close ='</tr>'; 
                    body += tr+td+tr_dum_close; 
                    k++;
                });
                var tr='<tr>';                       
                var td='<td colspan=5 align="right"><h3>Total Fees Paid : '+count+'</h3></td>';
                var tr_dum_close ='</tr>'; 
                body += tr+td+tr_dum_close; 

               full_body = table_open+body+"</table>";
               $('#show_details_subs').show();
               $('#disp_show_details_subs').show();
               $('#show_details_subs').html(full_body);
            }
        }
    });
} 
function getToppersListPart()
{
  var batch_id = $('#stu_batch_id_selected').val();
  var programme = $('#stu_programme_selected').val();
  var part_no = $('#subjects-part_no').val();
  var limit = $('#subjectsmapping-paper_no').val();
  $.ajax({
      url: base_url+'?r=ajaxrequest/parttopperslistrepo',
      type:'POST',
      data:{programme:programme,batch_id:batch_id,part_no:part_no,limit:limit},
      success:function(data){
        if(data==0){
          krajeeDialog.alert('No data found..');
          $('#mark_percent').hide();
        }else{
          $('.mark_percent_print_btn').show();
          $('#mark_percent').show();
          $('#mark_percent').html(data);
        }
      }
  });
}
/***$('#transfer_cert_button').on('click',function(){
  var batch = $('#stu_batch_id_selected').val();
  var bat_map_id = $('#stu_programme_selected').val();
  var reg_from = $('#student-register_number_from').val();
  var reg_to = $('#student-register_number_to').val(); 
  var date_issue = $('#date_issue').val();
  if(batch=="" || bat_map_id=="" || reg_to=="" || reg_from=="" || date_issue=='')
  {
    krajeeDialog.alert("Please select all feilds");
    return false;
  }
  else
  {
    $.ajax({
      url: base_url+'/mark-entry/transfercerficatedata',
      type:'POST',
      data:{batch:batch,bat_map_id:bat_map_id,reg_from:reg_from,reg_to:reg_to,date_issue:date_issue},
      success:function(data)
      {
        if(data!=0)
        {
          $('.university_report_tbl').show();
          $('#uni_rep_tbl').html(data);
        }
        else
        {
          krajeeDialog.alert('No data found / Import TC Details');
          $('.university_report_tbl').hide();
          return false;
        }
      }
    });
  }  
});***/
