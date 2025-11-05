<form class="form form-vertical m-1" action="{{ route('job-document-update')}}"  method="POST"  enctype="multipart/form-data">
    @csrf
    <input type="hidden" class="inputFieldHeight form-control"  name="job_project_id" value="{{$project->id}}">
    <input type="hidden" class="inputFieldHeight form-control"  name="project_id" value="{{$project->project_id}}">
    <section id="basic-vertical-layouts">
        <div class="row match-height">
            <div class="col-md-12 col-12">
                <div class="cardStyleChange">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">

                                <div class="col-8 col-md-10">
                                    <div class="form-group">
                                        <label for="files"> Upload Document </label>
                                        <input type="file" class="inputFieldHeight form-control" id="job_document" name="files[]" multiple required>
                                    </div>
                                </div>

                                <div class="col-4 col-md-2 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary formButton mt-1 mb-1" title="Form Save">
                                        <div class="d-flex">
                                            <div class="formSaveIcon">
                                                <img src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" alt="" srcset="" class="img-fluid" width="25">
                                            </div>
                                            <div><span> Upload</span></div>
                                        </div>
                                    </button>
                                </div>

                                <div class="col-12">
                                    <ul id="filenameList" class="mt-2 filenameList"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
<table class="table table-sm">
    <tbody>
        @foreach ($documents as $item)
            <tr id="{{'tr'.$item->id}}">
                <td>{{ $loop->index+1 }}</td>
                <td><a href="{{ asset('storage/upload/documents')}}/{{$item->filename}}" class="mr-1" target="blank">{{ $item->name }}</a> </td>
                <td>
                    <a href="{{ asset('storage/upload/documents')}}/{{$item->filename}}" class="mr-1" target="blank">
                        @if ($item->ext=='pdf')
                            <a href="{{ asset('storage/upload/project-document/' . $item->file_name) }}" target="blank">
                                <img src="{{asset('icon/pdf-download-icon-2.png')}}" class="img-fluid" style="width:100%; height:100px;" alt="{{$item->ext}}">
                            </a>
                            @else
                            <a href="{{ asset('storage/upload/project-document/' . $item->file_name) }}" target="blank">
                                <img src="{{ asset('storage/upload/project-document/' . $item->file_name) }}" class="img-fluid" style="width:100%; height:100px;" alt="{{$item->ext}}">
                            </a>
                        @endif
                    </a>
                </td>
                <td class="text-center">
                    <a href="#" class="delete_document" id="{{$item->id}}" onclick="return confirm('Are you sure to delete this?')"> <i class="bx bx-trash"></i> </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
