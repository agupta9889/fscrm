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
  // $(document).ready(function(){
    
  //     $('[data-toggle="popover"]').popover();   
  //     placement : 'top'
  // });
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
