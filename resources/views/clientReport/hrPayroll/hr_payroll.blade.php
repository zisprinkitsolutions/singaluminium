@extends('layouts.backend.app')
@section('content')
@include('backend.tab-file.style')

<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('backend.payroll.tab-sub-tab._basic_info_header', ['activeMenu' => 'false'])
            <div class="tab-content bg-white pt-1 px-2 active">
                <div class="tab-pane active">
                    {{-- @include('clientReport.hrPayroll._base_table_submenu',['activeMenu' => 'fal'])
                    <h3> Report </h3>
                    <p>
                        Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus.
                     </p>
                     <blockquote class="blockquote">
                        <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                      </blockquote> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
