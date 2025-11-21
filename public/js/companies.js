function exportToXlsx()
{
    TableToExcel.convert(document.getElementById('table'), {
        name: 'companies.xlsx',
        sheet: {
            name: 'Лист 1'
        }
    });
}

function exportToCsv()
{
    const headers = document.getElementsByTagName('th');
    const values = document.getElementsByTagName('td');

    let csv = '';
    for(let i = 0; i < headers.length; i++)
    {
        csv += '"' + headers[i].textContent + '"';
        if(i !== headers.length - 1) csv += ',';
    }
    csv += '\n';
    for(let i = 0; i < values.length; i++)
    {
        csv += '"' + values[i].textContent + '"';
        csv += (i + 1) % headers.length === 0 ? '\n' : ',';
    }
    const blob = new Blob([csv], { type: 'text/csv;charset=utf8;' })
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'companies.csv';
    link.style.display = 'none';

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function startLoading()
{
    document.getElementById('mainBody').hidden = true;
    document.getElementById('loading').hidden = false;
}
