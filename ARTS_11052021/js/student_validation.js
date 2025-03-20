var l = window.location;
var base_url = l.protocol + "//" + l.host  + l.pathname;
$("#student_form_required_page_button").on("click",function()
{

   var stu_name = $('#student-name').val();
   var stu_admission = $('#studentmapping-admission_category_type_id').val();
   var stu_aadhar_name = $('#student-aadhar_number').val();
   var stu_religion = $('#student-religion').val();
   var stu_nationality = $('#student-nationality').val();
   var stu_caste = $('#student-caste').val();
   var stu_sub_caste = $('#student-sub_caste').val();
   var stu_bloodgroup = $('#student-bloodgroup').val();
   var stu_dob = $('#student-dob').val();
   var stu_email_id = $('#student-email_id').val();
   var stu_mobile_no = $('#student-mobile_no').val();
   var stu_stu_batch_id_selected = $('#student-stu_batch_id_selected').val();
   var stu_stu_programme_selected = $('#student-stu_programme_selected').val();
   var stu_stu_section_select = $('#student-stu_section_select').val();
   var stu_register_number = $('#student-register_number').val();
   var stu_stu_status_select = $('#student-stu_status_select').val();
   var stu_guardian_name = $('#guardian-guardian_name').val();
   var stu_guardian_mobile_no = $('#guardian-guardian_mobile_no').val();
   var stu_guardian_relation = $('#guardian-guardian_relation').val();
   var stu_guardian_address = $('#guardian-guardian_address').val();
   var stu_guardian_income = $('#guardian-guardian_income').val();
   var stu_current_country = $('#stuaddress-current_country').val();
   var stu_current_state = $('#stuaddress-current_state').val();
   var stu_current_city = $('#stuaddress-current_city').val();
   var stu_current_address = $('#stuaddress-current_address').val();
   var stu_current_pincode = $('#stuaddress-current_pincode').val();
   

   var name = $('label[for=student-name]').text();
   var stu_addmission_name = $('label[for=studentmapping-admission_category_type_id]').text();
   var aadhar_name = $('label[for=student-aadhar_number]').text();
   var religion = $('label[for=student-religion]').text();
   var dob = $('label[for=student-dob]').text();
   var nationality = $('label[for=student-nationality]').text();
   var caste = $('label[for=student-caste]').text();
   var sub_caste = $('label[for=student-sub_caste]').text();
   var bloodgroup = $('label[for=student-bloodgroup]').text();
   var email_id = $('label[for=student-email_id]').text();
   var mobile_no = $('label[for=student-mobile_no]').text();
   var stu_batch_id_selected = $('label[for=student-stu_batch_id_selected]').text();
   var stu_programme_selected = $('label[for=student-stu_programme_selected]').text();
   var stu_section_select = $('label[for=student-stu_section_select]').text();
   var register_number = $('label[for=student-register_number]').text();
   var stu_status_select = $('label[for=student-stu_status_select]').text();
   var guardian_name = $('label[for=guardian-guardian_name]').text();
   var guardian_mobile_no = $('label[for=guardian-guardian_mobile_no]').text();
   var guardian_relation = $('label[for=guardian-guardian_relation]').text();
   var guardian_address = $('label[for=guardian-guardian_address]').text();
   var guardian_income = $('label[for=guardian-guardian_income]').text();
   var current_country = $('label[for=stuaddress-current_country]').text();
   var current_state = $('label[for=stuaddress-current_state]').text();
   var current_city = $('label[for=stuaddress-current_city]').text();
   var current_address = $('label[for=stuaddress-current_address]').text();
   var current_pincode = $('label[for=stuaddress-current_pincode]').text();
   if(stu_name=="" || typeof stu_name == 'undefined')
   {
        krajeeDialog.alert(name+" Not Available");
        return false;
   }
   else if(stu_admission=="" || typeof stu_admission == 'undefined')
   {
        krajeeDialog.alert(stu_addmission_name+" Not Available");
        return false;
   }else if(stu_bloodgroup=="" || typeof stu_bloodgroup == 'undefined') {
      krajeeDialog.alert(bloodgroup+" Not Available");
      return false;
   }
   else if(stu_caste=="" || typeof stu_caste == 'undefined') {
      krajeeDialog.alert(caste+" Not Available");
      return false;
   }
   else if(stu_nationality=="" || typeof stu_nationality == 'undefined') {
      krajeeDialog.alert(nationality+" Not Available");
      return false;
   }
   else if(stu_religion=="" || typeof stu_religion == 'undefined') {
      krajeeDialog.alert(religion+" Not Available");
      return false;
   }
   else if(stu_email_id=="" || typeof stu_email_id == 'undefined') {
      krajeeDialog.alert(email_id+" Not Available");
      return false;
   }
   else if(stu_stu_status_select=="" ) {
      krajeeDialog.alert(stu_status_select+" Not Chosen");
      return false;
   }
   else if(stu_stu_section_select=="" ) {
      krajeeDialog.alert(stu_section_select+" Not Chosen");
      return false;
   }
   else if(stu_stu_programme_selected=="" ) {
      krajeeDialog.alert(stu_programme_selected+" Not Specified");
      return false;
   }
   else if(stu_stu_batch_id_selected=="") {
      krajeeDialog.alert(stu_batch_id_selected+" Not Specified");
      return false;
   }
   else if(stu_current_pincode=="" || typeof stu_current_pincode == 'undefined') {
      krajeeDialog.alert(current_pincode+" Not Entered");
      return false;
   }
   else if(stu_current_address=="" || typeof stu_current_address == 'undefined') {
      krajeeDialog.alert(current_address+" Not Entered");
      return false;
   }
   else if(stu_current_city=="" || typeof stu_current_city == 'undefined') {
      krajeeDialog.alert(current_city+" Not Entered");
      return false;
   }
   else if(stu_current_state=="" || typeof stu_current_state == 'undefined') {
      krajeeDialog.alert(current_state+" Not Entered");
      return false;
   }
   else if(stu_guardian_income=="" || typeof stu_guardian_income == 'undefined') {
      krajeeDialog.alert(guardian_income+" Not Entered");
      return false;
   }
   else if(stu_guardian_address=="" || typeof stu_guardian_address == 'undefined') {
      krajeeDialog.alert(guardian_address+" Not Entered");
      return false;
   }
   else if(stu_guardian_relation=="" || typeof stu_guardian_relation == 'undefined') {
      krajeeDialog.alert(guardian_relation+" Not Entered");
      return false;
   }
   else if(stu_guardian_name=="" || typeof stu_guardian_name == 'undefined') {
      krajeeDialog.alert(guardian_name+" Not Entered");
      return false;
   }
   else if(stu_guardian_mobile_no=="" || typeof stu_guardian_mobile_no == 'undefined') {
      krajeeDialog.alert(guardian_mobile_no+" Not Entered");
      return false;
   }
   else if(stu_aadhar_name=="" || typeof stu_aadhar_name == 'undefined') {
      krajeeDialog.alert(aadhar_name+" Not Entered");
      return false;
   }
   else if(stu_register_number=="" || typeof stu_register_number == 'undefined') {
      krajeeDialog.alert(register_number+" Not Entered");
      return false;
   }
   else if(stu_mobile_no=="" || typeof stu_mobile_no == 'undefined') {
      krajeeDialog.alert(mobile_no+" Not Entered");
      return false;
   }
   else if(stu_sub_caste=="" || typeof stu_sub_caste == 'undefined') {
      krajeeDialog.alert(sub_caste+" Not Entered");
      return false;
   }
   else if(stu_dob=="") {
      krajeeDialog.alert(dob+" Not Entered");
      return false;
   }
   else
   {
      krajeeDialog.alert("You have Submitted All required information Successfully!!");
   }

});
// Validation Form Ends Here   