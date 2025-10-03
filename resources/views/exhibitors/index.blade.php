@extends('layouts.app')

@section('title')
{{ $event->name.' '.$event->year }} Exhibitors
@endsection

@section('content')
<div class="row my-4">
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
          <div class="card mb-3">
            <div class="card-body">
              <input class="form-control border-info" type="text" name="q" placeholder="Search Exhibitor" autofocus>
            </div>
          </div>
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>{{ $event->name.' '.$event->year }} Exhibitors</h6>
                </div>
                @hasanyrole('admin|super-admin')
                <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    <span onclick="" style="cursor: pointer" class="badge badge-sm bg-gradient-dark">Add Exhibitor</span>
                    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fa fa-ellipsis-v text-dark"></i>
                    </a>
                    <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Printed Badges</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Synch Badges</a></li>
                    </ul>
                  </div>
                </div>
                @endhasanyrole
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      @role('super-admin')
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Exhibitor</th>
                      @endrole
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Company Name</th>
                      @role('super-admin')
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">exhibitor Code</th>
                      @endrole
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Badges</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Remaining Badges</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody class="exhibitor-table">
                    @if(isset($exhibitors))
                    @foreach($exhibitors as $exhibitor)
                    <tr>
                      <td>
                        <span class="text-xs font-weight-bold"> {{ $exhibitor->created_at }} </span>
                      </td>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $exhibitor->company_name }}</h6>
                          </div>
                        </div>
                      </td>
                      @role('super-admin')
                      <td>
                        <span class="text-xs font-weight-bold"> {{ $exhibitor->code }} </span>
                      </td>
                      @endrole
                      <td>
                        <span class="text-xs font-weight-bold">  </span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold">  </span>
                      </td>
                      <td>
                        <a href="{{ route('exhibitor.badges.index',$exhibitor->id) }}" class="badge badge-sm bg-gradient-info">Go <i class="fa-solid fa-arrow-right"></i></a>
                        @role('super-admin')
                        <a href="{{ route('exhibitor.destroy.duplicate',$exhibitor->id) }}" class="badge badge-sm bg-gradient-danger">delete <i class="fa-solid fa-arrow-right"></i></a>
                        @endrole
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
                    {{ $exhibitors->links('components.pagination') }}
                  </div>
                </div>
            </div>
          </div>
        </div>
</div>
@endsection

@section('scripts')
<script>
  $('input[name="q"]').on('keyup', function(){
    var query = $(this).val();
    $.ajax({
      type: "get",
      url: "/search/exhibitor",
      data: {q:query},
      success: function (response) {
        $('.exhibitor-table').html(response);
      }
    });
  })
</script>
@endsection