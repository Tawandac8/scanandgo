@extends('layouts.app')

@section('title')
All Events
@endsection

@section('content')
    <div class="row my-4">
        <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>Events</h6>
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                  
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Year</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Exhibitors</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(isset($events))
                    @foreach($events as $event)
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $event->name }}</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold"> {{ $event->year }} </span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold"> 0 </span>
                      </td>
                      <td>
                        <a href="{{ route('badges.index',$event->id) }}" class="badge badge-sm bg-gradient-info">Go <i class="fa-solid fa-arrow-right"></i></a>
                      </td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection
