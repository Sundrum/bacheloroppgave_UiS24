@extends('layouts.app')
@section('content')
<section class="bg-white card-rounded"  id="app">
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
                    @foreach ($paymentData as $payment)
                    @php
                        $dateTimeParts = explode(' ', $payment->created_at);
                        $date = $dateTimeParts[0];
                        $time = substr($dateTimeParts[1], 0, 5); // Extracting time HH:MM
                    @endphp
                    <tr data-status="{{ $payment->payment_status }}" data-date="{{$date}}">
                        <form action="{{ route('invoice') }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_id" value="{{$payment->payment_id}}">
                        <td>{{ $date }}</td>
                        <td>{{ $time }}</td>
                        <td>{{ $payment->nets->orderDetails->reference ?? 'N/A' }}</td>
                        <td>{{ $payment->nets->orderDetails->amount/100 ?? 'N/A' }} {{ $payment->nets->orderDetails->currency }}</td>
                        <td>{{ $payment->nets->consumer->company->name ?? $customer->customer_name ?? 'N/A' }}</td> 
                        <td>{{ $payment->nets->paymentDetails->paymentMethod ?? 'N/A'}}</td>
                        <td>{{ $payment->getStatus($payment->payment_status)  ?? 'N/A'}}</td>
                        <td>
                            @if ($payment->payment_status == 3)
                            <button type="submit" value="{{$payment->payment_id}}" class="btn-PDF">
                                <i class="fa-solid fa-file-arrow-down" style="color: #00265a; font-size: 2em;"></i>
                            </button>
                            @else
                            N/A
                            @endif
                        </td>
                        </form>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
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
                    @foreach ($paymentData as $payment)
                    @php
                        $dateTimeParts = explode(' ', $payment->created_at);
                        $date = $dateTimeParts[0];
                        $time = substr($dateTimeParts[1], 0, 5); // Extracting time HH:MM
                    @endphp
                    <tr data-status="{{ $payment->payment_status }}" data-date="{{$date}}">
                        <form action="{{ route('invoice') }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_id" value="{{$payment->payment_id}}">
                        <td>{{ $date }}</td>
                        <td>{{ $time }}</td>
                        <td>{{ $payment->nets->orderDetails->reference ?? 'N/A' }}</td>
                        <td>{{ $payment->nets->orderDetails->amount/100 ?? 'N/A' }} {{ $payment->nets->orderDetails->currency }}</td>
                        <td>{{ $payment->nets->consumer->company->name ?? $customer->customer_name ?? 'N/A' }}</td> 
                        <td>{{ $payment->nets->paymentDetails->paymentMethod ?? 'N/A'}}</td>
                        <td>{{ $payment->getStatus($payment->payment_status)  ?? 'N/A'}}</td>
                        <td>
                            @if ($payment->payment_status == 3)
                            <button type="submit" value="{{$payment->payment_id}}" class="btn-PDF">
                                <i class="fa-solid fa-file-arrow-down" style="color: #00265a; font-size: 2em;"></i>
                            </button>
                            @else
                            N/A
                            @endif
                        </td>
                        </form>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
<script src="https://kit.fontawesome.com/bc63d04dca.js" crossorigin="anonymous"></script>
<script>
    var yearFilter = document.getElementById('year-filter');
    var monthFilter = document.getElementById('month-filter');
    var statusFilter = document.getElementById('status-filter');
    yearFilter.addEventListener('change', filterTable);
    monthFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
    function filterTable() {
        var selectedYear = yearFilter.value;
        var selectedMonth = monthFilter.value;
        var selectedStatus = statusFilter.value;
        var tableRows = document.querySelectorAll('#payment-table-body tr');
        tableRows.forEach(function(row) {
            var rowStatus = row.getAttribute('data-status');;
            var rowYear = row.getAttribute('data-date').split('-')[0];
            var rowMonth = row.getAttribute('data-date').split('-')[1];
            console.log("ROWYEAR",rowYear)
            console.log("ROWMOTH",rowMonth)
            if ((!selectedStatus || rowStatus === selectedStatus) && (!selectedYear || rowYear === selectedYear) && (!selectedMonth || rowMonth === selectedMonth)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
<style>
    .btn-PDF {
    background-color: white;
    border-color: white;
    box-shadow: none; /* Remove shadow */
    padding: 0; /* Remove padding */
    border: none; /* Remove border */
    }       
    .link-button {
    background: none;
    border: none;
    padding: 0;
    font: inherit;
    cursor: pointer;
    }

    .link-button:hover {
        color: lightskyblue; /* Change the color for the hover effect */
        text-decoration: underline;
    }

    #button {
        background: none;
        border: none;
    }
    thead {
        font-weight: 900;
    }

</style>
@endsection
