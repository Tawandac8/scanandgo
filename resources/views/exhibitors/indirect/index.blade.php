@extends('layouts.app')

@section('title')
{{ $exhibitor->company_name }} Indirect Exhibitors
@endsection

@section('content')
<div class="row my-4">
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>{{ $exhibitor->company_name }} Indirect Exhibitors</h6>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Company Name</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody class="exhibitor-table">
                    @if(isset($indirect_exhibitors))
                    @foreach($indirect_exhibitors as $exhibitor)
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $exhibitor->company_name }}</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <a href="{{ route('exhibitor.indirect.badges.index', $exhibitor->id) }}" class="badge badge-sm bg-gradient-info">View Badges</a>
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
</script>
@endsection