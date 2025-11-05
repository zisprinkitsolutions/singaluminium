<div class="border-bottom pb-1">
    <div class="d-flex flex-wrap  aigin-items-center">
        <p style="font-size:15px;font-weight:500;color:#444;margin:10px 20px 0 0;"> <span style="font-weight:bold"> Poject Name : </span> {{ $payment->project_name }}</p>
        <p style="font-size:15px;font-weight:500;color:#444;margin:10px 20px 0 0;"> <span style="font-weight:bold"> Poject Customer : </span> {{ $payment->party->pi_name}}</p>
        <p style="font-size:15px;font-weight:500;color:#444;margin:10px 20px 0 0;"> <span style="font-weight:bold"> Poject Budget : </span> {{ $payment->tasks->sum('budget') }}</p>
        <p style="font-size:15px;font-weight:500;color:#444;margin:10px 20px 0 0;"> <span style="font-weight:bold"> Total Payment : </span> {{ $payment->payments->sum('payment_amount')}}</p>
        <p style="font-size:15px;font-weight:500;color:#444;margin:10px 20px 0 0;"> <span style="font-weight:bold"> Due Amount : </span> {{ $payment->tasks->sum('budget') - $payment->payments->sum('payment_amount')}}</p>


    </div>
    <p style="font-size:15px;font-weight:500;color:#444;margin:10px 20px 0 0;"> <span style="font-weight:bold"> Poject Description : </span> {{ $payment->project_description}}</p>
</div>

<p style="font-size:15px;font-weight:500;color:#444;margin:10px 20px 0 0;"> <span style="font-weight:bold"> All Payments </span> </p>
<div class="my-1">
    <table class="table table-sm">
        <thead>
            <tr>
                <th> Payment No </th>
                <th class="text-center"> Date </th>
                <th class="text-center"> Payment Amount </th>
                <th class="text-center"> balance </th>
            </tr>
        </thead>
        <tbody>
            @php
                $balance = $payment->tasks->sum('budget');
            @endphp
            <tr>
                <td colspan="2"></td>
                <td style="text-align:center;font-weight:500;color:#475F7B;font-size:13;letter-spacing: 1px;text-transform:capitalize;"> Project Bugdet </td>
                <td style="text-align:center;font-weight:500;color:#475F7B;font-size:13;letter-spacing: 1px;text-transform:capitalize;"> DR {{$payment->tasks->sum('budget')}} </td>
            </tr>
            @foreach ($payment->payments as $item)
            @php
            $balance -= $item->payment_amount;
            @endphp
            <tr>
                <td>{{ 'Payment No '. $item->id }}</td>
                <td class="text-center">
                    {{ date('d/m/y',strtotime($item->date)) }}
                </td>
                <td class="text-center">
                    {{ $item->payment_amount }}
                </td>
                <td class="text-center">
                    DR {{ $balance  }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
