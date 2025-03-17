function activeInactiveStaff(id, status, url) {
    // console.log(`${baseUrl}${url} and id : ${id}, Status : ${status}`);

    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
            status: status,
        },
        success: function (res) {
            // console.log(res);
            if (res == 1) {
                location.reload();
            }
        },
    });
}

$('#addTicketForm').submit(function(event) {
    const storeSelect = $('[name="store_id"]');
    const storeError = $('#storeError');

    if (storeSelect.val() === null || storeSelect.val() === "") {
        event.preventDefault(); // Prevent form submission
        storeError.css('display', 'block'); 
    } else {
        storeError.css('display', 'none');
    }
});

function deleteStaff(id, url) {
    // console.log(`${baseUrl}/${url} and id: ${id}`);
    if (confirm("Do you really want to delete this data.")) {
        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "GET",
            data: {
                id: id,
            },
            success: function (res) {
                // console.log(res);
                if (res == 1) location.reload();
            },
        });
    } else {
        location.reload();
    }
}

function emailCheck(id = null) {
    if (id == null) {
        let email = document.getElementById("email");
        $.ajax({
            // url: "<?= $this->Url->build(['controller' => 'Users', 'action' => 'uniqueEmailcheck']) ?>",
            url: `${baseUrl}/Users/uniqueEmailcheck`,
            method: "GET",
            data: {
                email: email.value,
            },
            success: function (res) {
                if (res == 1) {
                    email.value = "";
                    document.getElementById("emailError").innerHTML =
                        "* Email already exists.";
                    document.getElementById("editEmailError").innerHTML =
                        "* Email already exists.";
                } else {
                    document.getElementById("emailError").innerHTML = "";
                }
            },
        });
    }
}

function editEmailCheck() {
    let email = document.getElementById("editEmail");
    let id = document.getElementById("editId");
    $.ajax({
        url: `${baseUrl}/Users/uniqueEmailcheck`,
        method: "GET",
        data: {
            email: email.value,
            id: id.value,
        },
        success: function (res) {
            if (res == 1) {
                email.value = "";
                document.getElementById("editEmailError").innerHTML =
                    "* Email already exists.";
            } else {
                document.getElementById("editEmailError").innerHTML = "";
            }
        },
    });
}

function formatPhone(obj) {
    let numbers = obj.value.replace(/\D/g, ""),
        char = { 0: "", 3: "-", 6: "-" };
    obj.value = "";
    for (var i = 0; i < numbers.length; i++) {
        obj.value += (char[i] || "") + numbers[i];
    }
    $(".phoneFormat").attr("maxlength", "12");
}

function editMasterClient(id, url,type) {
    //  console.log(id);
    //  console.log(url);
    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
        },
        success: function (res) {
            $("#editClient").html(res);
            $("#editClient").modal("show");
            if ($("#editAffiliateYes").is(":checked")) {
                document.getElementById("editAffiliateYesNo").style.display =
                    "";
                document.getElementById("editOverrideYesNo").style.display = "";
            } else {
                document.getElementById("editAffiliateYesNo").style.display =
                    "none";
                document.getElementById("editOverrideYesNo").style.display =
                    "none";
            }
            $(".editSingleExample").select2({
                dropdownParent: $("#editClient .editModal"),
                placeholder: "Select an option",
                width: "resolve",
            });

            $(".editMultiple").select2({
                dropdownParent: $("#editClient .editModal"),
                placeholder: "Select an option",
                width: "resolve",
            });

            if ($("#editOverrideNo").is(":checked")) {
                document.getElementById("editOverrideYesNo").style.display =
                    "none";
            } else {
                document.getElementById("editOverrideYesNo").style.display = "";
            }

            if(type=='View')
            {
                $('#clientForm input').attr('disabled', true);
                $('#clientForm textarea').attr('disabled', true);
                $('#clientForm select').attr('disabled', true);
                $('.edtsbmt').css('display','none');
            }
        },
    });
}

function editAffiliateYesNo(str) {
    if (str === "yes") {
        document.getElementById("editAffiliateYes").checked = true;
        document.getElementById("editAffiliateYesNo").style.display = "";
        
    } else {
        document.getElementById("editAffiliateNo").checked = true;
        document.getElementById("editAffiliateYesNo").style.display = "none";
        
    }
}

function editOverrideYesNo(str) {
    if (str === "yes") {
        document.getElementById("editOverrideYes").checked = true;
        document.getElementById("editOverrideYesNo").style.display = "";
    } else {
        document.getElementById("editOverrideNo").checked = true;
        document.getElementById("editOverrideYesNo").style.display = "none";
    }
}

// Team Code Start

function editTeam(id) {
    $.ajax({
        url: `${baseUrl}/Team/edit`,
        method: "GET",
        data: {
            id: id,
        },
        success: function (res) {
            $("#editTeam").html(res);
            $("#editTeam").modal("show");
            $("#edit-example-getting-started1").multiselect();
            $(".js-example-basic-multiple").select2({
                dropdownParent: $("#editTeam .modal-content"),
                placeholder: "Select an option",
                width: "resolve",
            });
        },
    });
}

// End Team

function openreasonBox(id,status,url)
{
    if(status == 1)
    {
        $("#reasonModal").modal("show");
        $('#client_id').val(id);
    }
    else
    {
        
        $("#reasonModal").modal("hide");

        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "GET",
            data: {
                id: id,
                status: status,
            },
            success: function (res) {
                // console.log(res);
                if (res == 1) {
                    location.reload();
                }
            },
        });
    }
    
}

function showInput(data) {
    let templateId = data.value;
    
    if(templateId == 5)
    {
        $('.customReason').css('display','block');
    }
    else{
        $('.customReason').css('display','none');
    }
    
}

// Store

function editStore(id,type,per,acper,aiper,adminpermission,addresspermission) {

    $.ajax({
        url: `${baseUrl}/Store/edit`,
        method: "GET",
        data: {
            id: id,
        },
        success: function (res) {
            $("#editStore").html(res);
            $("#editStore").modal("show");
            $(".js-example-basic-single1").select2({
                dropdownParent: $("#editStore .modal-content"),
                placeholder: "Select an option",
                width: "resolve",
            });
            $(".js-example-basic-multiple-store1").select2({
                dropdownParent: $("#editStore .modal-content"),
                placeholder: "Select an option",
                width: "resolve",
            });

            $('#store_edit').val(type);

            if(type=='View')
            {
                $('#storeForm select').attr('disabled', true);
                $('.radioclass').attr('disabled', true);
               
                if(acper == 2 || aiper == 2)
                {
                   $('.edtsbmt').css('display','');
                   $('.disabledclass').prop('readonly', true);
                }
                else if(adminpermission == 2)
                {
                   $('.edtsbmt').css('display','');
                }else{
                    $('.edtsbmt').css('display','none');
                    $('#storeForm input').attr('readonly', true);
                }

                if(per == 2)
                {
                    $('.accountRep').css('display','none'); 
                }

                if(addresspermission == 2)
                {
                    $('.edtsbmt').css('display','');
                    $('#storeForm textarea').attr('readonly', false);
                }
                else
                $('#storeForm textarea').attr('readonly', true);
            }
        },
    });
}


function showData(id,url,title,heading) {

    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
        },
        success: function (res) {
            // console.log(res)
           var out = JSON.parse(res)
           var html = '';
            for(var i=0;i<out.length;i++)
            {
                html +='<tr><td>'+out[i].name+'</td></tr>'
            }

            if(html == '')
            html +='<tr><td>No Data Available</td></tr>'

            $('.grptitle').text(title)
            $('.grphed').text(heading)
            $(".grplist").html(html);
            $("#showGrouplist").modal("show");
            
        },
    });
}

// End Store

function showDetail(id) {
    $.ajax({
        url: `${baseUrl}/Inventory/showDetail`,
        method: "GET",
        data: {
            id: id,
        },
        success: function (res) {
            $("#editStore").html(res);
            $("#editStore").modal("show");
            $(".js-example-basic-single1").select2({
                dropdownParent: $("#editStore .modal-content"),
                placeholder: "Select an option",
                width: "resolve",
            });
            $(".js-example-basic-multiple-store1").select2({
                dropdownParent: $("#editStore .modal-content"),
                placeholder: "Select an option",
                width: "resolve",
            });

            if(type=='View')
            {
                $('#storeForm input').attr('disabled', true);
                $('#storeForm select').attr('disabled', true);
                $('.edtsbmt').css('display','none');

                if(per == 2)
                {
                    $('.accountRep').css('display','none'); 
                }
            }
        },
    });
}

function fetchAffiliatParentData() {
    let clientId = $("#clients").val();
    if (clientId == "addClient") {
        $("#modalScrollable").modal("hide");
        $("#addClient").modal("show");
        $(".storeClient").select2({
            dropdownParent: $("#addClient .modal-content"),
            placeholder: "Select an option",
            width: "resolve",
        });

        $(".storeClientOverride").select2({
            dropdownParent: $("#addClient .modal-content"),
            placeholder: "Select an option",
            width: "resolve",
        });
    } else {
        $.ajax({
            url: `${baseUrl}/Store/fetchAffiliatParent`,
            method: "GET",
            data: {
                id: clientId,
            },
            success: function (res) {
                data = JSON.parse(res);
                // console.log(data.refAfName);
                if (data.refAfName && data.refId) {
                    $("#referAffiliate").val(data.refAfName);
                    $("#referAffiliateId").val(data.refId);
                    $("#overridePartner").val(data.override);
                    if (data.perAfName && data.perId) {
                        $("#parentAffiliate").val(data.perAfName);
                        $("#parentAffiliateId").val(data.perId);
                    } else {
                        $("#parentAffiliate").val("");
                        $("#parentAffiliateId").val(0);
                    }
                } else if(data.override)
                {
                    $("#overridePartner").val(data.override);
                }else {
                    $("#referAffiliate").val("");
                    $("#referAffiliateId").val(0);
                    $("#parentAffiliate").val("");
                    $("#parentAffiliateId").val(0);
                }
            },
        });
    }
}

function commissionCalculate(id) {
    $.ajax({
        url: `${baseUrl}/Store/commission`,
        method: "GET",
        data: {
            id: id,
        },
        success: function (res) {
            $("#commissionId").html(res);
            $("#commissionId").modal("show");

            $(".js-example-basic-single").select2({
                dropdownParent: $("#commissionId .modal-content"),
                placeholder: "Select an option",
                width: "resolve",
            });
        },
    });
}

function editStaff(id,type) {
    $.ajax({
        url: `${baseUrl}/InternalStaff/editInternalStaff`,
        method: "GET",
        data: {
            id: id
        },
        success: function(res) {
            $("#editStaff").html(res);
            $("#editStaff").modal("show");

            $(".js-example-multiple-issue2").select2({
                dropdownParent: $("#editStaff .modal-content"),
                placeholder: "Select an option",
                width: "resolve",
            });

            if(type=='View')
            {
                $('#staffForm input').attr('readonly', 'readonly');
                $('#staffForm select').attr('disabled', true);
                $('.edtsbmt').css('display','none');
            }
        }
    });
}



function updateStatus(id,url,obj)
{
    let status = obj.value;

    if(url=='Order/updateStatus' && status == 'Client Approval')
    {
        $('#clientTag').modal('show');
        $('#orderB_id').val(id);
    }
    else{
        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "GET",
            data: {
                id: id,
                status: status,
            },
            success: function (res) {
                // console.log(res);
                if (res == 1) {
                    location.reload();
                }
            },
        });
    }
    
}

function updatePaymentStatus(id,name,ordr_id,client_id,amt,old_status,store_id,obj)
{
    let status = obj.value;
    let url = 'Order/addPartialAmount';

    if(status == 'Partial Paid')
    {
        $('#partialPaid').modal('show');
        $('#partial_id').val(id);
        $('#name').val(name);
        $('#ordr_id').val(ordr_id);
        $('#pastatus').val(status);
    }
    else{
        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "POST",
            data: {
                client_id: client_id,
                order_id: ordr_id,
                payment_status: status,
                invoice_id: id,
                client_name: name,
                partial_amount: amt,
                old_status: old_status,
                store_id: store_id
            },
            beforeSend: function(xhr){
                xhr.setRequestHeader(
                    'X-CSRF-Token',csrfToken                
                );
            },
            success: function (res) {
                location.reload();
            },
        });
    }
    
}


