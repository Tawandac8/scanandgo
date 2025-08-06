@extends('layouts.app')

@section('title')
All Users
@endsection

@section('content')
    <div class="row my-4">
        <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>Users</h6>
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    <span onclick="addUser()" style="cursor: pointer" class="badge badge-sm bg-gradient-dark">Add User</span>
                    
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
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Password</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Event</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(isset($users))
                    @foreach($users as $user)
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold"> {{ ($user->getRoleNames()[0] != 'super-admin')? $user->getRoleNames()[0] : '' }} </span>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold"> {{ $user->email }} </span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold"> {{ $user->user_password }} </span>
                      </td>
                      <td class="text-center">
                        <span class="text-xs font-weight-bold text-danger"> {{ isset($user->event)? $user->event->name : '' }} </span>
                       
                      <td>
                         <button {{($user->getRoleNames()[0] == 'super-admin')? 'disabled' : ''}} style="cursor: pointer" onclick="editUser('{{$user->id}}')" class="btn btn-sm bg-gradient-info">Edit <i class="fa-solid fa-arrow-right"></i></button>
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
        <div class="col-lg-4 col-md-6">
          <div id="add-user-wrapper" class="card">
            <div class="card-body pb-0">
              <h4 class="badge-name">Add User</h4>
              <form action="{{ route('users.store') }}" method="post">
                @csrf
              <label class="required" for="">Name</label>
              <div class="mb-3">
                <input name="name" type="text" class="form-control" required>
              </div>
              <label class="required" for="">Email</label>
              <div class="mb-3">
                <input name="email" type="text" class="form-control" required>
              </div>
              <label class="required" for="">Password</label>
              <div class="mb-3">
                <input name="password" type="text" class="form-control" required>
              </div>
              <label for="">Role</label>
              <div class="mb-3">
                <select name="role" id="" class="form-select">
                  <option value="admin">Admin</option>
                  <option value="visitor-registration">Visitor Officer</option>
                  <option value="conference-registration">Conference Registration</option>
                  <option value="badges-office">Badges Office</option>
                </select>
              </div>
              @if(isset($events))
              <label for="">Event</label>
              <div class="mb-3">
                <select name="event" id="" class="form-select">
                  @foreach ($events as $event)
                  <option value="{{ $event->id }}">{{ $event->name }}</option>
                  @endforeach
                </select>
              </div>
              @endif
            </div>
            <div class="card-footer">
              <button class="btn bg-gradient-dark w-100">Add User</button>
            </div>
            </form>
          </div>
        </div>
      </div>
@endsection

@section('scripts')
<script>
  
</script>
@endsection