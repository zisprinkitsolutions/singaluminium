@extends('layouts.backend.app')
@section('content')
@include('backend.tab-file.style')

<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.accounting._header',['activeMenu' => 'false'])
            <div class="tab-content bg-white p-4 active">
                <div class="tab-pane active">
                    {{-- <h3> Report </h3>
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
