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
                    
                    @role('super-admin')
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm bg-gradient-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="bulkActionsBtn" disabled>
                          Bulk Actions
                        </button>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="javascript:;" onclick="bulkDelete()">Delete Selected</a></li>
                        </ul>
                    </div>
                    @endrole

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
                      <th style="width: 40px;">
                        <input type="checkbox" id="selectAll" class="ms-3">
                      </th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Company Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Badges</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody class="exhibitor-table">
                    @if(isset($exhibitors))
                    @foreach($exhibitors as $exhibitor)
                    <tr>
                      <td>
                        <input type="checkbox" class="exhibitor-checkbox ms-3" value="{{ $exhibitor->id }}">
                      </td>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $exhibitor->company_name }}</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold"> {{ $exhibitor->exhibitor_badges->count() }} </span>
                      </td>
                      <td>
                        <a href="{{ route('exhibitor.badge.export',$exhibitor->id) }}" class="badge badge-sm bg-gradient-success">Export Printed</a>
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
      data: {q:query, 'event_id':{{ $event->id }}},
      success: function (response) {
        $('.exhibitor-table').html(response);
        updateBulkActionBtn();
      }
    });
  })

  // Select all functionality
  $('#selectAll').on('change', function() {
    $('.exhibitor-checkbox').prop('checked', this.checked);
    updateBulkActionBtn();
  });

  // Individual checkbox change
  $(document).on('change', '.exhibitor-checkbox', function() {
    updateBulkActionBtn();
    if ($('.exhibitor-checkbox:checked').length == $('.exhibitor-checkbox').length) {
      $('#selectAll').prop('checked', true);
    } else {
      $('#selectAll').prop('checked', false);
    }
  });

  function updateBulkActionBtn() {
    var selectedCount = $('.exhibitor-checkbox:checked').length;
    if (selectedCount > 0) {
      $('#bulkActionsBtn').prop('disabled', false);
      $('#bulkActionsBtn').text('Bulk Actions (' + selectedCount + ')');
    } else {
      $('#bulkActionsBtn').prop('disabled', true);
      $('#bulkActionsBtn').text('Bulk Actions');
    }
  }

  function bulkDelete() {
    var selectedIds = [];
    $('.exhibitor-checkbox:checked').each(function() {
      selectedIds.push($(this).val());
    });

    if (selectedIds.length === 0) return;

    if (confirm('Are you sure you want to delete ' + selectedIds.length + ' exhibitors? This action cannot be undone.')) {
      $.ajax({
        url: "{{ route('exhibitors.bulk.destroy') }}",
        type: 'POST',
        data: {
          _token: "{{ csrf_token() }}",
          ids: selectedIds
        },
        success: function(response) {
          if (response.success) {
            alert(response.message);
            location.reload();
          } else {
            alert(response.message || 'Error occurred during bulk deletion');
          }
        },
        error: function(xhr) {
          var msg = 'An error occurred';
          if (xhr.status === 403) msg = 'Unauthorized action';
          alert(msg);
          console.error(xhr.responseText);
        }
      });
    }
  }
</script>
@endsection