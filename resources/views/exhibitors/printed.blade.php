@extends('layouts.app')

@section('title')
{{ $event->name }} Printed Badges
@endsection

@section('content')
<div class="row my-4">
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>{{ $event->name }} Badges</h6>
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    <span onclick="addBadge()" style="cursor: pointer" class="badge badge-sm bg-gradient-dark">Add Badge</span>

                  </div>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Badge Type</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Printed</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Printed Copies</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(isset($badges))
                    @foreach($badges as $badge)
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $badge->name }}</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold"> {{ $badge->badge_type->name }} </span>
                      </td>
                      <td>
                        @if($badge->is_printed)
                        <span class="badge badge-sm bg-gradient-success">Yes</span>
                        @else
                        <span class="badge badge-sm bg-gradient-danger">No</span>
                        @endif
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold"> {{ $badge->printed_copies }} </span>
                      </td>
                      <td>
                        
                      </td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer">
              <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    {{ $badges->links('components.pagination') }}
                  </div>
                </div>
            </div>
          </div>
        </div>
</div>
@endsection