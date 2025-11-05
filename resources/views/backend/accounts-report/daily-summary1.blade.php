@extends('layouts.backend.app')
@section('content')
    @include('layouts.backend.partial.style')
    <style>
        .tabPadding {
            padding: 5px;
        }

        .padding-right {
            padding-right: 10px;
        }

        .print-header-footer {
            display: none;
        }

        td {
            text-align: center !important;
        }

        th {
            background: #34465b !important;
            text-align: center !important;
            color: #fff !important;
        }

        @media(min-width:1300px) {
            .padding-right {
                padding-right: 0px !important;
            }
        }

        @media print {
            @page {
                max-width: 10px;

            }

            .print-header-footer {
                display: block !important;
            }

            body {
                margin: 0px;
                padding: 0px !important;
            }

            .bg-secondary {
                background-color: #34465b !important;
                border-radius: 0px !important;
                color: white !important;
                padding: 0px !important;
            }

            table {
                padding-right: 10px;
                padding-left: 10px
            }
        }
    </style>
    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">

                <div class="tab-content bg-white">
                    <div class="tab-pane active">
                        <div class="content-body pt-1">
                            <section id="widgets-Statistics ">

                                <div class="cardStyleChange">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div>
                                                {{-- <a href="#" class="btn btn_create mPrint formButton" title="Print" onclick="window.print()">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{asset('assets/backend/app-assets/icon/print-icon.png')}}" width="25">
                                                        </div>
                                                        <div><span>Print</span></div>
                                                    </div>
                                                </a> --}}
                                            </div>
                                        </div>
                                        <div class="row ml-1">
                                            <div class="col-md-3 d-none">
                                                <form action="" method="GET" class="d-flex row">
                                                    <div class="row form-group col-md-8" style="padding-left:7px;">
                                                        <input type="text"
                                                            class="inputFieldHeight form-control datepicker" name="date"
                                                            placeholder="Select Date" required autocomplete="off">
                                                    </div>
                                                    <div class="col-md-4 mr-0">
                                                        <button type="submit" class="btn mSearchingBotton mb-2 formButton"
                                                            title="Search">
                                                            <div class="d-flex">
                                                                <div class="formSaveIcon">
                                                                    <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                        width="25">
                                                                </div>
                                                                <div><span>Search</span></div>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="col-md-6 d-none">
                                                <form action="" method="GET" class="d-flex row">
                                                    <div class="row form-group col-md-4 mr-0">
                                                        <input type="text"
                                                            class="inputFieldHeight form-control datepicker" name="from"
                                                            placeholder="From Date" required autocomplete="off">
                                                    </div>
                                                    <div class="row form-group col-md-4 mr-0">
                                                        <input type="text"
                                                            class="inputFieldHeight form-control datepicker" name="to"
                                                            placeholder="To Date" required autocomplete="off">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="submit" class="btn mSearchingBotton mb-2 formButton"
                                                            title="Search">
                                                            <div class="d-flex">
                                                                <div class="formSaveIcon">
                                                                    <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                        width="25">
                                                                </div>
                                                                <div><span>Search</span></div>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                            </div>
                                            <div class="col-md-1 text-right col-padding-right">
                                                <a href="#"onclick="var e=document.getElementById('print-table').innerHTML,t=document.body.innerHTML;document.body.innerHTML=e,window.print(),document.body.innerHTML=t;"
                                                    class="btn btn-icon btn-secondary"><i class="bx bx-printer"></i>
                                                    Print</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="print-table">

                                    <span class="print-header-footer ">
                                        @include('layouts.backend.partial.modal-header-info')
                                    </span>
                                    <div class="card-body pt-0 pb-0">
                                        <h2 class="text-center col-6">Daily Summery</h2>
                                        @php
                                            $cash_other_receive = $other_receipt->where('pay_mode', 'Cash')->sum('amount');
                                            $bank_other_receive = $other_receipt->where('pay_mode', 'Bank')->sum('amount');
                                            $cash_other_cost = $fund_allocation->where('account_id_from', 1)->sum('transaction_cost');
                                            $bank_other_cost = $fund_allocation->where('account_id_from', 7)->sum('transaction_cost');
                                            $petty_cash_other_cost = $fund_allocation->where('account_id_from', 6)->sum('transaction_cost');

                                            $transfer_to_cash_from_bank = $fund_allocation->where('account_id_to', 1)->where('account_id_from', 7)->sum('amount');
                                            $transfer_to_cash_from_petty_cash = $fund_allocation->where('account_id_to', 1)->where('account_id_from', 6)->sum('amount');
                                            $transfer_from_cash_to_bank = $fund_allocation->where('account_id_from', 1)->where('account_id_to', 7)->sum('amount');
                                            $transfer_from_cash_to_petty_cash = $fund_allocation->where('account_id_from', 1)->where('account_id_to', 6)->sum('amount');

                                            $transfer_to_bank_from_petty_cash = $fund_allocation->where('account_id_to', 7)->where('account_id_from', 6)->sum('amount');
                                            $transfer_from_bank_to_petty_cash = $fund_allocation->where('account_id_from', 7)->where('account_id_to', 6)->sum('amount');
                                            $transfer_to_bank_from_visa_card = $fund_allocation->where('account_id_from', 6)->where('account_id_to', 7)->sum('amount');
                                            $transfer_from_bank_to_visa_card = $fund_allocation->where('account_id_from', 7)->where('account_id_to', 3)->sum('amount');

                                            $today_payment_visa_card = $today_payments->where('pay_mode', 'VISA Card')->where('type', 'due')->sum('total_amount');
                                            $previous_payment_visa_card = $previous_payments->where('pay_mode', 'VISA Card')->where('type', 'due')->sum('total_amount');
                                            $today_payment_petty_cash = $today_payments->where('pay_mode', 'Petty Cash')->where('type', 'due')->sum('total_amount');
                                            $previous_payment_petty_cash = $previous_payments->where('pay_mode', 'Petty Cash')->where('type', 'due')->sum('total_amount');

                                            $opening_balance_receipt_visa_card = $previous_fund_allocation->where('account_id_to', 3)->sum('amount');
                                            $opening_balance_payment_visa_card = $previous_fund_allocation->where('account_id_from', 3)->sum('amount');
                                            $opening_balance_receipt_petty_cash = $previous_fund_allocation->where('account_id_to', 6)->sum('amount');
                                            $opening_balance_payment_petty_cash = $previous_fund_allocation->where('account_id_from', 6)->sum('amount');

                                        @endphp
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-sm table-hover ">
                                                    <tr>
                                                        <th colspan="5" class="text-left bg-secondary text-white pl-1">Today's Cash Summery</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left pl-1">Opening Balance</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($opening_balance_cash = $opening_balance_receive_cash-$opening_balance_payment_cash,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="cash-today-sale-received">
                                                        <td class="text-left pl-1">Today's Cash Sales Received</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($today_cash_sale_receipts,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="cash-today-payment-expense">
                                                        <td class="text-left pl-1">Today's Cash Payment/Expense</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($today_cash_bill_payments,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="cash-previous-receivable-receive">
                                                        <td class="text-left pl-1">Previous Accounts Receivable Received</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($previous_cash_sale_receipts,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="cash-previous-payable-payment">
                                                        <td class="text-left pl-1">Previous Account Payable Payment</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($previous_cash_bill_payments,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="cash-advance-receive">
                                                        <td class="text-left pl-1">Advance Received</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($today_advance_cash_received,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="cash-advance-payment">
                                                        <td class="text-left pl-1">Advance Payment</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($today_advance_cash_payment,2)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left pl-1">Other Cash Received</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($cash_other_receive,2)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left pl-1">Other Cash Payment</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($cash_other_cost,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/7/1">
                                                        <td class="text-left pl-1">Transferred to Cash from Bank</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_to_cash_from_bank,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/6/1">
                                                        <td class="text-left pl-1">Transferred to Cash from Petty Cash</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_to_cash_from_petty_cash,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/1/7">
                                                        <td class="text-left pl-1">Transferred from Cash to Bank</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_from_cash_to_bank,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/1/6">
                                                        <td class="text-left pl-1">Transferred from Cash to Petty Cash</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_from_cash_to_petty_cash,2)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right pr-1"><strong>Today's Cash Total</strong></td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">
                                                            {{number_format(($cash_other_receive+$opening_balance_cash+$today_cash_sale_receipts+$previous_cash_sale_receipts+$today_advance_cash_received+$transfer_to_cash_from_bank+$transfer_to_cash_from_petty_cash)
                                                                - ($cash_other_cost+$today_cash_bill_payments+$previous_cash_bill_payments+$today_advance_cash_payment+$transfer_from_cash_to_bank+$transfer_from_cash_to_petty_cash),2)}}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-sm table-hover ">
                                                    <tr>
                                                        <th colspan="5" class="text-left bg-secondary text-white pl-1">Today's Bank Summery</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left pl-1">Opening Balance</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($opening_balance_bank = $opening_balance_receive_bank-$opening_balance_payment_bank,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="bank-today-sale-received">
                                                        <td class="text-left pl-1">Today's Bank Sales Received</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($today_bank_sale_receipts,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="bank-today-payment-expense">
                                                        <td class="text-left pl-1">Today's Bank Payment/Expense</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($today_bank_bill_payments,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="bank-previous-receivable-receive">
                                                        <td class="text-left pl-1">Previous Account Receivable Received</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($previous_bank_sale_receipts,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="bank-previous-payable-payment">
                                                        <td class="text-left pl-1">Previous Account Payable Payment</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($previous_bank_bill_payments,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="bank-advance-receive">
                                                        <td class="text-left pl-1">Advance Received</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($today_advance_bank_received,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="bank-advance-payment">
                                                        <td class="text-left pl-1">Advance Payment</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($today_advance_bank_payment,2)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left pl-1">Other Bank Received</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($bank_other_receive,2)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left pl-1">Other Bank Payment</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($bank_other_cost,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/1/7">
                                                        <td class="text-left pl-1">Transferred to Bank from Cash</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_from_cash_to_bank,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/6/7">
                                                        <td class="text-left pl-1">Transferred to Bank from Petty Cash</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_to_bank_from_petty_cash,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/3/7">
                                                        <td class="text-left pl-1">Transferred to Bank from VISA Card</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_to_bank_from_visa_card,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/7/1">
                                                        <td class="text-left pl-1">Transferred from Bank to Cash</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_to_cash_from_bank,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/7/6">
                                                        <td class="text-left pl-1">Transferred from Bank to Petty Cash</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_from_bank_to_petty_cash,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/7/3">
                                                        <td class="text-left pl-1">Transferred from Bank to VISA Card</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_from_bank_to_visa_card,2)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right pr-1"><strong>Today's Bank Total</strong></td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">
                                                            {{number_format(
                                                                ($bank_other_receive+$opening_balance_bank+$today_bank_sale_receipts+$previous_bank_sale_receipts+$today_advance_bank_received+$transfer_from_cash_to_bank+$transfer_to_bank_from_petty_cash+$transfer_to_bank_from_visa_card)
                                                                -
                                                                ($bank_other_cost+$today_bank_bill_payments+$previous_bank_bill_payments+$today_advance_bank_payment+$transfer_to_cash_from_bank+$transfer_from_bank_to_petty_cash+$transfer_from_bank_to_visa_card)
                                                            ,2)}}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-sm table-hover ">
                                                    <tr>
                                                        <th colspan="5" class="text-left bg-secondary text-white pl-1">Today's VISA Card Summery</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left pl-1">Opening Balance</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($opening_balance_visa_card = $opening_balance_receipt_visa_card-$opening_balance_payment_visa_card,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="today-payment-expense/VISA Card">
                                                        <td class="text-left pl-1">Today's VISA Card Payment/Expense</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($today_payment_visa_card,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="previous-payment-expense/VISA Card">
                                                        <td class="text-left pl-1">Previouse VISA Card Payment/Expense</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($previous_payment_visa_card,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/3/7">
                                                        <td class="text-left pl-1">Transferred from VISA Card to Bank </td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_to_bank_from_visa_card,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/7/3">
                                                        <td class="text-left pl-1">Transferred to VISA Card from Bank</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_from_bank_to_visa_card,2)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left pl-1">Other VISA Card Payment</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">0.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right pr-1"><strong>Today's VISA Card Total</strong></td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">
                                                            {{number_format(($opening_balance_visa_card+$transfer_from_bank_to_visa_card)
                                                                - ($today_payment_visa_card+$previous_payment_visa_card+$transfer_to_bank_from_visa_card),2)}}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-sm table-hover ">
                                                    <tr>
                                                        <th colspan="5" class="text-left bg-secondary text-white pl-1">Today's Petty Cash Summery</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left pl-1">Opening Balance</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($opening_balance_petty_cash = $opening_balance_receipt_petty_cash-$opening_balance_payment_petty_cash,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="today-payment-expense/Petty Cash">
                                                        <td class="text-left pl-1">Today's Petty Cash Payment/Expense</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($today_payment_petty_cash,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="previous-payment-expense/Petty Cash">
                                                        <td class="text-left pl-1">Previouse Petty Cash Payment/Expense</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($previous_payment_petty_cash,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/6/1">
                                                        <td class="text-left pl-1">Transferred from Petty Cash to Cash</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_to_cash_from_petty_cash,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/6/7">
                                                        <td class="text-left pl-1">Transferred from Petty Cash to Bank </td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_to_bank_from_petty_cash,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/7/6">
                                                        <td class="text-left pl-1">Transferred to Petty Cash from Bank</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_from_bank_to_petty_cash,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="fund-transfer/1/6">
                                                        <td class="text-left pl-1">Transferred to Petty Cash from Cash</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($transfer_from_cash_to_petty_cash,2)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left pl-1">Other Petty Cash Payment</td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">{{number_format($petty_cash_other_cost,2)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right pr-1"><strong>Today's Petty Cash Total</strong></td>
                                                        <td style="width: 15% !important;" class="text-right pr-1">
                                                            {{number_format(($opening_balance_petty_cash+$transfer_from_bank_to_petty_cash+$transfer_from_cash_to_petty_cash)
                                                                - ($petty_cash_other_cost+$today_payment_petty_cash+$previous_payment_petty_cash+$transfer_to_bank_from_petty_cash+$transfer_to_cash_from_petty_cash),2)}}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-sm table-hover ">
                                                    <tr>
                                                        <th colspan="5" class="text-left bg-secondary text-white pl-1">Till Date Credit (Receivable) Details</th>
                                                    </tr>
                                                    <tr class="daily-summery" data="previous-account-receivable">
                                                        <td class="text-left pl-1"> Previouse Accounts Receivable </td>
                                                        <td class="text-right pr-1" style="width: 15% !important;">{{number_format($previous_account_receivable,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="today-account-receivable">
                                                        <td class="text-left pl-1"> Today's Accounts Receivable </td>
                                                        <td class="text-right pr-1" style="width: 15% !important;">{{number_format($today_account_receivable,2)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right pr-1"> <strong>Till Date Receivable Total</strong> </td>
                                                        <td class="text-right pr-1" style="width: 15% !important;">{{number_format($previous_account_receivable+$today_account_receivable,2)}}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-sm table-hover ">
                                                    <tr>
                                                        <th colspan="5" class="text-left bg-secondary text-white pl-1">Till Date Credit (Payable) Details</th>
                                                    </tr>
                                                    <tr class="daily-summery" data="previous-account-payable">
                                                        <td class="text-left pl-1"> Previouse Accounts Payable </td>
                                                        <td class="text-right pr-1" style="width: 15% !important;">{{number_format($previous_account_payable,2)}}</td>
                                                    </tr>
                                                    <tr class="daily-summery" data="today-account-payable">
                                                        <td class="text-left pl-1"> Today's Accounts Payable </td>
                                                        <td class="text-right pr-1" style="width: 15% !important;">{{number_format($today_account_payable,2)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right pr-1"> <strong>Till Date Receivable Total</strong> </td>
                                                        <td class="text-right pr-1" style="width: 15% !important;">{{number_format($previous_account_payable+$today_account_payable,2)}}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="divFooter mb-1 ml-1 print-header-footer ">
                                        Business Software Solutions by
                                        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="" width="70"></span>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal2" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="voucherPreviewShow2">

            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    window.onafterprint = function() {
        // Initialize your date picker code here
        location.reload();
            $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy"
        });
    };
    $(document).on("click", ".daily-summery", function(e) {
        e.preventDefault();
        var url= $(this).attr('data');
        console.log(url);
		$.ajax({
			url: url,
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}'
			},
			success: function(response){
                document.getElementById("voucherPreviewShow").innerHTML = response;
                $('#voucherPreviewModal').modal('show')
			}
		});
	});
    $(document).on("click", ".receipt_exp_view", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
		$.ajax({
			url: "{{URL('receipt-list-modal')}}",
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}',
                id:id,
			},
			success: function(response){
                document.getElementById("voucherPreviewShow2").innerHTML = response;
                $('#voucherPreviewModal2').modal('show')
			}
		});
	});
    $(document).on("click", ".sale_view", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
        $.ajax({
            url: "{{route('sale-modal')}}",
            type: "post",
            cache: false,
            data:{
                _token:'{{ csrf_token() }}',
                id:id,
            },
            success: function(response){
                document.getElementById("voucherPreviewShow2").innerHTML = response;
                $('#voucherPreviewModal2').modal('show')
            }
        });
    });
    $(document).on("click", ".purch_exp_view", function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        $.ajax({
            url: "{{ route('purch-exp-modal') }}",
            type: "post",
            cache: false,
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
            },
            success: function(response) {
                document.getElementById("voucherPreviewShow2").innerHTML = response;
                $('#voucherPreviewModal2').modal('show')
            }
        });
    });
    $(document).on("click", ".payment-voucher", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
		$.ajax({
			url: "{{URL('payment-modal')}}",
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}',
                id:id,
			},
			success: function(response){
                document.getElementById("voucherPreviewShow2").innerHTML = response;
                $('#voucherPreviewModal2').modal('show');
			}
		});
	});

    function printFunction($id) {
        var sale_id = $id;
        var iframe = $('<iframe>');
            iframe.css({
                position: 'absolute',
                width: '0',
                height: '0',
            });
            $('body').append(iframe);

        fetch('/get-invoice-print/' + sale_id)
        .then(response => response.text())
        .then(invoiceContent => {
            var doc = iframe[0].contentWindow.document;
            doc.open();
            doc.write(invoiceContent);
            doc.close();

            // Wait for all images to load
            var images = doc.images;
            var totalImages = images.length;
            var imagesLoaded = 0;

            if (totalImages === 0) {
                printIframe();
            } else {
                for (var i = 0; i < totalImages; i++) {
                    images[i].onload = imageLoadHandler;
                    images[i].onerror = imageLoadHandler; // In case an image fails to load
                }
            }

            function imageLoadHandler() {
                imagesLoaded++;
                if (imagesLoaded === totalImages) {
                    printIframe();
                }
            }

            function printIframe() {
                iframe[0].contentWindow.focus();
                iframe[0].contentWindow.print();
            }
        })
        .catch(error => {
            console.error('Error fetching invoice content:', error);
        });
    }
    $(document).on("click", ".allocation-show", function(e) {
        e.preventDefault();
        var url= $(this).attr('href');
        $.ajax({
            url:url,
            type: "get",
            cache: false,

            success: function(response){
                document.getElementById("voucherPreviewShow2").innerHTML = response;
                $('#voucherPreviewModal2').modal('show');
            }
        });
    });
</script>
@endpush
