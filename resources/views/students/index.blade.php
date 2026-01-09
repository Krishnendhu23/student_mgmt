@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Student Listing</h2>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('students.create') }}" class="btn btn-success">Add Student</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                @foreach($columns as $col => $label)
                    @if(in_array($col, $allowedSorts))
                        @php
                            $newOrder = ($sortBy === $col && $order === 'asc') ? 'desc' : 'asc';
                        @endphp
                    <th class="{{ $sortBy === $col ? 'sorted-column' : '' }}">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $col, 'order' => $newOrder]) }}">
                            {{ $label }}
                            <span class="sort-icons">
                                <i class="fas fa-sort-up {{ $sortBy === $col && $order === 'asc' ? 'active' : '' }}"></i>
                                <i class="fas fa-sort-down {{ $sortBy === $col && $order === 'desc' ? 'active' : '' }}"></i>
                            </span>
                        </a>
                    </th>
                    @else
                        <th>{{ $label }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if($students->isEmpty())
            <tr>
                <td colspan="7" class="text-center text-danger">No record(s) found</td>
            </tr>
            @else
            @foreach($students as $student)
            <tr>
                <td>{{ $student->admission_no }}</td>
                <td>{{ ucwords($student->name) }}</td>
                <td>{{ $student->age }}</td>
                <td>{{ ucfirst($student->gender) }}</td>
                <td>{{ $student->mark }}</td>
                <td>
                    <span class="{{ $student->result == 'pass' ? 'text-success' : 'text-danger' }}">
                        {{ ucfirst($student->result) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('students.show', $student->id) }}" title="Edit"><i class="fas fa-edit"></i></a>
                    <button type="button" class="btn btn-sm btn-danger" data-id="{{ $student->id }}" onclick="deleteStudent(this)"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="6" class="text-center">
                    {{ $students->links('pagination::bootstrap-5') }}
                </td>
            </tr>
            @endif
        </tbody>
    </table>

</div>
<script>
    $(document).on('hidden.bs.toast', '.toast', function() {
        location.reload();
    });

    /**function for student data delete
     * @param {object} obj 
     */
    function deleteStudent(obj) {

        let id = $(obj).data('id'); //selected row student id

        Swal.fire({
            title: 'Are you sure?',
            text: "This student record will be deleted!",
            icon: 'warning',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: `/students/${id}`,
                    type: 'DELETE',
                    success: function(response) {

                        $(document).Toasts('create', {
                            class: 'bg-success',
                            title: 'Deleted',
                            body: response.message,
                            autohide: true,
                            delay: 2000,
                            position: 'topRight',
                            onHidden: function() {
                                location.reload();
                            }
                        });

                    },
                    error: function() {

                        $(document).Toasts('create', {
                            class: 'bg-danger',
                            title: 'Error',
                            body: 'Unable to delete student',
                            delay: 3000,
                            position: 'topRight'
                        });
                    }
                });
            }

        });
    }
</script>
@endsection