@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">

                    <div class="card-header">{{ __('BO-Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>

                    <div class="col-md-12">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-new-match-tab" data-toggle="pill" href="#pills-new-match" role="tab" aria-controls="pills-new-match" aria-selected="true">New match</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-match-results-tab" data-toggle="pill" href="#pills-match-results" role="tab" aria-controls="pills-match-results" aria-selected="false">Update match results</a>
                            </li>
                    </ul>
                    </div>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-new-match" role="tabpanel" aria-labelledby="pills-new-match-tab">
                            <div class="col-md-12">

                            <!-- CREATE NEW MATCH -->
                            <form method="POST" action="{{ route('add-match') }}">
                                @csrf

                                <div class="form-group">

                                    <div class="col-md-12">

                                    <label for="team_a">TEAM A:</label>
                                        <select class="col-md-4 form-control" id="team_a" name="team_a" onchange="updateSelect()">
                                            <option value="" selected disabled>Select first team</option>
                                        @foreach($teams AS $team)
                                            <option value="{{$team->id}}">{{$team->name}} <i class="flag-icon flag-icon-{{$team->flag}}"></i></option>
                                        @endforeach
                                    </select>

                                    <label for="team_b">TEAM B:</label>
                                    <select class="col-md-4 form-control" id="team_b" name="team_b">
                                        <option value="" selected disabled>Select opponent team</option>
                                        @foreach($teams AS $team)
                                            <option value="{{$team->id}}">{{$team->name}} <i class="flag-icon flag-icon-{{$team->flag}}"></i></option>
                                        @endforeach
                                    </select>

                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                        <label>Select date and time:</label><br>
                                        <input type="text" class="col-md-3 form-control datetimepicker-input"
                                               name="match_date"
                                               data-target="#datetimepicker1"
                                               />
                                        <div class="input-group-append" data-target="#datetimepicker1"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">Add match</button>
                                </div>

                            </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-match-results" role="tabpanel" aria-labelledby="pills-match-results-tab">
                            <div class="col-md-12">
                                <!-- UPDATE MATCH RESULTS -->
                                <form method="POST" action="{{ route('set-match-result') }}">
                                    @csrf

                                    @if(!empty($concluded_matches->first()))
                                        @foreach($concluded_matches AS $match)

                                            <div class="row">

                                            <div class="col-md-6">{{$match->team_a_name}} VS {{$match->team_b_name}} <br>
                                                concluded on {{$match->end_date}}</div>

                                            <!-- SELECT WINNER BETWEEN THE TWO TEAMS-->
                                            <div class="col-md-6 form-group">
                                                <label >Select winner: </label>
                                                <select class="form-control" name="winners[{{$match->id}}]">
                                                    <option value="{{$match->team_a}}">{{$match->team_a_name}}</option>
                                                    <option value="{{$match->team_b}}">{{$match->team_b_name}}</option>
                                                </select>
                                            </div>

                                            </div>
                                        @endforeach
                                    @else
                                    <div class="col-md-12">
                                        <p>No matches have yet concluded</p>
                                    </div>
                                    @endif
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Update results</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
