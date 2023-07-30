<form action="{{ route('saveTask') }}" method="POST">
            @csrf
            <div class="input-group mb-3">
                <input type="text" name="taskname" class="form-control" placeholder="Enter your text" aria-label="Enter your text" aria-describedby="button-addon">
                <button class="btn btn-primary" type="submit" id="button-addon">Submit</button>
            </div>
        </form>

        <!-- Table to display tasks and actions -->
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">Tasks</th>
                <th scope="col">Actions</th>
                </tr>
            </thead>
            
            <tbody>
                @if (isset($tasks) && count($tasks) > 0)
                    @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task->task_name }}</td>

                            <td>
                                <a href="{{ route('updateTask', $task->id) }}" class="btn btn-primary btn-sm">Update</a>
                                <a href="{{ route('deleteTask', $task->id) }}" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <td>No tasks found.</td>
                @endif
            </tbody>
        </table>
        