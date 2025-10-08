@extends('layouts.app')

@section('title')
{{ $event->name }} Badges
@endsection

@section('content')
<div class="row my-4">
        <div class="col-lg-8 col-md-12 mb-md-0 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                  <input class="form-control border-info" type="text" name="q" placeholder="Search Delegate" autofocus>
                </div>
            </div>
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>{{ $event->name }} Badges</h6>
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    <a href="{{ route('delegates.export',$event->id) }}" class="badge badge-sm bg-gradient-dark">Export printed</a>

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
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Company Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Badge Type</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Printed</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Printed Copies</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody class="delegates-table">
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
                        <span class="text-xs font-weight-bold"> {{ $badge->company_name }} </span>
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
                    {{ $badges->links('components.pagination') }}
                  </div>
                </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-12 mb-md-0 mb-4">
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
            frameDoc.document.write('<html><head><title>DIV Contents</title>');
            //Append the external CSS file.
            frameDoc.document.write('<link href="{{ asset('css/print.css') }}" rel="stylesheet" type="text/css">');
            //Append the DIV contents.
            frameDoc.document.write(contents);
            frameDoc.document.write('</body></html>');
            frameDoc.document.close();
            setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                frame1.remove();
            }, 500);
        }

        function viewBadge(id){
            $.ajax({
                url: '/other/badge/'+id,
                type: 'GET',
                success: function(response) {
                    $('#badge-wrapper').html(response);
                    $('.add-exhibitor-badge').slideUp('fast','swing', function() {
                        $('#badge-wrapper').slideDown('fast');
                    });

                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function startPrint(id){
            printBadge()
            changePrintStatus(id)

            window.onafterprint = function() {
        // Reload the page after the print dialog is closed
        window.location.reload();
        // The 'true' parameter forces a reload from the server, ignoring the cache.
    };

        }

        function changePrintStatus(id){
            $.ajax({
                url: '/other/badge/print/'+id,
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

        function storeBadge(id){
            var name = $('#name').val();
            var company = $('#company').val();
            var badge_type = $('#badge_type').val();
            var _token = $('meta[name="csrf-token"]').attr('content');
            if(badge_type == null || badge_type == ""){
                alert('Please select badge type');
                $('#badge_type').css('border','1px solid red');
                return;
            }
            $.ajax({
                url: '/badge/add',
                type: 'POST',
                data: {
                    name: name,
                    company: company,
                    badge_type: badge_type,
                    event: id,
                    _token: _token
                },
                success: function(response) {
                    $('#badge-wrapper').html(response);
                    $('.add-exhibitor-badge').slideUp('fast','swing', function() {
                        $('#badge-wrapper').slideDown('fast');
                    });
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        //search delegates
        $('input[name="q"]').on('keyup', function(){
            var query = $(this).val();
            var event_id = {{ $event->id }};
            var _token = $('meta[name="csrf-token"]').attr('content');
            
            $.ajax({
                url: '/delegates/search',
                type: 'POST',
                data: {
                    query: query,
                    event_id: event_id,
                    _token: _token
                },
                success: function(response) {
                    //update table body
                    console.log(response);
                    $('.delegates-table').html(response);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
</script>
@endsection
