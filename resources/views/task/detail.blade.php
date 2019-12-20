@extends('layouts.backend.master')

@section('base.title', 'Project|Detail')


@push('base.css')

@endpush
@section('master.content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Task Detail</h1>
                    <div style=" margin-left: 50%; margin-top: 10px;">
                        <a href="" class="btn btn-info" style=" border: 2px; width: 70px">PDF <i class="fa fa-eye"></i></a>
                        <a href="" class="btn btn-primary" style="border: 2px; width: 70px">PDF <i
                                class="fa fa-download"></i></a>
                    </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Task Detail</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card card-outline card-red">
            <div class="card-header">
                <h3 class="card-title">Detail of <strong>---</strong></h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="card card-outline card-orange col-4 offset-1" style="min-height: 70px">
                                <small style="text-align: center; font-size: 14px">Assigned</small>
                                <strong style="text-align: center">
                                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $task->created_at)->diffForHumans() }}
                                </strong>
                            </div>
                            <div class="card card-outline card-orange col-4 offset-1" style="min-height: 70px">
                                <small style="text-align: center; font-size: 14px">Time Left</small>
                                <strong style="text-align: center"></strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-10 offset-1">
                                <small style="text-align: center; font-size: 16px; font-weight: bold">Description</small>
                                <p style="font-size: 14px ">{{ $task->description }}</p>
                                <small style="text-align: center; font-size: 16px; font-weight: bold">Progress</small>

                            </div>

                            <div class="col-6 text-center">
                                <input type="text" class="knob" readonly value="@foreach($task->requirements as $requirement){{ ($requirement->percentage) }} @endforeach" data-skin="tron" data-thickness="0.1"
                                       data-width="120"
                                       data-height="120" data-fgColor="#f56954">

                                <div class="knob-label" style="color: orange">Out of {{ $difference == 0 ? 100 : ceil((count($task->requirements)*100)/count($task->project->requirements)) }}%</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <p style="font-weight: bold;">Requirements</p>
                        <table class="table" style="text-align: center">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Requirement</th>
                                <th scope="col">Filename</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>

@php
$files = [];
@endphp
                            @foreach($task->requirements as $key => $requirement)
                                <tr>
                                    <th scope="row">{{ $key++ }}</th>
                                    <td>{{ $requirement->name }}</td>
                                    @if(!in_array($task->filename,$files))
                                        @php
                                            $files[] = $task->filename;
                                        @endphp
                                    <td style="vertical-align: middle" rowspan="{{count($task->requirements)}}">{{ $task->filename }} <a href="{{url('task/download',$task->id)}}"><i class="fa fa-download"></i></a> </td>
                                    @endif
                                    @if($requirement->status == 3)
                                        <td><a style="" class="btn btn-sm btn-warning" href="">Pending</a></td>
                                    @else
                                    <td><a style="" class="btn btn-sm btn-success" href="{{ route('task.submit', $requirement->id) }}">submit</a></td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{--progress chart--}}
            </div>
        </div>
        <!-- /.card -->
    </section>
@endsection
@push('base.js')

@endpush