function sendInvoice(clientId,storeId,amount,date,profit,order_id) {

    $('.send').prop('disabled', true);

    $.ajax({
        url: `${baseUrl}/Store/sendInvoice`,
        method: "POST",
        data: {
            clientId: clientId,
            storeId: storeId,
            amount: amount,
            orderDate: date,
            profit: profit,
            order_id: order_id
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader(
                'X-CSRF-Token',csrfToken                
            );
        },
        success: function (res) {
            if(res == 1)
            {
                $('.invCls_'+storeId+'_'+date).text('Payment Pending')
                $('.send').prop('disabled', false);
            }
            else{
                var data = '<div class="alert alert-danger alert-dismissible text-center" id="flashError" role="alert"><b>Invoice can not be send for negative amounts.</b><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'

                $('#msg').html(data);
                $('.send').prop('disabled', false);
            }
            
        },
    });
}

function sentFba(id,obj)
{
        
    $('#orderId').val(id);
    $('#fbaID').val(obj.value);

    if(obj.value==='0')
    {
        $('.wire').css('display','none');
        $('.noWire').css('display','block');
        $('.noWire').html('<h5>Are you Sure, You want to change it as NO!</h5>');
        document.getElementById("sbid").innerHTML="Yes";
        $('#wire_no').removeAttr('required');
    }
    else{
        $('.wire').css('display','block');
        $('.noWire').css('display','none');
        document.getElementById("sbid").innerHTML="Add ID";
        $('#wire_no').prop('required',true);
    }

    $("#addWire").modal("show");
}

function editRoles(id,type) {
    //  console.log(id);
    $.ajax({
        url: `${baseUrl}/Roles/edit`,
        method: "GET",
        data: {
            id: id
        },
        success: function(res) {
            $("#editRole").html(res);
            $("#editRole").modal("show");

            if(type=='View')
            {
                $('#roleForm input').attr('disabled', true);
                $('.edtsbmt').css('display','none');
            }

        }
    });
}

//for bulk order status
function bulkOrder()
{ 
    var check = false; var bulk='';
    $('input[type=checkbox]').each(function () {
        // var sThisVal = (this.checked ? $(this).val() : "");
        if(this.checked)
        {
            check= true;
            bulk += $(this).val()+',';
        }
        
    });

    if(check)
    {
        $('.checkAll').css('display','block');
        $('#bulkInvoiceId').val(bulk);
    }
    else{
        $('.checkAll').css('display','none');
    }

}

function bulkChat()
{ 
    var bulk='';
    $('.feecheckbox').each(function () {
        // var sThisVal = (this.checked ? $(this).val() : "");
        if(this.checked)
        {
            check= true;
            bulk += $(this).val()+',';
        }
        
    });

    if(check)
    {
        $('#bulkChatId').val(bulk);
    }
    

}

function myWire(id)
{
    var paymentId = $('#wire_'+id).val();
    // console.log(paymentId);

    $.ajax({
        url: `${baseUrl}/Order/updateWire`,
        method: "POST",
        data: {
            id: id,
            wire: paymentId
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader(
                'X-CSRF-Token',csrfToken                
            );
        },
        success: function (res) {
           location.reload()
        },
    });
}

function updateTracking(id)
{
    var trackingId = $('#tracking_'+id).val();
    // console.log(paymentId);

    $.ajax({
        url: `${baseUrl}/Inventory/updateTrack`,
        method: "POST",
        data: {
            id: id,
            trackingId: trackingId
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader(
                'X-CSRF-Token',csrfToken                
            );
        },
        success: function (res) {
        //    location.reload()
        },
    });
}

function editAffiliateClient(id, url,type) {
    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
        },
        success: function (res) {
            $("#editAffiliates").html(res);
            $("#editAffiliates").modal("show");

            if(type=='View')
            {
                $('#clientForm input').attr('disabled', true);
                $('#clientForm textarea').attr('disabled', true);
                $('#clientForm select').attr('disabled', true);
                $('.edtsbmt').css('display','none');
            }
        },
    });
}

function fetchStoreParentData() {
    let clientId = $("#clients").val();
    
    $.ajax({
        url: `${baseUrl}/Store/fetchAffiliatParent`,
        method: "GET",
        data: {
            id: clientId,
        },
        success: function (res) {
            data = JSON.parse(res);
            // console.log(data);
            if (data.refAfName && data.refId) {
                $("#referAffiliate").val(data.refAfName);
                $("#referAffiliateId").val(data.refId);
                
            } else {
                $("#referAffiliate").val("");
                $("#referAffiliateId").val(0);
                
            }
        },
    });
    
}

function editCommission(id,rec_id,amt)
{
    $("#cmn_id").val(id);
    // $("#recipient").prop('selectedIndex', rec_id);
    console.log(rec_id)
    $('#recipient').val(rec_id);
    $('#recipient').trigger('change');
    $("#amount").val(amt);

    $("#editCmn").modal("show");
}

//for bulk sales status
function bulkStatus()
{ 
    var check = false; var bulk='';
    $('input[type=checkbox]').each(function () {
        // var sThisVal = (this.checked ? $(this).val() : "");
        if(this.checked)
        {
            check= true;
            bulk += $(this).val()+',';
        }
        
    });

    if(check)
    {
        $('.checkAll').css('display','block');
        $('#commission_id').val(bulk);
    }
    else{
        $('.checkAll').css('display','none');
    }

}

function teamForm()
{
    let teamID = $('#teamID').val();
    let pwd = $('#teamPwd').val();

    if(teamID == '' || pwd == '')
    {
        $('#team').html('Please enter ID');
        $('#tpwd').html('Please enter password');
    }
    else{
        $.ajax({
            url: `${baseUrl}/Client/welcomePage`,
            method: "POST",
            data: {
                teamId: teamID,
                pwd: pwd,
                from:'ajax'
            },
            beforeSend: function(xhr){
                xhr.setRequestHeader(
                    'X-CSRF-Token',csrfToken                
                );
            },
            success: function (res) {
               location.reload()
            },
        });
    }
    
}

function checkIssue(issue)
{
    let type = issue.value;

    if(type==6)
    {
        $('.storeRadio').css('display','none');
        // $('.showStore').css('display','block');
        $('.inventStore').attr('required',true);
    }
    else{
        $('.storeRadio').css('display','block');
        // $('.showStore').css('display','none');
        $('.inventStore').attr('required',false);
        // document.getElementById('inlineRadio1').checked = false;
        // document.getElementById('inlineRadio2').checked = false;
        // document.getElementById('inlineRadio3').checked = false;
    }
}


function storeYesNo(str) {
    
    if (str === 'yes') {
        document.getElementById('inlineRadio1').checked = true;
        $('.showStore').css('display','block');
        $('.inventStore').attr('required',true);

    } 
    else if(str === 'no') {
        document.getElementById('inlineRadio2').checked = true;
        $('.showStore').css('display','none');
        $('.inventStore').attr('required',false);
    }
    else
    {
        document.getElementById('inlineRadio3').checked = true;
        $('.showStore').css('display','none');
        $('.inventStore').attr('required',false);
    }
}




function sendChat()
{
    let msg = $('#support_msg').val();
    let receiver = $('#support_staff').val();
    let store = $('#store').val();
    let source = $('#category').val();
    if (msg.trim() === "") {
        $('#support_msg').css('border', '2px solid red');
        $('#support_msg').addClass('shake');
        setTimeout(() => {
            $('#support_msg').css('border', '');
            $('#support_msg').removeClass('shake');
        }, 1000);        
        return; 
    }
    $.ajax({
            url: `${baseUrl}/Client/onboardingChat`,
            method: "POST",
            data: {
                    receiver_id: receiver,
                    msg: msg,
                    store: store,
                    source:source
                  },
                    beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-Token',csrfToken);},
                    success: function (res) {
                        let chatHtml = '<div class="outgoing"><div class="bubble"><p>'+ msg +'</p></div><p style="font-size: smaller;">now</p></div>';
                        $('.onboard').append(chatHtml);
                        // if(source == 6){ $('.launchBody').append(chatHtml);}
                        // else{ $('.onboard').append(chatHtml);} 
                        $('#support_msg').val('');
                    },
        });
}

function sendlChat()
{
    let msg = $('#lsupport_msg').val();
    let receiver = $('#support_staff').val();
    let store = $('#store').val();
    let source = $('#lcategory').val();
    $.ajax({
            url: `${baseUrl}/Client/onboardingChat`,
            method: "POST",
            data: {
                    receiver_id: receiver,
                    msg: msg,
                    store: store,
                    source:source
                  },
                    beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-Token',csrfToken);},
                    success: function (res) {
                        let chatHtml = '<div class="outgoing"><div class="bubble"><p>'+ msg +'</p></div><p style="font-size: smaller;">now</p></div>';
                        $('.launchonboard').append(chatHtml);
                        // if(source == 6){ $('.launchBody').append(chatHtml);}
                        // else{ $('.onboard').append(chatHtml);} 
                        $('#lsupport_msg').val('');
                    },
        });
}

function closeModal()
{
    // alert('hello');
    $('#basicModal').css('display','none');
}

let selectedChatIds = new Set();
$(document).on('change', '.chat-select', function() {
    let chatId = $(this).data('id');
    if ($(this).prop('checked')) {
        selectedChatIds.add(chatId);
    } else {
        selectedChatIds.delete(chatId);
    }
    console.log("on change", selectedChatIds);
    
});

function restoreCheckboxSelections() {
    $('.chat-select').each(function() {
        let chatId = $(this).data('id');
        if (selectedChatIds.has(chatId)) {
            $(this).prop('checked', true);
        }
    });
}

function saveCheckboxSelections() {
    selectedChatIds.clear();
    $('.chat-select:checked').each(function() {
        selectedChatIds.add($(this).data('id'));
    });
}

// for account manager chat
let selectedLaunchChatIds = new Set();
$(document).on('change', '.launchChat-select', function() {
    let chatId = $(this).data('id');
    if ($(this).prop('checked')) {
        selectedLaunchChatIds.add(chatId);
    } else {
        selectedLaunchChatIds.delete(chatId);
    }
    console.log("on change Launch", selectedLaunchChatIds);
    
});

function restoreCheckboxSelections1() {
    $('.launchChat-select').each(function() {
        let chatId = $(this).data('id');
        if (selectedLaunchChatIds.has(chatId)) {
            $(this).prop('checked', true);
        }
    });
}

function saveCheckboxSelections1() {
    selectedLaunchChatIds.clear();
    $('.launchChat-select:checked').each(function() {
        selectedLaunchChatIds.add($(this).data('id'));
    });
}

