@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Student</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" id="studentForm" name="studentForm">

        <!-- Hidden field for edit operations -->
        <input type="hidden" name="student_id" id="student_id" value="{{ $student->id ?? '' }}">

        <div class="mb-2 row">
            <label for="name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-4">
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $student->name ?? '') }}" required autocomplete="off">
                <span class="text-danger error-text name_error"></span>
            </div>
        </div>
        <div class="mb-2 row">
            <label for="gender_male" class="col-sm-2 col-form-label">Gender</label>
            <div class="col-sm-4">
                <input type="radio" name="gender" id="gender_male" value="Male" {{ old('gender', $student->gender ?? '') == 'Male' ? 'checked' : 'checked' }} required> Male
                <input type="radio" name="gender" id="gender_female" value="Female" {{ old('gender', $student->gender ?? '') == 'Female' ? 'checked' : '' }} required> Female
            </div>
            <span class="text-danger error-text gender_error"></span>
        </div>
        <div class="mb-2 row">
            <label for="age" class="col-sm-2 col-form-label">Age</label>
            <div class="col-sm-4">
                <select name="age" id="age" class="form-control" required>
                    <option value="">-- Select Age --</option>
                    @for($i = 5; $i <= 15; $i++)
                        <option value="{{ $i }}" {{ old('age', $student->age ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                </select>
            </div>
            <span class="text-danger error-text age_error"></span>
        </div>

        <div class="mb-2 row">
            <label for="mark" class="col-sm-2 col-form-label">Mark</label>
            <div class="col-sm-4">
                <input type="number" name="mark" id="mark" class="form-control" value="{{ old('mark', $student->mark ?? '') }}" required max="100" min="0">
                <span class="text-danger error-text mark_error"></span>
            </div>
        </div>

        <div class="col-sm-4 text-center mt-4">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-warning" onclick="window.location='{{ route('students.index') }}'">Cancel</button>
        </div>
    </form>
</div>
<script>
    const studentStoreUrl = "{{ route('students.store') }}";

    $("#studentForm").validate({
        
        errorClass: 'invalid-feedback',  
        errorElement: 'div', 
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },

        rules: {
            name: {
                required: true,
                minlength: 3
            },
            gender: {
                required: true
            },
            age: {
                required: true
            },
            mark: {
                required: true,
                number: true,
                min: 0,
                max: 100
            }
        },
        messages: {
            name: {
                required: "Please enter name",
                minlength: "Name must be at least 3 characters",
                maxlength: "Name cannot exceed 100 characters"
            },
            gender: {
                required: "Please select gender"
            },
            age: {
                required: "Please select age"
            },
            mark: {
                required: "Please enter mark",
                number: "Mark must be a valid number",
                min: "Mark cannot be less than 0",
                max: "Mark cannot be more than 100"
            }
        },
        submitHandler: function(form) {

            let id = $("#student_id").val();
            let url = id ? `/students/${id}` : studentStoreUrl;
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: {
                    name: $("#name").val(),
                    age: $("#age").val(),
                    mark: $("#mark").val(),
                    gender: $("input[name='gender']:checked").val()
                },
                success: function(response) {
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Success',
                        body: response.message,
                        autohide: true,
                        delay: 2000,
                        // onclose: function() {
                        // }
                    });
                    window.location.href = "{{ route('students.index') }}";
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function(field, messages) {
                            $('.' + field + '_error').text(messages[0]);
                        });
                    } else {
                        $(document).Toasts('create', {
                            class: 'bg-danger',
                            title: 'Error',
                            body: 'An error occurred while processing your request.',
                        });
                    }
                }
            });
        }
    });
</script>
@endsection