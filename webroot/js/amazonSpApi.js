$(document).ready(function () {
    $("#amazon_report_card").on("click","#amazon_report_request_button",function(){
        store_id = $(this).attr('data-store-id');
        SpApiRequestReportPopup(store_id);
    });
});

function SpApiViewReportDocument(store_id, document_id) {
    $.ajax({
        url: `${baseUrl}/amazon-sp-api/get-report-document`,
        method: "POST",
        data: {
            store_id: store_id,
            document_id: document_id
        },
        success: function (res) {
            $("#view_document_modal").html(res);
            $("#view_document_modal").modal("show");
            $("#view_document_table").DataTable({
                // scrollX: true,
            });
        },
    });
}
function SpApiDownloadReportDocument(store_id, document_id, file_type) {

    window.location.href = `${baseUrl}/amazon-sp-api/download-report-document-json?store_id=` + store_id +`&document_id=` + document_id + `&file_type=` + file_type;

}

function SpApiApplyReportFilterSubmit(event){
    event.preventDefault();
    form = event.target.elements;
    console.log('report filter was submitted');
    store_id = form.store_id.value;
    report_type = form.report_type.value;
    start_date = form.start_date.value;
    end_date = form.end_date.value;
    $.ajax({
        url: `${baseUrl}/amazon-sp-api/get-report-list`,
        method: "POST",
        data: {
            store_id: store_id,
            report_type: report_type,
            start_date: start_date,
            end_date: end_date
        },
        success: function (res) {
            $("#amazon_document_table_container").html(res);
            init_amazon_document_table();
        },
    });
}

function SpApiRequestReportPopup(store_id){
    console.log('SpApiRequestReportPopup has run.');
    $.ajax({
        url: `${baseUrl}/amazon-sp-api/request-report`,
        method: "GET",
        data: {store_id: store_id,},
        success: function (res) {
            $("#amazon_request_report_form_modal").html(res);
            $("#amazon_request_report_form_modal").modal("show");
        },
    });
}

function SpApiRequestReportFormSubmit(event){
    event.preventDefault();
    form = event.target.elements;
    console.log('report filter was submitted');
    store_id = form.store_id.value;
    report_type = form.report_type.value;
    start_date = form.start_date.value;
    end_date = form.end_date.value;
    $.ajax({
        url: `${baseUrl}/amazon-sp-api/request-report`,
        method: "POST",
        data: {
            store_id: store_id,
            report_type: report_type,
            start_date: start_date,
            end_date: end_date
        },
        success: function (res) {
            $("#amazon_request_report_form_modal").html(res);
            $("#amazon_request_report_form_modal").modal("show");
        },
    });
}