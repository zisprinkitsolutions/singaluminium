@php
    use App\Permission;
    use Illuminate\Support\Facades\DB;


@endphp
        <div class="row">
            <div class="col-md-12">
              @if ($errors->any())
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif
            </div>
          </div>
        <div class="content-body p-2">
            <form class="form form-vertical"
            action="{{ route('aditional-permission.update')}}"
            method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" value="{{$user->id}}" name="user_id">
                <!-- Basic Vertical form layout section start -->
                <section id="basic-vertical-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title">Aditional Permissions</h4>
                                </div>

                                <div class="card-body">
                                    {{-- <form class="form form-vertical"> --}}
                                        <div class="form-body">
                                            <div class="row">

                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label></label>
                                                        <ul class="list-unstyled mb-0">
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="checkbox">
                                                                        <input type="checkbox" class="checkbox-input select-all-permission" id="select-all">
                                                                        <label for="select-all"> Select All  </label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                @error('permissions')
                                                <div class="col-md-12 col-12">
                                                <span class="error">{{ $message }}</span>
                                                </div>
                                                @enderror
                                            </div>

                                        </div>
                                    {{-- </form> --}}
                                </div>
                            </div>

                        </div>

                    </div>
                </section>
                <!-- Basic Vertical form layout section end -->

                <section id="basic-checkbox">
                    <div class="row">
                        @forelse ($modules as $module)
                            @if (Permission::whereDoesntHave('roles', function ($query) use ($role) {$query->where('role_id', $role);})
                            ->where('module_id',$module->id)->count()>0)


                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">{{$module->name}}</h4>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            @foreach (Permission::whereDoesntHave('roles', function ($query) use ($role) {$query->where('role_id', $role);})
                                            ->where('module_id',$module->id)->get() as $key=>$permission)
                                            <li class="d-inline-block mr-2 mb-1">
                                                <fieldset>
                                                    <div class="checkbox">
                                                        <input type="checkbox" class="checkbox-input" id="permission-id-{{$permission->id}}"
                                                        value="{{ $permission->id}}" name="permissions[]"
                                                             @if(isset($user))
                                                                @foreach($user->addPermissions as $rPermission)
                                                                {{ $permission->id == $rPermission->id ? 'checked' : '' }}
                                                                @endforeach
                                                            @endif >
                                                         <label for="permission-id-{{$permission->id}}">{{ $permission->name}}</label>
                                                    </div>
                                                </fieldset>
                                            </li>
                                            @endforeach

                                    </div>
                                </div>
                            </div>
                            @endif

                        @endforeach
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary mr-1"> Update</button>
                            <button type="reset" class="btn btn-light-secondary">Reset</button>
                        </div>

                    </div>
                </section>

            </form>
        </div>
    </div>
</div>


