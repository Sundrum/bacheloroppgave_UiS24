@extends('layouts.app')
@section('content')



<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <h4>Payment history for {{ $userData['customer_name'] ?? ''}}</h4>
            <label for="date-filter">Filter by Date:</label>
            <select id="year-filter">
                <option value="">Year</option>
                @for ($year = date('Y'); $year >= 2020; $year--)
                    <option value="{{$year}}">{{$year}}</option>
                @endfor
            </select>
            <select id="month-filter">
                <option value="">Month</option>
                @for ($month = 1; $month <= 12; $month++)
                    <option value="{{str_pad($month, 2, '0', STR_PAD_LEFT)}}">{{date('F', mktime(0, 0, 0, $month, 1))}}</option>
                @endfor
            </select>
            <label for="status-filter">Status:</label>
            <select id="status-filter">
                <option value="" selected>All</option>
                <option value="0">Created</option>
                <option value="1">Cancelled</option>
                <option value="2">Failed</option>
                <option value="3">Completed</option>
            </select>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Company</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>PDF</th>
                    </tr>
                </thead>
                <tbody id="payment-table-body">
                    
                </tbody>
            </table>
        </div>
    </div>
    <div id="LoadingText" style="text-align: center;">
    Loading history:
    <p id="dateCount"></p>
    </div>
</section>





<script src="https://kit.fontawesome.com/bc63d04dca.js" crossorigin="anonymous"></script>
<script>

 
let customer = {!! json_encode($customer ?? null) !!};
document.getElementById('year-filter').addEventListener('change', filterTable);
document.getElementById('month-filter').addEventListener('change', filterTable);
document.getElementById('status-filter').addEventListener('change', filterTable);

function submitForm(paymentId) {
    // Find the form element corresponding to the paymentId
    const form = document.getElementById(`form_${paymentId}`);
    // Redirect to the newly generated view if needed
    window.location.href = 'https://student.portal.7sense.no/downloadinvoice?paymentId=' + paymentId;
    }

async function fetchPaymentHistory(Date) {
        try {
            const response = await fetch(`/fetchPaymentHistory?date=${Date}`);
            if (!response.ok) {
                //throw new Error('Failed to fetch payment history');
            }
            const paymentData = await response.json();
            // Process payment data and update the UI
            const dateCountElement = document.getElementById('dateCount');
            dateCountElement.textContent = Date;
            updatePaymentTable(paymentData);
        } catch (error) {
            //console.error('Error fetching payment history:', error);
        }
    }

// Function to format date as yyyy-mm-dd
function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    // Function to update the payment table with fetched data
// Function to update the payment table with fetched data
function updatePaymentTable(paymentData) {
    // Clear existing table rows
    const paymentTableBody = document.getElementById('payment-table-body');
    //paymentTableBody.innerHTML = '';

    // Populate table rows with fetched data
    paymentData.forEach(payment => {
        const dateTimeParts = payment.created_at.split(' ');
        const date = dateTimeParts[0];
        const time = dateTimeParts[1].substring(0, 5); // Extracting time HH:MM
        // Create table row
        const row = document.createElement('tr');
        row.innerHTML = `
            <form id="form_${payment.payment_id}" method="POST" action="https://student.portal.7sense.no/invoice">
                <input type="hidden" name="payment_id" value="${payment.payment_id}">
                <td id="date">${date}</td>
                <td>${time}</td>
                <td>${payment.nets.orderDetails.reference ?? 'N/A' }</td>
                <td>${payment.nets.orderDetails.amount/100 ?? 'N/A'} ${payment.nets.orderDetails.currency}</td>
                <td>${payment.nets.consumer.company.name ?? customer.customer_name ?? 'N/A' }</td> 
                <td>${payment.nets.paymentDetails.paymentMethod ?? 'N/A'}</td>
                <td id="status">${getStatus(payment.payment_status) ?? 'N/A'} </td>
                <td>
                    ${payment.payment_status === 3 && payment.nets.orderDetails.amount/100 !== 0 ?`
                    <button type="button" onclick="submitForm('${payment.payment_id}')" class="btn-PDF">
                        <i class="fa-solid fa-file-arrow-down" style="color: #00265a; font-size: 2em;"></i>
                     </button>
                    ` : ''}
                </td>
            </form>
        `;
        // Append row to table body
        paymentTableBody.appendChild(row);
        filterTable();
    });
}
function getStatus(statusCode) {
    const statusCodes = {
        0: 'Created',
        1: 'Cancelled',
        2: 'Failed',
        3: 'Completed'
    };
    return statusCodes[statusCode] ?? 'Unknown';
}
function getDescription(description) {
    switch(description) {
        case "Created":
            return 0;
        case "Cancelled":
            return 1;
        case "Failed":
            return 2;
        case "Completed":
            return 3;
        default:
            return null;
    }
}


