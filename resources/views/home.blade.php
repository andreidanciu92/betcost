@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">

                <div class="card-header">{{ __('Dashboard') }}</div>

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
                            <a class="nav-link active" id="pills-new-bets-tab" data-toggle="pill" href="#pills-new-bets" role="tab" aria-controls="pills-new-match" aria-selected="true">New bets</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-all-bets-tab" data-toggle="pill" href="#pills-all-bets" role="tab" aria-controls="pills-all-bets" aria-selected="false">All bets</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-user-ranking-tab" data-toggle="pill" href="#pills-user-ranking" role="tab" aria-controls="pills-user-ranking" aria-selected="false">User ranking</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-new-bets" role="tabpanel" aria-labelledby="pills-new-bets-tab">
                        <div class="col-md-12">

                            <!-- CREATE NEW MATCH -->
                            <form method="POST" action="{{ route('add-bets') }}">
                                @csrf

                                @if(!empty($matches_to_be_bet_on->first()))

                                    @foreach($matches_to_be_bet_on AS $match)

                                    <div class="row" style="text-align: center; margin-top:10px">

                                        <div class="col-md-5">
                                            <input
                                                @if(isset($match->user_bet_on) && $match->user_bet_on == $match->team_a)
                                                {{'checked'}} {{'disabled'}}
                                                @elseif(isset($match->user_bet_on) && $match->user_bet_on != $match->team_a)
                                                {{'disabled'}}
                                                @endif
                                                type="radio" value="{{$match->team_a}}" name="bets[{{$match->id}}]">
                                            <span class="flag-icon flag-icon-{{$match->team_a_flag}}"></span>
                                            <span>{{$match->team_a_name}}</span>
                                        </div>

                                        <div class="col-md-2">
                                            <h5>VS</h5>
                                        </div>

                                        <div class="col-md-5">
                                            <span class="flag-icon flag-icon-{{$match->team_b_flag}}"></span>
                                            <span>{{$match->team_b_name}}</span>
                                            <input
                                                @if(isset($match->user_bet_on) && $match->user_bet_on == $match->team_b)
                                                {{'checked'}} {{'disabled'}}
                                                @elseif(isset($match->user_bet_on) && $match->user_bet_on != $match->team_b)
                                                {{'disabled'}}
                                                @endif
                                                type="radio"
                                                value="{{$match->team_b}}"
                                                name="bets[{{$match->id}}]">
                                        </div>

                                        <div class="col-md-12">
                                            <span>starts at {{$match->start_date}}</span>
                                        </div>

                                    </div>
                                    @endforeach

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Place bets</button>
                                    </div>

                                @else

                                    <div class="col-md-12">
                                        <p>There are no matches yet</p>
                                    </div>

                                @endif

                            </form>
                        </div>
                    </div>

                    <!-- ALL BETS -->
                    <div class="tab-pane fade" id="pills-all-bets" role="tabpanel" aria-labelledby="pills-all-bets-tab">
                        <div class="container">
                            <!-- UPDATE MATCH RESULTS -->
                            <form method="POST" action="{{ route('get-all-bets') }}">
                                <div class="col-md-12">
                                @csrf
                                @if(!empty($all_bets))
                                    @foreach($all_bets AS $bet)
                                        <div class="col-md-12">
                                            {{$bet->user_name}} bet on {{$bet->team_user_bet_on_name}} in {{$bet->team_a_name}} VS {{$bet->team_b_name}}
                                        </div>
                                    @endforeach
                                @endif
                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- RANKING -->
                    <div class="tab-pane fade" id="pills-user-ranking" role="tabpanel" aria-labelledby="pills-user-ranking-tab">
                        <div class="container">
                            <!-- UPDATE MATCH RESULTS -->
                            <form method="POST" action="{{ route('get-ranking') }}">
                                @csrf
                                @if(!empty($ranking))
                                    @foreach($ranking AS $user)
                                        <div class="col-md-12">
                                            {{$user->name}} has {{$user->points}} points
                                        </div>
                                    @endforeach
                                @endif
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection
