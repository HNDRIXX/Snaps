@extends('layouts.app')

@section('content')
<div class="container">
    <!-- <div class="row">
        <div class="col-3 p-5">
            <img src="/assets/img/logo.png" class="rounded-circle" width="120" alt="">
        </div>

        <div class="col-9 pt-5 ps-5">
            <div><h5>HNDRX</h5></div>

            <div class="d-flex">
                <div><strong>Lorem Ipsum</strong></div>
                <div class="ps-5" ><strong>Lorem Ipsum</strong></div>
                <div class="ps-5" ><strong>Lorem Ipsum</strong></div>
            </div>

            <div>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit, exercitationem tempore ab architecto.</div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-4">
            <img src="/assets/img/img.jpg" alt="" class="w-100">
        </div>

        <div class="col-4">
            <img src="/assets/img/img.jpg" alt="" class="w-100">
        </div>

        <div class="col-4">
            <img src="/assets/img/img.jpg" alt="" class="w-100">
        </div>
    </div> -->

    <div class="second-container mt-5">
        <form  action="{{ route('saveUpdatedTask') }}" method="POST">
            @csrf
            <div class="input-group mb-3">
                <input type="text" name="updatetask" class="form-control" placeholder="Update Task" value="{{ $task->task_name }}" aria-label="Enter your text" aria-describedby="button-addon">
                <input type="hidden" name="id" value="{{ $task->id }}">
                <button class="btn btn-primary" type="submit" id="button-addon">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
