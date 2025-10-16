@extends('layouts.app')

@section('title')
{{ $event->name.' '.$event->year }} Report
@endsection

@section('content')
<h4>Visitor Report</h4>
<hr>
<div class="row">
  <div class="col-lg-3 col-md-3 col-12 mt-4 mt-md-0">
              <div class="card">
                <span class="mask bg-gradient-primary opacity-10 border-radius-lg"></span>
                <div class="card-body p-3 position-relative">
                  <div class="row">
                    <div class="col-8 text-start">
                      <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                        <i class="fa-solid fa-person-walking-luggage"></i>
                      </div>
                      <h5 class="text-white font-weight-bolder mb-0 mt-3">
                        {{ $total_visitors }}
                      </h5>
                    </div>
                    <div class="col-4">
                      <div class="dropstart text-end mb-6">
                      </div>
                      <p class="text-white text-sm text-end font-weight-bolder mt-auto mb-0">Total Visitors</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
      <div class="row my-4">
        <div class="col-md-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>{{ count($countries) }} Countries</h6>
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">

                </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($countries as $country)
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{{ $country }}</h6>
                        </div>
                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        @foreach($otherBadges as $badgetype)
  <div class="col-lg-3 col-md-3 col-12 mt-4 mt-md-0">
              <div class="card">
                <span class="mask bg-gradient-primary opacity-10 border-radius-lg"></span>
                <div class="card-body p-3 position-relative">
                  <div class="row">
                    <div class="col-8 text-start">
                      <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                        <i class="fa-solid fa-person-walking-luggage"></i>
                      </div>
                      <h5 class="text-white font-weight-bolder mb-0 mt-3">
                        {{ $badgetype->badges->count() }}
                      </h5>
                    </div>
                    <div class="col-4">
                      <div class="dropstart text-end mb-6">
                      </div>
                      <p class="text-white text-sm text-end font-weight-bolder mt-auto mb-0">{{$badgetype->name}}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
        </div>
@endsection

@section('scripts')
          <script>
            $(document).ready(function() {
            setInterval(function() {
                location.reload();
                }, 15000); // 15000 milliseconds = 15 seconds
            });
          </script>
@endsection
