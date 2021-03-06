<?php

namespace App\Http\Controllers;

use App\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Match;
use Illuminate\Support\Facades\DB;
use App\Bet;

class MatchesController extends Controller
{

    public function store(Request $request)
    {

        $team_a = $request->input('team_a');
        $team_b = $request->input('team_b');
        $date = $request->input('match_date');

        $carbon_date = Carbon::createFromFormat('d/m/Y H:i', $date);

        $start_date = $carbon_date->toDateTimeString();
        $end_date = $carbon_date->addHour(2)->toDateTimeString();

        // INSERT NEW MATCH
        if(isset($team_a, $team_b, $date)) {
            Match::create([
                'team_a' => $team_a,
                'team_b' => $team_b,
                'start_date' => $start_date,
                'end_date' => $end_date
            ]);
        }

        // SET TEAMS AS IDLE SO THAT THEY CANNOT BE SELECTED UNTIL THE MATCH IS FINISHED
        if(isset($team_a, $team_b)) {
            Team::whereIn('id', [$team_a, $team_b])->update(['is_idle' => 1]);
        }

        return redirect(route('home'));
    }

    public function updateMatches(Request $request)
    {
        $losers = array();

        $matches_outcome = $request->input('winners');

        if(!empty($matches_outcome)) {
            $winners = array_values($matches_outcome);

            // UPDATE MATCHES, SET WINNERS AND END MATCH
            foreach ($matches_outcome as $id_match => $winner) {

                $flight = Match::find($id_match);
                $flight->winner = $winner;
                $flight->is_over = 1;
                $flight->save();

                $losers[] = $flight->team_a == $winner ? $flight->team_b : $flight->team_a;
            }

            // CALCULATE WHO HAS WON THE BETS
            $this->updateBets($matches_outcome);

            // UPDATE TEAMS STATUS
            $this->updateTeams($winners, $losers);

        }

        return redirect(route('home'));
    }

    private function updateBets($matches_outcome = array())
    {
        foreach ($matches_outcome as $id_match => $winner) {
            Bet::where('match_id', '=', $id_match)->where('team_user_bet_on', '=', $winner)->update(['user_won' => 1]);
        }
    }

    private function updateTeams($winners = array(), $losers = array())
    {

        if(!empty($winners)) {
            // UPDATE WINNING TEAMS, REMOVING IDLE SO THEY CAN PLAY AGAIN
            Team::whereIn('id', $winners)->update(['is_idle' => 0]);
        }

        if(!empty($losers)) {
            // UPDATE LOSING TEAMS, REMOVING THEM FROM THE TOURNAMENT
            Team::whereIn('id', $losers)->update(['is_idle' => 0, 'is_active' => 0]);
        }
    }

}