function fetchdata()
{
        let receiver = $('#support_staff').val();
        let sender = $('#client').val();
        let store = $('#store').val();
        let category = $('#category').val();

        saveCheckboxSelections();
        $.ajax({
                    url: `${baseUrl}/Client/getonboardingChat`,
                    type: 'get',
                    data: { store: store, category:category},
                    success: function(res){
                        data = JSON.parse(res);
                        let onboardchatHtml = ''; 
                        for(let i=0;i<data.length;i++)
                        {
                            // let d1 = data[i].created_date;//2023-08-23T06:50:30+00:00;
                            // let wd = d1.split("T");
                            // let d2 = wd[0].split("-");
                            // let d3 = wd[1].split("+");
                            let timestamp = data[i].created_date;//d2[1]+'/'+d2[2]+'/'+d2[0]+' '+d3[0];

                            if(data[i].sender_id == sender){

                                if(data[i].type=='file'){

                                        var design = '<a class="text-decoration-none" href="https://wte-partners.s3.us-east-2.amazonaws.com/img/onboarding/'+data[i].doc+'" download><i class="bx bx-download"></i>'+data[i].doc+'</a>';
                                        
                                        onboardchatHtml += '<div class="outgoing d-inline">';
                                        if(data[0].store_type == '3')
                                        {
                                            onboardchatHtml += '<input type="checkbox" class="chat-select" data-id="'+data[i].id+'" />'
                                        }
                                        
                                        onboardchatHtml += '<div class="bubble" style="background-color:#f5f5f9;"><p>'+ design +'</p></div><p style="font-size: smaller;">'+ timestamp +' EST</p></div>';
                                }
                                else{ 

                                        onboardchatHtml += '<div class="outgoing d-inline">';
                                        if(data[0].store_type == '3')
                                        {
                                            onboardchatHtml += '<input type="checkbox" class="chat-select" data-id="'+data[i].id+'" />'
                                        }
                                        onboardchatHtml += '<div class="bubble"><p>'+ data[i].chat +'</p></div><p style="font-size: smaller;">'+ timestamp +' EST</p></div>'; 
                                    }

                                } 
                                else{ 
                                    
                                    if(data[i].type=='file') { 
                                        var design = '<a class="text-decoration-none" href="https://wte-partners.s3.us-east-2.amazonaws.com/img/onboarding/'+data[i].doc+'" download><i class="bx bx-download"></i>'+data[i].doc+'</a>';

                                        onboardchatHtml += '<div class="incoming">';
                                        if(data[0].store_type == '3')
                                        {
                                            onboardchatHtml += '<input type="checkbox" class="chat-select" data-id="'+data[i].id+'" />'
                                        }
                                        
                                        onboardchatHtml += '<div class="bubble" style="background-color:#f5f5f9;"><p>'+ design +'</p></div><p style="font-size: smaller;">';
                                        if(category =='Onboarding'){
                                            onboardchatHtml += timestamp +' EST</p></div>'; 
                                        }else{
                                            onboardchatHtml += data[i].name+', '+ timestamp +' EST</p></div>';
                                        }

                                        } 
                                        else
                                        { 

                                            onboardchatHtml += '<div class="incoming">';
                                            if(data[0].store_type == '3')
                                            {
                                                onboardchatHtml += '<input type="checkbox" class="chat-select" data-id="'+data[i].id+'" />'
                                            }
                                            onboardchatHtml += '<div class="bubble"><p>'+ data[i].chat +'</p></div><p style="font-size: smaller;">';
                                            if(category =='Onboarding'){
                                                onboardchatHtml += timestamp +' EST</p></div>'; 
                                            }else{
                                                onboardchatHtml += data[i].name+', '+ timestamp +' EST</p></div>';
                                            }
                                        } 
                                } 
                        } 
                                
                        $('.onboard').html(onboardchatHtml);
                        restoreCheckboxSelections(); 
                    }, 
                        
                    complete:function(data){ 
                        setTimeout(fetchdata,15000); 
                    } 
            });
}

function fetchaccountdata()
{
        let receiver = $('#support_staff').val();
        let sender = $('#client').val();
        let store = $('#store').val();
        let category = $('#lcategory').val();

        saveCheckboxSelections1();
        $.ajax({
                    url: `${baseUrl}/Client/getonboardingChat`,
                    type: 'get',
                    data: { store: store, category:category},
                    success: function(res){
                        data = JSON.parse(res);
                        let onboardchatHtml = ''; 
                        for(let i=0;i<data.length;i++)
                        {
                            let timestamp = data[i].created_date;//2023-08-23T06:50:30+00:00;

                            if(data[i].sender_id == sender){

                                if(data[i].type=='file'){

                                        var design = '<a class="text-decoration-none" href="https://wte-partners.s3.us-east-2.amazonaws.com/img/onboarding/'+data[i].doc+'" download><i class="bx bx-download"></i>'+data[i].doc+'</a>';

                                        onboardchatHtml += '<div class="bubble" style="background-color:#f5f5f9;"><p>'+ design +'</p></div><p style="font-size: smaller;">'+ timestamp +' EST</p></div>';
                                        
                                        onboardchatHtml += '<div class="outgoing d-inline">';
                                        if(data[0].store_type == '3')
                                        {
                                            onboardchatHtml += '<input type="checkbox" class="launchChat-select" data-id="'+data[i].id+'" />'
                                        }
                                        
                                        
                                        onboardchatHtml += '<div class="bubble" style="background-color:#f5f5f9;"><p>'+ design +'</p></div><p style="font-size: smaller;">'+ timestamp +' EST</p></div>';
                                }
                                else{ 

                                        onboardchatHtml += '<div class="outgoing d-inline">';
                                        if(data[0].store_type == '3')
                                        {
                                            onboardchatHtml += '<input type="checkbox" class="launchChat-select" data-id="'+data[i].id+'" />'
                                        }
                                        onboardchatHtml += '<div class="bubble"><p>'+ data[i].chat +'</p></div><p style="font-size: smaller;">'+ timestamp +' EST</p></div>'; 
                                    }

                                } 
                                else{ 
                                    
                                    if(data[i].type=='file') { 
                                        var design = '<a class="text-decoration-none" href="https://wte-partners.s3.us-east-2.amazonaws.com/img/onboarding/'+data[i].doc+'" download><i class="bx bx-download"></i>'+data[i].doc+'</a>';

                                        onboardchatHtml += '<div class="incoming d-inline">';

                                        if(data[0].store_type == '3')
                                        {
                                            onboardchatHtml += '<input type="checkbox" class="launchChat-select" data-id="'+data[i].id+'" />'
                                        }
                                        
                                        onboardchatHtml += '<div class="bubble" style="background-color:#f5f5f9;"><p>'+ design +'</p></div><p style="font-size: smaller;">'+data[i].name+', '+ timestamp +' EST</p></div>'; 

                                        } 
                                        else
                                        { 

                                            onboardchatHtml += '<div class="incoming d-inline">';

                                            if(data[0].store_type == '3')
                                            {
                                                onboardchatHtml += '<input type="checkbox" class="launchChat-select" data-id="'+data[i].id+'" />'
                                            }
                                            onboardchatHtml += '<div class="bubble"><p>'+ data[i].chat +'</p></div><p style="font-size: smaller;">'+data[i].name+', '+ timestamp +' EST</p></div>'; 
                                        } 
                                } 
                        } 
                                
                        $('.launchonboard').html(onboardchatHtml);
                        restoreCheckboxSelections1(); 
                    }, 
                        
                    complete:function(data){ 
                        setTimeout(fetchaccountdata,15000); 
                    } 
            });
}

function supportAddFile() {
   

    $("#addSupportFile").submit(function(e) {
    e.preventDefault();    
    var formData = new FormData(this);

    $.ajax({
        url: `${baseUrl}/Client/addSupportFile`,
        type: 'POST',
        data: formData,
        success: function (res) {

            $('#supportModal').modal('hide');
            let commentData = JSON.parse(res);
            var design = '<a class="text-decoration-none" href="javascript:void(0);" onclick="getDoc(`'+commentData.file+'`)"><i class="bx bx-download"></i>'+commentData.file+'</a>';

            let chatHtml = '<div class="outgoing"><div class="bubble"><p>'+ design +'</p></div><p style="font-size: smaller;">now</p></div>';
            if(commentData.category == 'Olaunch')
            {
                $('.launchonboard').append(chatHtml);
            }
            else
            {
                $('.onboard').append(chatHtml);
            }
            
            
        },
        cache: false,
        contentType: false,
            processData: false
        });
    });

        
}

function updatetbl(val)
{
    if ($("#"+val).is(":checked")) {
        $.ajax({
            url: `${baseUrl}/Client/welcomePage`,
            method: "POST",
            data: {
                type: val,
                from:'ecom'
            },
            beforeSend: function(xhr){
                xhr.setRequestHeader(
                    'X-CSRF-Token',csrfToken                
                );
            },
            success: function (res) {
                if(val === 'ecom' || val === 'deposit_method')
               location.reload()
            },
        });
    }
}

function updateWalmarttbl(val){
    if ($("#"+val).is(":checked")) {
        $.ajax({
            url: `${baseUrl}/Client/walmartOnboardingPage`,
            method: "POST",
            data: {
                type: val,
                from:'ecom'
            },
            beforeSend: function(xhr){
                xhr.setRequestHeader(
                    'X-CSRF-Token',csrfToken                
                );
            },
            success: function (res) {
                if(val === 'ecom' || val === 'deposit_method')
               location.reload()
            },
        });
    }
}

function claculateTotal()
{
    let amt = $('#bamount').val();
    let qnt = $('#quantity').val();
    if(qnt == '')
    qnt = 0;
       let total = (amt * qnt).toFixed(2) ;
    $('#totalamt').val(total);
}

function claculatePackageTotal()
{
    let amt = $('#resale_amt').val();
    let qnt = $('#amazon_package').val();
    if(qnt == '')
    qnt = 0;
       let total = (amt * qnt).toFixed(2) ;
    $('#totalamt').val(total);

    let cost = $('#cost_unit').val();
    
       let total1 = (cost * qnt).toFixed(2) ;

       let profit = (total - total1).toFixed(2);
       $('#profit').val(profit);
}

function claculatePackageTotalInv()
{
    let amt = $('#bamount').val();
    let qnt = $('#quantity').val();
    if(qnt == '')
    qnt = 0;
       let total = (amt * qnt).toFixed(2) ;
    $('#totalamt').val(total);

    let cost = $('#cost_unit').val();
    
       let total1 = (cost * qnt).toFixed(2) ;

       let profit = (total - total1).toFixed(2);
       $('#profit').val(profit);
}

function claculatePackage()
{
    let pkg = $('#amazon_package').val();
    let qnt = $('#unit_package').val();
    if(qnt == '')
    qnt = 0;
       let total = (pkg*qnt) ;
    $('#quantity').val(total);
}

function claculatePackageInv()
{
    let pkg = $('#quantity').val();
    let qnt = $('#unit_package').val();
    if(qnt == '')
    qnt = 0;
       let total = (pkg*qnt) ;
    $('#unit_quantity').val(total);
}

function calculatePckcost(obj)
{
    let amt = parseFloat($('#real_cost_unit').val());
    let qnt = parseInt(obj.value);

    let total = amt*qnt;

    $('#cost_unit').val(total);
}

   
$(document).ready(function(){

    let url = window.location.href

    if(url.search('dashboard') > 0 || url.search('page') > 0 || url.search('pad') > 0)
    {     
        fetchdata();
        setTimeout(fetchdata,15000);
    }
    
    let page = url.split('/');
    let page_name = page[page.length-1]
    if(page_name == 'welcome-page' || url.search('walmart-onboarding-page') > 0)
    {
        fetchaccountdata();
        setTimeout(fetchaccountdata,15000);
    }

    // setInterval(function () {
    //     alert('You have been logged out due to backend issues.');
    //             window.location.href = `${baseUrl}/users/logout`;
    // }, 5000);
            
       
});

$(function(){
    $('.close').click(function(){      
        $('iframe').attr('src', $('iframe').attr('src'));
    });
});



function submitData(val,brand,order)
{
    $.ajax({
        url: `${baseUrl}/Order/updateBrand`,
        method: "POST",
        data: {
            status: val,
            brand_id: brand,
            order_id: order
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader(
                'X-CSRF-Token',csrfToken                
            );
        },
        success: function (res) {
           location.reload()
        },
    });
}

function downloadReport(type)
{
    $('#dwn_type').val(type);
    document.getElementById("reportData").submit();

}

function getDoc(img)
{
    $.ajax({
        url: `${baseUrl}/Client/getDoc`,
        method: "POST",
        data: {
            img: img
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader(
                'X-CSRF-Token',csrfToken                
            );
        },
        success: function (presignedUrl) {

            var link = document.createElement('a');
            link.href = presignedUrl;  // URL of the file (pre-signed)
            link.target = '_blank';  // Open the link in a new tab
            
            // Trigger the link
            link.click();
        }
    });
}


function getTicketDoc(img)
{
    $.ajax({
        url: `${baseUrl}/Tickets/getTicketDoc`,
        method: "POST",
        data: {
            img: img
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader(
                'X-CSRF-Token',csrfToken                
            );
        },
        success: function (presignedUrl) {

            var link = document.createElement('a');
            link.href = presignedUrl;  // URL of the file (pre-signed)
            link.target = '_blank';  // Open the link in a new tab
            
            // Trigger the link
            link.click();
        }
    });
}

function getInventoryDoc(img)
{
    $.ajax({
        url: `${baseUrl}/Inventory/getInventoryDoc`,
        method: "POST",
        data: {
            img: img
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader(
                'X-CSRF-Token',csrfToken                
            );
        },
        success: function (presignedUrl) {

            var link = document.createElement('a');
            link.href = presignedUrl;  // URL of the file (pre-signed)
            link.target = '_blank';  // Open the link in a new tab
            
            // Trigger the link
            link.click();
        }
    });
}



