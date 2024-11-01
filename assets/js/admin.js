const { default: tinymce } = require("tinymce");
const { generateDataTable } = require("./datatable");

document.addEventListener("DOMContentLoaded", function () { 
    const tableDataSelector = document.querySelector(".js-table-data");
    const tableData = JSON.parse(tableDataSelector.getAttribute("data-table-job-api"));


    generateDataTable(
        tableData,
        [
            "name",
            "description",
            "functionName",
            "link"
        ],
        '#job-api-table',
        true,
        '/admin/job_service/#id');
    
        tinymce.init({
            selector: '#job_api_services_description'
          });
})