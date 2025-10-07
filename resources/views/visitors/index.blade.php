@extends('layouts.app')

@section('title')
{{ $event->name.' '.$event->year }} Visitors
@endsection

@section('content')
    <div class="row my-4">
        <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
          <div class="card mb-3">
            <div class="card-body">
              <div class="row">
                <div class="col-md-8">
                  <input onchange="searchVisitor(this.value)" class="form-control border-info search-by-qr" type="text" name="q" placeholder="Search/Scan Visitor" autofocus>
                  <input  class="form-control border-info search-by-name" type="text" name="name" placeholder="Enter Visitor Name">
                </div>
                <div class="col-md-4">
                  <select onchange="searchBy(this.value)" class="form-select" name="search_by" id="">
                    <option value="qr" selected>Scan Code/Search Code</option>
                    <option value="name">Search By Name</option>
                  </select>
                </div>
              </div>
              
            </div>
          </div>
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>Business Visitors</h6>
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    <span onclick="addNewVisitor()" style="cursor: pointer" class="badge badge-sm bg-gradient-dark">Add Visitor</span>
                    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fa fa-ellipsis-v text-dark"></i>
                    </a>
                    <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Printed Badges</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Synch Badges</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Full Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Institution</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Position/Level</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Printed</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody class="visitors-table">
                    @if(isset($visitors))
                    @foreach($visitors as $visitor)
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $visitor->title.' '.$visitor->first_name.' '.$visitor->last_name }}</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold"> {{ $visitor->company_name }} </span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold"> {{ $visitor->position }} </span>
                      </td>
                      <td class="text-center">
                        @if(!$visitor->is_printed)
                        <span class="text-xs font-weight-bold text-danger"> <i class="fa-solid fa-xmark"></i> </span>
                        @else
                        <span class="text-xs font-weight-bold text-success"> <i class="fa-solid fa-check"></i> </span>
                        @endif
                      </td>
                      <td>
                        <span style="cursor: pointer" onclick="viewVisitor({{ $visitor->id }})" class="badge badge-sm bg-gradient-info">Go <i class="fa-solid fa-arrow-right"></i></span>
                      </td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer">
              <div class="col-lg-12 col-12 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    {{ $visitors->links('components.pagination') }}
                  </div>
                </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
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
          <div id="add-visitor-wrapper" class="card">
            <div class="card-body pb-0">
              <h4 class="badge-name">Add Visitor</h4>
              <label for="">Title</label>
              <div class="mb-3">
                <select name="title" class="form-select" id="">
                  <option value="" selected disabled>Select Title</option>
                  <option value="Amb">Amb</option>
                  <option value="Capt">Capt</option>
                  <option value="Col">Col</option>
                  <option value="Dr">Dr</option>
                  <option value="Eng">Eng</option>
                  <option value="Gen">Gen</option>
                  <option value="Hon">Hon</option>
                  <option value="H.E">H.E</option>
                  <option value="Lt">Lt</option>
                  <option value="Major">Major</option>
                  <option value="Mr">Mr</option>
                  <option value="Mrs">Mrs</option>
                  <option value="Miss">Miss</option>
                  <option value="Ms">Ms</option>
                  <option value="Pres">Pres</option>
                  <option value="Prof">Prof</option>
                  <option value="Rev">Rev</option>
                  <option value="">None of the above</option>
                </select>
              </div>
              <label class="required" for="">First Name</label>
              <div class="mb-3">
                <input name="first_name" type="text" class="form-control" required>
              </div>
              <label class="required" for="">Last Name</label>
              <div class="mb-3">
                <input name="last_name" type="text" class="form-control" required>
              </div>
              <label for="" class="required">Position/Level</label>
              <div class="mb-3">
                <select name="position" id="" class="form-select" required>
                  <option value="" selected disabled>Select Position</option>
                  <option value="CEO">CEO</option>
                  <option value="CFO">CFO</option>
                  <option value="CMO">CMO</option>
                  <option value="COO">COO</option>
                  <option value="CTO">CTO</option>
                  <option value="ICT">ICT</option>
                  <option value="Marketing">Marketing</option>
                  <option value="Procurement">Procurement</option>
                  <option value="Project Manager">Project Manager</option>
                  <option value="Finance">Finance</option>
                  <option value="Sales">Sales</option>
                  <option value="Security">Security</option>
                  <option value="Self">Self Employed</option>
                  <option value="Self">Student</option>
                  <option value="">None of the above</option>
                </select>
              </div>
              <label for="">Institution <span class="text-muted">(Company/School)</span></label>
              <div class="mb-3">
                <input name="company" type="text" class="form-control">
              </div>
              <label for="" class="required">Email</label>
              <div class="mb-3">
                <input name="email" type="email" class="form-control" required>
              </div>
              <label for="" class="required">Phone</label>
              <div class="mb-3">
                <input name="phone" type="text" class="form-control" required>
              </div>
              <label for="" class="required">Country</label>
              <div class="mb-3">
                <select name="country" id="" class="form-select" required>
                  @foreach ($countries as $country)
                  <option value="{{ $country->id }}">{{ $country->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="card-footer">
              <span onclick="addVisitor()" class="btn bg-gradient-dark w-100">Add Visitor</span>
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

        function printerDetails(visitorId){
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: "/visitor/print/"+visitorId,
                data: {'_token':_token},
                success: function (response) {
                    

                }
            });
        }

        function startPrint(visitorId){
            printBadge()
            printerDetails(visitorId)
            
        }

        //add visitor
        function addVisitor(){
            var _token = $('meta[name="csrf-token"]').attr('content');
            var title = $('select[name="title"]').val();
            var first_name = $('input[name="first_name"]').val();
            var last_name = $('input[name="last_name"]').val();
            var position = $('select[name="position"]').val();
            var company = $('input[name="company"]').val();
            var email = $('input[name="email"]').val();
            var phone = $('input[name="phone"]').val();
            var country = $('select[name="country"]').val();
            $.ajax({
                type: "post",
                url: "/visitor/add",
                data: {'_token':_token,'title':title,
                'first_name':first_name,'last_name':last_name,
                'position':position,'company':company,
                'email':email,'phone':phone,'country':country},
                success: function (response) {
                    $('#badge-wrapper').html(response);
                    $('#add-visitor-wrapper').slideUp('fast','swing',function(){
                        $('#badge-wrapper').slideDown('fast','swing');
                    })
                }
            });
        }

        function viewVisitor(visitorId){
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: "/visitor/view/"+visitorId,
                data: {'_token':_token},
                success: function (response) {
                    $('#badge-wrapper').html(response);
                    $('#add-visitor-wrapper').slideUp('fast','swing',function(){
                        $('#badge-wrapper').slideDown('fast','swing');
                    })
                }
            });
        }

        function addNewVisitor(){
          $('#badge-wrapper').slideUp('fast','swing',function(){
            $('#add-visitor-wrapper').slideDown('fast','swing');
          });  
        }

        function searchBy(val){
            if(val == 'name'){
                $('.search-by-name').show();
                $('.search-by-qr').hide();
            }else{
                $('.search-by-name').hide();
                $('.search-by-qr').show();
            }
        }

        function searchVisitor(q){
            var _token = $('meta[name="csrf-token"]').attr('content');
            $('input[name="q"]').val('');
            $.ajax({
                type: "post",
                url: "/visitor/search",
                data: {'_token':_token,'q':q},
                success: function (response) {
                    if(response == 'false'){
                      alert('Visitor badge not found');
                      $('#badge-wrapper').slideUp('fast','swing',function(){
                        $('#add-visitor-wrapper').slideDown('fast','swing');
                    })
                    }else{
                      $('#badge-wrapper').html(response);
                    $('#add-visitor-wrapper').slideUp('fast','swing',function(){
                        $('#badge-wrapper').slideDown('fast','swing');
                    })
                    }
                    
                }
            });
        }

        $('input[name="name"]').on('keyup',function(){
            var name = $(this).val();
            
            $.ajax({
                type: "get",
                url: "/visitor/name/search/",
                data: {'name':name},
                success: function (response) {
                  console.log(response);
                    $('.visitors-table').html(response);
                }
            });
        })
</script>
@endsection

@section('styles')
<style>
  #badge-wrapper{
    display: none;
  }

  .search-by-name{
    display: none;
  }
</style>
@endsection