function extraReceive()
{
    let counter = $('#loop').val()
    let cases = parseInt($('#ncases').val())
    let cost_case = $('#new_cost').val();
    let type = $('#type').val();
    let inventory_id = $('#inventory_id').val();
    let old_cost = $('#cost_per_case').val();
    let product_count = $('#total').val();
    if(type == 'less')
    var dinput = $('#less_action').val()
    else
    var dinput = 0;

    if(dinput !== '2')
    {
        $.ajax({
            url: `${baseUrl}/Inventory/updateCost`,
            method: "POST",
            data: {
                id: inventory_id,
                cost: cost_case,
                type: type,
                dinput: dinput,
                cases: cases
            },
            beforeSend: function(xhr){
                xhr.setRequestHeader(
                    'X-CSRF-Token',csrfToken                
                );
            },
            success: function (res) {
                if(type == 'extra')
                {
                    $('#costc'+counter).text(cost_case)
                    $('#cases'+counter).val(cases)
                    $('#pcase'+counter).text(cases)
                    $('#rcase'+counter).text(cases)
                }
                // if(type == 'less' && dinput != 2)
                // {
                //     var total = parseFloat(old_cost)*parseInt(cost_case)
                //     $('#rcase'+counter).text(cost_case)
                //     $('#tcost'+counter).text(total)
                //     $('#cases'+counter).val(cost_case)
                //     $('#pcase'+counter).text(cases)
                // }
               
               $('#extraUnit').modal('hide');
               if(product_count == 1)
               {
                    $('.edtsbmt').removeClass('btndisplay');
                    $('.edtsbmt2').addClass('btndisplay');
               }
            },
        });
    }
    else{
        $('#extraUnit').modal('hide');
        $('.edtsbmt2').removeClass('btndisplay');
        $('.edtsbmt').addClass('btndisplay');
    }


    
}


function welcomeBack(val)
{
    $.ajax({
        url: `${baseUrl}/Client/welcomePage`,
        method: "POST",
        data: {
            status: val,
            from:'ajax'
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader(
                'X-CSRF-Token',csrfToken                
            );
        },
        success: function (res) {
           location.reload()
        },
    });
}


//End

function welcomeUpdate(val,type)
{
    $.ajax({
        url: `${baseUrl}/Client/welcomeUpdate`,
        method: "POST",
        data: {
            id: val,
            type: type,
            from:'ajaxupdate'
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader(
                'X-CSRF-Token',csrfToken                
            );
        },
        success: function (res) {
        //    location.reload()
        },
    });
}

function launchUpdate(val,col)
{
    $.ajax({
        url: `${baseUrl}/Client/launchUpdate`,
        method: "POST",
        data: {
            id: val,
            col:col
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader(
                'X-CSRF-Token',csrfToken                
            );
        },
        success: function (res) {
        //    location.reload()
        },
    });
}

function dptYesNo(str) {
    
    if (str === 'yes') {
        document.getElementById('inlineRadio1').checked = true;
        $('.showDpt').css('display','block');

    } else {
        document.getElementById('inlineRadio2').checked = true;
        $('.showDpt').css('display','none');
    }
}

function StorePermission(str) {

    if (str === 'yes') {
        document.getElementById('inlineRadio2').checked = true;
        $('.selectedstore').css('display','block');
        $('.allstore').attr('required',true);

    } else {
        document.getElementById('inlineRadio1').checked = true;
        $('.selectedstore').css('display','none');
        $('.allstore').attr('required',false);
    }
}


function EditInventory(id, url,type,from,page) {

    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
            page: page
        },
        success: function (res) {

            if(type == 'brand')
            {
                $("#inventoryTag").html(res);
                $("#inventoryTag").modal("show");

                if(from == 'custom')
                {
                    $('#adjustpartial').css('display','none');
                    $('#editquantity').attr('readonly', 'readonly');
                }
                else
                {
                    $('#adjustpartial').css('display','block');
                    $('#editquantity').removeAttr('readonly');
                }
            }
            if(type == 'buyback')
            {
                $("#buybackTag").html(res);
                $("#buybackTag").modal("show");
            }
            
        },
    });
}

function inventoryInvoice(id, url,type) {

    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
            type: type
        },
        success: function (res) {
            
        },
    });
}


function EditCredit(id, url,from) {

    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
            from: from
        },
        success: function (res) {
            $("#editcreditNote").html(res);
            $("#editcreditNote").modal("show");
        },
    });
}


function InventoryClaculateTotal()
{
    let amt = $('#editbamount').val();
    let qnt = $('#editquantity').val();
    if(qnt == '')
        qnt = 0;
    let total = (amt * qnt).toFixed(2) ;
    $('#edittotalamt').val(total);
}

function PartialInventoryClaculateTotal()
{
    let amt = $('#editbamount').val();
    let qnt = $('#editquantity').val();
    let totalqnt = $('#edqnt').val();
    var amValue = document.getElementById("editbamount1");
    let amt1 = amValue.value;
    var edValue = document.getElementById("editquantity1");
    var qnt1 = edValue.value;
    if(qnt1 == '')
    qnt1 = 0;

    if(parseInt(qnt1) > parseInt(totalqnt))
    {
        $('#editquantity1').val(0); 
        qnt1 = 0;
    }
    let rem = parseInt(totalqnt)-parseInt(qnt1);
    $('#editquantity').val(rem);   
    qnt = $('#editquantity').val();


    let finalqnt = parseInt(qnt) + parseInt(qnt1);    
    
    
    
    let total = (amt * qnt).toFixed(2) ;  
    let total1 = (amt1 * qnt1).toFixed(2) ;
    let fianl_amt = parseFloat(total) + parseFloat(total1);

    $('#edittotalamt').val(fianl_amt);
}

function buybackClaculateTotal()
{
    let amt = $('#buybackamt').val();
    let qnt = $('#buybackquantity').val();
    if(qnt == '')
        qnt = 0;
    let total = (amt * qnt).toFixed(2) ;
    $('#buybacktotalamt').val(total);
}


//Code added by kajal For Requested Brand approval Edit
function EditRequestedBrand(id, url,from) {

    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
            from: from
        },
        success: function (res) {
            $("#RequestedTag").html(res);
            $("#RequestedTag").modal("show");
        },
    });
}
//End

function formatNumeric(obj) {
    obj.value = obj.value.replace(/[^0-9]/g,"");
}

function compareUnit(obj)
{
    var order = parseInt($('#cases').val());
    var rec = parseInt(obj.value);
    var recive = $('#qty_received').val();
    if(recive == '')
    var rec1 = 0;
    else
    var rec1 = parseInt(recive)

    var total = parseInt(rec+rec1)

    if(total>order)
    obj.value = order - rec1

    var units = parseInt($('#case_units').val());
    var pck = units*obj.value;
    $('#package_count').val(pck)
}

function checkAsin(obj)
{
    var plan = $('#distribute_plan').val();
    var rec = parseInt(obj.value);

    var asin = $('#asin').val();
    var walmart = $('#walmart').val();
    if(rec > 1)
    $('#asin').val('');
    else
    {
        if(plan == 'Resale on Amazon')
        $('#asin').val(asin);
        else
        $('#asin').val(walmart);
    }
    

    var units = parseInt($('#units_to_distributed').val());
    var cost = parseFloat($('#cost_unit').val());
    var pckcost = cost*rec
    var pck = Math.round(units/rec);
    $('#package_count').val(pck)
    $('#cost_pckg').val(pckcost)
}

function checkUpdatdAsin(obj)
{
    // var plan = $('#distribute_plan').val();
    var rec = parseInt(obj.value);

    // var asin = $('#asin').val();
    // var walmart = $('#walmart').val();
    // if(rec > 1)
    // $('#asin').val('');
    // else
    // {
    //     if(plan == 'Resale on Amazon')
    //     $('#asin').val(asin);
    //     else
    //     $('#asin').val(walmart);
    // }
    

    var units = parseInt($('#units_to_distributed').val());
    var cost = parseFloat($('#cost_unit').val());
    var pckcost = cost*rec
    var pck = Math.round(units/rec);
    $('#package_count').val(pck)
    $('#cost_pckg').val(pckcost)
}

function brandData() {
    let brandId = $("#brandId").val();

        $.ajax({
            url: `${baseUrl}/order/fetchBrandData`,
            method: "GET",
            data: {
                id: brandId
            },
            success: function (data) {
                res = JSON.parse(data);
                if (data)
                {
                    $("#product").html(res.html);
                    $("#Probrand_name").val(res.brand_name);
                    $("#bamount").val("");
                    $("#totalamt").val("");
                }
                else 
                {
                    $("#product").html("<option value=''>No Product Found</option>");
                }
            },
        });
}

function invBrandData() {
    let brandId = $("#brandId").val();
    let store_id = $("#store_id").val();

        $.ajax({
            url: `${baseUrl}/order/fetchInvBrandData`,
            method: "GET",
            data: {
                id: brandId,
                store_id:store_id
            },
            success: function (data) {
                res = JSON.parse(data);
                if (data)
                {
                    $("#product").html(res.html);
                    $("#Probrand_name").val(res.brand_name);
                    $("#bamount").val("");
                    $("#totalamt").val("");
                }
                else 
                {
                    $("#product").html("<option value=''>No Product Found</option>");
                }
            },
        });
}

function ReqBrandData() {
    let brandId = $("#brand_id").val();

        $.ajax({
            url: `${baseUrl}/order/fetchBrandData`,
            method: "GET",
            data: {
                id: brandId
            },
            success: function (data) {
                res = JSON.parse(data);
                if (data)
                {
                    $("#Request_product").html(res.html);
                    $("#Request_brand_name").val(res.brand_name);
                }
                else 
                {
                    $("#Request_product").html("<option value=''>No Product Found</option>");
                    $("#Request_brand_name").val("0");
                }
            },
        });
}

function EditRequestProductData() {
    let brandId = $("#Reqbrand_id").val();
        $.ajax({
            url: `${baseUrl}/order/fetchBrandData`,
            method: "GET",
            data: {
                id: brandId
            },
            success: function (data) {
                res = JSON.parse(data);
                if (data)
                {
                    $("#productId").html(res.html);
                    $("#editBrandDataReq").val(res.brand_name);
                }
                else 
                {
                    $("#productId").html("<option value=''>No Product Found</option>");
                    $("#editBrandDataReq").val("0");
                }
            },
        });
}


function editBrandData() {
    let brandId = $("#edit_brandId").val();
        $.ajax({
            url: `${baseUrl}/order/fetchBrandData`,
            method: "GET",
            data: {
                id: brandId
            },
            success: function (data) {
                res = JSON.parse(data);
                if (data)
                {
                    $("#editProduct").html(res.html);
                    $("#edit_brand_name").val(res.brand_name);
                    $("#editbamount").val("");
                    $("#edittotalamt").val("");
                }
                else 
                {
                    $("#editProduct").html("<option value=''>No Product Found</option>");
                }
            },
        });
}

function productData() {
    let productID = $("#product").val();
        $.ajax({
            url: `${baseUrl}/order/fetchproductData`,
            method: "GET",
            data: {
                id: productID,
            },
            success: function (data) {

                res = JSON.parse(data);
                if (data)
                {
                    $("#bamount").val(res.amount);
                    $("#procheck").val(res.name);
                    claculateTotal();
                }
                else 
                {
                    $("#bamount").val("");
                    $("#procheck").val("");
                }
            },
        });
}

function productInvData() {
    let productID = $("#product").val();
        $.ajax({
            url: `${baseUrl}/order/fetchInvproductData`,
            method: "GET",
            data: {
                id: productID,
            },
            success: function (data) {

                res = JSON.parse(data);
                if (data)
                {
                    $("#bamount").val(res.amount);
                    $("#procheck").val(res.name);
                    $("#qty_available").val(res.case);
                    $("#pck_available").val(res.package);
                    $("#cost_unit").val(res.cost);
                    $("#unit_package").val(res.no_of_unit);
                    claculateTotal();
                }
                else 
                {
                    $("#bamount").val("");
                    $("#procheck").val("");
                }
            },
        });
}

