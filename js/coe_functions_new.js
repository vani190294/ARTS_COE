function curriculumreport() 
{
    var coe_dept_id = $('#coe_dept_id').val(); 
    var degree_type = $('#degree_type').val();
    var coe_regulation_id = $('#coe_regulation_id').val();
    $('#curriculumdata').html('');
    $('#curriculumdataview').hide();
    $('#approvecurriculum').hide();
    if(coe_regulation_id!='' && degree_type!='' && coe_dept_id!='')    
    {
        $.ajax({
        url: base_url+'?r=ajaxrequest/getcurriculumdata',
        type:'POST',
        data:{coe_regulation_id:coe_regulation_id,degree_type:degree_type,coe_dept_id:coe_dept_id},
        success:function(data)
        {     
            var parsed = $.parseJSON(data);
            $('#curriculumdata').html('');
            if(parsed[0]['curriculumdata']=='')
            {
                $('#curriculumdataview').hide();
                $('#approvecurriculum').hide();
                krajeeDialog.alert('No Data Pls Check');
                return false;
            }
            else
            {
                $('#curriculumdataview').show();
                $('#curpdf').show();
                $('#curriculumdata').html(parsed[0]['curriculumdata']);
            }
         }
      });
    }
}

function cdcfinalreport() 
{
    var coe_dept_id = $('#coe_dept_id').val(); 
    var degree_type = $('#degree_type').val();
    var coe_regulation_id = $('#coe_regulation_id').val();
    var semester = $('#semester').val();
    $('#curriculumdata').html('');
    $('#curriculumdataview').hide();
    $('#approvecurriculum').hide();
    if(coe_regulation_id!='' && degree_type!='' && coe_dept_id!='')    
    {
        $.ajax({
        url: base_url+'?r=curriculum-subject/cdcfinalreportdata',
        type:'POST',
        data:{semester:semester,coe_regulation_id:coe_regulation_id,degree_type:degree_type,coe_dept_id:coe_dept_id},
        success:function(data)
        {     
            var parsed = $.parseJSON(data);
            $('#curriculumdata').html('');
            if(parsed[0]['curriculumdata']=='')
            {
                $('#curriculumdataview').hide();
                $('#approvecurriculum').hide();
                krajeeDialog.alert('No Data Pls Check');
                return false;
            }
            else
            {
                if(parsed[0]['noapproved']>0)
                {
                    if(parsed[0]['checksh_curriculumdata']>0)
                    {
                        krajeeDialog.alert('S&H Not Approved Pls Check');
                        $('#approvecurriculum').hide();
                    }
                    else
                    {
                        if(parsed[0]['check_bos']==0 || parsed[0]['check_bos']=='')
                        {
                            krajeeDialog.alert('Please Upload BOS!');
                            $('#approvecurriculum').show();
                        }
                        else
                        {
                            $('#approvecurriculum').show();
                        }
                    }
                        $('#curriculumdataview').show();                        
                        $('#curpdf').show();
                        $('#curriculumdata').html(parsed[0]['curriculumdata']);
                    return true;
                }
                else
                {
                    if(parsed[0]['servicecoursefromotherdept']>0 || (parsed[0]['check_bos']==0 || parsed[0]['check_bos']==''))
                    {
                        krajeeDialog.alert('BOS Not Uploaded or Service Course Not Approved From Other Dept. Please Check!');
                    }

                    $('#approvecurriculum').hide();
                    $('#curriculumdataview').show();
                    $('#curpdf').show();
                    $('#curriculumdata').html(parsed[0]['curriculumdata']);
                }
                
            }
         }
      });
    }
}

function cdcshreport() 
{
    var coe_dept_id = 8;//$('#coe_dept_id').val(); 
    var degree_type = $('#degree_type').val();
    var coe_regulation_id = $('#coe_regulation_id').val();
    var semester = $('#semester').val();

    $('#curriculumdata').html('');
    $('#curriculumdataview').hide();
    $('#approvecurriculum').hide();
    if(coe_regulation_id!='' && degree_type!='' && coe_dept_id!='')    
    {
        $.ajax({
        url: base_url+'?r=curriculum-subject/cdcshreportdata',
        type:'POST',
        data:{semester:semester,coe_regulation_id:coe_regulation_id,degree_type:degree_type,coe_dept_id:coe_dept_id},
        success:function(data)
        {     
            var parsed = $.parseJSON(data);
            $('#curriculumdata').html('');
            if(parsed[0]['curriculumdata']=='')
            {
                $('#curriculumdataview').hide();
                $('#approvecurriculum').hide();
                krajeeDialog.alert('No Data Pls Check');
                return false;
            }
            else
            {
                if(parsed[0]['noapproved']>0)
                {
                    if(parsed[0]['check_bos']==0 || parsed[0]['check_bos']=='')
                    {
                        krajeeDialog.alert('BOS Not Uploaded!');
                        $('#approveservicecurriculum').show();
                    }
                    else
                    {
                        $('#approveservicecurriculum').show();
                    }

                    $('#curriculumdataview').show();
                    //$('#approveservicecurriculum').hide();
                    $('#curpdf').show();
                    $('#curriculumdata').html(parsed[0]['curriculumdata']);

                    if(parsed[0]['userid']==835)
                    {
                         $('#approveservicecurriculum').show();
                    }

                    return true;
                }
                else if(parsed[0]['approved']>0)
                {
                    $('#curriculumdataview').show();
                    $('#approveservicecurriculum').hide();
                    $('#curpdf').show();
                    $('#curriculumdata').html(parsed[0]['curriculumdata']);                   
                    return true;
                }
                else
                {
                    $('#curriculumdataview').hide();
                    $('#approveservicecurriculum').hide();
                    krajeeDialog.alert('No Data Pls Check');
                    return false;
                }
            }
         }
      });
    }
}