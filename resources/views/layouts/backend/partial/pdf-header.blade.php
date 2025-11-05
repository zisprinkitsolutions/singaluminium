<style>
    @media{
        .border1{
            color: red !important;
        }
        .border2{
            color: green !important;
        }
    }
</style>

<section class="invoice-view-wrapper">
    <div style="width:100%;">
        @if ($image)
            <img src="{{ $image}}" alt="" style="height: 130px !important;width:100%;">
        @else
            <h2> {{$company_name}} </h2>
        @endif

    </div>

    <p style="border-top: 3px solid #ff0000c0 !important" style="padding-bootm: 0px !important; margin-bootm:0px !important;"></p>
    <p style="border-top: 3px solid #008000b6 !important" ></p>
</section>