function productDataEdit() {
    let productID = $("#editProduct").val();
        $.ajax({
            url: `${baseUrl}/order/fetchproductData`,
            method: "GET",
            data: {
                id: productID,
            },
            success: function (data) {

                res = JSON.parse(data);
                if (data)
                {
                    $("#editbamount").val(res.amount);
                    $("#edit_product_name").val(res.name);
                    InventoryClaculateTotal();
                }
                else 
                {
                    $("#editbamount").val("Amount");
                    $("#edit_product_name").val("0");
                }
            },
        });
}

function ProductName() {
    let productID = $("#Request_product").val();
        $.ajax({
            url: `${baseUrl}/order/fetchproductData`,
            method: "GET",
            data: {
                id: productID,
            },
            success: function (data) {
                res = JSON.parse(data);
                if (data)
                {
                    $("#ReqProductName").val(res.name);
                }
                else 
                {
                    $("#ReqProductName").val("0");
                }
            },
        });
}

function EditProductName() {
    let productID = $("#productId").val();
        $.ajax({
            url: `${baseUrl}/order/fetchproductData`,
            method: "GET",
            data: {
                id: productID,
            },
            success: function (data) {
                res = JSON.parse(data);
                if (data)
                {
                    $("#editProductDataReq").val(res.name);
                }
                else 
                {
                    $("#editProductDataReq").val("0");
                }
            },
        });
}

function orderdelete(id,store_id)
{
    if (confirm("Are you sure you want to delete this order")) 
    { 
        var url = 'Order/orderDelete';

        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "GET",
            data: {
                id: id,
                store_id: store_id
            },
            success: function (res) {
                // console.log(res);
                if (res == 1) location.reload();
            },
        });
    }
    
}

function creditdelete(id,store_id)
{
    if (confirm("Are you sure you want to delete this credit")) 
    { 
        var url = 'Order/creditDelete';

        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "GET",
            data: {
                id: id,
                store_id: store_id
            },
            success: function (res) {
                // console.log(res);
                if (res == 1) location.reload();
            },
        });
    }
    
}

function purchaseDelete(id)
{
    if (confirm("Are you sure you want to delete this inventory")) 
    { 
        var url = 'Inventory/delete';

        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "GET",
            data: {
                id: id
            },
            success: function (res) {
                // console.log(res);
                if (res == 1) location.reload();
            },
        });
    }
    
}

function deleteTicket(id, url) {
    if (confirm("Do you really want to delete this ticket.")) {
        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "GET",
            data: {
                id: id,
            },
            success: function (res) {
                if (res == 1) location.reload();
            },
        });
    } 
}



function addRow()
{
    var output = '<div class="col-md-6"><div class="mb-3"><label class="form-label" for="">Amount/item</label><div class="input-group input-group-merge"><span id="2" class="input-group-text"><i class="bx bx-dollar"></i></span><input type="text" class="form-control" id="editbamount1" name="bamount1" onblur="PartialInventoryClaculateTotal()"></div></div></div><div class="col-md-6"><div class="mb-3"><label class="form-label" for="basic-icon-default-lastname">Quantity</label>  <div class="input-group input-group-merge"><span id="basic-icon-default-lastname2" class="input-group-text"><i class="bx bx-pin"></i></span><input type="number" class="form-control" id="editquantity1" name="quantity1" onblur="PartialInventoryClaculateTotal()" autocomplete="off" required></div><span style="float:right; color:red" onclick="deleteRow()"><i class="bx bx-trash me-1"></i></span></div></div>';

    $('.adjust').html(output);
    $('#editquantity').attr('readonly', 'readonly');
    $('#partial').val('1');
}

function deleteRow()
{
    $('.adjust').html('');
    $('#editquantity').removeAttr('readonly');
    $('#partial').val('0');
    let qnt = $('#edqnt').val();
    $('#editquantity').val(qnt);
    InventoryClaculateTotal();
}

function getChildData(id,rowCount)
{

    var url = 'order/getInventoryChildData';
    var toggle = $('#getdetail').val();

    
    if(toggle == 0) 
    {
        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "GET",
            data: {
                id: id
            },
            success: function (res) {
                
                var obj = JSON.parse(res);
                //  console.log(obj.length)
                let trData = '';
                for (let i = 0; i < obj.length; i++) {


                    trData += `
                    <tr class="row-${rowCount}subclass"><th></th><td>${obj[i]['inventory_date']}</td><td>${obj[i]['inventory_id']}</td><td>${obj[i]['asin']}</td><td>${obj[i]['brand_name']}</td><td>${obj[i]['product_name']}</td><td>${obj[i]['notes'] == 'Buyback' ? `<a href="javascript:void(0);" title="Open Return Id" onclick="showReturnIds(${obj[i]['brand_approval_id']},'Order/showReturnIds','List of Return Id','Return Id')"><i class='bx bx-low-vision me-1 clr-theme'></i></a>` : ''}</td><td>${obj[i]['notes']}</td><td>${obj[i]['quantity']}</td><td>${obj[i]['remain_quantity']}</td><td>$${obj[i]['amount']}</td><td>$${obj[i]['total_amount']}</td><td>$${obj[i]['shipping_cost']}</td><td></td>`

                    if(obj[i]['notes'] != 'Buyback')
                    {
                        if(!obj[i]['quantity'])
                            {
                                trData += `<td> <a id="editClientSelect" href="javascript:void(0);" onclick="EditInventory(${obj[i]['id']}, 'order/editClientThing','brand','custom')">
                                <i class="bx bx-edit-alt me-1"></i>
                                       
                                </a><a href="javascript:void(0);" title="Reverse Inventory" onclick="reverseInventory(${obj[i]['id']})">
                                <i class="bx bx-reset me-1"></i>
                                       
                                </a><a href="javascript:void(0);" onclick="adjustmentDelete(${obj[i]['id']}, 'order/deleteAdjustment')">
                                <i class="bx bx-trash me-1"></i>
                                       
                                </a></td>`
                            }
                            else{
                                trData += `<td> <a id="editClientSelect" href="javascript:void(0);" onclick="EditInventory(${obj[i]['id']}, 'order/editClientThing','brand','custom')">
                                <i class="bx bx-edit-alt me-1"></i>
                                       
                                </a><a href="javascript:void(0);" title="Reverse Inventory" onclick="reverseInventory(${obj[i]['id']})">
                                <i class="bx bx-reset me-1"></i>
                                       
                                </a></td>`
                            }
                    }
                    else{
                        trData += `<td></td>`
                    }
                    
                    trData += '</tr>'
                }
                    // console.log(trData);
                    // for example you want to insert after the 2nd row.eq( rowCount )
                    var rowAfterYouWantToInsert = $("#row-"+rowCount);
                    // console.log(rowAfterYouWantToInsert)
                    // console.log(table.row('#row-'+rowCount))
                    // new row with two cells
                    rowAfterYouWantToInsert.after( trData );
                    $("#example1").trigger("update"); 
                    $('#getdetail').val(1);
               
            },
        });
    }
    else{
        
        $('.row-'+rowCount+'subclass').remove()
        
            $('#getdetail').val(0);
    }

        
}


function getManagementChildData(id,rowCount,user,permission)
{

    var url = 'store/getManagementChildData';
    var toggle = $('#getMdetail').val();

    
    if(toggle == 0) 
    {
        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "GET",
            data: {
                id: id
            },
            success: function (res) {
                
                var obj = JSON.parse(res);
                //  console.log(obj.length)
                let trData = '';
                for (let i = 0; i < obj.length; i++) {

                    var amount = ((obj[i]['profitData'] * obj[i]['management_fee']) / 100).toFixed(2);
                    var amount2 = ((obj[i]['profitData'] * obj[i]['fees']) / 100).toFixed(2);
                    var amt = 0;
                    if(obj[i]['send_invoice'] == 0)
                    amt = amount;
                    else
                    amt = amount2;

                    if(obj[i]['invoice_id'] == '-')
                    var invoice = '-';
                    else
                    var invoice = 'inv-'+obj[i]['invoice_id']
                       
                    trData += `
                    <tr class="row-${rowCount}subclass">
                    <td></td>`
                    if (user != 2){
                    trData += `<td></td><td></td>`
                    }
                    
                    trData += `<td>${obj[i]['store_name']}</td><td>${obj[i]['order_date']}</td><td>${obj[i]['profitData'].toFixed(2)}</td><td>${amt}</td><td>${invoice}</td>`
                      

                    if(obj[i]['send_invoice']==0 && ((user == 1 && permission == 2) || user == 0)) 
                    {
                        trData += `<td class="invCls_${obj[i]['id']}_${obj[i]['full_order_date']}">
                        <button class="btn btn-sm btn-primary send" style="width: 100%;"
                        onclick="sendInvoice(${obj[i]['client_id']},${obj[i]['id']},${amount},'${obj[i]['full_order_date']}',${(obj[i]['profitData']).toFixed(2)})">
                        Send Invoice
                        </button>
                    </td>`
                    }
                    else{
                        trData += `<td id="status_${obj[i]['order_id']}">${obj[i]['payment']}</td>`
                    }

                    if((user == 1 && permission == 2) || user==0) {

                        trData += `<td>`
                        if(obj[i]['payment'] == 'Payment Pending' && obj[i]['order_id'] != 0) { 
                        
                        trData += `<button class="btn btn-sm btn-primary" id="btn_${obj[i]['order_id']}" style="width: 100%;" onclick="payment(${obj[i]['order_id']})">Update Status</button>`
                        
                    } 
                        
                        trData += `</td>
                        <td>`
                        if((obj[i]['send_invoice']== 1 && obj[i]['payment'] == 'Payment Pending' && obj[i]['order_id'] != 0))
                        {
                            trData += `<a class="editamount" href="javascript:void(0);"
                            onclick="EditAmount(${obj[i]['order_id']},${amount2},${obj[i]['profitData']})"><i
                                class="bx bx-edit-alt me-1"></i>
                                </a>`
                        }
                        
                        trData += `<a href="${baseUrl}/Store/managementDelete/${btoa(obj[i]['id'])}/${btoa(obj[i]['full_order_date'])}/2" title="Delete"
                        onclick="return confirm('Do you really want to delete this data ?')"><i class="bx bx-trash me-1"></i></a>
                                </td>`
                    }
                    trData += '</tr>'
                }
                // console.log(trData);
                // for example you want to insert after the 2nd row.eq( rowCount )
                var rowAfterYouWantToInsert = $("#row-"+rowCount);
                // console.log(rowAfterYouWantToInsert)
                // console.log(table.row('#row-'+rowCount))
                // new row with two cells
                rowAfterYouWantToInsert.after( trData );
                $("#exampletable").trigger("update"); 
                $('#getMdetail').val(1);
               
            },
        });
    }
    else{
        
        $('.row-'+rowCount+'subclass').remove()
        
            $('#getMdetail').val(0);
    }

        
}


function getInventoryChildData(id,rowCount)
{

    var url = 'inventory/getInventoryChildData';
    var toggle = $('#getWdetail').val();
    console.log(toggle)
    
    if(toggle == 0) 
    {
        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "GET",
            data: {
                id: id
            },
            success: function (res) {
                
                var obj = JSON.parse(res);
                //  console.log(obj.length)
                let trData = `<tr class="row-${rowCount}subclass"><th></th><th>Product Name</th><th>Brand Name</th><th></th><th>ASIN</th><th>SKU</th><th colspan="6">Received Date</th>`;
                for (let i = 0; i < obj.length; i++) {

                       
                    trData += `
                    <tr class="row-${rowCount}subclass">
                    <td></td>`
                                        
                    trData += `<td>${obj[i]['product_name']}</td><td>${obj[i]['brand_name']}</td><td></td><td>${obj[i]['asin']}</td><td>${obj[i]['sku']}</td><td colspan="6">${obj[i]['receiving_date']}</td>`
                      

                    trData += '</tr>'
                }
                // console.log(trData);
                // for example you want to insert after the 2nd row.eq( rowCount )
                var rowAfterYouWantToInsert = $("#row-"+rowCount);
                // console.log(rowAfterYouWantToInsert)
                // console.log(table.row('#row-'+rowCount))
                // new row with two cells
                rowAfterYouWantToInsert.after( trData );
                $("#ResaleAmazon").trigger("update"); 
                $('#getWdetail').val(1);
               
            },
        });
    }
    else{
        
        $('.row-'+rowCount+'subclass').remove()
        
            $('#getWdetail').val(0);
    }

        
}

