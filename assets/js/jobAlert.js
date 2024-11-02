import { performSearch } from "./search";

document.addEventListener("DOMContentLoaded", function () {
    const tableDataSelector = document.querySelector(".js-table-data");
    const tableDataAdzuna = JSON.parse(tableDataSelector.getAttribute("data-jobs"));
    
    document.getElementById("search-input").addEventListener('keyup', e=>performSearch(e.target.value, tableDataAdzuna, 'job-list', ['company', 'description']))
})
