<form action="{{ route('project.expense.store') }}" method="POST">
    @csrf
    <div class="d-flex">
        <div class="form-group" style="max-width: 300px;">
            <label for=""> Select Project  </label>
            <select name="job_project_id" class="form-control job_project_id select2-input @error('job_project_id') is-invalid @enderror" required>
                <option  selected disabled value=" "> Select Projects</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}"> {{ $project->project_name }} </option>
                @endforeach
            </select>
            @error('job_project_id')
            <p class="text-danger"> {{ $message }} </p>
            @enderror

        </div>

        <div class="form-group ml-1" style="max-width: 200px;">
            <label for=""> Date  </label>
            <input type="text" name="date"  class="date form-control" data-due="0" value="{{ date('d/m/Y') }}" required>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 5px;">
        <h2 class="title" style="color: #444; font-size:18px;"> Expesnses </h2>
        <button type="button" class="add_items project-btn" style="background-color:#4CB648;"> Add </button>
    </div>
    <div class="table-responsive mb-2">
        <table class="auto-index table">
            <thead>
                <tr>
                    <th class="text-center"> S.NO </th>
                    <th> Items </th>
                    <th> Unit </th>
                    <th> quantity </th>
                    <th> Rate </th>
                    <th> Price </th>
                    <th class="text-center"> Action</th>
                </tr>
            </thead>
            <tbody id="input-container">
                <tr>
                    <td class="text-center">  </td>
                    <td>
                        <input name="item[]" class="form-control item" required>
                    </td>
                    <td>
                        <select name="unit[]"  class="unit form-control" required>
                            @foreach ($units as $unit)
                            <option value="{{ $unit->id }}"> {{ $unit->name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>  <input type="number" name="qty[]" class="qty form-control" step='any' required> </td>

                    <td> <input type="number" name="rate[]" class="form-control rate"  required step="any"> </td>
                    <td> <input type="number" name="price[]" class="form-control price"  required step="any"> </td>
                    <td class="text-center"> <button class="project-btn delete_items"> <i class="bx bx-trash"></i></button> </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td class="text-center form-control"> </td>
                    <td  colspan="4"> <input type="text" class="form-control text-right" value="Total Price">   </td>
                    <td  colspan="1"> <input type="number" class="form-control total-price" value="00" step="any">   </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end align-items-center" style="margin-bottom: 5px;">
        <button type="submit" class="btn btn-primary" style="background-color:#4CB648;"> Save  </button>
    </div>
</form>



