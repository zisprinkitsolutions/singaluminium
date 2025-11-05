<div class="modal-content">
    <div class="modal-header" style="padding: 5px 18px;background:#364a60;">
        <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">Inventory Reports</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <section id="widgets-Statistics" class="mb-2" id="inventory_list_view">
        @include('backend.inventory.sub-head', ['activeMenu' => 'inventory'])

        <div class="table-responsive" style="padding:0 15px !important;">
            <table class="table table-bordered table-sm table-striped 2filter-table thermal-table" id="print-table">
            <thead class="thead">
                <tr class="trFontSize text-center">
                    <th> Account Head </th>
                    <th> Unit </th>
                    <th> Opening Stock </th>
                    <th> Qty In </th>
                    <th> Qty Out </th>
                    <th> Closing Stock </th>
                </tr>
            </thead>
            <tbody id="purch-body">
                @foreach ($products as $key => $item)
                    @php
                        $values = $item->openning_product_office( $from, $item->id);
                        $quantity = $item->quantity_in( $from, $to, $item->id);
                        $quantity_out = $item->quantity_out( $from, $to, $item->id);
                        $closing_qunatity = $values['opening'] + $quantity['quantity_in'] - $quantity_out;
                    @endphp
                    <tr class="account_head_allocation text-center trFontSize" onclick="inventory_assign_btn(this)">
                        <input type="hidden" name="" class="inventory_expense_head" value="{{$item->id}}">
                        <input type="hidden" name="" class="imventory_qty" value="{{$closing_qunatity}}">
                        <td class="text-left pl-1">{{ $item->fld_ac_head }}</td>
                        <td> - </td>
                        <td>{{ floatval($values['opening'])}}</td>
                        <td>{{ floatval($quantity['quantity_in'])}}</td>
                        <td>{{ floatval($quantity_out) }}</td>
                        <td>{{ floatval($closing_qunatity)}}</td>
                    </tr>
                    @foreach ($item->sub_heads as $sub_head)
                        @php
                            $values = $sub_head->sub_head_openning_product_office( $from, $sub_head->id);
                            $quantity = $sub_head->sub_head_quantity_in( $from, $to, $sub_head->id);
                            $quantity_out = $sub_head->sub_head_quantity_out( $from, $to, $sub_head->id);
                            $closing_qunatity = $values['opening'] + $quantity['quantity_in'] - $quantity_out;
                        @endphp

                        <tr class="account_head_allocation text-center trFontSize" onclick="inventory_assign_btn(this)">
                            <input type="hidden" name="" class="inventory_expense_head" value="Sub{{$sub_head->id}}">
                            <input type="hidden" name="" class="imventory_qty" value="{{$closing_qunatity}}">
                            <td class="text-left pl-3">|-- {{ $sub_head->name }}</td>
                            <td> {{ optional($sub_head->unit)->name }}</td>
                            <td>{{ floatval($values['opening'])}}</td>
                            <td>{{ floatval($quantity['quantity_in'])}}</td>
                            <td>{{ floatval($quantity_out) }}</td>
                            <td>{{ floatval($closing_qunatity)}}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
        </div>
    </section>
</div>
