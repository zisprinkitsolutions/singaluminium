@extends('layouts.backend.app-print')
@section('content')
   
<style>
    @media print{
        .cardStyleChange{
            overflow: hidden !important;
        }
    }
</style>
<section id="widgets-Statistics">
    <div class="cardStyleChange">
        <table class="table table-sm">
            <thead  class="thead-light">
                <tr style="height: 50px;">
                    <th>Code</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>TRN Number</th>
                    <th>Email</th>
                    <th>Contact Person</th>
                    <th>Contact Number</th>
                    {{-- <th>Phone Number</th> --}}
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($partyInfos as $pInfo)
                <tr class="trFontSize">
                    <td>{{ $pInfo->pi_code }}</td>
                    <td>{{ $pInfo->pi_name }}</td>
                    <td>{{ $pInfo->pi_type }}</td>
                    <td>{{ $pInfo->trn_no }}</td>
                    <td>{{ $pInfo->email }}</td>
                    <td>{{ $pInfo->con_person }}</td>
                    <td>{{ $pInfo->con_no }}</td>
                    {{-- <td>{{ $pInfo->phone_no }}</td> --}}
                    <td>{{ $pInfo->address }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection