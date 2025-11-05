
    <section class="p-1" id="widgets-Statistics">
        <form class="chart-form" action="{{route('traking-store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="d-flex">
                <div class="form-group w-100">
                    <input type="hidden" name="project_id" value="{{$project->id}}">

                    <label for=""> Gantt Chart Name </label>
                    <input type="text" name="project_name" readonly value="{{ old('project_name',$project->name) }}" autocomplete="off"
                    class="form-control @error('project_name') is_invalid @enderror" placeholder="Project name">

                    @error('project_name')
                        <p class="text-danger"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group w-100 ml-1">
                    <div class="d-flex justify-content-between" style="margin-bottom: 3px;">
                        <label for=""> Company Name </label>
                    </div>
                    <select disabled name="customer_id" class="form-control customer_id @error('customer_id') is-invalid @enderror">
                        <option  selected disabled> Select Customer </option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id',$project->party_id) == $customer->id ? 'selected' : ' ' }}> {{ $customer->pi_name }} </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="text-danger"> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-1">
                <h2 class="tasks-title"> Tasks </h2>
                {{-- <button type="button" class="add_items project-btn"> Add </button> --}}
            </div>

            <table class="auto-index repeater1 table table-sm">

                <thead>
                    <tr>
                        <th style="width: 5%" class="text-center"> S.NO </th>
                        <th style="width: 30%;"> Task Name </th>
                        <th style="width:25%;"> Assign To </th>
                        <th style="width: 15%" class="text-center">  Start Date </th>
                        <th style="width: 15%" class="text-center"> End Date </th>
                        <th style="width: 10%" class="text-center"> Completed % </th>
                    </tr>
                </thead>

                <tbody id="input-container">
                    @foreach ($project->items as $item)
                    <tr>
                        <td class="text-center"> </td>
                        <td>
                            <input type="hidden" name="task_id[]" value="{{$item->id}}">
                            <input type="text" name="task_name[]" class="form-control @error('task_name') is-invalid @enderror" required value="{{ $item->name }}" autocomplete="off" readonly>
                        </td>

                        <td>
                            <select  class="form-control common-select2300 assign_to text-center" readonly>
                                <option value=""> Select </option>
                                @foreach ($employees as $employee)
                                    <option value="{{$employee->full_name}}" {{$employee->full_name == $item->assign_by ? 'selected' : ' '}}> {{$employee->code . ' ' . $employee->full_name . ' ' . $employee->contact_number}} </option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <input type="text" name="start_time[]" class="form-control datepicker text-center" value="{{$item->start_date ? date('d/m/Y', strtotime($item->start_date)) : null}}" autocomplete="off"  required>
                        </td>

                        <td>
                            <input type="text" name="end_time[]" class="form-control datepicker text-center" autocomplete="off" value="{{$item->end_date ? date('d/m/Y', strtotime($item->end_date)) : null}}"  required>
                        </td>

                        <td>
                            <input type="number" name="completed[]" value="{{ $item->progress }}" min="0" max="100" class="form-control text-center" step="any" required>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
            <div class="d-flex justify-content-between mt-1">
                <div class="form-group" style="width: 300px;margin-top: 10px;">
                    <label for=""> Upload Documents </label>
                    <input
                        class="form-control file_upload  @error('voucher_file') is-invalid @enderror" type="file" name="voucher_file[]" style="padding: 0px !important; border:none" accept="application/pdf,image/png,image/jpeg,application/msword" multiple>
                    @error('voucher_file')
                        <p class="text-danger"> {{ $message }}</p>
                    @enderror

                    <ul id="fileList" class="list-group mt-1"></ul>
                </div>

                <button type="submit" class="project-btn save-btn d-inline align-self-start mt-2 btn-info"> Save </button>
            </div>
        </form>
    </section>
    <script>
        $(document).ready(function() {
            $('.customer_id').select2();
            $(".add_items").click(function () {
                addInput();
            });

            $('.date').datepicker({dateFormat:'dd/mm/yy'})
        });

        let selectedFiles = [];

        function updateFileListDisplay() {
            $('#fileList').empty();
            selectedFiles.forEach((file, index) => {
                const html = `
                    <li class="list-group-item d-flex justify-content-between align-items-center" style="padding: 5px 10px !important;">
                        ${file.name}
                        <button type="button" class="btn btn-sm btn-danger remove-file" data-index="${index}">
                            Remove
                        </button>
                    </li>
                `;
                $('#fileList').append(html);
            });
        }

        function updateFileInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            $('.file_upload')[0].files = dt.files;
        }

        $(document).on('change', '.file_upload', function (e) {
            const newFiles = Array.from(e.target.files);

            // Optional: Prevent duplicates (based on name and size)
            newFiles.forEach(newFile => {
                if (!selectedFiles.some(f => f.name === newFile.name && f.size === newFile.size)) {
                    selectedFiles.push(newFile);
                }
            });

            updateFileListDisplay();
            updateFileInput();
        });

        $(document).on('click', '.remove-file', function () {
            const index = $(this).data('index');
            selectedFiles.splice(index, 1);
            updateFileListDisplay();
            updateFileInput();
        });

     </script>

