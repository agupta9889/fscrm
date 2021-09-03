//--Copy API Key--
function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
    alert('API Key Copied!');
}
//-- Add User Role Selection --
$("select.crmrole").change(function(){
    
    var selectedRole = $(this).children("option:selected").val();
    //alert("You have selected the role - " + selectedRole);
    if(selectedRole === 'Coaching Manager' ){
      document.getElementById('textInput').style.display ='block';
    }
    else{
      document.getElementById('textInput').style.display = 'none';
    }
    
});


//--Phone Setting phone option selection--
function onButtonClick(){
    document.getElementById('phones').style.display ='block';
    document.getElementById('integrations').style.display ='none';
}
function onButtonClick1(){
    document.getElementById('integrations').style.display = 'block';
    document.getElementById('phones').style.display ='none';
}
//--Popover show--
  $(document).ready(function(){
    
      $('[data-toggle="popover"]').popover();   
      placement : 'top'
  });
//--Rotator ID get--
function rotatorId(id)
{
    $("#phone").val(id);
}

//--Edit Rotator Page--
$(document).ready(function(){
    $(".rotatorsetting").click(function() {
        $(".rotatorid").val($(this).attr('attrid'));
        $(".rotatorname").val($(this).attr('attrname'));
        $(".rotatorstatus").val($(this).attr('attstatus')).attr('selected','selected');
        $(".testmatch").val($(this).attr('attrtestnumber'));
    })
});

//--Date picker--
$(function() {

    var start = moment().subtract(0, 'days');
    var end = moment();
    
    $('#reportrange span').html(start.format('Y-MM-DD') + ' - ' + end.format('Y-MM-DD'));
    $('#id_start_date').val(start.format('YYYY-MM-DD'));
    $('#id_end_date').val(end.format('YYYY-MM-DD'));
    
    function cb(start, end) {
        $('#reportrange span').html(start.format('Y-MM-DD') + ' - ' + end.format('Y-MM-DD'));
        $('#id_start_date').val(start.format('YYYY-MM-DD'));
        $('#id_end_date').val(end.format('YYYY-MM-DD'));
        filterByDate();
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    
    //cb(start, end);

    function filterByDate() {
        var sDate = $('#id_start_date').val();
    	var eDate = $('#id_end_date').val();
        var phoneID = $('#phone_id').val();
        console.log(phoneID);
        var _token = $('input[name="_token"]').val();
        console.log(sDate);
        console.log(eDate);
        if(sDate == start.format('Y-MM-DD')){
            location.reload();
        }
        $.ajax({
            url:  "/filterdate",
            //dataType: "json",
            type: "post",
            async: true,
            data: {"startDate":sDate,"endDate":eDate,"phoneID":phoneID, _token:_token},
            success: function (data) {
                   //console.log(data.totalReportAct);
                   $('#accepted').html(data.totalReportActCount);
                   $('.todayLeads').html(0);
                   $(".reportList").find("tr:gt(0)").remove();
                   $.each(data.totalReportAct, function(index, val){
                   console.log('Arun', index, val);
                   //var createddate = new date(val.created_at);
                    $('.reportList').append('<tr class="text-center"><td>' + val.first_name + val.last_name + '</td><td>' + val.email + '</td> <td>' + val.phone + '</td><td><label class="badge badge-success"><i class="ti-check"></i></label><br/>' + val.sales_number +
                    '</td><td>' + val.created_at + '</td></tr>'); 
                    });
                
                    for(let i=0; i < data.reportLeads.length; i++){
                        //console.log("ID:"+ data.reportLeads[i].rotator_id + "total : " +data.reportLeads[i].total);
                        $('#rotatorLeadCount'+data.reportLeads[i].rotator_id).html(data.reportLeads[i].total + ' <label class="text-success">'+ data.reportLeads[i].total +' / <label class="text-danger"> 0' );
                        
                        for(let j=0; j < data.totalReportLeadsObj[i].length; j++){
                            $('.totalReportLeads').html(data.totalReportLeadsObj[i][j]);
                        }
                   }
            },
            error: function (xhr, exception, thrownError) {
                var msg = "";
                if (xhr.status === 0) {
                    msg = "Not connect.\n Verify Network.";
                } else if (xhr.status == 404) {
                    msg = "Requested page not found. [404]";
                } else if (xhr.status == 500) {
                    msg = "Internal Server Error [500].";
                } else if (exception === "parsererror") {
                    msg = "Requested JSON parse failed.";
                } else if (exception === "timeout") {
                    msg = "Time out error.";
                } else if (exception === "abort") {
                    msg = "Ajax request aborted.";
                } else {
                    //msg = "Error:" + xhr.status + " " + xhr.responseText;
                }
               
            }
        }); 
    }
   
});



// Data Table UnExport 
$(document).ready(function() {
    
    $('#exampleUnexp').DataTable({
        dom: 'Bfrtip',
        buttons: {
          buttons: [
              { extend: 'excel', text: 'Export', className: 'getExportCount' }
          ]
     }
    });

     
    $(".getExportCount").click(function(){
        var exportID = $("#export_count").val();
        var rotatorID = $("#rotatorID").val();

        $.ajax({
            url:  "/updateExportCount",
            type: "post",
            //async: true,
            data: {exportID:exportID,rotatorID:rotatorID},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                  
                console.log(data);
                   
            },
            error: function (xhr, exception, thrownError) {
               
            }
        }); 
    });
});

// Data Table Report 
$(document).ready(function() {
    $('#exampleReport').DataTable({
        dom: 'Bfrtip',
        buttons: {
          buttons: [
              { extend: 'excel', text: 'Export', className: 'getExportCount' }
          ]
     }
    });
});
