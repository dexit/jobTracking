import moment from "moment";
import { generateDataTable } from "./datatable";

document.addEventListener("DOMContentLoaded", function () {
    const tableDataSelector = document.querySelector(".js-table-data");
    const tableData = JSON.parse(tableDataSelector.getAttribute("data-address-book"))
        .map(contact => {
            const note = contact.note.length > 150 ? contact.note.slice(0, 150) + '...' : contact.note
            return {
                ...contact,
                lastName: contact.lastName.toUpperCase(),
                firstName: titlelize(contact.firstName),
                createdAt: moment(contact.createdAt).format('D/M/Y'),
                note
            }
        });

    generateDataTable(tableData, ['lastName', 'firstName', 'email', 'company', 'createdAt', 'note', 'link'], true, '#address-book-table', '/address/book/id/edit');
})

function titlelize(str) {
    if (!!str) {
        return str
    }
    str = str.slice(0, 1).toUpperCase() + str.toLowerCase().slice(1);


    return str
}