//By kajal
function OpenEmailModal(type, form_name)
{
    if(type == 'clientStore')
    {
        $('.brandDiv').hide();  
        $('.productDiv').hide();  
        $('.amountDiv').hide();  
        $('.quantityDiv').hide();
        $('#storeId').attr('required', '');   
    }

    if(type == 'client')
    {
        $('.storeDiv').hide(); 
        $('.brandDiv').hide();  
        $('.productDiv').hide();  
        $('.amountDiv').hide();  
        $('.quantityDiv').hide();
    }

    if(type == 'brandAprroval')
    { 
        $('.storeDiv').hide(); 
        $('#brandId').attr('required', ''); 
        $('#product').attr('required', ''); 
        $('#bamount').attr('required', ''); 
        $('#quantity').attr('required', ''); 

    }
    console.log(form_name)
    $('#form_type').val(form_name);
    $("#addStoreEmail").modal("show");
}

$('.emailRefresh').click(function(){   
        location.reload(true);
});
//End

function archiveTicket(id)
{
    if (confirm("Are you sure you want to Archive this Ticket")) 
    { 
        var url = 'Tickets/archiveTicket';

        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "GET",
            data: {
                id: id
            },
            success: function (res) {
                // console.log(res);
                if (res == 1) location.reload();
            },
        });
    }
    
}

function adjustmentDelete(id,url)
{
    if (confirm("Are you sure you want to delete this entry")) 
    { 

        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "GET",
            data: {
                id: id
            },
            success: function (res) {
                // console.log(res);
                if (res == 1) location.reload();
            },
        });
    }
    
}

function reverseInventory(id)
{
    var url = 'Order/reverseInventory';
    if (confirm("Are you sure you want to reverse this inventory")) 
    { 
        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "GET",
            data: {
                id: id
            },
            success: function (res) {
                // console.log(res);
                if (res == 1) location.reload();
            },
        });
    }
}

function calculatePercentage(obj)
{
    var amt = parseFloat(obj.value);
    var profit =  parseFloat($('#profitData').val())

    var percent = ((amt*100)/profit).toFixed(2);
    $('#fee_percent').text('Management Fees Percent will be : '+percent+'%')

    $('#percentFee').val(percent);
}


//Product Library Start 

function inventoryProData(obj,count) {
    let productID = obj.value;

    let pro = $('#pro-'+count).val();

    

    if(pro != productID)
    {
        
        $.ajax({
            url: `${baseUrl}/inventory/fetchproductData`,
            method: "GET",
            data: {
                id: productID,
            },
            success: function (data) {

                res = JSON.parse(data);
                $('#pro-'+count).val(productID);
                if (data)
                {
                   
                    $("#brand-"+count).val(res.name);
                    $("#brand_id-"+count).val(res.id);
                    $("#sku-"+count).val(productID);
                    $('#productID-'+count).val(productID)                    

                    var $select = $("#sku-"+count);
                    $select.empty();
                    if (res.sku && res.sku.trim() !== "" && res.sku !== "0") {
                        $select.append('<option value="' + productID + '" selected>' + res.sku + '</option>');
                    } else {
                        $select.append('<option value="' + productID + '" selected>-</option>');
                    }

                    $select.trigger('change');
                    var $producte = $("#productID-"+count);
                    $producte.empty();
                    if (res.product_name && res.product_name.trim() !== "") {
                        $producte.append('<option value="' + productID + '" selected>' + res.product_name + '</option>');
                    } else {
                        $producte.append('<option value="' + productID + '" selected>-</option>');
                    }
                    $producte.trigger('change');

                    $('.product-input').trigger('change');
                    $('.product-sku-input').trigger('change')
                }
                else 
                {
                    $("#brand-"+count).val("");
                    $("#brand_id-"+count).val("");
                    $("#sku-"+count).val("");
                }
            },
        });
    }

        
}

//Total Cost Amount Per Item
function toatalCost(id)
{
    
    let quantity = $('#quantity'+id).val();
    let cost = $('#cost_per_Item'+id).val();
    
    if(quantity == '')
    quantity = 0;
    if(cost == '')
    cost = 0;
    let total = (quantity * cost).toFixed(2);
    console.log(total)
    // let total = (quantity * cost);
    $('#total_cost'+id).val(total);

    toatalOrderCost()

}

//Total Cost Amount Per Item
function totalSell(id)
{
    
    let quantity = $('#selling_quantity'+id).val();
    let cost = $('#selling_cost'+id).val();
    
    if(quantity == '')
    quantity = 0;
    if(cost == '')
    cost = 0;
    let total = (quantity * cost).toFixed(2);
    console.log(total)
    // let total = (quantity * cost);
    $('#total_selling'+id).val(total);

}

//Total Cost Amount Per Item
function toatalOrderCost()
{

    var sum = 0
    $( ".total-costing" ).each(function( index ) {
            sum += parseFloat($(this).val());
      });
    
    if($('#total_ship').val() == '')
    var ship = 0
    else
    var ship = parseFloat($('#total_ship').val());

    let total = (parseFloat(sum) + ship).toFixed(2)
    $('#total_cost_order').val(total);

}

//End

function toatalCostEdit()
{
    
    let quantity = $('#quantitye').val();
    let cost = $('#cost_per_Iteme').val();
    
    if(quantity == '')
    quantity = 0;
    if(cost == '')
    cost = 0;
    let total = (quantity * cost).toFixed(2);
    $('#total_coste').val(total);
}


function checkCode(obj)
{
    var rec = obj.value;
    if(rec == 'Resale on Walmart')
    {
        $('.asincode').css('display','block');
        $('.ascode').text('Walmart Code');
        $('#asin').removeAttr('disabled','disabled');
    }
    else if(rec == 'Resale on Amazon')
    {
        $('.asincode').css('display','block');
        $('.ascode').text('ASIN');
        $('#asin').removeAttr('disabled','disabled');
    }
    else if(rec == 'TBD')
    {
        $('.asincode').css('display','block');
        $('.ascode').text('ASIN/Product Code');
        $('#asin').removeAttr('disabled','disabled');
    }
    else{ 
        $('.asincode').css('display','none');
    }
}

function calculatePackage(obj)
{
    var units = parseInt($('#units_avl').val());
    
    var rec = parseInt(obj.value)
    var cost = parseFloat($('#cost_unit').val());

    if(units < rec || rec == 0)
    {
        rec = ''
        cost=''
        obj.value = ''
    }
    $('#package_count').val(rec);
    $('#cost_pckg').val(cost)
}

function calculateDonatedPackage(obj)
{
    var units = parseInt($('#units_avl').val());
    
    var rec = parseInt(obj.value)

    if(units < rec)
    {
        obj.value = units
    }
}

function showDiv(obj)
{
    var rec = obj.value;
    if(rec == 'combine')
    {
        $('.combine').css('display','block')
        $('.split').css('display','none')
        $('.sellas').css('display','none')
        $(".splitsku").removeAttr("required");
        $(".mergsku").attr("required","required");
        $(".asin_wpid").attr("required","required");
        $(".units_to_sell_class").removeAttr("required","required");
        $(".units_to_csell_class").attr("required","required");
        $(".units_to_ssell_class").removeAttr("required","required");    
        $(".asin_wpid_split").removeAttr("required","required");    
    }
    else if(rec == 'split')
    {
        $('.combine').css('display','none')
        $('.split').css('display','block')
        $('.sellas').css('display','none')
        $(".splitsku").attr("required","required");
        $(".mergsku").removeAttr("required");
        $(".asin_wpid_split").attr("required","required");    
        $(".asin_wpid").removeAttr("required","required");
        $(".units_to_sell_class").removeAttr("required","required");
        $(".units_to_csell_class").removeAttr("required","required");
        $(".units_to_ssell_class").attr("required","required");        
    }
    if(rec == 'sell')
    {
        $('.combine').css('display','none')
        $('.split').css('display','none')
        $('.sellas').css('display','block')
        $(".splitsku").removeAttr("required");
        $(".mergsku").removeAttr("required");
        $(".asin_wpid_split").removeAttr("required","required");    
        $(".asin_wpid").removeAttr("required","required");
        $(".units_to_sell_class").attr("required","required");      
        $(".units_to_csell_class").removeAttr("required","required"); 
        $(".units_to_ssell_class").removeAttr("required","required"); 
    }
}

function calculateCombineUnits(obj)
{
    var productList = $('#product_list').val();
    const units = []
    for(var i = 0; i < productList.length;i++)
    {
        var parry = productList[i].split('-')
        units.push(parry[1]) 
    }
    units.sort(function(a, b){return a - b});
    var sellUnit = parseInt(obj.value);
    

    if(sellUnit>units[0])
    obj.value = ''

    // var units = parseInt($('#case_units').val());
    // var pck = units*obj.value;
    // $('#package_count').val(pck)
}

function calculateSplitUnits(obj)
{
    var units = $('#units_availble').val();
    
    var sellUnit = parseInt(obj.value);
    

    if(sellUnit>units)
    obj.value = ''

    // var units = parseInt($('#case_units').val());
    // var pck = units*obj.value;
    // $('#package_count').val(pck)
}


document.querySelectorAll('.Number').forEach(function(element) {
    element.addEventListener('keypress', function(event) {
    var keyword = event.which || event.keyCode;

        if (!(event.shiftKey === false && (keyword == 46 || keyword == 8 || keyword == 37 || keyword == 39 || (keyword >= 48 && keyword <= 57)))) {
            event.preventDefault();
        }
    });
});

function checkpackage()
{
    let avl_pkg = parseInt($('#pck_available').val());
    let amz_pkg = parseInt($('#amazon_package').val());
    let pkg;

    if (avl_pkg < amz_pkg) {
       pkg = avl_pkg;
    } else {
       pkg = amz_pkg;
    }
    $('#amazon_package').val(pkg);
}

function checkPackageInv()
{
    let avl_pkg = parseInt($('#pck_available').val());
    let amz_pkg = parseInt($('#quantity').val());
    let pkg;

    if (avl_pkg < amz_pkg) {
       pkg = avl_pkg;
    } else {
       pkg = amz_pkg;
    }
    $('#quantity').val(pkg);
}

function bulkOrderdelete()
{ 
    var check = false; var bulk='';
    $('input[type=checkbox]').each(function () {
        if(this.checked)
        {
            check= true;
            bulk += $(this).val()+',';
        }        
    });

    if(check)
    {
        $('.checkAllDelete').css('display','block');
        $('#bulkInvoiceDeleteId').val(bulk);
    }
    else{
        $('.checkAllDelete').css('display','none');
    }
}

function fetchStores(clientData, id) {
    let clientId = clientData.value;
    $.ajax({
        url: "<?= $this->Url->build(['controller' => 'Order', 'action' => 'selectAllStore']) ?>",
        method: 'GET',
        data: {
            clientId: clientId
        },
        success: function(res) {
            let resData = JSON.parse(res);
            let output = [];
            resData.forEach(element => {
                output.push('<option value="' + element.id + '">' + element.store_name +
                    '</option>');
            });
            if (id == 'storeId')
                $('#storeId').html(output.join(''));
            else
                $('#addTicketStore').html(output.join(''));
        }
    })
}
function EditRefund(id, url,from) {

    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
            from: from
        },
        success: function (res) {
            $("#editrefund").html(res);
            $("#editrefund").modal("show");
        },
    });
}

function refunddelete(id,store_id)
{
    if (confirm("Are you sure you want to delete this credit")) 
    { 
        var url = 'Order/refundDelete';

        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "GET",
            data: {
                id: id,
                store_id: store_id
            },
            success: function (res) {
                if (res == 1) location.reload();
            },
        });
    }
    
}

function deleteSupportData(id, url,tablename) {
    if (confirm("Do you really want to delete this data.")) {
        $.ajax({
            url: `${baseUrl}/${url}`,
            method: "GET",
            data: {
                id: id,
                tablename:tablename,
            },
            success: function (res) {
                if (res == 1) location.reload();
            },
        });
    } else {
        location.reload();
    }
}

