@extends('layouts.app')

@section('title')
{{ $exhibitor->company_name }} Badges
@endsection

@section('content')
<div class="row my-4">
        <div class="col-lg-8 col-md-12 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>{{ $exhibitor->company_name }} Badges</h6>
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    <a href="{{ route('exhibitor.indirect.index',$exhibitor->id) }}" class="badge badge-sm bg-gradient-dark">Indirect</a>
                    <a href="{{ route('exhibitor.badge.printed',$exhibitor->id) }}" class="badge badge-sm bg-gradient-warning">Printed</a>
                    
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th style="width: 40px;">
                        <input type="checkbox" id="selectAllBadges" class="ms-3">
                      </th>
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
                        <input type="checkbox" class="badge-checkbox ms-3" value="{{ $badge->id }}">
                      </td>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">
                              @role('super-admin')
                                <span onclick="editBadge({{ $badge->id }})" style="cursor: pointer; border-bottom: 1px dashed #cb0c9f;">{{ $badge->name }}</span>
                              @else
                                {{ $badge->name }}
                              @endrole
                            </h6>
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
                        <span onclick="viewBadge({{ $badge->id }})" style="cursor: pointer" class="badge badge-sm bg-gradient-info">Go <i class="fa-solid fa-arrow-right"></i></span>
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
        <div class="col-lg-4 col-md-12 mb-md-0 mb-4">
          <div class="card mb-4 serial-number">
            <div class="card-body">
              <label for="">Serial Number</label>
              <input type="text" id="serial_number" class="form-control" placeholder="Enter Serial Number">
            </div>
          </div>
            <div id="badge-wrapper" class="card text-center">
            <div id="badge" class="card-body pb-0">
              <h4 class="badge-name">John Doe</h4>
              <p class="text-sm text-dark">
                Programmer <br> ZITF Company
              </p>
              <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate('Badge sample')) !!} ">
            </div>
            <div class="card-footer">
              <span onclick="" class="btn bg-gradient-primary">Print</span>
            </div>
          </div>
        </div>
</div>


@endsection

@section('scripts')
<script>
    function printBadge(){
            var contents = $("#badge").html();
            var frame1 = $('<iframe />');
            frame1[0].name = "frame1";
            frame1.css({ "position": "absolute", "top": "-1000000px" });
            $("body").append(frame1);
            var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
            frameDoc.document.open();
            //Create a new HTML document.
            frameDoc.document.write('<html><head><title>Print Badge</title>');
            //Append the external CSS file.
            frameDoc.document.write('<link href="{{ asset('css/print.css') }}" rel="stylesheet" type="text/css">');
            frameDoc.document.write('</head><body>');
            //Append the DIV contents.
            frameDoc.document.write(contents);
            frameDoc.document.write('</body></html>');
            frameDoc.document.close();

            var frameWindow = frame1[0].contentWindow;
            frameWindow.onafterprint = function() {
                window.location.reload();
            };

            setTimeout(function () {
                frameWindow.focus();
                frameWindow.print();
            }, 500);
        }

        function viewBadge(id){
            $.ajax({
                url: '/indirect-exhibitor/view-badge/'+id,
                type: 'GET',
                success: function(response) {
                    $('#badge-wrapper').html(response);
                    $('.serial-number').show();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function startPrint(id){
          var serial_number = $('#serial_number').val();
          if(serial_number == ''){
            $('#serial_number').addClass('is-invalid');
            $('#serial_number').after('<span class="text-danger">Please enter serial number</span>');
            return;
          }
          $('#serial_number').removeClass('is-invalid');
          $('#serial_number').after('<span class="text-success">Serial number entered</span>');

          $.ajax({
            url: '/exhibitor/update-serial-number/'+id,
            type: 'GET',
            data: {
              'serial_number': serial_number
            },
            success: function(response) {
              printBadge()
              changePrintStatus(id)
            },
            error: function(xhr) {
            }
          });
        }

        function changePrintStatus(id){
            $.ajax({
                url: '/exhibitor/badge/print/'+id,
                type: 'GET',
                success: function(response) {
                },
                error: function(xhr) {
                }
            });
        }

        function addBadge(){
            $('#badge-wrapper').slideUp('fast','swing', function() {
                $('.add-exhibitor-badge').slideDown('fast');
            });
        }

        @role('super-admin')
        function editBadge(id) {
            $.ajax({
                url: '/exhibitor/badge/data/' + id,
                type: 'GET',
                success: function(badge) {
                    $('#edit_badge_id').val(badge.id);
                    $('#edit_name').val(badge.name);
                    $('#edit_badge_type_id').val(badge.badge_type_id);
                    $('#edit_is_printed').val(badge.is_printed);
                    $('#edit_printed_copies').val(badge.printed_copies);
                    $('#edit_serial_number').val(badge.serial_number);
                    $('#edit_printed_by').val(badge.printed_by);
                    $('#edit_printed_date').val(badge.printed_date);
                    $('#edit_printed_in_bulawayo').prop('checked', badge.printed_in_bulawayo == 1);
                    
                    $('#editBadgeModal').modal('show');
                }
            });
        }

        function updateBadge() {
            var id = $('#edit_badge_id').val();
            var data = {
                _token: '{{ csrf_token() }}',
                name: $('#edit_name').val(),
                badge_type_id: $('#edit_badge_type_id').val(),
                is_printed: $('#edit_is_printed').val(),
                printed_copies: $('#edit_printed_copies').val(),
                serial_number: $('#edit_serial_number').val(),
                printed_by: $('#edit_printed_by').val(),
                printed_date: $('#edit_printed_date').val(),
                printed_in_bulawayo: $('#edit_printed_in_bulawayo').is(':checked') ? 1 : 0
            };

            $.ajax({
                url: '/exhibitor/badge/update/' + id,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                }
            });
        }

        // Bulk selection logic
        $('#selectAllBadges').on('change', function() {
            $('.badge-checkbox').prop('checked', this.checked);
            updateBulkBadgeActionBtn();
        });

        $(document).on('change', '.badge-checkbox', function() {
            updateBulkBadgeActionBtn();
            if ($('.badge-checkbox:checked').length == $('.badge-checkbox').length) {
                $('#selectAllBadges').prop('checked', true);
            } else {
                $('#selectAllBadges').prop('checked', false);
            }
        });

        function updateBulkBadgeActionBtn() {
            var selectedCount = $('.badge-checkbox:checked').length;
            if (selectedCount > 0) {
                $('#bulkBadgeActionsBtn').prop('disabled', false);
                $('#bulkBadgeActionsBtn').text('Bulk Actions (' + selectedCount + ')');
            } else {
                $('#bulkBadgeActionsBtn').prop('disabled', true);
                $('#bulkBadgeActionsBtn').text('Bulk Actions');
            }
        }

        function bulkDeleteBadges() {
            var selectedIds = [];
            $('.badge-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) return;

            if (confirm('Are you sure you want to delete ' + selectedIds.length + ' badges? This action cannot be undone.')) {
                $.ajax({
                    url: "{{ route('exhibitor.badges.bulk.destroy') }}",
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
        @endrole
</script>
@endsection

@section('styles')
    <style>
        .add-exhibitor-badge{
            display:none;
        }

        .serial-number{
            display:none;
        }
    </style>
@endsection
