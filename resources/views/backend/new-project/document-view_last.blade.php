


<section class="print-hideen border-bottom" style="padding: 5px 15px;background:#364a60;">
    <div class="row align-items-center">
        <div class="col-md-10">
            <h3 style="font-family:Cambria;color:white; font-size:16px; margin-bottom:0;"> Project Name:
                {{ \Illuminate\Support\Str::limit($project->project_name, 50) }} </h3>
        </div>
        <div class="col-md-2">
            <div class="d-flex flex-row-reverse" style="padding-right: 8px;padding-top: 6px;">
                <div class=""><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close" style="padding-bottom: 8px;"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
            </div>
        </div>
    </div>
</section>
<section id="widgets-Statistics">
    <div class="row">
        @foreach ($documents as $document)
            <div class="col-md-2 text-center py-1 px-4 print-hideen document-file" id="document-{{$document->id}}">
                <button class="remove-document py-1 d-none" id={{$document->id}}>
                    <i class="bx bx-trash text-danger"></i>
                </button>
                @if ($document->ext=='pdf')
                <a href="{{ asset('storage/upload/project-document/' . $document->file_name) }}" target="blank">
                    <img src="{{asset('icon/pdf-download-icon-2.png')}}" class="img-fluid" style="width:100%;" alt="{{$document->ext}}">
                </a>
                @else
                <a href="{{ asset('storage/upload/project-document/' . $document->file_name) }}" target="blank">
                    <img src="{{ asset('storage/upload/project-document/' . $document->file_name) }}" class="img-fluid" style="width:100%;" alt="{{$document->ext}}">
                </a>
                @endif
            </div>
        @endforeach
    </div>
    <form action="{{route('project-document-store')}}" method="post" enctype="multipart/form-data" class="mt-2 ml-1">
        @csrf
        <input type="hidden" value="{{$project->project_id}}" name="project_id">
        <input type="hidden" value="{{$project->id}}" name="job_project_id">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="">Voucher Scan/File</label>
                <input type="file" class="form-control inputFieldHeight" name="voucher_scan[]" accept="image/*,application/pdf" multiple id="fileInput">
            </div>
            <div class="col-md-2 text-right d-flex justify-content-end mt-2 mb-1">
                <button type="submit" class="btn btn-primary formButton" id="submitButton">
                    <div class="d-flex">
                        <div class="formSaveIcon">
                            <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}"
                                alt="" srcset="" width="25">
                        </div>
                        <div><span>Save</span></div>
                    </div>
                </button>
                <a href="{{route("purchase-expense")}}" class="btn btn-warning  d-none" id="newButton">New</a>
            </div>
            <div class="col-md-6"></div>
            <div class="col-md-6" id="fileList">
                <div class="col-md-6"></div>
            </div>
        </div>
    </form>

</section>

