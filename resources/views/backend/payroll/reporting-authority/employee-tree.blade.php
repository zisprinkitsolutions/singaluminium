@php
    $colors = [
        '#2c3e50', '#2980b9', '#27ae60', '#8e44ad',
        '#e67e22', '#1abc9c', '#d35400'
    ];
    $textColor = $colors[$depth % count($colors)];
@endphp

<li style="color: {{ $textColor }};">
    <div class="tree-node" style="padding-left: {{ 20}}px;">
        <span class="employee-name">{{ $employee->full_name . ' (' .$employee->code . ')' }}</span>
        <button class="btn btn-icon text-info edit-btn" data-url="{{route('reporting.authority.edit',$employee->id)}}">
            <i class='bx bx-edit'></i>
        </button>
    </div>

    @if ($employee->recursiveSubordinates->count())
        <ul>
            @foreach ($employee->recursiveSubordinates as $child)
                @include('backend.payroll.reporting-authority.employee-tree', [
                    'employee' => $child,
                    'depth' => $depth + 1
                ])
            @endforeach
        </ul>
    @endif
</li>
