@extends('layouts.app')

@push('styles')
    <link href="{{ asset('assets/css/home.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="container">
    <div class="second-container mt-5">
        <button type="button" class="btn w-100" data-bs-toggle="modal" data-bs-target="#exampleModal">Create Snap</button>


        <div class="story-wrapper text-center text-white">
            <h2 class="m-4"><strong>Snaps Feed</strong></h2>

            <div class="row justify-content-start">
                @foreach ($snaps as $snap)
                    <div class="col-md-6 w-24 mx-auto text-center mb-5">
                        <div class="story-header ps-1 pb-2 text-start">
                            <p id="story-name">{{ $snap->task_name }}</p>
                            <span id="story-desc">Snap by : {{ $snap->user_name}}</span>
                        </div>

                        <div class="story-card d-flex justify-content-center align-items-center">
                            <img id="story-image" src="data:{{ $snap->image_content_type }};base64,{{ $snap->image_data }}" alt="Image" draggable="false">
                        </div>

                        <div class="text-end" id="story-interact">
                            <div class="heartButtonContainer" data-story-id="{{ $snap->id }}">
                                @php
                                    $countInteracts = $interacts->where('story_id', $snap->id)->count();
                                @endphp
                        
                                @php $userInteracted = false; @endphp
                                @forelse ($interacts as $interact)
                                    @if ($userId == $interact->user_id && $snap->id == $interact->story_id)
                                        @php $userInteracted = true; @endphp
                                        @break
                                    @endif
                                @empty
                                @endforelse
                        
                                <div class="footer-snap text-dark fw-bold">
                                    <span class="snap-count" id="interactionsCount_{{ $snap->id }}">{{ $countInteracts }}</span>
                                    <button type="button" class="heart-button" data-story-id="{{ $snap->id }}" data-user-interacted="{{ $userInteracted ? 'true' : 'false' }}">
                                        <i class="fa-{{ $userInteracted ? 'solid' : 'regular' }} fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- <table class="table table-striped table-condensed table-bordered table-dark mt-5">
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
        </table> -->
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Snap</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('saveTask') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="username" value="{{ $userName }}">

                        <div class="input-group mb-3">
                            <input type="text" name="taskname" class="form-control" id="searchInput" placeholder="Snap Name..." required>
                        </div>

                        <ul id="suggestions" class=""></ul>

                        <div class="input-group mb-3">
                            <input type="file" name="imagefile" class="form-control" placeholder="Upload File" aria-label="Upload File" aria-describedby="button-addon" required>
                        </div>

                        <button class="btn w-100 text-dark" type="submit" id="button-addon">Create</button>
                    </form>
                </div>
            </div>
        </div>
        </div>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
    // Use jQuery to handle the AJAX request and display suggestions
    $(document).ready(function() {
        $('#searchInput').keyup(function() {
            var query = $(this).val();

            if (query.length >= 2) {
                $.ajax({
                    url: '/get-suggestions',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        query: query
                    },
                    success: function(data) {
                        var suggestionsList = $('#suggestions');
                        suggestionsList.empty();

                        if (data.length > 0) {
                            data.forEach(function(adjective) {
                                suggestionsList.append('<li>' + adjective + '</li>');
                            });
                            suggestionsList.show();
                        } else {
                            suggestionsList.hide();
                        }
                    }
                });
            } else {
                $('#suggestions').hide();
            }
        });

        // Handle click on a suggestion to populate the input field
        $(document).on('click', '#suggestions li', function() {
            var selectedAdjective = $(this).text();
            $('#searchInput').val(selectedAdjective);
            $('#suggestions').hide();
        });

        // Hide suggestions if clicked outside the input field or suggestions list
        $(document).on('click', function(event) {
            if (!$(event.target).closest('#suggestions').length && !$(event.target).closest('#searchInput').length) {
                $('#suggestions').hide();
            }
        });
    });
</script>

@if(Session::has('success'))
    <script>
        Toastify({
            text: "{{ Session::get('success') }}",
            duration: 3000, // 3 seconds
            gravity: "top",
            position: "right",
            backgroundColor: "green",
            stopOnFocus: true,
        }).showToast();
    </script>
@endif


<script>
    const containers = document.querySelectorAll('.heartButtonContainer');

    containers.forEach(container => {
        const userId = {{ $userId }};
        const storyId = container.getAttribute('data-story-id');
        const button = container.querySelector('.heart-button');
        const heartIcon = button.querySelector('i');
        const interactionsCountElement = container.querySelector(`#interactionsCount_${storyId}`);

        button.addEventListener('click', function () {
            const userInteracted = this.getAttribute('data-user-interacted') === 'true';

            if (heartIcon.classList.contains('fa-solid')) {
                button.disabled = true;
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/story-interact', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            const data = `userid=${userId}&storyid=${storyId}&_token={{ csrf_token() }}`;

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const newInteracted = !userInteracted;
                        button.setAttribute('data-user-interacted', newInteracted.toString());
                        heartIcon.classList.toggle('fa-solid', newInteracted);
                        heartIcon.classList.toggle('fa-regular', !newInteracted);

                        const countInteracts = parseInt(interactionsCountElement.textContent);
                        interactionsCountElement.textContent = newInteracted ? countInteracts + 1 : countInteracts - 1;
                    } else {
                        console.error('Error: AJAX request failed.');
                    }

                    button.disabled = false;
                }
            };
            xhr.send(data);
        });
    });
</script>

@endsection