function getPreviousDate(date) {
    // Get the year, month, and day components from the provided date
    const year = date.getFullYear();
    const month = date.getMonth() + 1; // Note: JavaScript months are zero-based
    const day = date.getDate();

    // Calculate the previous date
    let previousDay = day - 1;
    let previousMonth = month;
    let previousYear = year;

    // Adjust the date if the day is 0 (meaning it's the first day of the month)
    if (previousDay === 0) {
        // Get the last day of the previous month
        const lastDayOfPreviousMonth = new Date(year, month - 1, 0).getDate();
        previousMonth--; // Move to the previous month
        previousDay = lastDayOfPreviousMonth; // Set the day to the last day of the previous month
    }

    // Adjust the month and year if necessary
    if (previousMonth === 0) {
        // If the previous month is 0, it means we moved back from January to December of the previous year
        previousMonth = 12; // Set the previous month to December
        previousYear--; // Decrement the year
    }

    // Return the previous date as a Date object
    return new Date(previousYear, previousMonth - 1, previousDay); // Note: JavaScript months are zero-based
}


let currentDate = new Date();
let formattedDate = formatDate(currentDate);

// Loop until the date is older than 2020
async function LoadPaymentHistory() {
    while (currentDate.getFullYear() >= 2020) {
        // Await the fetchPaymentHistory function to finish before continuing
        await fetchPaymentHistory(formattedDate);
        
        // Get the previous date
        currentDate = getPreviousDate(currentDate);
        formattedDate = formatDate(currentDate);
    }
}

// Function to filter the table based on filter options
function filterTable() {
    const selectedYear = document.getElementById('year-filter').value;
    const selectedMonth = document.getElementById('month-filter').value;
    const selectedStatus = document.getElementById('status-filter').value;
    const tableRows = document.querySelectorAll('#payment-table-body tr');
    
    tableRows.forEach(row => {

        var rowStatus = row.querySelector('#status').textContent.trim();
        var rowYear = row.querySelector('#date').textContent.split('-')[0];
        var rowMonth = row.querySelector('#date').textContent.split('-')[1];

        if ((!selectedStatus || rowStatus.toString() === getStatus(selectedStatus).toString()) && 
            (!selectedYear || rowYear === selectedYear) && 
            (!selectedMonth || rowMonth === selectedMonth)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

//Entry Point
LoadPaymentHistory();

</script>
<style>
    .bg-white.card-rounded .btn-PDF {
    background-color: white;
    border-color: white;
    box-shadow: none; /* Remove shadow */
    padding: 0; /* Remove padding */
    border: none; /* Remove border */
}

.bg-white.card-rounded .link-button {
    background: none;
    border: none;
    padding: 0;
    font: inherit;
    cursor: pointer;
}

.bg-white.card-rounded .link-button:hover {
    color: lightskyblue; /* Change the color for the hover effect */
    text-decoration: underline;
}

.bg-white.card-rounded #button {
    background: none;
    border: none;
}

.bg-white.card-rounded thead {
    font-weight: 900;
}

.bg-white.card-rounded .pressable {
    width: 30px; /* Adjust the width and height as needed */
    height: 30px;
    border-radius: 30%;
    background-color: #00265a;
}

.bg-white.card-rounded .pressable:hover {
    background-color: #a7c49d;
}



</style>
@endsection
