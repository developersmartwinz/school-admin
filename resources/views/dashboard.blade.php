@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')

<div class="row">

    <!-- STUDENTS -->
    <div class="col-lg-3 col-6" data-aos="fade-up">
        <a href="/students">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $data['students'] }}</h3>
                    <p>Students</p>
                </div>
                <div class="icon"><i class="fas fa-user-graduate"></i></div>
                <div class="small-box-footer">
                    View Details <i class="fas fa-arrow-circle-right"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- TEACHERS -->
    <div class="col-lg-3 col-6" data-aos="fade-up">
        <a href="/teachers">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $data['teachers'] }}</h3>
                    <p>Teachers</p>
                </div>
                <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="small-box-footer">
                    View Details <i class="fas fa-arrow-circle-right"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- CLASSES -->
    <div class="col-lg-3 col-6" data-aos="fade-up">
        <a href="/classes">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $data['classes'] }}</h3>
                    <p>Classes</p>
                </div>
                <div class="icon"><i class="fas fa-school"></i></div>
                <div class="small-box-footer">
                    View Details <i class="fas fa-arrow-circle-right"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- SUBJECTS -->
    <div class="col-lg-3 col-6" data-aos="fade-up">
        <a href="/subjects">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $data['subjects'] }}</h3>
                    <p>Subjects</p>
                </div>
                <div class="icon"><i class="fas fa-book"></i></div>
                <div class="small-box-footer">
                    View Details <i class="fas fa-arrow-circle-right"></i>
                </div>
            </div>
        </a>
    </div>

</div>


<div class="row mt-2">

    <!-- SECTIONS -->
    <div class="col-lg-3 col-6" data-aos="fade-up">
        <a href="/sections">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $data['sections'] }}</h3>
                    <p>Sections</p>
                </div>
                <div class="icon"><i class="fas fa-layer-group"></i></div>
                <div class="small-box-footer">
                    View Details <i class="fas fa-arrow-circle-right"></i>
                </div>
            </div>
        </a>
    </div>
<?php /*
    <!-- TIMETABLE -->
    <div class="col-lg-3 col-6">
        <a href="/timetable">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $data['timetable'] }}</h3>
                    <p>Timetable</p>
                </div>
                <div class="icon"><i class="fas fa-calendar"></i></div>
                <div class="small-box-footer">
                    Manage <i class="fas fa-arrow-circle-right"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- TIMETABLE GRID -->
    <div class="col-lg-3 col-6">
        <a href="/timetable/grid">
            <div class="small-box bg-dark">
                <div class="inner">
                    <h3>View</h3>
                    <p>Timetable Grid</p>
                </div>
                <div class="icon"><i class="fas fa-th"></i></div>
                <div class="small-box-footer">
                    Open Grid <i class="fas fa-arrow-circle-right"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- TIME SLOTS -->
    <div class="col-lg-3 col-6">
        <a href="/time-slots">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>Manage</h3>
                    <p>Time Slots</p>
                </div>
                <div class="icon"><i class="fas fa-stream"></i></div>
                <div class="small-box-footer">
                    Configure <i class="fas fa-arrow-circle-right"></i>
                </div>
            </div>
        </a>
    </div>

</div>
*/ ?>
@stop