@extends('layouts.app')

@section('title')
{{ $event->name.' '.$event->year }} Report
@endsection

@section('content')
<div class="row">
    @foreach($badges as $dates)
    @foreach($dates as $key => $value)
            <div class="col-lg-3 col-md-3 col-12 mt-4 mt-md-0">
              <div class="card">
                <span class="mask bg-dark opacity-10 border-radius-lg"></span>
                <div class="card-body p-3 position-relative">
                  <div class="row">
                    <div class="col-8 text-start">
                      <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                        <i class="fa-solid fa-person-walking-luggage"></i>
                      </div>
                      <h5 class="text-white font-weight-bolder mb-0 mt-3">
                        {{ $value }}
                      </h5>
                    </div>
                    <div class="col-4">
                      <div class="dropstart text-end mb-6">
                      </div>
                      <p class="text-white text-sm text-end font-weight-bolder mt-auto mb-0">{{ \Carbon\Carbon::parse($key)->format('M d') }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
    @endforeach
          </div>
@endsection