function openMeeting() {
    $.ajax({
        url: `${baseUrl}/Support/clientMeeting`,
        method: "GET",
        data: {
        },
        success: function(res) {
            $("#weeklymeeting").html(res);
            $("#weeklymeeting").modal('show');
        }
    })
}

function activeInactiveResource(id, status, url) {

    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
            status: status,
        },
        success: function (res) {
            if (res == 1) {
                location.reload();
            }
        },
    });
}

function updateappointment(id, status, url) {

    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
            status: status,
        },
        success: function (res) {
            if (res == 1) {
                location.reload();
            }
        },
    });
}


function updateArrivalStatus(id, status) {

    $.ajax({
        url: `${baseUrl}/Inventory/updateArrivalStatus`,
        method: "GET",
        data: {
            id: id,
            status: status,
        },
        success: function (res) {
            if (res == 1) {
                location.reload();
            }
        },
    });
}

function Showinputs(type) {
    const checkboxPrefix = type === 'add' ? 'add' : 'edit';

    const urlCheckbox = document.getElementById(`${checkboxPrefix}url`);
    const textCheckbox = document.getElementById(`${checkboxPrefix}text`);
    const embedCheckbox = document.getElementById(`${checkboxPrefix}embed`);

    document.getElementById(`${type}urlInput`).style.display = urlCheckbox.checked ? 'block' : 'none';
    $('.url').prop('required', urlCheckbox.checked);
     
    document.getElementById(`${type}textInput`).style.display = textCheckbox.checked ? 'block' : 'none';
    $('.description');

    document.getElementById(`${type}embedInput`).style.display = embedCheckbox.checked ? 'block' : 'none';
    $('.embed_code').prop('required', embedCheckbox.checked);

}

function showstore(val,type)
{
    const Prefix = type === 'add' ? 'add' : 'edit';
    var storeTypeDiv = document.getElementById(`${Prefix}_store_type_div`);
    var storeTypeInput = document.getElementById(`${Prefix}_store_type`);

    if (val === 'Client' || val === 'Both') {
        storeTypeDiv.style.display = 'block';
        storeTypeInput.setAttribute('required', 'required');
    } else {
        storeTypeDiv.style.display = 'none';
        storeTypeInput.removeAttribute('required');
    }
}

function showTags(id,url,title,heading) {
    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
        },
        success: function (res) {
        var out = JSON.parse(res)
        var html = '';
            for(var i=0;i<out.length;i++)
            {
                html +='<tr><td>'+out[i].tags+'</td></tr>'
            }

            if(html == '')
            html +='<tr><td>No Data Available</td></tr>'

            $('.tagtitle').text(title)
            $('.taghed').text(heading)
            $(".taglist").html(html);
            $("#showTaglist").modal("show");
            
        },
    });
}

function editResourceData(id,type) {
    $.ajax({
        url: `${baseUrl}/Support/editSupportResources`,
        method: "GET",
        data: {
            id: id
        },
        success: function(res) {
            $("#editResource").html(res);
            $("#editResource").modal('show');
            // CKEDITOR.replace( 'Message' );
            $('#editResource').on('shown.bs.modal', function () {
                $('#Message').summernote({
                    height: 200,
                    dialogsInBody: true,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview']]
                    ],
                });
            });


            $(".js-example-basic-multiple-store1").select2({
                dropdownParent: $("#editResource .modal-content"),
                placeholder: "Select an option",
                width: "resolve",
            });

            if(type=='View')
            {
                $('#editResource input').attr('readonly', 'readonly');
                $('#editResource textarea').attr('readonly', 'readonly');
                $('#editResource select').attr('disabled', true);
                $('#editResource input[type="checkbox"]').attr('disabled', true);
                $('.edtsbmt').css('display','none');
            }
        }
    })
}

function openEmbedCode(id,url) {
    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
        },
        success: function (res) {
            $("#embedCodeData").html(res);
            $('#embedcodeModal').modal('show');           
        },
    });
}

function toggleReadMore(elementId) {
    var element = document.getElementById(elementId);
    var moreText = document.getElementById(elementId + '-more');
    var toggleButton = document.getElementById(elementId + '-toggle');
    var dots = document.getElementById(elementId + '-dots');

    if (moreText.style.display === 'none') {
        moreText.style.display = 'inline';
        toggleButton.innerHTML = 'Read less';
        dots.style.display = "none";
    } else {
        dots.style.display = "inline";
        moreText.style.display = 'none';
        toggleButton.innerHTML = 'Read more';
    }
}
function updateRanking(id,tablename)
{
    var val = $('#ranking'+id).val();

    $.ajax({
        url: `${baseUrl}/Support/updateRank`,
        method: "POST",
        data: {
            id: id,
            tablename: tablename,
            val: val
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader(
                'X-CSRF-Token',csrfToken                
            );
        },
        success: function (res) {
        },
    });
}
function updatenoifiactionseen(id){
    $.ajax({
        url: `${baseUrl}/Client/updatenotification`,
        method: "POST",
        data: {
            id: id,
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader(
                'X-CSRF-Token',csrfToken                
            );
        },
        success: function (res) {
          location.reload()
        },
    });
}

function resolveOrder(store_id)
{
    var url = 'Order/resolveOrder';
    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            store_id: store_id
        },
        success: function (res) {
            if (res == 1) location.reload();
        },
    });    
}

function bulkManagerUpdate() {
    $('#selectall').prop('checked', false);

    var bulk = [];
    var check = false;
    var table = $('#store_example').DataTable();

    // Iterate through all rows in the DataTable
    table.rows().every(function() {
        let row = this.node(); // Get the actual row node
        let $checkbox = $(row).find('input[type="checkbox"]'); 
        console.log($checkbox.prop('checked'));

        if ($checkbox.prop('checked')) {
            check = true;
            bulk.push($checkbox.val());
        }
    });

    if (check) {
        $('.checkAll').show();
        $('#bulkStoreId').val(bulk.join(','));
        getAccountManager($('#bulkStoreId').val());
    } else {
        $('.checkAll').hide();
    }
}

function clientBulkManagerUpdate() {
    $('#selectall').prop('checked', false);

    var bulk = [];
    var check = false;
    var table = $('#masterClient').DataTable();

    // Iterate through all rows in the DataTable
    table.rows().every(function() {
        let row = this.node(); // Get the actual row node
        let $checkbox = $(row).find('input[type="checkbox"]'); 
        console.log($checkbox.prop('checked'));

        if ($checkbox.prop('checked')) {
            check = true;
            bulk.push($checkbox.val());
        }
    });

    if (check) {
        $('.checkAll').show();
        $('#bulkClientId').val(bulk.join(','));
        getAccountManagerClient($('#bulkClientId').val());
    } else {
        $('.checkAll').hide();
    }
}



// function bulkManagerUpdate()
// { 
//     $('#selectall').prop('checked', false);
//     var check = false; var bulk='';
//     $('input[type=checkbox]').each(function () {
//         if(this.checked)
//         {
//             check= true;
//             bulk += $(this).val()+',';
//         }
        
//     });

//     if(check)
//     {
//         $('.checkAll').css('display','block');
//         $('#bulkStoreId').val(bulk);
//         getAccountManager(bulk);
//     }
//     else{
//         $('.checkAll').css('display','none');
//     }

// }

// function selectBulk()
// { 
//     var check = false; var bulk='';
//     if ($('#selectall').is(":checked"))
//     {
//         $('.feecheckbox').prop('checked', true);
//         $('.feecheckbox').each(function () {

//             if(this.checked)
//             {
//                 check= true;
//                 bulk += $(this).val()+',';
//             }
            
//         });
//         $('.checkAll').css('display','block');
//         $('#bulkStoreId').val(bulk);
//         getAccountManager(bulk);
//     }
//     else{
//         $('.feecheckbox').prop('checked', false);
//         $('.checkAll').css('display','none');
//     }

// }
function selectBulk() {
    var bulk = '';

    if ($('#selectall').is(":checked")) {
        var table = $('#store_example').DataTable();
        table.rows().every(function() {
            var rowData = this.data();
            var checkboxHtml = rowData[0]; // First element contains the checkbox HTML
            var id = $(checkboxHtml).val(); // Extract the ID value from the checkbox

            bulk += id + ',';
            // Ensure the checkbox is checked
            $(this.node()).find('.feecheckbox').prop('checked', true);
        });

        $('.checkAll').css('display', 'block');
        $('#bulkStoreId').val(bulk);
        getAccountManager(bulk);
    } else {
        var table = $('#store_example').DataTable();
        table.rows().every(function() {
            // Uncheck the checkboxes
            $(this.node()).find('.feecheckbox').prop('checked', false);
        });

        $('.checkAll').css('display', 'none');
    }
}

function selectBulkClient() {
    var bulk = '';

    if ($('#selectall').is(":checked")) {
        var table = $('#masterClient').DataTable();
        table.rows().every(function() {
            var rowData = this.data();
            var checkboxHtml = rowData[0]; // First element contains the checkbox HTML
            var id = $(checkboxHtml).val(); // Extract the ID value from the checkbox

            bulk += id + ',';
            // Ensure the checkbox is checked
            $(this.node()).find('.feecheckbox').prop('checked', true);
        });

        $('.checkAll').css('display', 'block');
        $('#bulkClientId').val(bulk);
        getAccountManagerClient(bulk);
    } else {
        var table = $('#masterClient').DataTable();
        table.rows().every(function() {
            // Uncheck the checkboxes
            $(this.node()).find('.feecheckbox').prop('checked', false);
        });

        $('.checkAll').css('display', 'none');
    }
}

function getAccountManager(ids){
    //update the account manager dropdown
    let url = 'Store/getAccountManager';
    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "POST",
        dataType : 'json',
        data: {
            id: ids,
        },
        success: function (res) {
            const $dropdown = $('#assignBulkManager');
            $dropdown.empty();

            if (res.accountManagers && res.accountManagers.length > 0) {
                $dropdown.append('<option value="0">Select Account Manager</option><option value="No">No Account Manager</option>');
                $.each(res.accountManagers, function (index, manager) {
                    const availableCapacity = manager.capacity - manager.store_assign;
                    if (availableCapacity > 0) {
                        $dropdown.append(
                            `<option value="${manager.id}-${availableCapacity}">
                                ${capitalize(manager.first_name)} ${capitalize(manager.last_name)} (${availableCapacity})
                            </option>`
                        );
                    }
                });
            }
        },
    });


}

function getAccountManagerClient(ids){
    //update the account manager dropdown
    let url = 'Client/getAccountManager';
    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "POST",
        dataType : 'json',
        data: {
            id: ids,
        },
        success: function (res) {
            const $dropdown = $('#assignBulkManager');
            $dropdown.empty();

            if (res.accountManagers && res.accountManagers.length > 0) {
                $dropdown.append('<option value="0">Select Account Manager</option><option value="No">No Account Manager</option>');
                $.each(res.accountManagers, function (index, manager) {
                    const availableCapacity = manager.capacity - manager.client_assign;
                    if (availableCapacity > 0) {
                        $dropdown.append(
                            `<option value="${manager.id}-${availableCapacity}">
                                ${capitalize(manager.first_name)} ${capitalize(manager.last_name)} (${availableCapacity})
                            </option>`
                        );
                    }
                });
            }
        },
    });


}
function capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function checkRole(obj)
{
    var role = obj.value;
    if(role == 15)
    {
        $('.manager_bio').css('display','block');
        $('.manager_bios').css('display','block');
    }
    else if(role == 20){
        $('.manager_bio').css('display','none');
        $('.manager_bios').css('display','block');
    }
    else{
        $('.manager_bio').css('display','none');
        $('.manager_bios').css('display','none');
    }
    if(role == 16)
    {
        $('.account_manager').css('display','block');
        $('.store_per').css('display','none');
    }
    else{
        $('.account_manager').css('display','none');
        $('.store_per').css('display','block');
    }
}

