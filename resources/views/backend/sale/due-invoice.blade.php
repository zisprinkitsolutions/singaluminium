
<option value="">Select...</option>
@foreach ($invoices as $invoice)
<option value="{{$item->id}}">{{$invoice->invoice_no?$invoice->invoice_no:$invoice->proforma_invoice_no}}</option>

@endforeach

