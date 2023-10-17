@extends('parents.layout')
@php
    $c_year = \App\Helpers\Helpers::instance()->getCurrentAccademicYear();
    $header = \App\Helpers\Helpers::instance()->getHeader();
@endphp
@section('section')
<div class="mx-3">

    <div class="mt-5 py-3 text-center">
        <h2 class="text-primary text-uppercase py-3">{{__('text.transaction_initialized')}}</h3>
        <div class="h3 mt-4 py-5 text-dark bg-light border-top border-bottom border-2 border-primary rounded">{{__('text.pending_transaction_prase')}}</div>
        <div id="loader" class="pt-5 d-flex justify-content-center">
            <div id="outer_wall" style="width: 20rem; height: 20rem; border: 1px solid white; border-radius: 50%; display:flex; flex-direction: column-reverse; justify-content:center; align-content:center; vertical-align:middle;">
                <div id="inner_wall" class="mx-auto text-success h4" style="width: 12rem; height: 12rem; border: 1px solid white; border-radius: 50%; background-color:beige; display:flex; flex-direction:column; justify-content:center; text-align:center;">{{__('text.word_processing')}}</div>
            </div>
        </div>
    </div>
    
</div>
@endsection
@section('script')
<script>

    // Create a spinner animation
    let colors = ['#fff', '#ea8', '#2f7', '#a7d', '#d9e', '#a2f'];
    let percentages = [0, 50, 58, 70, 88, 99];
    let gap = 10;
    setInterval(function(){
        let bg = `conic-gradient(${colors[0]} ${(percentages[0]+gap-10)%100}% ${(percentages[0]+gap)%100}%, ${colors[1]} ${(percentages[1]+gap-10)%100}% ${(percentages[1]+gap)%100}%, ${colors[2]} ${(percentages[2]+gap-10)%100}% ${(percentages[2]+gap)%100}%, ${colors[3]} ${(percentages[3]+gap-10)%100}% ${(percentages[3]+gap)%100}%, ${colors[4]} ${(percentages[4]+gap-10)%100}% ${(percentages[4]+gap)%100}%, ${colors[5]} ${(percentages[5]+gap-10)%100}% ${(percentages[5]+gap)%100}% )`;
        document.getElementById('outer_wall').style.backgroundImage = bg;
        // console.log(bg);
        gap += 3;
    }, 200);

    // check for the transaction status every 3s
    $set_interval = setInterval(() => {
        _url = "{{config('tranzak.tranzak.base').config('tranzak.tranzak.transaction_details').$transaction->requestId}}";
        headers = { 'Access-Control-Allow-Origin': '*',  'Authorization' : "Bearer {{ cache($cache_token_key) }}"};
        $.ajax({
            method: 'get',
            url: _url,
            headers: headers,
            success: function(data){
                // check if status is completed or failed
                response_data = data.data
                console.log(response_data);
                if((response_data.status == "SUCCESSFUL") || (response_data.status == "CANCELLED") || (response_data.status == "FAILED") || (response_data.status == "REVERSED")){
                    url = "{{route('student.tranzak.payment.complete', ['type'=>$item_type])}}";
                    query_string = `?qstring=${JSON.stringify(response_data)}`;
                    action = url+query_string;
                    
                    window.location = action;
                }
            }
        });
    }, 5000);
</script>
@endsection