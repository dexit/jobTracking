import { performSearch } from "./search";

document.addEventListener("DOMContentLoaded", function () {
    const tableDataSelector = document.querySelector(".js-table-data");
    const tableData = JSON.parse(tableDataSelector.getAttribute("data-jobs"));
    
    document.getElementById("search-input").addEventListener('keyup', e=>performSearch(e.target.value, tableData, 'job-list', ['company', 'description']))
})
