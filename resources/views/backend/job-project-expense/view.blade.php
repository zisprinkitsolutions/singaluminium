<div class="border-bottom pb-1">
    <div class="d-flex flex-wrap  aigin-items-center">
        <p style="font-size:15px;font-weight:500;color:#444;margin:10px 20px 0 0;"> <span style="font-weight:bold"> Poject Name : </span> {{ $job_project->project_name }}</p>
    </div>
    <p style="font-size:15px;font-weight:500;color:#444;margin:10px 20px 0 0;"> <span style="font-weight:bold"> Poject Description : </span> {{ $job_project->project_description}}</p>
</div>

<p style="font-size:15px;font-weight:500;color:#444;margin:10px 20px 0 0;"> <span style="font-weight:bold"> All Expenses </span> </p>
<div class="my-1">
    <table class="table table-sm">
        <thead>
            <tr>
                <th class=""> Expens No </th>
                <th class=""> Item </th>
                <th class="text-center"> Date </th>
                <th class="text-center"> Qty </th>
                <th class="text-center"> Unit  </th>
                <th class="text-center"> Price </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($job_project->expenses as $item)
            <tr>
                <td class="">{{ 'Expense No '. $item->id }}</td>
                <td>{{ $item->item }}</td>
                <td class="text-center">
                    {{ date('d/m/y',strtotime($item->date)) }}
                </td>
                <td class="text-center">
                    {{ $item->qty}}
                </td>
                <td class="text-center">
                    {{ $item->unit->name}}
                </td>
                <td class="text-center">
                    {{ $item->price }}
                </td>
            </tr>
            @endforeach
            <td colspan="4" class="text-right text-dark">

            </td>
            <td class="text-center text-dark" style="border-right:1px solid #ddd;">
                Total
            </td>
            <td class="text-center text-dark">
                {{ $job_project->expenses->sum('price') }}
            </td>
        </tbody>
    </table>
</div>