function showDeactivation(id,url,title,heading) {

    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
        },
        success: function (res) {
            var out = JSON.parse(res)
           var html = '';
            for(var i=0;i<out.length;i++)
            {
                if(out[i].deactivation_template_id == 5)
                {
                    html +='<tr><td>'+out[i].custom_message+'</td></tr>'
                }else if(out[i].deactivation_template_id == 0){
                    html +='<tr><td>No Data Available</td></tr>'
                }else{
                  html +='<tr><td>'+out[i].templates+'</td></tr>'
                }
            }

            if(html == '')
            html +='<tr><td>No Data Available</td></tr>'

            $('.deatitle').text(title)
            $('.deahed').text(heading)
            $(".dealist").html(html);
            $("#showdeactivation").modal("show");
            
        },
    });
}

function openInternalTicket(store_id,client_id,type,department_id='',support_staff='') {

    var url = 'Tickets/openInternalTicket';
    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            store_id: store_id,
            client_id: client_id,
            department_id :department_id,
            support_staff :support_staff,
            type : type,
        },
        success: function(res) {
            $('#openInternalTicket').html(res);
            
            var hiddenInput = document.getElementById("type");
            hiddenInput.value = type;
            document.getElementById("productDiv").style.display = "none";
            document.getElementById('storeYes').checked = true;
            document.getElementById('departmentYes').checked = true;
            
            if(store_id == 0){
                document.getElementById("storeDiv").style.display = "none";
                document.getElementById('storeNo').checked = true;
            }
            if(department_id == '')
            {
                document.getElementById("departmentDiv").style.display = "none";
                document.getElementById('departmentNo').checked = true;
            }

            $('#openInternalTicket').modal('show');
        }
    })
}


// function departmentYesNo(str) {
//     if (str === 'yes') {
//         document.getElementById('departmentYes').checked = true;
//         document.getElementById('departmentDiv').style.display = '';

//     } else {
//         document.getElementById('departmentNo').checked = true;
//         document.getElementById('departmentDiv').style.display = 'none';
//         $('#departmentSelect').val(0);
//         filterStaff();
//     }
// }

// function productYesNo(str) {
//     if (str === 'yes') {
//         document.getElementById('productYes').checked = true;
//         document.getElementById('productDiv').style.display = '';
//     } else {
//         document.getElementById('productNo').checked = true;
//         document.getElementById('productDiv').style.display = 'none';
//     }
// }
// function storeesNo(str) {
//     if (str === 'yes') {
//         document.getElementById('storeYes').checked = true;
//         document.getElementById('storeDiv').style.display = '';
//     } else {
//         document.getElementById('storeNo').checked = true;
//         document.getElementById('storeDiv').style.display = 'none';
//     }
// }

function fetchStoreData(clientData, id) {
    let clientId = clientData.value;
    var url = 'Order/selectStore';
    $.ajax({
        url: `${baseUrl}/${url}`,
        method: 'GET',
        data: {
            clientId: clientId
        },
        success: function(res) {
            let resData = JSON.parse(res);
            let output = ['<option value="0">Select Store</option>'];
            let i= 1; let sel = '';
            resData.forEach(element => {
                if(i == 1)
                sel = 'selected';
                
                output.push('<option value="' + element.id + '"'+ sel+'>' + element.store_name +
                    '</option>');

                    i++;
            });
            if (id == 'storeId')
                $('#storeId').html(output.join(''));
            else if(id == 'storeIds')
                $('#storeIds').html(output.join(''));
            else
                $('#addTicketStore').html(output.join(''));
        }
    })
}

function showBio(id,url,heading) {

    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
        },
        success: function (res) {
           var out = JSON.parse(res)
           var html = '';
            for(var i=0;i<out.length;i++)
            {
                html +='<tr><td>'+out[i].manager_bio+'</td></tr>'
            }

            if(html == '')
            html +='<tr><td>No Data Available</td></tr>'

            $('.biohed').text(heading)
            $(".biolist").html(html);
            $("#showBio").modal("show");
            
        },
    });
}

function showTempManager(pmdata, type, id) {
    let pmId = pmdata.value;
    var url = 'Users/selectTempManager';
    $.ajax({
        url: `${baseUrl}/${url}`,
        method: 'GET',
        data: {
            pmId: pmId,
            id: id
        },
        success: function(res) {
            let output = [];
            let resData = JSON.parse(res);
            
            if (type === 'editTempAccountManager') {
                var users = resData.users;
                var staffManagerIds = resData.staffManagerIds;
                
                users.forEach(element => {
                    var selected = staffManagerIds.includes(element.id) ? ' selected' : '';
                    output.push('<option value="' + element.id + '"' + selected + '>' + element.name + '</option>');
                });
            } else {
                resData.forEach(element => {
                    output.push('<option value="' + element.id + '">' + element.name + '</option>');
                });
            }
            
            $('#' + type).empty();
            $('#' + type).html(output.join(''));
        }
    });
}


$(document).ready(function() {
    var exportJobId = localStorage.getItem('exportJobId');
    if (exportJobId) {
        checkExportStatus(exportJobId);
    }
});

$("#myExportForm").submit(function(e) {    
    e.preventDefault();

    let form = $(this);
    var actionUrl = form.attr('action');

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: form.serialize(),
        dataType: "json",
        success: function(response) {
            // Display flash message

            $('#flash-messages').html('<div class="alert alert-success text-center alert-dismissible" role="alert"><b>' + response.flashMessage + '</b><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            // alert('Excel export started in the background.');
            
            //remove exportjobId if it exists in local storage the add afterwards
            localStorage.removeItem('exportJobId');

            // Store exportJobId in local storage
            localStorage.setItem('exportJobId', response.exportJobId);
            // Start checking the export status
            checkExportStatus(response.exportJobId);
        },
        error: function(xhr, status, error) {
            console.log(error);
            // alert('Error exporting Excel: ' + error);
        }
    });
});

function checkExportStatus(exportJobId) {
    $.ajax({
        url: `${baseUrl}/Order/checkExportStatus/` + exportJobId,
        type: 'GET',
        dataType: "json",
        success: function(response) {
            if (response.status == 'completed') {
                // Download the file once the export is completed
                if (confirm('The export is complete. Do you want to download the file now?')) {
                    // Download the file once the export is completed
                    window.location.href = `${baseUrl}/Order/downloadExportFile/` + exportJobId;                
                }
                
                // Remove exportJobId from local storage
                localStorage.removeItem('exportJobId');
            } else {
                // Continue checking the status
                setTimeout(function() {
                    checkExportStatus(exportJobId);
                }, 1000); // Check every 1 second
            }
        },
        error: function(xhr, status, error) {
            // Handle error checking status
            console.log(error);
            alert('Error checking export status: ' + error);
        }
    });
}

function openReceivedModal(id)
{
    $.ajax({
        url: `${baseUrl}/Buybacks/getProductLocation`,
        method: 'GET',
        data: {
            id: id,
            call: 'import'
        },
        success: function(res) {
            const obj = JSON.parse(res)
            $("#buyback_import_id").val(id)
            $("#last_location").val(obj.location)
            $('#product_import_id').val(obj.product_id)
            $("#receiveQuantityModal").modal("show");
        }
    });
}

function updateImportProductStatus(id)
{
    $.ajax({
        url: `${baseUrl}/Buybacks/updateImportProductStatus`,
        method: 'GET',
        data: {
            id: id
        },
        success: function() {
            
            
            $('.imptbtn_'+id).prop('disabled', true);
            $('.imptbtn_'+id).css('background-color', "#606060");
            $('.imptbtn_'+id).css('color', "white");
            $('.imptbtn_'+id).removeClass('btn-primary');
            
        },
        error: function(xhr, status, error) {
            console.log(error);
            // alert('Error exporting Excel: ' + error);
        }
    });
}

function approveBuybackStatus(id,qnt)
{
    $.ajax({
        url: `${baseUrl}/Buybacks/approveBuybackStatus`,
        method: 'GET',
        data: {
            id: id,
            quantity: qnt
        },
        success: function() {
            
            
            location.reload();
            
        },
        error: function(xhr, status, error) {
            console.log(error);
            // alert('Error exporting Excel: ' + error);
        }
    });
}

function returnOverage(id,qnt,product_id)
{
    $.ajax({
        url: `${baseUrl}/Buybacks/returnOverage`,
        method: 'GET',
        data: {
            id: id,
            quantity: qnt,
            product_id: product_id
        },
        success: function() {
            
            
            location.reload();
            
        },
        error: function(xhr, status, error) {
            console.log(error);
            // alert('Error exporting Excel: ' + error);
        }
    });
}

function filterStaff(){
    let department_id = $('#departmentSelect').val();
    $.ajax({
        url: `${baseUrl}/Users/staffAccordingtoDepartment`,
        method: 'GET',
        data: {
            id: department_id
        },
        dataType: 'json',
        success: function(res) {
            let staffSelect = $('#staffSelect');
            staffSelect.empty();
            staffSelect.append('<option value="0">Select Staff</option>');

            if (res && res.length > 0) {
                res.forEach(staff => {
                    staffSelect.append(`<option value="${staff.id}">${staff.first_name} ${staff.last_name}</option>`);
                });
            }
        }
    })
}

function editUserPermission(id){
    $.ajax({
        url: `${baseUrl}/InternalStaff/individualPermission`,
        method : 'POST',
        data: {
            id: id
        },
        success: function(res){
            $("#editPermission").html(res);
            $("#editPermission").modal("show");
        }
    });     
}

function EditWatcher(id) {
    $.ajax({
        url: `${baseUrl}/Tickets/editInternalTicketWatcher`,
        method: "GET",
        data: {
            ticket_id: id,
        },
        success: function (res) {
            $("#watcherModal").html(res);
            $('#watcherModal').modal('show');
        },
    });
}

function fetchStore() {
    let clientId = $('#client_id').val();
    if (clientId == "0") {
        $('#store_id').html('<option value="0">Please Select</option><option value="not_store_specific">Not Store Specific</option>');
        return;
    }

    $.ajax({
        url: `${baseUrl}/Order/selectStore`,
        type: 'GET',
        data: { clientId: clientId },
        dataType: 'json',
        success: function(response) {
            // Clear the current options
            $('#store_id').empty();

            $.each(response, function(index, store) {
                $('#store_id').append($('<option>', {
                    value: store.id,
                    text: store.store_name
                }));
            });
        },
        error: function() {
            console.log("An error occurred while fetching stores.");
        }
    });
}

function AssignClient(id){
    $.ajax({
        url : `${baseUrl}/Tickets/editInternalTicketClient`,
        method : "GET",
        data : {id: id},
        success : function(res){
            $("#assignClientStoreModal").html(res);
            $("#assignClientStoreModal").modal('show');
        }
    })
}

function getClientStore(){
    let clientId = $('#client_id').val();

    $.ajax({
        url: `${baseUrl}/Order/selectStore`,
        type: 'GET',
        data: { clientId: clientId },
        dataType: 'json',
        success: function(response) {
            // Clear the current options
            $('#store_id').empty();

            //add option select store
            $('#store_id').append('<option value="0">Select Store</option>');
            $.each(response, function(index, store) {
                $('#store_id').append($('<option>', {

                    value: store.id,
                    text: store.store_name
                }));
            });
        },
        error: function() {
            console.log("An error occurred while fetching stores.");
        }
    });
}

function togglePasswordVisibility(id) {
    let passwordInput = document.getElementById(id);
    let toggleIcon = document.getElementById("toggleIcon");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.classList.remove("bx-hide");
        toggleIcon.classList.add("bx-show");
    } else {
        passwordInput.type = "password";
        toggleIcon.classList.remove("bx-show");
        toggleIcon.classList.add("bx-hide");
    }
}

function showReturnIds(id,url,title,heading) {

    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
        },
        success: function (res) {
           var out = JSON.parse(res)
           var html = '';
            for(var i=0;i<out.length;i++)
            {
                html +='<tr><td>'+out[i].return_id+'</td></tr>'
            }

            if(html == '')
            html +='<tr><td>No Data Available</td></tr>'

            $('.returnTitle').text(title)
            $('.returnhed').text(heading)
            $(".returnlist").html(html);
            $("#showReturnlist").modal("show");
            
        },
    });
}

function updateExecutiveStatus(id, status, url) {

    $.ajax({
        url: `${baseUrl}/${url}`,
        method: "GET",
        data: {
            id: id,
            status: status,
        },
        success: function (res) {
            if (res == 1) {
                location.reload();
            }
        },
    });
}
